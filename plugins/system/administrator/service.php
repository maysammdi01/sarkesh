<?php
namespace core\plugin\administrator;


class service extends module{
	
	function __construct(){
		
	}
	
	/*
	 * for load basic html panel
	 * @return string, html content
	 */
	public function load()
		return $this->moduleLoad();
}
