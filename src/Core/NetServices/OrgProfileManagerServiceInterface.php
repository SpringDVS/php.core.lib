<?php
namespace SpringDvs\Core\NetServices;

/**
 * Interface for interacting with the Org Profile
 * manager within a public facing network service
 */
interface OrgProfileManagerServiceInterface {
	/**
	 * Get the profile of the organisation
	 * @return \SpringDvs\Core\NetServices\OrgProfile|null The profile
	 */
	public function getProfile();
}