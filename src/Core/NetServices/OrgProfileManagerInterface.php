<?php
namespace SpringDvs\Core\NetServices;
use SpringDvs\Core\NetServices\OrgProfileManagerServiceInterface;


/**
 * Interface for interacting with the Org Profile
 * manager
 */
interface OrgProfileServiceInterface
extends OrgProfileManagerServiceInterface {
	
	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\OrgProfileManagerServiceInterface::getProfile()
	 */
	public function getProfile();
	
	/**
	 * Update the profile
	 * 
	 * @param OrgProfile $profile The new profile to update with
	 */
	public function updateProfile(OrgProfile $profile);
}