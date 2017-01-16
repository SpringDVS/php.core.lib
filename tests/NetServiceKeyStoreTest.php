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
	
	public function testSetKeySuccessful() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->once())
			->method('primary')
			->with(1001)
			->willReturn(true);
		
		$kvs->registerStorage('example', $ekvs);
	
		$this->assertTrue($kvs->set('example.primary', 1001));
	}

	public function testSetKeyBadModuleSub() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->never())
			->method('primary');

		$kvs->registerStorage('example', $ekvs);
	
		$this->assertFalse($kvs->set('inavlid.primary', 1001));
	}

	public function testSetKeyBadModuleBadKeySub() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->never())
			->method('primary');
	
		$kvs->registerStorage('example', $ekvs);
	
		$this->assertFalse($kvs->set('example.secondary', 1001));
	}
	
	public function testSetKeyBadModuleBadKey() {
		$kvs = new NetServiceKeyStore();
		$ekvs = $this->mockExampleKeyStore();
		$ekvs->expects($this->never())
			->method('primary');
		
		$kvs->registerStorage('example', $ekvs);
	
		$this->assertFalse($kvs->set('example', 1001));
	}

	private function mockExampleKeyStore() {
		return $this->getMockBuilder(ExampleKeyStore::class)
				->setMethods(['primary'])
				->getMock();
	}
}