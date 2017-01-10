<?php
namespace \SpringDvs\Core\NetServices\Impl;
use SpringDvs\Core\NetServiceInterface;
use SpringDvs\Core\NetServices\KeyringInterface;
use SpringDvs\Core\NetServiceKeyStore;
use SpringDvs\Core\Snur;
use SpringDvs\Core\DataStoreInterface;
use SpringDvs\Core\NotificationInterface;
use SpringDvs\ProtocolResponse;
use SpringDvs\Core\LocalNodeInterface;
use SpringDvs\Core\ServiceEncoding;
use SpringDvs\Message;
use SpringDvs\Core\Notification;
use SpringDvs\Core\NetServices\CertificatePullInterface;
use SpringDvs\Core\NetServices\KeyServiceInterface;


class CicCertService
implements NetServiceInterface, CertificatePullInterface
{
	/**
	 * @var \SpringDvs\Core\NetServices\KeyringInterface The keyring
	 */
	private $keyring;
	
	/**
	 * @var NetServiceKeyStore The key-value store for netservices
	 */
	private $kvs;
	
	/**
	 * @var Snur SpringNet URI request object
	 */
	private $snur;
	
	/**
	 * @var DataStoreInterface The service data store
	 */
	private $store;
	
	/**
	 * @var LocalNodeInterface The local node
	 */
	private $node;
	
	/**
	 * @var SpringDvs\Core\NetServices\KeyServiceInterface The key service
	 */
	private $kservice;
	
	/**
	 * @var \SpringDvs\Core\NotificationInterface Interface for registering notifications
	 */
	private $notif;
	
	public function __construct(KeyringInterface $keyring, NetServiceKeyStore $kvs,
								DataStoreInterface $store, NotificationInterface $notif,
								LocalNodeInterface $node, KeyServiceInterface $kservice,
								Snur $snur) {
		$this->keyring = $keyring;
		$this->kvs = $kvs;
		$this->snur = $snur;
		$this->store = $store;
		$this->notif = $notif;
		$this->node = $node;
		$this->kservice = $kservice;
	}

	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServiceInterface::run()
	 */
	public function run($uriPath, $uriQuery) {
		$branch = isset($uriPath[1]) ? $uriPath : null;
	
		switch($branch) {
			case null:
				return $this->getCertificate();
			case 'key':
				return $this->getKey();
			case 'pull':
				$keyid = isset($uriPath[2])
							? $uriPath[2]
							: null;
				return $this->pullRequest($keyid);
			case 'pullreq':
				$source = isset($uriQuery['source'])
							? $uriQuery['source']
							: null;
				return $this->pullRequest($source);
			default:
				return ''.ProtocolResponse::UnsupportedAction;
		}
	}
		
	private function getCertificate() {
		$cert = $this->keyring->getNodeCertificate();
		
		$response = null; 
		if($cert) {
			$response = [
				'name' => $cert->name(),
				'email' => $cert->email(),
				'keyid' => $cert->keyid(),
				'sigs' => $cert->signatureKeyids(),
				'armor' => $cert->armor()
			];
		} else {
			$respones = 'error';
		}
		
		return ServiceEncoding::json(['certificate' => $response], $this->node);
	}
	
	private function getKey() {
		$key = $this->keyring->getNodePublicKey();
		
		$response = 'error';
		if($key) {
			$response = $key->armor();
		}
		
		return ServiceEncoding::json(['key' => $response], $this->node);
	}
	
	private function pull($keyid) {
		$response = 'error';
		if($keyid && ($key = $this->keyring->getKey($keyid))) {
			$response = $key->armor();
		}

		return ServiceEncoding::json(['key' => $response], $this->node);
	}
	
	private function pullRequest($source) {
		if($source == null) {
			return ServiceEncoding::json(['result' => 'error'], $this->node);
		}
		
		if($this->kvs->get('cert.notify')) {
			return $this->notify($source);
		} else {
			return $this->performPull($source);
		}
	}
	
	private function notify($source) {
		$tag = 'cert_pullreq';
		if($this->store->dataExists($tag, $source)) {
			return ServiceEncoding::json(['result' => 'queued'], $this->node);
		}

		$action = $this->kvs('cert.pullreqaction');
		$message = "$source is requesting an update to your public certificate";
		
		$nid = $this->notif->registerNotification(new Notification('Certifcate Pull Request',
																   $action,
																   $source,
																   $message));
		
		$this->store->addData($tag, $store);
		
		$this->notif->activateNotification($nid);
		return ServiceEncoding::json(['result' => 'ok'], $this->node);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \SpringDvs\Core\NetServices\CertificatePullInterface::performPull()
	 */
	public function performPull($source) {
		$keyid = $this->keyring->getNodeKeyid();
		$uri = "spring://$source";
		try {
			$response = $this->snur->requestFirstResponseFromUri($uri, "service $uri/cert/pull/$keyid", $local);
			$pulled = json_decode($response->getContentResponse()->getServiceText()->get());
			$obj = reset($pulled);
			$key = (isset($obj['key']) && $obj['key'] != "error")
						? new Certificate($obj['key'])
						: null;
			
			if(!$key){ return false; }
			
			
			$subject = $this->keyring->getNodePublicKey();
			if(!$subject){ return false; }
			
			$certificate = $this->kservice->import($key, $subject);
			if(!$certificate){ return false; }
						
			$this->keyring->setNodeCertificate($certificate);
			return true;
		} catch(\Exception $e) {
			return false;
		}
		
	}
}