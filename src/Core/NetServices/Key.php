<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;

/**
 * Representation of a key
 * 
 * In terms of the network's software, a key is the ASCII 
 * armor representation of a GPG/OpenPGP key. Public and 
 * private keys are both represented by this interface.
 * 
 * A public key can also be expanded out into a full
 * certificate (i.e. take ASCII armor of public key and
 * decode it into a GPG/OpenPGP certificate)
 *
 */
class Key {
	
	
	/**
	 * @var string The ASCII armor of the certificate or key
	 */
	protected $armor;
	
	/**
	 * @var boolean Whether certificate is owned by node
	 */
	protected $owned;

	public function __construct($armor, $owned = false) {
		$this->armor = $armor;
		$this->owned = $owned;
	}
	/**
	 * Get the ASCII armor of the key
	 * 
	 * @return string The ASCII armor
	 */
	public function armor() {
		return $this->armor;
	}
	
	/**
	 * Check if key is owned by node
	 * @return boolean True if owned
	 */
	public function owned() {
		return $this->owned;
	}
	
}