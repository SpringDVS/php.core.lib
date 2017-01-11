<?php
use PHPUnit\Framework\TestCase;
use SpringDvs\Core\Snur;
use SpringDvs\Core\LocalNodeInterface;
use SpringDvs\Core\NetServices\BulletinServiceInterface;

use SpringDvs\Core\NetServices\KeyringInterface;
use SpringDvs\Core\NetServices\KeyringServiceInterface;
use SpringDvs\Core\DataStoreInterface;
use SpringDvs\Core\NetServiceKeyStore;
use SpringDvs\Core\NotificationInterface;
use SpringDvs\Core\NetServices\KeyServiceInterface;

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
	
	protected function mockBulletinServiceInterface() {
		$methods = [
			'withUid', 'withFilters'
		];
		return $this->getMockBuilder(BulletinServiceInterface::class)
					->setMethods($methods)
					->getMock();
	}
	
	protected function mockSnur() {
		$methods = [
				'resolveUri', 'requestFirstResponse',
				'requestFirstResponseFromUri'
		];
		return $this->getMockBuilder(Snur::class)
					->setMethods($methods)
					->getMock();
	}
	
	protected function mockKeyringInterface() {
		$methods = [
				'getNodePublicKey', 'getNodePrivateKey',
				'getNodeCertificate', 'getNodeKeyid',
				'resetNodeKeys', 'setNodeCertificate',
				'setNodePrivate', 'setCertificate',
				'getCertificate', 'removeCertificate',
				'getKey', 'getResolvedCertificate',
				'getUidList', 'getUidName',
				'getPrivateKey', 'hasCertificate',
				'getCertificateCount','hasPrivateKey'
		];
		return $this->getMockBuilder(KeyringInterface::class)
						->setMethods($methods)
						->getMock();
	}
	
	protected function mockKeyringServiceInterface() {
		$methods = [
				'getNodePublicKey','getNodeCertificate', 
				'getNodeKeyid','setNodeCertificate',
				'getKey'
		];
		return $this->getMockBuilder(KeyringServiceInterface::class)
			->setMethods($methods)
			->getMock();
	}
	
	protected function mockKeyServiceInterface() {
		$methods = [
				'generateKeyPair', 'import',
				'expand', 'sign',
		];
		return $this->getMockBuilder(KeyServiceInterface::class)
						->setMethods($methods)
						->getMock();
	}
	
	protected function mockDataStoreInterface() {
		$methods = [
				'getAllDataFromTag', 'getDataFromId',
				'addData', 'removeDataWithId',
				'dataExists'
		];
		return $this->getMockBuilder(DataStoreInterface::class)
						->setMethods($methods)
						->getMock();
	}
	
	protected function mockNetServiceKeyStore() {
		$methods = [
				'registerStorage', 'get',
		];
		return $this->getMockBuilder(NetServiceKeyStore::class)
						->setMethods($methods)
						->getMock();
	}
	
	protected function mockNotificationInterface() {
		$methods = [
				'registerNotification', 'activateNotification'
		];
		return $this->getMockBuilder(NotificationInterface::class)
						->setMethods($methods)
						->getMock();
	}
	
	protected function stdLocalNodeInterfaceUri(&$node) {
		$node->expects($this->once())
		->method('uri')
		->willReturn('alpha.venus.uk');
	}
}