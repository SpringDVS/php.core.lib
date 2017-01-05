<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;
use SpringDvs\Core\NetServices\Key as Key;

/**
 * A signature for a certificate
 */
class Signature {
	/**
	 * @var string The key ID of the signature
	 */
	public $keyid;
	
	/**
	 * @var string The resolved name attached to the key ID (if any)
	 */
	public $name;
	
	/**
	 * Create a new signature
	 * 
	 * There will always be a key ID, but if a name is not
	 * resolved it defaults to 'unknown'.
	 * 
	 * @param string $keyid The key ID
	 * @param string $name The name associated with the key
	 */
	public function __construct($keyid, $name = 'unknown') {
		$this->keyid = $keyid;
		$this->name = $name;
	}
}

/**
 * An GPG/OpenPGP certificate representation
 * 
 * In terms of the network's software -- the certificate is an expanded
 * public key. While a Key is an ascii armor form of a key. This
 * implements the interface for a key since a certificate contains
 * a key.
 */
class Certificate implements Key {
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
	 * @var string The ASCII armor of the certificate or key
	 */
	private $armor;
	
	/**
	 * @var boolean Whether certificate is owned by node
	 */
	private $owned;
	
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
		$this->armor = $armor;
		$this->uidName = $name;
		$this->uidEmail = $email;
		$this->keyId = $keyid;
		$this->signatures = $signatures;
		$this->owned = $owned;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\Key::armor()
	 */
	public function armor() {
		return $this->armor;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\Key::owned()
	 */
	public function owned() {
		return $this->owned;
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
	 * Check if key is owned by node
	 * @return boolean True if owned
	 */

}