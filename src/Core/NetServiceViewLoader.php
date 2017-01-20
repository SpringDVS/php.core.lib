<?php
namespace SpringDvs\Core;

/**
 * Interface for providing a method of loading
 * views from the larger system
 *
 */
interface NetServiceViewLoader {
	/**
	 * Get the rendered version of a view
	 *  
	 * Services get the view through the format:
	 * 
	 * 'module.view'
	 * 
	 * @param string $view The view name
	 * @param mixed[] $data An associative array of data
	 * @return string The rendered view
	 */
	public function load($view, $data);
}