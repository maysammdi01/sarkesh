<?php
namespace core\plugin;
use core\cls\core as core;
use core\plugin\log as log;

class log extends log\module{
	
	//this function return url of core menus to admin area
	public static function core_menu(){
		$menus = [[core\general::create_url(array('plugin','log','action','log_dashbord')	),_('Syatem log')]];
		
		return $menus;
	}

	/*
	 * This function use for submit log in system
	 * INPUT:USER(STRING),PLUGIN(STRING),OPTION(STRING)
	 * OUTPUT:Log id(INTEGER)
	 */
	 public function insert($plugin,$key,$options,$user =''){
		 return $this->module_insert($plugin,$key,$options,$user);
	 }
}
