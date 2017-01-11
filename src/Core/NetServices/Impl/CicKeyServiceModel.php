<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices\Impl;

use SpringDvs\Core\NetServices\KeyServiceInterface;
use SpringDvs\Core\NetServices\Certificate;
use SpringDvs\Core\NetServices\Key;
use SpringDvs\Core\NetServices\Signature;
/**
 * Model for interacting CICs secure key service interface
 *
 */
class CicKeyServiceModel implements KeyServiceInterface {

	private $service;
	
	/**
	 * Create a new CIC Key service model
	 * 
	 * @param string $service The URL of the service end point
	 */
	public function __construct($service = 'https://pkserv.spring-dvs.org/process/') {
		$this->service = $service;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::generateKeyPair()
	 */
	public function generateKeypair($name, $email, $passphrase) {
		$body = "KEYGEN\n{$passphrase}\n{$name}\n{$email}\n\n";

		$response =  $this->performRequest($body);
		
		if(!$response || $response['public'] == "" || $response['private'] == "") {
			return null;
		}
		
		$keys['public'] = new Certificate($response['public'], true);
		$keys['private'] = new Certificate($response['private'], true);
		
		return $keys;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::import()
	 */
	public function import(\SpringDvs\Core\NetServices\Key $key,
						   \SpringDvs\Core\NetServices\Key $subject = null) {
		
		$body = "IMPORT\nPUBLIC {\n{$key->armor()}\n}\n";
		if($subject) {
			$body .= "SUBJECT {\n{$subject->armor()}\n}\n";
		}

		$body .= "\n";
		$response = $this->performRequest($body);
		
		// Check if valid
		if(!$response){ return false; }
		foreach($response as $k => $v) {
			if($k == 'sigs'){ continue; } // Skip signatures as my be empty
			if($v == "") return false; // The rest need something in them
		}
		
		$signatures = [];
		foreach($response['sigs'] as $sig) {
			$signatures[] = new Signature($sig);
		}
		
		return new Certificate($response['armor'],
							   false,
							   $response['name'],
							   $response['email'],
							   $response['keyid'],
							   $signatures);
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::expand()
	 */
	public function expand(\SpringDvs\Core\NetServices\Key $key) {
		return $this->import($key);
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::sign()
	 */
	public function sign(\SpringDvs\Core\NetServices\Certificate $certificate,
						 \SpringDvs\Core\NetServices\Key $key,
						  $passphrase) {
		$body = "SIGN\n{$passphrase}\nPUBLIC {\n{$certificate->armor()}\n}\nPRIVATE {\n{$key->armor()}\n}\n";
		$response = $this->performRequest($body);
		if(!$response || $response['public'] == "") {
			return null;
		}

		return new Certificate($response['public']);
	}


	/**
	 * Perform a cURL request on the key service
	 * 
	 * This sends the body of the request and decodes the
	 * response from JSON into an associative array
	 * @param string $body Body of the request
	 * @return boolean|mixed Array of JSON object | false
	 */
	private function performRequest($body) {

		$ch = curl_init($this->service);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_POST,           1 );
		curl_setopt($ch, CURLOPT_USERAGENT,      "VnnSpringnet/0.1" );
		curl_setopt($ch, CURLOPT_POSTFIELDS,      $body);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
				'User-Agent: VnnSpringnet/0.1'));
		$json = curl_exec($ch);

		if($json === false) {
			return false;
		}
		try {
			return json_decode($json, true);
		} catch(\Exception $e) {
			return false;
		}
	}
}