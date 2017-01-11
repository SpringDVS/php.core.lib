<?php


use SpringDvs\Core\NetServices\Certificate;
use SpringDvs\Core\NetServices\Signature;
use SpringDvs\Core\NetServices\Key;
use SpringDvs\Core\NetServices\Impl\CciCertService;
use SpringDvs\ContentResponse;
use SpringDvs\Message;
use SpringDvs\ContentService;
use SpringDvs\ServiceTextFmt;
use SpringDvs\Core\ServiceEncoding;
use SpringDvs\Core\Notification;

class CciCertServiceTest
extends MockReady
{
	private $mKeyring;
	private $mKvs;
	private $mStore;
	private $mNotif;
	private $mSnur;
	private $mNode;
	private $mKservice;
	private $service;
	private $path;
	
	protected function setUp() {
		parent::setUp();
		$this->mKeyring = $this->mockKeyringInterface();
		$this->mKvs = $this->mockNetServiceKeyStore();
		$this->mStore = $this->mockDataStoreInterface();
		$this->mNotif = $this->mockNotificationInterface();
		$this->mKservice = $this->mockKeyServiceInterface();
		$this->mSnur = $this->mockSnur();
		$this->mNode = $this->mockLocalNodeInterface();
		$this->service = new CciCertService($this->mKeyring, $this->mKvs,
											$this->mStore, $this->mNotif,
											$this->mNode, $this->mKservice,
											$this->mSnur);
		
		$this->path = ['cert'];
	}

	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/foobar/
	 */
	public function testUnsupportedAction() {
		$this->path[] = 'foobar';
		$this->mNode->expects($this->never())
						->method('uri');

		$response = $this->service->run($this->path, []);
		$this->assertEquals('121', $response);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/
	 */
	public function testCert() {
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$expects = new Certificate('armor', false, 'node','node@example.com', 'keyid', [new Signature('keyid')]);
		$this->mKeyring->expects($this->once())
						->method('getNodeCertificate')
						->withAnyParameters()
						->willReturn($expects);
		
		$response = $this->service->run($this->path, []);
		$check = MessageDecoder::jsonServiceText($response);
		
		$this->assertObjectHasAttribute('alpha.venus.uk', $check);
		
		$val = MessageDecoder::jsonServiceTextStripNode($response);

		$this->assertNotNull($val);
		$this->assertObjectHasAttribute('certificate', $val);

		$cert = $val->certificate;
		
		$this->assertEquals($expects->name(), $cert->name);
		$this->assertEquals($expects->email(), $cert->email);
		$this->assertEquals($expects->keyid(), $cert->keyid);
		$this->assertCount(1, $cert->sigs);
		$this->assertEquals($expects->signatureKeyids(), $cert->sigs);
		$this->assertEquals($expects->armor(), $cert->armor);
	}

	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/key/
	 */
	public function testKey() {
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$this->path[] = 'key';
		$expects = new Key('armor');
		
		$this->mKeyring->expects($this->once())
						->method('getNodePublicKey')
						->withAnyParameters()
						->willReturn($expects);
		
		
		$response = $this->service->run($this->path, []);

		$val = MessageDecoder::jsonServiceTextStripNode($response);

		$this->assertNotNull($val);
		$this->assertObjectHasAttribute('key', $val);
		
		$key = $val->key;

		$this->assertNotNull($key);
		$this->assertEquals($expects->armor(), $key);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/key/
	 */
	public function testKeyFailure() {
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$this->path[] = 'key';
	
		$this->mKeyring->expects($this->once())
			->method('getNodePublicKey');
	
	
		$response = $this->service->run($this->path, []);
		$val = MessageDecoder::jsonServiceTextStripNode($response);
			
		$this->assertNotNull($val);
		$this->assertObjectHasAttribute('key', $val);
		$key = $val->key;
		$this->assertEquals('error', $key);
	}

	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pull/abcd/
	 */
	public function testPullSuccessful() {
		$this->stdLocalNodeInterfaceUri($this->mNode);

		$this->path[] = 'pull';
		$this->path[] = 'abcd';
		$expects = new Key('armor');
	
		$this->mKeyring->expects($this->once())
			->method('getKey')
			->with('abcd')
			->willReturn($expects);
	
		
		$response = $this->service->run($this->path, []);

		$val = MessageDecoder::jsonServiceTextStripNode($response);
		
		$this->assertNotNull($val);
		$this->assertObjectHasAttribute('key', $val);

		$key = $val->key;
		
		$this->assertEquals($expects->armor(), $key);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pull/invalid
	 */
	public function testPullFailure() {
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$this->path[] = 'pull';
		$this->path[] = 'invalid';
	
		$this->mKeyring->expects($this->once())
				->method('getKey')
				->with('invalid');
	
	
		$response = $this->service->run($this->path, []);
		$val = MessageDecoder::jsonServiceTextStripNode($response);
		
		$this->assertNotNull($val);
		$this->assertObjectHasAttribute('key', $val);

		$key = $val->key;
		$this->assertEquals('error', $key);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pullreq/?source=other.venus.uk
	 */
	
	public function testPullRequestImmediate() {

		$this->stdLocalNodeInterfaceUri($this->mNode);
		$otherNode = $this->mockLocalNodeInterface();
		$nodePublicKey = new Key('nodepublicarmor');
		$nodeCertificate = new Certificate('nodepublicarmor', false, 
										   'alpha.venus.uk', 'alpha@example.com',
										   'nodekeyid',[new Signature('nodekeyid')]);

		$responseMessage = Message::fromStr(ServiceEncoding::json(['key' => 'nodepublicarmor'], $otherNode));
		
		$otherNode->method('uri')->willReturn('other.venus.uk');
		
		$this->mKeyring->expects($this->once())
			->method('getNodeKeyid')
			->withAnyParameters()
			->willReturn('nodekeyid');
		
		$this->mKeyring->expects($this->once())
			->method('getNodePublicKey')
			->withAnyParameters()
			->willReturn($nodePublicKey);
		
		$this->mKeyring->expects($this->once())
			->method('setNodeCertificate')
			->with($nodeCertificate);
						
						
		$this->mSnur->expects($this->once())
			->method('requestFirstResponseFromUri')
			->with('spring://other.venus.uk', 'service spring://other.venus.uk/cert/pull/nodekeyid')
			->willReturn($responseMessage);
		
		$this->mKvs->expects($this->once())
			->method('get')
			->with('cert.notify')
			->willReturn(false);
		
		$this->mKservice->expects($this->once())
			->method('import')
			->with($nodePublicKey)
			->willReturn($nodeCertificate);

		
		$this->path[] = 'pullreq';
		$response = $this->service->run($this->path, ['source' => 'other.venus.uk']);
		
		$obj = MessageDecoder::jsonServiceTextStripNode($response);
		
		$this->assertNotNull($obj);
		$this->assertObjectHasAttribute('result', $obj);
		$this->assertEquals('ok', $obj->result);
	}

	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pullreq/?source=other.venus.uk
	 */
	public function testPullRequestImmediateFailureBadKeyId() {
	
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$otherNode = $this->mockLocalNodeInterface();
		$nodePublicKey = new Key('nodepublicarmor');
		$nodeCertificate = new Certificate('nodepublicarmor', false,
				'alpha.venus.uk', 'alpha@example.com',
				'nodekeyid',[new Signature('nodekeyid')]);
	
		$responseMessage = Message::fromStr(ServiceEncoding::json(['key' => 'error'], $otherNode));
	
		$otherNode->method('uri')->willReturn('other.venus.uk');
	
		$this->mKeyring->expects($this->once())
			->method('getNodeKeyid')
			->withAnyParameters()
			->willReturn('nodekeyid');
	
		$this->mKeyring->expects($this->never())->method('getNodeCertificate');

		$this->mKeyring->expects($this->never())->method('getNodePublicKey');
	
	
		$this->mSnur->expects($this->once())
			->method('requestFirstResponseFromUri')
			->with('spring://other.venus.uk', 'service spring://other.venus.uk/cert/pull/nodekeyid')
			->willReturn($responseMessage);
	
		$this->mKvs->expects($this->once())
			->method('get')
			->with('cert.notify')
			->willReturn(false);
	
		$this->mKservice->expects($this->never())->method('import');	
	
		$this->path[] = 'pullreq';
		$response = $this->service->run($this->path, ['source' => 'other.venus.uk']);
		$obj = MessageDecoder::jsonServiceTextStripNode($response);
		
		$this->assertNotNull($obj);
		$this->assertObjectHasAttribute('result', $obj);
		$this->assertEquals('error', $obj->result);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pullreq/?source=other.venus.uk
	 */
	public function testPullRequestImmediateFailureBadConnection() {
	
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$otherNode = $this->mockLocalNodeInterface();
		$nodePublicKey = new Key('nodepublicarmor');
		$nodeCertificate = new Certificate('nodepublicarmor', false,
				'alpha.venus.uk', 'alpha@example.com',
				'nodekeyid',[new Signature('nodekeyid')]);
	
		$otherNode->method('uri')->willReturn('other.venus.uk');
	
		$this->mKeyring->expects($this->once())
		->method('getNodeKeyid')
		->withAnyParameters()
		->willReturn('nodekeyid');
	
		$this->mKeyring->expects($this->never())->method('getNodeCertificate');
	
		$this->mKeyring->expects($this->never())->method('getNodePublicKey');
	
	
		$this->mSnur->expects($this->once())
			->method('requestFirstResponseFromUri')
			->with('spring://other.venus.uk', 'service spring://other.venus.uk/cert/pull/nodekeyid')
			->willReturn(null);
	
		$this->mKvs->expects($this->once())
			->method('get')
			->with('cert.notify')
			->willReturn(false);
	
		$this->mKservice->expects($this->never())->method('import');
	
		$this->path[] = 'pullreq';
		$response = $this->service->run($this->path, ['source' => 'other.venus.uk']);
		$obj = MessageDecoder::jsonServiceTextStripNode($response);
	
		$this->assertNotNull($obj);
		$this->assertObjectHasAttribute('result', $obj);
		$this->assertEquals('error', $obj->result);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pullreq/?source=other.venus.uk
	 */
	public function testPullRequestImmediateFailureBadResponse() {
	
		$this->stdLocalNodeInterfaceUri($this->mNode);
		$otherNode = $this->mockLocalNodeInterface();
		$nodePublicKey = new Key('nodepublicarmor');
		$nodeCertificate = new Certificate('nodepublicarmor', false,
				'alpha.venus.uk', 'alpha@example.com',
				'nodekeyid',[new Signature('nodekeyid')]);
	
	
	
		$otherNode->method('uri')->willReturn('other.venus.uk');
		$responseMessage = Message::fromStr(ServiceEncoding::json(['nonsense' => 'blah'], $otherNode));

		$this->mKeyring->expects($this->once())
			->method('getNodeKeyid')
			->withAnyParameters()
			->willReturn('nodekeyid');
	
		$this->mKeyring->expects($this->never())->method('getNodeCertificate');
	
		$this->mKeyring->expects($this->never())->method('getNodePublicKey');
	
		$this->mSnur->expects($this->once())
			->method('requestFirstResponseFromUri')
			->with('spring://other.venus.uk', 'service spring://other.venus.uk/cert/pull/nodekeyid')
			->willReturn($responseMessage);
		
		$this->mKvs->expects($this->once())
			->method('get')
			->with('cert.notify')
			->willReturn(false);
	
		$this->mKservice->expects($this->never())->method('import');
	
		$this->path[] = 'pullreq';
		$response = $this->service->run($this->path, ['source' => 'other.venus.uk']);
		$obj = MessageDecoder::jsonServiceTextStripNode($response);
	
		$this->assertNotNull($obj);
		$this->assertObjectHasAttribute('result', $obj);
		$this->assertEquals('error', $obj->result);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pullreq/?source=other.venus.uk
	 */
	public function testPullRequestNotifyNew() {
	
		$this->stdLocalNodeInterfaceUri($this->mNode);

		$action = 'keyring/pullrequests';
		$source = 'cert';
		$otherNode = 'other.venus.uk';
		$message = "$otherNode is requesting an update to your public certificate";
		
		$mockNotif = new Notification('Certifcate Pull Request',$action,$source,$message);


		$this->mKvs->expects($this->exactly(2))
			->method('get')
			->withConsecutive(['cert.notify'], ['cert.pullreqaction'])
			->willReturn(true, $action);
	
		$this->mNotif->expects($this->once())
			->method('registerNotification')
			->with($mockNotif)
			->willReturn(1001);
		

		$this->mNotif->expects($this->once())
			->method('activateNotification')
			->with(1001)
			->willReturn(true);

		$this->mStore->expects($this->once())
			->method('dataExists')
			->with('cert_pullreq', $otherNode)
			->willReturn(false);
		
		$this->mStore->expects($this->once())
			->method('addData')
			->with('cert_pullreq',$otherNode)
			->willReturn(9);
	
		$this->path[] = 'pullreq';
		$response = $this->service->run($this->path, ['source' => 'other.venus.uk']);
		$obj = MessageDecoder::jsonServiceTextStripNode($response);
	
		$this->assertNotNull($obj);
		$this->assertObjectHasAttribute('result', $obj);
		$this->assertEquals('ok', $obj->result);
	}
	
	/**
	 * Testing point of service:
	 * 		alpha.venus.uk/cert/pullreq/?source=other.venus.uk
	 */
	public function testPullRequestNotifyQueued() {
	
		$this->stdLocalNodeInterfaceUri($this->mNode);
	
		$otherNode = 'other.venus.uk';
	
		$this->mKvs->expects($this->once())
			->method('get')
			->with('cert.notify')
			->willReturn(true);
	
		$this->mNotif->expects($this->never())->method('registerNotification');
	
	
		$this->mNotif->expects($this->never())->method('activateNotification');
	
		$this->mStore->expects($this->once())
			->method('dataExists')
			->with('cert_pullreq', $otherNode)
			->willReturn(true);
	
		$this->mStore->expects($this->never())->method('addData');
	
		$this->path[] = 'pullreq';
		$response = $this->service->run($this->path, ['source' => 'other.venus.uk']);
		$obj = MessageDecoder::jsonServiceTextStripNode($response);
	
		$this->assertNotNull($obj);
		$this->assertObjectHasAttribute('result', $obj);
		$this->assertEquals('queued', $obj->result);
	}
}