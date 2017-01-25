<?php
namespace SpringDvs\Core\NetServices\Impl;

use SpringDvs\Core\LocalNodeInterface;
use SpringDvs\Core\NetServiceInterface;
use SpringDvs\Core\ServiceEncoding;
use SpringDvs\Core\NetServices\Bulletin;
use SpringDvs\Core\NetServices\BulletinManagerServiceInterface;
use SpringDvs\Core\NetServiceViewLoaderInterface;

/**
 * The canonical implementation of the Bulletin service which
 * can easily be plugged into a web system, especially using
 * the SpringDvs\Core\NetServiceHandler
 */
class CciBulletinService
implements NetServiceInterface
{ 
	/**
	 * @var SpringDvs\Core\NetServices\BulletinManagerServiceInterface The bulletin repository
	 */
	private $repo;
	
	/**
	 * @var SpringDvs\Core\LocalNodeInterface The Local node inteface
	 */
	private $node;
	
	/**
	 * @var SpringDvs\Core\NetServiceViewLoader The view loader
	 */
	private $views;
	
	/**
	 * @var string The source of the bulletin post (spring, web)
	 */
	private $source;
	
	/**
	 * Initialise the service with a reader interface on the repo
	 * 
	 * @param BulletinManagerServiceInterface $repo The repository to use
	 * @param LocalNodeInterface $node The local node
	 * @param NetServiceViewLoaderInterface $views The view loader
	 * @param string $source The source of the response
	 */
	public function __construct(BulletinManagerServiceInterface $repo,
								NetServiceViewLoaderInterface $views, LocalNodeInterface $node,
								$source = 'spring') {
		$this->repo = $repo;
		$this->node = $node;
		$this->views = $views;
		$this->source = $source;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServiceInterface::run()
	 */
	public function run($uriPath, $uriQuery) {

		if(!isset($uriPath[1]) || empty($uriPath[1])) {
			$headers = $this->repo->withFilters($uriQuery);
			return ServiceEncoding::json($this->encodeHeaderArray($headers),
					$this->node);
		}
		switch($uriPath[1]) {
			case 'post':
				$uid = isset($uriPath[2]) ? $uriPath[2] : null;
				
				$bulletin = ($b = $this->repo->withUid($uid))
					? $b : Bulletin::error("Bulletin does not exist");
				$view = isset($uriQuery['view']) ? $uriQuery['view'] : 'json';
				
				return $this->formatView($view, $bulletin);
			default:
				return "105";
		}	
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
				'source' => $this->source // source network of bulletin
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
	
	/**
	 * Format the bulletin into a particular view
	 * 
	 * @param string $view The view name
	 * @param Bulletin $bulletin
	 * @return string The output of the response
	 */
	private function formatView($view, Bulletin $bulletin) {
		switch($view) {
			case 'json':
				return ServiceEncoding::json($this->encodeBulletin($bulletin),
						$this->node);
			case 'web':
				return ServiceEncoding::text($this->views->load('bulletin.web',
						['bulletin' => $bulletin]));
			default:
				return "105"; // Internal error -- View does not exist
		}
	}
}