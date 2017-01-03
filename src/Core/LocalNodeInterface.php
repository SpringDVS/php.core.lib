<?php
namespace SpringDvs\Core;

interface LocalNodeInterface {
	
	/**
	 * Get the local Springname of the node
	 * 
	 * @return string The Springname as a string
	 */
	public function springname();
	
	/**
	 * Get the regional network the node is connected to
	 * 
	 * @return string The Regional network as string
	 */
	public function regional();
	
	/**
	 * Get the top cluster
	 * 
	 * @return string The top cluster of regionals
	 */
	public function top();
	
	/**
	 * Get the URI of the node
	 * 
	 * @return string The URI as a string 
	 */
	public function uri();
	
	/**
	 * Get the HTTP service layer hostname of the node
	 * 
	 * @return string The HTTP hostname as a string
	 */
	public function hostname();
	
	/**
	 * Get the path to the HTTP end point for servicing
	 * 
	 * @return string The HTTP hostpath as a string
	 */
	public function hostpath();
	
	/**
	 * Get the HTTP hostname and path endpoint
	 * 
	 * @return string The HTTP hostfield as a string
	 */
	public function hostfield();
	
	/**
	 * Get the Node ID if in multinode system
	 * @return mixed The unique ID of the node
	 */
	public function nodeid();
}