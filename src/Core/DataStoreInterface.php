<?php
namespace SpringDvs\Core;

/**
 * Interface for storing data from service requests
 * 
 * The implementation of the ID for the data is flexible
 * although a good choice would be hashing the tag and data
 * into a fingerprint for quick retrieval as well as checking
 * if it exists in the store
 */
interface DataStoreInterface {
	
	/**
	 * Get all the pieces of data associated with a tag
	 * @param string $tag
	 * @return mixed[] All the data for the tag
	 */
	public function getAllDataFromTag($tag);
	
	/**
	 * Get the data associated with ID under a given tag
	 * 
	 * @param string $tag
	 * @param mixed $id
	 * @return mixed|null The stored with ID
	 */
	public function getDataFromId($tag, $id);
	
	/**
	 * Add data to the store under a given tag
	 * 
	 * If there is a notification associated with the data, you
	 * can set the notification ID
	 * 
	 * @param string $tag
	 * @param mixed $data
	 * @param number $notifid
	 * @return mixed The ID of the data
	 */
	public function addData($tag, $data, $notifid = 0);
	
	/**
	 * Remove data with given ID from the store
	 * 
	 * @param unknown $tag
	 * @param unknown $id
	 */
	public function removeDataWithId($tag, $id);
	
	/**
	 * Check whether the data already exists
	 * 
	 * How it is checked is implementation specific
	 * 
	 * @param string $tag
	 * @param mixed $data
	 */
	public function dataExists($tag, $data);
}