<?php
namespace SpringDvs\Core\NetServices;

/**
 * Interface for performing a pull action on a node
 * and storing that certificate in the keyring.
 * 
 * The pull action is for getting the current node's
 * certificate off another node.
 */
interface CertificatePullInterface {
	/**
	 * Perform a pull off a node
	 * 
	 * @param string $source The URI to pull from
	 * @return boolean True if the action was successful
	 */
	public function performPull($source);
}