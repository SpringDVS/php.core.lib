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
class CciKeyServiceModel implements KeyServiceInterface {

	private $service;
	private $action;
	/**
	 * Create a new CIC Key service model
	 * 
	 * @param string $service The URL of the service end point
	 */
	public function __construct($service = 'https://pkserv.spring-dvs.org') {
		$this->service = $service;
		
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::generateKeyPair()
	 */
	public function generateKeypair($name, $email, $passphrase) {
		$this->action('genkey');
		
		$body = json_encode(array(
			'name' => $name,
			'email' => $email,
			'passphrase' => $passphrase
		));
		
		$response =  $this->response($this->performRequest($body));
		if(!$response){ return null; }
	
		$keys['public'] = new Certificate($response['public'], true);
		$keys['private'] = new Certificate($response['private'], true);
		
		return $keys;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::update()
	 */
	public function update(\SpringDvs\Core\NetServices\Key $key,
						   \SpringDvs\Core\NetServices\Key $subject) {
	   	$this->action('update');
		$body = json_encode(array(
			'public' => $key->armor(),
			'subject' => $subject->armor(),
		));

		$response = $this->response($this->performRequest($body));
		
		
		if(!$response){ return null; }
		return $this->expand(new Key($response['public']));
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::expand()
	 */
	public function expand(\SpringDvs\Core\NetServices\Key $key) {
		$this->action('expand');
		$body = json_encode(array(
				'public' => $key->armor(),
		));
		
		$response = $this->response($this->performRequest($body));
		if(!$response){ return null; }
		
		// Check if valid
		$signatures = [];
		foreach($response['sigs'] as $sig) {
			$signatures[] = new Signature($sig);
		}
		
		return new Certificate($key->armor(),
				false,
				$response['name'],
				$response['email'],
				$response['keyid'],
				$signatures);
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\KeyServiceInterface::sign()
	 */
	public function sign(\SpringDvs\Core\NetServices\Certificate $certificate,
						 \SpringDvs\Core\NetServices\Key $key,
						  $passphrase) {
		$this->action('sign');
		$body = json_encode(array(
				'passphrase' => $passphrase,
				'public' => $certificate->armor(),
				'private' => $key->armor(),
		));
		$response = $this->response($this->performRequest($body));

		return new Key($response['public']);
	}
	
	public function response($result) {
		if(!$result || !isset($result['result'])
		|| $result['result'] == 'error' || !isset($result['response'])) {
			return null;
		}
		return $result['response'];
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

		$ch = curl_init($this->service . '/' . $this->action);

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
	
	private function action($action) {
		$this->action = $action;
	}
}