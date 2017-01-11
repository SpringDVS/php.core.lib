<?php
/* Notice:  Copyright 2016, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;
use SpringDvs\Core\NetServices\BulletinServiceInterface;

/**
 * Interface for interacting with a bulletin repo
 */
interface BulletinRepositoryInterface
extends BulletinServiceInterface
{
	
	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\BulletinReaderInterface::withFilters()
	 */
	public function withFilters(array $filters = array());
	
	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\BulletinReaderInterface::withUid()
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