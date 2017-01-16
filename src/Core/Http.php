<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core;


class Http {
	
	/**
	 * perform a direct HTTP POST request on the node
	 * 
	 * @param string $hostfield The full hostname and hostpath to the point of service
	 * @param string $message The message to send in the body
	 * @param boolean $secure Toggle to try only secure connections
	 * 
	 * @return string The response from the post request
	 */
	public static function postRequest($hostfield, $message, $secure = false) {
		$response = self::runRequest('https://'.$hostfield.'/spring/', $message);
		
		if(!$response && !$secure) {
			$response = self::runRequest('http://'.$hostfield.'/spring/', $message);
		}
		return $response;
	}
	private static function runRequest($uri, $message) {
		$ch = curl_init($uri);
		$len = strlen($message);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_POST,           1 );
		curl_setopt($ch, CURLOPT_USERAGENT,      "Springnet/0.1");
		curl_setopt($ch, CURLOPT_POSTFIELDS,      $message);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		return curl_exec($ch);
	}
}