<?php
namespace core\plugin;
use core\cls\core as core;
use core\plugin\menus as menus;

class menus extends menus\module{
	 
	//add menu to administrator area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','new_menu']);
		array_push($menu,[$url, _('New Menu')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_menus']);
		array_push($menu,[$url, _('List Menus')]);
		$ret = array();
		array_push($ret,_('Menus'));
		array_push($ret,$menu);
		return $ret;
	}
	
	
	
}


