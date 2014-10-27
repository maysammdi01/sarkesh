<?php
namespace core\plugin;
use core\cls\core as core;
use core\plugin\log as log;

class log extends log\module{
	


	/*
	 * This function use for submit log in system
	 * INPUT:USER(STRING),PLUGIN(STRING),OPTION(STRING)
	 * OUTPUT:Log id(INTEGER)
	 */
	 public static function insert($plugin,$key,$options,$user =''){
		 return $this->module_insert($plugin,$key,$options,$user);
	 }
}
