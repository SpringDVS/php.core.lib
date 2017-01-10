<?php
namespace SpringDvs\Core;

/**
 * Provides an interface with the rest of the  system so 
 * net services can notify the encapsulating system  that 
 * an event has occurred. 
 *
 */
interface NotificationInterface {
	
	/**
	 * Notify the system of an event
	 * 
	 * This registers a notification in an inactive state because
	 * we assume no transaction capability
	 * 
	 * @param Notification $message
	 * @return Unique ID of notification
	 */
	public function registerNotification(Notification $message);
	
	/**
	 * Activate the notification with the unique ID
	 * 
	 * This finishes the transaction
	 * 
	 * @param mixed $nid The notifiction ID
	 * @return boolean Whether notification is activated or not
	 */
	public function activateNotification($nid);

}