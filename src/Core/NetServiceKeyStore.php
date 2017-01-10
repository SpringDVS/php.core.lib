<?php
namespace SpringDvs\Core;

class NetServiceKeyStore {
	private $map;
	
	public function registerStorage($module, $storage) {
		$this->map[$module] = $storage;
	}
	
	public function get($key) {
		$part = explode('.', $key);
		$storage = $this->map[$part[0]];
		$variable = $part[1];
		
		if(!isset($this->map[$storage])
		|| !is_callable([$storage, $variable])) {
			return null;
		}

		return $storage->$variable;
	}
}