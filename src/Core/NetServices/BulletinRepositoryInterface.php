<?php
/* Notice:  Copyright 2016, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;

/**
 * Interface for interacting with a bulletin repo
 */
interface BulletinRepositoryInterface {
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
	
	
	/**
	 * Add a new bulletin to the repository
	 * 
	 * This method takes a Bulletin object and inserts it into the database.
	 * The unique ID of the bulletin is handed back. The UID is implementation
	 * specific so returned has mixed.
	 * 
	 * @param \SpringDvs\Core\NetServices\Bulletin $bulletin
	 * @return mixed|null Unique ID of the bulletin that has been inserted or null
	 */
	public function addBulletin(\SpringDvs\Core\NetServices\Bulletin $bulletin);
	
	/**
	 * Remove the bulletin with UID
	 * 
	 * Remove the bulletin that has the specified Unique ID. The UID is
	 * implementation specific.
	 *  
	 * @param mixed $uid The UID of the bulletin to remove
	 * @return bool Success of removal operation
	 */
	public function removeBulletin($uid);
	
}