<?php
/* Notice:  Copyright 2016, The Care Connections Initiative c.i.c.
 * Authors: Charlie Fyvie-Gauld <cfg@zunautica.org>
 * License: Apache License, Version 2 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace SpringDvs\Core\NetServices;

/**
 * Full implementaton of a bulletin
 *
 */
class Bulletin implements BulletinHeader  {
	
	/**
	 * @var mixed Unique ID of the bulletin
	 */
	private $uid;
	
	/**
	 * @var string Title of the bulletin
	 */
	private $title;
	
	/**
	 * @var string[] The categories associated with bulletin
	 */
	private $categories;
	
	/** 
	 * @var string[] The tags associated with the bulletin
	 */
	private $tags;
	
	/**
	 * @var string The content of the bulletin (if any)
	 */
	private $content;
	
	
	public function __construct($uid, $title,
								array $tags, array $categories,
								$content = "")
	{
		$this->uid = $uid;
		$this->title = $title;
		$this->categories = $categories;
		$this->tags = $tags;
		$this->content = $content;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\BulletinHeader::title()
	 * 
	 * @return string The title of the bulletin
	 */
	public function title() {
		return $this->title;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\BulletinHeader::categories()
	 * 
	 * @return string[] List of categories
	 */
	public function categories() {
		return $this->categories;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\BulletinHeader::tags()
	 * 
	 * @return string[] List of tags
	 */
	public function tags() {
		return $this->tags;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\BulletinHeader::uid()
	 * 
	 * @return mixed Implementation specific UID of bulletin
	 */
	public function uid() {
		return $this->uid;
	}
	
	/**
	 * Get the content body of the bulletin
	 * 
	 * @return string The content
	 */
	public function content() {
		return $this->content;
	}
	
	/**
	 * Create a bulletin that demonstrates an error
	 * 
	 * @return \SpringDvs\Core\NetServices\Bulletin The constructed bulletin
	 */
	public static function error($errorTitle) {
		return new Bulletin("#error", $errorTitle, [], []);
	}
}