<?php
namespace SpringDvs\Core\NetServices\Impl;

use SpringDvs\Core\LocalNodeInterface;
use SpringDvs\Core\NetServiceInterface;
use SpringDvs\Core\ServiceEncoding;
use SpringDvs\Core\NetServices\Bulletin as Bulletin;
use SpringDvs\Core\NetServices\BulletinReaderInterface;

/**
 * The canonical implementation of the Bulletin service which
 * can easily be plugged into a web system, especially using
 * the SpringDvs\Core\NetServiceHandler
 */
class CciBulletinService
implements NetServiceInterface
{
	
	/**
	 * @var SpringDvs\Core\NetServices\BulletinReaderInterface The bulletin repository
	 */
	private $repo;
	
	/**
	 * @var SpringDvs\Core\LocalNodeInterface The Local node inteface
	 */
	private $node;
	
	/**
	 * Initialise the service with a reader interface on the repo
	 * 
	 * @param BulletinReaderInterface $repo The repository to use
	 * @param LocalNodeInterface $node The local node
	 */
	public function __construct(BulletinReaderInterface $repo, LocalNodeInterface $node) {
		$this->repo = $repo;
		$this->node = $node;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServiceInterface::run()
	 */
	public function run($uriPath, $uriQuery) {
		if(isset($uriPath[1]) && !empty($uriPath[1])) {
			
			$bulletin = ($b = $this->repo->withUid($uriPath[1]))
							? $b : Bulletin::error("Bulletin does not exist");

			return ServiceEncoding::json($this->encodeBulletin($bulletin),
										 $this->node);
		}
		$headers = $this->repo->withFilters($uriQuery);
		return ServiceEncoding::json($this->encodeHeaderArray($headers),
									 $this->node);
	}
	
	/**
	 * Encode a bulletin as a JSON array
	 * 
	 * @param Bulletin $bulletin
	 * @return string[]
	 */
	private function encodeBulletin(Bulletin $bulletin) {
		return [
			'title' => $bulletin->title(),
			'uid' => $bulletin->uid(),
			'content' => $bulletin->content(),
			'tags' => $bulletin->tags(),
		];
	}
	
	/**
	 * Encode a header as a JSON array
	 * 
	 * @param \SpringDvs\Core\NetServices\BulletinHeader $header
	 * @return string[] An array for encoding as JSON
	 */
	private function encodeHeader(\SpringDvs\Core\NetServices\BulletinHeader $header) {
		return [
				'title' => $header->title(),
				'uid' => $header->uid(),
				'tags' => $header->tags(),
		];
	}
	
	/**
	 * Encode an array of headers as an array of JSON arrays
	 * 
	 * @param \SpringDvs\Core\NetServices\BulletinHeader[] $headers
	 * @return string[] An array for encoding as JSON
	 */
	private function encodeHeaderArray($headers) {
		$v = [];
		
		foreach($headers as $header) {
			$v[] = $this->encodeHeader($header);
		}
		
		return $v;
	}
}