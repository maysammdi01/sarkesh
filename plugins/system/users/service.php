<?php
namespace core\plugin\users;
use \core\cls\core as core;

class service extends module{
	
	function __construct(){
		
	}
	
	/*
	 * show login page in service mode
	 * @return string html content
	 */
	public function login(){
		if(!$this->isLogedin())
			return $this->moduleLoginSinglePage();
		return core\router::jump([PLUGIN_OPTIONS]);
	}
	
	
}
