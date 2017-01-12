<?php
use SpringDvs\Core\NetServiceHandler;
use SpringDvs\Uri;

require '../vendor/autoload.php';



class NetServiceHandlerTest
extends MockReady
{
	/**
	 * @var \SpringDvs\Core\NetServiceHandler Testing handler
	 */
	private $handler;
	
	/**
	 * @var \SpringDvs\Core\LocalNodeInterface Mocked local node
	 */
	private $node;
	
	public function setUp() {
		parent::setUp();
		
		$this->node = $this->mockLocalNodeInterface();
		
		$this->node->expects($this->any())
			->method('springname')
			->withAnyParameters()
			->willReturn('alpha');
		
		$this->handler = new NetServiceHandler($this->node);
	}
	

	/**
	 * Test handling a request not meant for current node
	 */
	public function testBadRoute() {
		$expected  = '103';
		$uri = new Uri('spring://beta.venus.uk/testpoint');
		
		$this->handler->register('testpoint', function() {
			return '200';
		});
		
		$actual = $this->handler->run($uri);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Handling unsupported service
	 */
	public function testBadService() {
		$expected  = '122';
		$uri = new Uri('spring://alpha.venus.uk/invalid');
	
		$this->handler->register('testpoint', function() {
			return '200';
		});
	
		$actual = $this->handler->run($uri);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Testing a URI that does not point to a service
	 */
	public function testNoService() {
		$expected  = '104';
		$uri = new Uri('spring://alpha.venus.uk');
	
		$this->handler->register('testpoint', function() {
			return '200';
		});
	
		$actual = $this->handler->run($uri);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Testing an invalid (not a string) response from a service
	 */
	public function testInvalidResponseFromService() {
		$expected  = '122';
		$uri = new Uri('spring://alpha.venus.uk/invalid');
	
		$this->handler->register('testpoint', function() {
			return false;
		});
	
			$actual = $this->handler->run($uri);
			$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test a fully functional response from a service
	 */
	public function testSuccessfulService() {
		$expected  = '200';
		$uri = new Uri('spring://alpha.venus.uk/testpoint');
	
		$this->handler->register('testpoint', function() {
			return '200';
		});

		$actual = $this->handler->run($uri);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test handling of URI data
	 */
	public function testUriSections() {
		$expected  = '200';
		$uri = new Uri('spring://alpha.venus.uk/testpoint/foo?q=1');
	
		// Return '200' if everything is ok
		$this->handler->register('testpoint', function($uriPath, $uriQuery) {
			
			if($uriPath == ['testpoint','foo']
			&& $uriQuery = ['q' => 1]) {
				return '200';
			}
			
			return '104';
		});
	
		$actual = $this->handler->run($uri);
		$this->assertEquals($expected, $actual);
	}
}