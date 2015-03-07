<?php
namespace core\plugin\administrator;
use core\cls\db as db;
use core\cls\core as core;

trait addons {
	use \core\plugin\users\addons;
	/*
	 * get administrator menu from all plugins
	 * @return array 2d
	 */
	 public function getMenus(){
		//get menus from all plugins
		$menu = (array) null;
		$orm = db\orm::singleton();
		$plugins = $orm->find('plugins','enable=1');
		foreach($plugins as $plugin){
			//now get all menus from plugins
			if(file_exists(AppPath . '/plugins/system/' . $plugin->name . '/module.php'))
				$PluginName = '\\core\\plugin\\' . $plugin->name . '\\module';
			else
				$PluginName = '\\addon\\plugin\\' . $plugin->name . '\\module';
			$PluginObject = new $PluginName;
			if(method_exists($PluginObject,'coreMenu'))
				array_push($menu,call_user_func(array($PluginObject,'coreMenu')));
		}
		return $menu;
	}
	
	/*
	 * check for that user has adminPanel permission
	 * @return boolean
	 */
	public function hasAdminPanel(){
		if($this->hasPermission('adminPanel'))
			return true;
		return false;
	}
	
	/*
	 * get system active theme
	 * @return string active theme
	 */
	public function activeTheme(){
		$registry = core\registry::singleton();
		return $registry->get('administrator','active_theme');
	}
}
