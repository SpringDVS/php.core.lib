<?php
namespace SpringDvs\Core\NetServices;

interface Key {
	
	/**
	 * Get the ASCII armor of the key
	 * 
	 * @return string The ASCII armor
	 */
	public function armor();
	
	/**
	 * Check if key is owned by node
	 * @return boolean True if owned
	 */
	public function owned();
	
}