<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core;

/**
 * Object to help encode response to service requests correctly
 */
class ServiceEncoding {
	
	/**
	 * Set the encoding type for message
	 * 
	 * This create a complete response string including the
	 * encoding and response code of the message
	 * 
	 * @param string $content
	 * @return string The complete response message
	 */
	public static function text($content) {
		$text = "service/text $content";
		$len = strlen($text);
		return "200 $len $text";
	}
	
	/**
	 * This takes and encodes a JSON array into a string
	 * 
	 * The JSON response is quite specific on the network so this
	 * does the correct encoding
	 * 
	 * @param array $content JSON array for encoding
	 * @param LocalNodeInterface $local Local node details
	 * @return string The complete response message
	 */
	public static function json(array $content, LocalNodeInterface $localNode) {
		return self::text(json_encode([$localNode->uri() => $content]));
	}
}