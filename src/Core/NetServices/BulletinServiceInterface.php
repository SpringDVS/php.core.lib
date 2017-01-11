<?php
namespace SpringDvs\Core\NetServices;

/**
 * Interface for interacting with the bulletin repository
 * from with a public facing network service
 *
 */
interface BulletinServiceInterface {
	/**
	 * Get the bulletins with given filters
	 *
	 * The filters are placed in an associative array as
	 * ['filter' => 'value'].
	 * A missing filter implies with the default if there
	 * is one or not used in the search if there is no
	 * default. If an empty array is passed in then all
	 * values will either be empty or default.
	 *
	 * Filters:
	 *  * categories - Any categories to filter through
	 *  * tags - Any tags to filter through
	 *  * limit - The maximum number of bulletins (default: 5)
	 *
	 * @param array $filters The array of filters
	 * @return \SpringDvs\Core\NetServices\BulletinHeader[] Array of bulletin headers
	 */
	public function withFilters(array $filters = array());
	
	/**
	 * Get the bulletin with given unique ID
	 *
	 * The UID is used to locate a single bulletin in the
	 * repository. The UID format is implementation specific
	 * and not defined here.
	 *
	 * @param mixed $uid
	 * @return \SpringDvs\Core\NetServices\Bulletin|null Bulletin if found or null if invalid UID
	 */
	public function withUid($uid);
}