<?php
/* Notice:  Copyright 2017, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core;
use \SpringDvs\ContentResponse as ContentResponse;
use \SpringDvs\Message as Message;

/**
 * SpringNet Uri Request
 *
 * This class provides the means of resolving nodes and
 * performing requests on the springnet.
 *
 */
class Snur {
	/**
	 * Perform a resolution of a Spring URI.
	 *
	 * If there is an error then it returns false otherwise
	 * it will return an array of objects that implement the
	 * INodeNetInterface
	 *
	 * @param string $uri
	 * @param \SpringDvs\Core\LocalNodeInterface The local node object
	 * @return array(\SpringDvs\INodeNetInterface) | false
	 */
	public function resolveUri($uri, LocalNodeInterface $local) {
		
		if(substr($uri,0,9) != 'spring://'){ $uri = "spring://$uri"; }
		$message = Message::fromStr("resolve $uri");
	
		$responseMessage = null;
		$responseType = null;
		foreach($local->primary() as $primary) {
			$responseHttp = Http::postRequest($primary->hostField(),
	   										  $message->toStr());
			if($responseHttp === false){ continue; }
			
			try{
				$responseMessage = Message::fromStr($responseHttp);
				$responseType = $responseMessage->getContentResponse()->type();
			} catch(\Exception $e) {
				continue; // Got a bad response, so try next one
			}
		}

		if(!$responseMessage) { return false; }

		switch($responseType) {
			case ContentResponse::Network:
				return $responseMessage->getContentResponse()->getNetwork();
			case ContentResponse::NodeInfo:
				return array($responseMessage->getContentResponse()->getNodeInfo());
			default:
				return false;
		}
	}

	/**
	 * Perform a request and accept first response
	 *
	 *  This method takes an array of potential target nodes
	 *  and if the request fails, it moves onto the next one.
	 *
	 *  If there is no valid response then the entire method
	 *  fails by returning null.
	 *
	 * @param mixed $msg The message is either string or \SpringDvs\Message
	 * @param \SpringDvs\Node[] $nodes
	 * 
	 * @return \SpringDvs\Message|null
	 */
	public function requestFirstResponse($message, array $nodes) {
		
		$msgstr = $this->prepare($message);
		foreach($nodes as $node) {
			$responseHttp = Http::postRequest($node->hostfield(), $msgstr);
			if(!$responseHttp){ continue; }
			
			try {
				$responseMessage = \SpringDvs\Message::fromStr($responseHttp);
				if(!$responseMessage->getContentResponse()->isOk()) {
					continue; // Got a bad response, so try next node
				}
			} catch(Exception $e) {
				continue; // invalid response, so try next node
			}
			return $responseMessage; // got a valid message to pass back
		}
		return null;
	}

	/**
	 * Perform a request on a given URI and take the first response
	 *
	 * This method combines the resolution and request. If any fail it returns
	 * null otherwise it returns a message
	 *
	 * @param String $uri The URI to make the request
	 * @param mixed $message The message to send with as string or \SpringDvs\Message
	 * @return \SpringDvs\Message|null
	 */
	public function requestFirstResponseFromUri($uri, $message, LocalNodeInterface $local) {
		$nodes = $this->resolveUri($uri, $local);
		if(!$nodes) return null;
		return $this->requestFirstResponse($message, $nodes);
	}

	/**
	 * Prepare message for sending via HTTP
	 * 
	 * @param mixed $message Message is either string or \SpringDvs\Message
	 * @return string
	 */
	private function prepare($message) {
		if( is_string($message) ) {
			return $message;
		} else if($message instanceof \SpringDvs\Message) {
			return $message->toStr();
		} else {
			return "";
		}
	}

}