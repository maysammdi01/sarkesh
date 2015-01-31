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
	 
	 	//add menu to administrator area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','log','a','show_reports_filters']);
		array_push($menu,[$url, _('System Reports')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','log','a','php_error_logs']);
		array_push($menu,[$url, _('PHP errors')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','log','a','updates']);
		array_push($menu,[$url, _('Available updates')]);
		$ret = array();
		array_push($ret,['<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>' , _('Reports')]);
		array_push($ret,$menu);
		return $ret;
	}
	
	//this function show php error logs
	public function php_error_logs(){
		return $this->module_php_error_logs();
	}
	
	//this function handle btn event for clear php error logs
	public function onclick_btn_clear_php_logs($e){
		
		return $this->module_onclick_btn_clear_php_logs($e);
	}
	
	//this function show reports filter for select filter and in next step show logs
	public function show_reports_filters(){
		return [1,1];
	}
	
}


