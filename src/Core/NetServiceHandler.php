<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core;
use SpringDvs\Uri;;

/**
 * A standard object for handling service requests
 * 
 * This takes a URI and responds with either a service that
 * is registered in it's router or an error code. This means
 * that the services architecture can be implementation 
 * specific while this provides a reusable way of initially handling 
 * the requests.
 *
 */
class NetServiceHandler extends NetServiceRouter {
	
	/**
	 * @var \SpringDvs\Core\LocalNodeInterface The local node
	 */
	private $localNode;
	
	public function __construct(LocalNodeInterface $localNode) {
		$this->localNode = $localNode;
	}
	
	/**
	 * Run a service requested in the URI
	 * 
	 * If the service is registered then it will be run
	 * and the response returned from this function. If
	 * any errors are encountered, a response code is returned
	 * as a string;
	 * 
	 * @param Uri $uri
	 * @return string The response/error from the service
	 */
	public function run(Uri $uri) {
		
		$uriPath = $uri->res();
		
		$uriQuery = [];
		
		parse_str($uri->query(), $uriQuery);

		if(!isset($uriPath[0])) {
			return "104"; // Malformed content -- no service specified
		}
		
		$module = $uriPath[0];
		
		if(reset($uri->route()) != $this->localNode->springname()) {
			return "103"; //  Network error -- it's not the right node
		}
		$service = $this->getService($module);
		
		if(!$service || !is_callable($service)) {
			return "122"; // Unsupported Service -- we don't run this service
		}
		
		
		$response = call_user_func($service, $uriPath, $uriQuery, $this->localNode);
		if(!is_string($response)){ return "105"; } // service failed to respond correctly -- internal error
		
		return $response;
	}
}