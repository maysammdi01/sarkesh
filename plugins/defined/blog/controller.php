<?php
namespace addon\plugin;
use core\cls\core as core;
use addon\plugin\blog as blog;

class blog extends blog\module{
	 
	//add menu to administrator area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','new_post']);
		array_push($menu,[$url, _('New Post')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);
		array_push($menu,[$url, _('Catalogues')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','settings']);
		array_push($menu,[$url, _('Blog Settings')]);
		$ret = array();
		array_push($ret,_('Blog'));
		array_push($ret,$menu);
		return $ret;
	}
	
	
	
}


