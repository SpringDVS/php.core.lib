<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;

/**
 * An GPG/OpenPGP certificate representation
 * 
 * In terms of the network's software -- the certificate is an expanded
 * public key. While a Key is an ascii armor form of a key. This
 * implements the interface for a key since a certificate contains
 * a key.
 */
class Certificate
extends Key {
	/**
	 * @var string The name on the certificate
	 */
	private $uidName;
	
	
	/**
	 * @var string The Email on the certificate
	 */
	private $uidEmail;
	
	/**
	 * @var string The Key ID on the certificate
	 */
	private $keyId;
	
	
	/**
	 * @var \SpringDvs\Core\NetServices\Signature[] The signtures that are the certificate
	 */
	private $signatures;
	
	/**
	 * Construct a certificate object with given details
	 * 
	 * @param string $armor The armor of the certificate
	 * @param boolean $owned Flag whether they key is owned by local node 
	 * @param string $name The name on the certificate
	 * @param string $email The email on the certificate
	 * @param string $keyid The key ID on the certificate
	 * @param Signature[] $signatures An array of signatures (if any)
	 */
	public function __construct($armor, $owned = false, $name = null, $email = null, $keyid = null, $signatures = []) {
		parent::__construct($armor, $owned);
		$this->uidName = $name;
		$this->uidEmail = $email;
		$this->keyId = $keyid;
		$this->signatures = $signatures;
	}


	/**
	 * Get the name on the certificate
	 * 
	 * @return string The name
	 */
	public function name() {
		return $this->uidName;
	}
	
	/**
	 * Get the email on the certificate
	 * 
	 * @return string The email address
	 */
	public function email() {
		return $this->uidEmail;
	}
	
	/**
	 * Get the Key ID on the certificate
	 * 
	 * @return string The key ID
	 */
	public function keyid() {
		return $this->keyId;
	}
	
	/**
	 * Get the signatures on the certificate
	 * 
	 * @return \SpringDvs\Core\NetServices\Signature[] A list of Signatures (if any)
	 */
	public function signatures() {
		return $this->signatures;
	}
	
	/**
	 * Get the array of keyids that have signed certificate
	 * 
	 * @return string[] The keyids that signed certificate
	 */
	public function signatureKeyids() {
		$sigs = [];
		foreach($this->signatures as $sig) {
			$sigs[] = $sig->keyid;
		}
		
		return $sigs;
	}
	
	public function signatureString() {
		$comma = count($this->signatures);

		$out = '';
		foreach($this->signatures as $sig) {
			$out .= $sig->keyid . (--$comma > 0 ? ',' : '');
		}
		return $out;
	}


}