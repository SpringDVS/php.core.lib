<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core;

/**
 * This stores a map of service end points and the actual
 * service system to run when it is requested
 */
class NetServiceRouter {
	private $map;
	
	/**
	 * Register a service module with the router
	 * 
	 * @param string $endpoint The point of service
	 * @param callable $callback The service to run
	 */
	public function register($endpoint, callable $callback) {
		$this->map[$endpoint] = $callback;
	}
	
	/**
	 * Retrieve a service service system with given enpoint
	 * 
	 * @param string $endpoint The endpoint of the service
	 * @return NULL|callable The service system to run or null if not found
	 */
	protected function getService($endpoint) {
		if(!isset($this->map[$endpoint])) return null;
		
		return $this->map[$endpoint];
	}
}