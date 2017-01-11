<?php
namespace SpringDvs\Core\NetServices\Impl;

use SpringDvs\Core\LocalNodeInterface;
use SpringDvs\Core\NetServiceInterface;
use SpringDvs\Core\NetServices\OrgProfile;
use SpringDvs\Core\NetServices\OrgProfileManagerServiceInterface;
use SpringDvs\Core\ServiceEncoding;

/**
 * The canonical implementation of the OrgProfile service which
 * can easily be plugged into a web system, especially using
 * the SpringDvs\Core\NetServiceHandler
 */
class CciOrgProfileService
implements NetServiceInterface
{
	/**
	 * @var LocalNodeInterface The local node interface
	 */
	private $node;
	
	/**
	 * @var OrgProfileManagerServiceInterface The org profile interface
	 */
	private $profile;

	public function __construct(OrgProfileManagerServiceInterface $orgprofile, LocalNodeInterface $node) {
		$this->node = $node;
		$this->profile = $orgprofile;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServiceInterface::run()
	 */
	public function run($uriPath, $uriQuery) {
		$profile = $this->profile->getProfile();
		return ServiceEncoding::json([
			'name' => $profile->name(),
			'website' => $profile->website(),
			'tags' => $profile->tags()
		], $this->node);
	}
}