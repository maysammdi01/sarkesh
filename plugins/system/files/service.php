<?php
namespace core\plugin\files;
use \core\cls\core as core;

class service extends module{
	
	function __construct(){}
	
	
	/*
	 * function for do upload file operation
	 * @return string xml content
	 */
	public function doUpload(){
		return $this->moduleDoUpload();
	}
}
