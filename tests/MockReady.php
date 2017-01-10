<?php
use \SpringDvs\Core\LocalNodeInterface;
use SpringDvs\Core\NetServices\BulletinReaderInterface;
use PHPUnit\Framework\TestCase;

class MockReady extends TestCase {
	
	protected function mockLocalNodeInterface() {
		return $this->getMockBuilder(LocalNodeInterface::class)
					->setMethods(['uri','springname',
								  'regional','top',
							      'hostname','hostpath',
								  'hostfield','nodeid',
								  'primary'
								])
					->getMock();
	}
	
	protected function mockBulletinReaderInterface() {
		return $this->getMockBuilder(BulletinReaderInterface::class)
					->setMethods(['withUid', 'withFilters'])
					->getMock();
	}
}