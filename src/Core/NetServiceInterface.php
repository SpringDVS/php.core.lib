<?php
namespace SpringDvs\Core;

interface NetServiceInterface {
	/**
	 * Perform the service action
	 * 
	 * @param string[] $uriPath
	 * @param string[] $uriQuery
	 * @return string The service response
	 */
	public function run($uriPath, $uriQuery);
}