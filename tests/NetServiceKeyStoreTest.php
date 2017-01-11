<?php

use SpringDvs\Core\NetServiceKeyStore;

interface ExampleKeyStore {
	public function primary();
}

class NetServiceKeyStoreTest
extends MockReady {
	public function testGetKeySuccessful() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->once())
				->method('primary')
				->withAnyParameters()
				->willReturn('foobar');
		
		$kvs->registerStorage('example', $ekvs);
		
		$this->assertEquals('foobar', $kvs->get('example.primary'));		
	}
	
	public function testGetKeyFailureBadModuleSub() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->never())
				->method('primary');

		$kvs->registerStorage('example', $ekvs);
	
		$this->assertNull($kvs->get('invalid.primary'));
	}
	
	public function testGetKeyFailureBadKeySub() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->never())
		->method('primary');
	
		$kvs->registerStorage('example', $ekvs);
	
		$this->assertNull($kvs->get('example.secondary'));
	}
	
	public function testGetKeyFailureBadKey() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->never())
				->method('primary');

		$kvs->registerStorage('example', $ekvs);
	
		$this->assertNull($kvs->get('invalid'));
	}
	
	private function mockExampleKeyStore() {
		return $this->getMockBuilder(ExampleKeyStore::class)
				->setMethods(['primary'])
				->getMock();
	}
}