<?php
namespace SpringDvs\Core\NetServices;

/**
 * An interface for the header of a bulletin
 * 
 * The bulletin header currently consists of:
 *  * title
 *  * categories
 *  * tags
 *  * uid
 *  
 * The header is the part that is broadcast on
 * the network 
 *
 */
interface BulletinHeader {
	
	/**
	 * Get the title from the header
	 * @return string The title
	 */
	public function title();
	
	/**
	 * Get the categories or empty array when none
	 * 
	 * @return string[] List of categories 
	 */
	public function categories();
	
	/**
	 * Get the tags or empty array when none
	 * 
	 * @return string[] List of tags
	 */
	public function tags();
	
	/**
	 * Get the UID
	 * 
	 * @return mixed Implementation specific UID
	 */
	public function uid();
}