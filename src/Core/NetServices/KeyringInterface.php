<?php
namespace SpringDvs\Core\NetServices;

interface KeyringInterface {

	/**
	 * Get the public key of the local node
	 * 
	 * @return \SpringDvs\Core\NetServices\Key | null
	 */
	public function getNodePublicKey();
	
	
	/**
	 * Get the private key of the local node
	 * 
	 * @return \SpringDvs\Core\NetServices\Key | null
	 */
	public function getNodePrivateKey();
	
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
	 * Destroy the current keys and certificate of the node
	 * 
	 * @return void
	 */
	public function resetNodeKeys();
	
	/**
	 * Set the certificate for the local node
	 * 
	 * @param \SpringDvs\Core\NetServices\Certificate $certificate
	 * @return void
	 */
	public function setNodeCertificate(\SpringDvs\Core\NetServices\Certificate $certificate);
	
	/**
	 * Set the private key of the local node
	 * 
	 * @param \SpringDvs\Core\NetServices\Key $key
	 * @return void
	 */
	public function setNodePrivate(\SpringDvs\Core\NetServices\Key $key);

	/**
	 * Set/update the certificate for the given key ID
	 * 
	 * @param \SpringDvs\Core\NetServices\Certificate $certificate
	 * @return boolean True if the certificate was set or updates
	 */
	public function setCertificate(\SpringDvs\Core\NetServices\Certificate $certificate);
	
	/**
	 * Get the certificate of a given key id from the keyring
	 * 
	 * @param string $keyid The key ID to search for
	 * @return \SpringDvs\Core\NetServices\Certificate | null
	 */
	public function getCertificate($keyid);
	
	/**
	 * Remove the certificate with the given key id from the keyring
	 * 
	 * @param string $keyid The key ID to search for removal
	 * @return boolean True if found and removed
	 */
	public function removeCertificate($keyid);
	
	/**
	 * Get the key with the given key ID
	 * 
	 * @param string $keyid The key ID to search for
	 * @return \SpringDvs\Core\NetServices\Key | null
	 */
	public function getKey($keyid);
	
	/**
	 * Get a certificate with the signature names resolved
	 * 
	 * If the keyid on the signature is in the keyring, the
	 * signature's name is resolved on the certificate
	 * 
	 * @param string $keyid The key ID to search for
	 * @return \SpringDvs\Core\NetServices\Certificate | null
	 */
	public function getResolvedCertificate($keyid);
	
	/**
	 * Get a paginated list of names and key ids
	 * 
	 * @param integer $page The current page
	 * @param integer $limit The number of results on page
	 * @return \SpringDvs\Core\NetServices\Certificate[]
	 */
	public function getUidList($page, $limit = 10);
	
	/**
	 * Get the name associated with a given key ID
	 * 
	 * @param string $keyid The key ID to search for
	 * @return string The name on the certificate
	 */
	public function getUidName($keyid);
	
	/**
	 * Check if the local node has a private key
	 * 
	 * @return boolean True if there is a certificate
	 */
	public function hasPrivateKey();
	
	/**
	 * Check if the local node has a certificate
	 * 
	 * @return boolean True if there is a certificate
	 */
	public function hasCertificate();
	
	/**
	 * Get the number of certificates in the keyring (not inc. local node's)
	 * 
	 * @return integer total number of certificates
	 */
	public function getCertificateCount();
}