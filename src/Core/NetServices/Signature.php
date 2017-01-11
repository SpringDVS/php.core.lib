<?php
namespace SpringDvs\Core\NetServices;
/**
 * A signature for a certificate
 */
class Signature {
	/**
	 * @var string The key ID of the signature
	 */
	public $keyid;

	/**
	 * @var string The resolved name attached to the key ID (if any)
	 */
	public $name;

	/**
	 * Create a new signature
	 *
	 * There will always be a key ID, but if a name is not
	 * resolved it defaults to 'unknown'.
	 *
	 * @param string $keyid The key ID
	 * @param string $name The name associated with the key
	 */
	public function __construct($keyid, $name = 'unknown') {
		$this->keyid = $keyid;
		$this->name = $name;
	}
}