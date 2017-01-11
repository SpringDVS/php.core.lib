<?php
namespace SpringDvs\Core;

class NetServiceKeyStore {
	private $map;
	
	public function registerStorage($module, $storage) {
		$this->map[$module] = $storage;
	}
	
	public function get($key) {
		$part = explode('.', $key);
		if(count($part) != 2) {
			return null;
		}

		$module = $part[0];
		$variable = $part[1];
		
		if(!isset($this->map[$module])
		|| !is_callable([$this->map[$module], $variable])) {
			return null;
		}
		
		return $this->map[$module]->$variable();
	}
}