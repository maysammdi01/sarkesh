<?php
namespace core\plugin;
use core\cls\core as core;
use core\plugin\reports as reports;

class reports extends reports\module{

	//add menu to administrator area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','reports','a','php_error_logs']);
		array_push($menu,[$url, _('PHP errors')]);

		$ret = array();
		array_push($ret,_('Reports'));
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
	
}
