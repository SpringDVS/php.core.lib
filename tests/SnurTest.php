<?php

use SpringDvs\Core\Snur;
use SpringDvs\Node;
use SpringDvs\NodeInfoFmt;
use SpringDvs\ContentResponse;
use SpringDvs\Message;

class SnurTest
extends MockReady
{
	private $snur;
	private $primary;

	
	public function setUp() {
		$this->snur = new Snur();
		$this->primary = new Node('woodsage','woodsage.spring-dvs.org');
	}
	
	/**
	 * @group online
	 */
	public function testResolve() {
		$uri = "spring://woodsage.esusx.uk";
		$node = $this->mockLocalNodeInterface();
		$node->expects($this->once())
			->method('primary')
			->willReturn([$this->primary]);
		$list = $this->snur->resolveUri($uri, $node);

		$this->assertCount(1, $list);
		$info = $list[0];
		$this->assertTrue($info instanceof NodeInfoFmt);
		$this->assertEquals('woodsage', $info->spring());
		$this->assertEquals('woodsage.spring-dvs.org', $info->host());
	}
	
	/**
	 * @group online
	 */
	public function testMessage() {
		$uri = "spring://woodsage.esusx.uk";
		$node = $this->mockLocalNodeInterface();

		$node->expects($this->once())
			->method('primary')
			->willReturn([$this->primary]);
		
		$response = $this->snur->requestFirstResponseFromUri('spring://woodsage.esusx.uk', 'service spring://woodsage.esusx.uk/cert/', $node);

		$this->assertTrue($response instanceof Message);
		
		$this->assertTrue($response->getContentResponse()->isOk());
		
		$this->assertEquals(ContentResponse::ServiceText, $response->getContentResponse()->type());
	}
}