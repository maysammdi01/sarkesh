<?php
namespace core\plugin\administrator;
use \core\cls\db as db;
use \core\cls\core as core;

class module extends view{
	use addons;
	use \core\plugin\users\addons;
	/*
	 * construct
	 */
	function __construct(){
		parent::__construct();
	}
	
	//this function return back menus for use in admin area
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','administrator','dashboard']);
		array_push($menu,[$url, _('Dashboard')]);
		$ret = array();
		array_push($ret, ['<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>' , _('Administrator')]);
		array_push($ret,$menu);
		return $ret;
	}
	
	/*
	 * load basic administrator panel
	 * @param string $opt, option of action
	 * @return string, html content
	 */
	protected function moduleLoad($opt){
		if($this->isLogedin()){
			$opt = explode('/',$opt);
			$registry = core\registry::singleton();
			return $this->viewLoad($this->getMenus(),$opt,$this->getCurrentUserInfo(),$registry->getPlugin('administrator'));
		}
		return core\router::jump(['service','users','login','service/administrator/load/administrator/dashboard']);
	}
}
