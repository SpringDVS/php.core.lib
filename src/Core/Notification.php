<?php
namespace SpringDvs\Core;

class Notification {
	
	/**
	 * @var string The title of the notification
	 */
	private $title;
	
	/**
	 * @var string The action (where to go to resolve notification)
	 */
	private $action;
	
	/**
	 * @var string The source of the notification
	 */
	private $source;
	
	/**
	 * @var string The message that accompanies the notification
	 */
	private $message;
	
	public function __construct($title, $action, $source, $message) {
		$this->title = $title;
		$this->action = $action;
		$this->source = $source;
		$this->message = $message;
	}
}