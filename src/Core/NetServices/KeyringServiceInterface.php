<?php
namespace SpringDvs\Core\NetServices;

/**
 * Interface for interacting with a keyring from within
 * a public facing network service
 *
 */
interface KeyringServiceInterface {
	/**
	 * Get the public key of the local node
	 *
	 * @return \SpringDvs\Core\NetServices\Key | null
	 */
	public function getNodePublicKey();
	
	/**
	 * Get the public key certificate of the local node
	 *
	 * @return \SpringDvs\Core\NetServices\Certificate | null
	 */
	public function getNodeCertificate();
	
	/**
	 * Get the public key ID of the local node
	 *
	 *  @return string
	 */
	public function getNodeKeyid();
	
	/**
	 * Set the certificate for the local node
	 *
	 * @param \SpringDvs\Core\NetServices\Certificate $certificate
	 * @return void
	 */
	public function setNodeCertificate(\SpringDvs\Core\NetServices\Certificate $certificate);
	
	/**
	 * Get the key with the given key ID
	 *
	 * @param string $keyid The key ID to search for
	 * @return \SpringDvs\Core\NetServices\Key | null
	 */
	public function getKey($keyid);
}