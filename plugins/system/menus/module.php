<?php
namespace core\plugin\menus;
use \core\cls\core as core;
use \core\cls\db as db;
use \core\cls\browser as browser;
class module extends view{
	use addons;
	function __construct(){}
	
	/*
	 * ADD menus to administrator area
	 * @return 2D array
	 */
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','menus','newMenu']);
		array_push($menu,[$url, _('New menu')]);
		$url = core\general::createUrl(['service','administrator','load','menus','listMenus']);
		array_push($menu,[$url, _('List menus')]);
		$ret = array();
		array_push($ret, ['<span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>' , _('Menus')]);
		array_push($ret,$menu);
		return $ret;
	}
	
	/*
	 * show list of menus
	 * @return array [title,content]
	 */
	public function moduleListMenus(){
		if($this->hasAdminPanel()){
			$orm = db\orm::singleton();
			return $this->viewListMenus($orm->findAll('menus'));
		}
		return browser\msg::pageAccessDenied();
	}
	
	/*
	 * insert or edite menu
	 * @return array [title,content]
	 */
	public function moduleDoMenu(){
		$options = explode('/',PLUGIN_OPTIONS);
		if($this->hasAdminPanel()){
			$localize = core\localize::singleton();
			if(count($options) == 2){
				//new mode
				return $this->viewDoMenu($localize->getAll());
			}
			else{
				//edite menu
				$orm = db\orm::singleton();
				echo $options[2];
				if($orm->count('menus','id=?',[$options[2]]) != 0){
					$menu = $orm->findOne('menus','id=?',[$options[2]]);
					return $this->viewDoMenu($localize->getAll(),$menu);
				}
				
			}
			
		}
		return browser\msg::pageAccessDenied();	
	}
	
}
