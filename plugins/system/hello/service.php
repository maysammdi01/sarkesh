<?php
namespace core\plugin\hello;
use \core\control as control;

class service extends module{
	
	function __construct(){
		
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function sample(){
		$result = $this->moduleSample();
		return $result[1];
	 }
}
