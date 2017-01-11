<?php
use SpringDvs\Core\NetServices\Impl\CciOrgProfileService;
use SpringDvs\Core\NetServices\OrgProfile;

require '../vendor/autoload.php';



class CciOrgProfileServiceTest
extends MockReady
{
	/**
	 * Test point of service:
	 * 		alpha.venus.uk/orgprofile/
	 */
	public function testRequestOrgProfile() {
		$node = $this->mockLocalNodeInterface();
		$this->stdLocalNodeInterfaceUri($node);
		$orgprofile = $this->mockOrgProfileServiceInterface();
		
		$orgprofile->expects($this->once())
			->method('getProfile')
			->willReturn(new OrgProfile('org', 'http://site', ['tag1','tag2']));
		$service = new CciOrgProfileService($orgprofile, $node);
		
		$response = $service->run(['orgprofile'], []);
		$check = MessageDecoder::jsonServiceText($response);
		$this->assertObjectHasAttribute('alpha.venus.uk', $check);
		
		$profile = MessageDecoder::jsonServiceTextStripNode($response);
		
		$this->assertEquals('org', $profile->name);
		$this->assertEquals('http://site', $profile->website);
		$this->assertEquals(['tag1','tag2'], $profile->tags);
	}
}