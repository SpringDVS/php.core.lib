<?php
require '../vendor/autoload.php';
require 'MockReady.php';
require 'MessageDecoder.php';

use SpringDvs\Core\NetServices\Bulletin;


class CciBulletinServiceTest
extends MockReady
{

	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/post/1001
	 */
	public function testRequestUidJsonViewSuccess() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		
		$this->stdLocalNodeInterfaceUri($node);

		$reader->expects($this->once())
					->method('withUid')
					->with($this->equalTo('1001'))
					->willReturn(new Bulletin('1001', 'Title', ['tag1','tag2'],[],"Content"));
		
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views, $node);
		
		
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin','post', '1001'], [], $node));
		
		$this->assertEquals('alpha.venus.uk', key($response));

		$bulletin = reset($response);

		$this->assertEquals('1001', $bulletin->uid);
		$this->assertEquals('Title', $bulletin->title);
		$this->assertCount(2, $bulletin->tags);
		$this->assertEquals('tag1', $bulletin->tags[0]);
		$this->assertEquals('tag2', $bulletin->tags[1]);
		$this->assertEquals('Content', $bulletin->content);
	}
	
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/post/1001
	 */
	public function testRequestUidWebViewSuccess() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
	
	
		$reader->expects($this->once())
			->method('withUid')
			->with($this->equalTo('1001'))
			->willReturn(new Bulletin('1001', 'Title', ['tag1','tag2'],[],"Content"));
		
		$views->expects($this->once())
			->method('load')
			->with('bulletin.web')
			->willReturn('WebView');
		
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views, $node);
	
	
		$response = MessageDecoder::extractServiceText($service->run(['bulletin','post', '1001'], ['view' => 'web'], $node));
	
		$this->assertEquals('WebView', $response);
	}
	
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/post/1001
	 */
	public function testRequestUidJsonViewSpecifiedSuccess() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		
		$this->stdLocalNodeInterfaceUri($node);
	
		$reader->expects($this->once())
		->method('withUid')
		->with($this->equalTo('1001'))
		->willReturn(new Bulletin('1001', 'Title', ['tag1','tag2'],[],"Content"));
	
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views, $node);
	
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin','post', '1001'], [], $node));
		
		$this->assertEquals('alpha.venus.uk', key($response));
		
		$bulletin = reset($response);
		
		$this->assertEquals('1001', $bulletin->uid);
		$this->assertEquals('Title', $bulletin->title);
		$this->assertCount(2, $bulletin->tags);
		$this->assertEquals('tag1', $bulletin->tags[0]);
		$this->assertEquals('tag2', $bulletin->tags[1]);
		$this->assertEquals('Content', $bulletin->content);
	}

	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/post/1002
	 */
	public function testRequestUidFailureNoUid() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);
		
		$reader->expects($this->once())
					->method('withUid')
					->with($this->equalTo('1002'));

		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views, $node);
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin', 'post', '1002'], []));
		
		$this->assertEquals('alpha.venus.uk', key($response));
		$bulletin = reset($response);
		
		$this->assertEquals('#error', $bulletin->uid);
	}
	
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/post/1001
	 */
	public function testRequestUidFailureNoView() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		
	
		$reader->expects($this->once())
			->method('withUid')
			->with($this->equalTo('1001'))
			->willReturn(new Bulletin('1001', 'Title', ['tag1','tag2'],[],"Content"));


		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views, $node);
		$response = $service->run(['bulletin', 'post', '1001'], ['view'=>'invalid']);
	
		$this->assertEquals('105', $response);
	}
	
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/
	 */
	public function testRequestHeadersNoFilters() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);
		
		$reader->expects($this->once())
					->method('withFilters')
					->with([])
					->willReturn($this->generateBulletinHeaders(5));
		
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views,$node);
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin'], []));
		
		$this->assertEquals('alpha.venus.uk', key($response));
		$headers = reset($response);
		
		$this->assertCount(5, $headers);
		

		for($i = 0; $i < 5; $i++) {
			$header = $headers[$i];
			$this->assertEquals("100{$i}", $header->uid);
			$this->assertEquals("Title {$i}", $header->title);
			$this->assertCount(2, $header->tags);
			$this->assertEquals("tag-{$i}-1", $header->tags[0]);
			$this->assertEquals("tag-{$i}-2", $header->tags[1]);
			$this->assertEquals("spring", $header->source);
		}
	}
	
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/
	 */
	public function testRequestHeadersNoFiltersWebSource() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);
	
		$reader->expects($this->once())
			->method('withFilters')
			->with([])
			->willReturn($this->generateBulletinHeaders(5));
	
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views,$node, 'web');
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin'], []));
	
		$this->assertEquals('alpha.venus.uk', key($response));
		$headers = reset($response);
	
		$this->assertCount(5, $headers);
	
	
		for($i = 0; $i < 5; $i++) {
			$header = $headers[$i];
			$this->assertEquals("100{$i}", $header->uid);
			$this->assertEquals("Title {$i}", $header->title);
			$this->assertCount(2, $header->tags);
			$this->assertEquals("tag-{$i}-1", $header->tags[0]);
			$this->assertEquals("tag-{$i}-2", $header->tags[1]);
			$this->assertEquals("web", $header->source);
		}
	}

	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/?limit=10
	 */
	public function testRequestHeadersLimitFilter() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);
	
		$reader->expects($this->once())
					->method('withFilters')
					->with(['limit' => '10'])
					->willReturn($this->generateBulletinHeaders(7));
	
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader, $views,$node);
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin'], ['limit' => '10'], $node));
	
		$this->assertEquals('alpha.venus.uk', key($response));
		$headers = reset($response);
	
		$this->assertCount(7, $headers);
	
	
		for($i = 0; $i < 7; $i++) {
			$header = $headers[$i];
			$this->assertEquals("100{$i}", $header->uid);
			$this->assertEquals("Title {$i}", $header->title);
			$this->assertCount(2, $header->tags);
			$this->assertEquals("tag-{$i}-1", $header->tags[0]);
			$this->assertEquals("tag-{$i}-2", $header->tags[1]);
			$this->assertEquals("spring", $header->source);
		}
	}

	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/?categories=Foo
	 */
	public function testRequestHeadersCategoriesFilter() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);

		$reader->expects($this->once())
			->method('withFilters')
			->with(['categories' => 'Foo'])
			->willReturn($this->generateBulletinHeaders(5));
	
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader,$views,$node);
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin'], ['categories' => 'Foo'], $node));
	
		$this->assertEquals('alpha.venus.uk', key($response));
		$headers = reset($response);
	
		$this->assertCount(5, $headers);
	
	
		for($i = 0; $i < 5; $i++) {
			$header = $headers[$i];
			$this->assertEquals("100{$i}", $header->uid);
			$this->assertEquals("Title {$i}", $header->title);
			$this->assertCount(2, $header->tags);
			$this->assertEquals("tag-{$i}-1", $header->tags[0]);
			$this->assertEquals("tag-{$i}-2", $header->tags[1]);
			$this->assertEquals("spring", $header->source);
		}
	}

	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/?tags=foo
	 */
	public function testRequestHeadersTagsFilter() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);
	
		$reader->expects($this->once())
			->method('withFilters')
			->with(['tags' => 'foo'])
			->willReturn($this->generateBulletinHeaders(5));
	
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader,$views,$node);
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin'], ['tags' => 'foo'], $node));
	
		$this->assertEquals('alpha.venus.uk', key($response));
		$headers = reset($response);
	
		$this->assertCount(5, $headers);
	
	
		for($i = 0; $i < 5; $i++) {
			$header = $headers[$i];
			$this->assertEquals("100{$i}", $header->uid);
			$this->assertEquals("Title {$i}", $header->title);
			$this->assertCount(2, $header->tags);
			$this->assertEquals("tag-{$i}-1", $header->tags[0]);
			$this->assertEquals("tag-{$i}-2", $header->tags[1]);
			$this->assertEquals("spring", $header->source);
		}
	}
	
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/bulletin/
	 */
	public function testRequestHeadersOnEmptyRepo() {
		$reader = $this->mockBulletinManagerServiceInterface();
		$node = $this->mockLocalNodeInterface();
		$views = $this->mockNetServiceViewLoader();
		$this->stdLocalNodeInterfaceUri($node);
	
		$reader->expects($this->once())
			->method('withFilters')
			->with([])
			->willReturn($this->generateBulletinHeaders(0));
	
		$service  = new SpringDvs\Core\NetServices\Impl\CciBulletinService($reader,$views,$node);
		$response = MessageDecoder::jsonServiceText($service->run(['bulletin'], [], $node));
	
		$this->assertEquals('alpha.venus.uk', key($response));
		$headers = reset($response);
	
		$this->assertCount(0, $headers);
	}

	
	private function generateBulletinHeaders($count) {
		$headers = [];
		for($i = 0; $i < $count; $i++) {
			$uid = "100{$i}";
			$title = "Title {$i}";
			$tags = ["tag-{$i}-1","tag-{$i}-2"];
			$categories = [];
			$headers[] = new Bulletin($uid, $title, $tags, $categories);
		}
		
		return $headers;
	}
}