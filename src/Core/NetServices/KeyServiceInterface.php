<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;

/**
 * Interface for a key management service
 * 
 * This could be local or run as a secure remote service.
 * However, this is not an interface for the keyring, this
 * just provides the resources for the keyring
 */
interface KeyServiceInterface {
	
	/**
	 * Generate new key pair action
	 * 
	 * The name is usually the node that is generating the keypair
	 * since the certificate will represent the node. The email
	 * tends to be the contact email for the node.
	 * 
	 * The passphrase is used to lock the private key up in a safe.
	 * The given passphrase is used to unlock it for performing actions
	 * with the private key.
	 * 
	 *  The returned value should be an array in the form of:
	 *  
	 *  [ 'public' => Key, 'private' => Key ]
	 * 
	 * If there was any error, the key value will be null
	 * 
	 * @param string $name Name to put on the certificate
	 * @param string $email The email to put on the certificate
	 * @param string $passphrase The passphrase to
	 * @return \SpringDvs\Core\NetServices\Key[] | null
	 */
	public function generateKeyPair($name, $email, $passphrase);
	
	
	/**
	 * 
	 * @param \SpringDvs\Core\NetServices\Key $key
	 * @param \SpringDvs\Core\NetServices\Key $subject
	 */
	public function update(\SpringDvs\Core\NetServices\Key $key,
						   \SpringDvs\Core\NetServices\Key $subject);
	/**
	 * Expand a key into a certificate
	 * 
	 * This is similar to the import action but with a more specific
	 * name. This takes a key and expands it into a certificate.
	 * 
	 * @param \SpringDvs\Core\NetServices\Key $key The key to expand
	 * @return \SpringDvs\Core\NetServices\Certificate | null
	 */
	public function expand(\SpringDvs\Core\NetServices\Key $key);

	/**
	 * Sign certificate action
	 * 
	 * This will sign the given certificate with the given private key.
	 * The passphrase is used to unlock the private key from the safe.
	 * 
	 * There is no import or expansion performed, just the public key is 
	 * returned with the new signature included.
	 * 
	 * @param \SpringDvs\Core\NetServices\Certificate $certificate Certificate to sign
	 * @param \SpringDvs\Core\NetServices\Key $key The private key used for signing
	 * @param sting $passphrase The passphrase used to unlock the private key
	 * @return \SpringDvs\Core\NetServices\Key | null
	 */
	public function sign(\SpringDvs\Core\NetServices\Certificate $certificate,
						 \SpringDvs\Core\NetServices\Key $key,
						  $passphrase);
}