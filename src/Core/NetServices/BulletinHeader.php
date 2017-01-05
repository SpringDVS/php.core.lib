<?php
/* Notice:  Copyright 2016, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
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