<?php

namespace SpringDvs\Core\NetServices;

/**
 * Represent an oranisation's profile
 *
 */
class OrgProfile {
	/**
	 * @var string The name of the organisation
	 */
	private $name;
	
	/**
	 * @var The URL of the webpage
	 */
	private $webpage;
	
	/**
	 * @var string[] An array of tags
	 */
	private $tags;
	
	/**
	 * @param string $name Name of organisation
	 * @param string $website URL to homepage
	 * @param string[] $tags A list of tags
	 */
	public function __construct($name, $website, $tags) {
		$this->name = $name;
		$this->webpage = $website;
		$this->tags = $tags;
	}
	
	/**
	 * Get the name on the profile
	 * 
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
	
	/**
	 * Get the URL to the webpage
	 * 
	 * @return string
	 */
	public function website() {
		return $this->webpage;
	}
	
	/**
	 * Get the tags
	 * 
	 * @return string[]
	 */
	public function tags() {
		return $this->tags;
	}
}