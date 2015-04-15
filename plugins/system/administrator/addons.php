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
	
	/*
	 * this function search plugin directory and install new plugins in database and remove deleted plugins
	 */
	public function refreshPlugins(){
		$orm = db\orm::singleton();
		$pluginsDB = $orm->findAll('plugins');
		$dir = AppPath . 'plugins/defined';
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			if(is_dir(AppPath . 'plugins/defined/' . $filename) && $filename != '.' && $filename != '..'){
				$files[] = $filename;
			}
		}
		$newPlugins = [];
		
		foreach($files as $file){
			$isExists = false;
			foreach($pluginsDB as $pluginDB){
				if($file == $pluginDB->name) $isExists = true;
			}
			if(! $isExists) array_push($newPlugins,$file);
		}
		//install new plugins
		foreach($newPlugins as $plugin){
			$fileAdr = AppPath . 'plugins/defined/' . $plugin . '/setup.php';
			if(file_exists($fileAdr)){
				if(method_exists('\\addon\\plugin\\' . $plugin . '\\setup' ,'install')){
					$newPlugin = $orm->dispense('plugins');
					$newPlugin->name = $plugin;
					$newPlugin->enable = 0;
					$newPlugin->can_edite = 1;
					$orm->store($newPlugin);
					$pluginString = '\\addon\\plugin\\' . $plugin . '\\setup';
					$pluginObj = new $pluginString;
					call_user_func(array( $pluginObj,'install'));
				}
			}
		}
	}
	
	/*
	 * install widget
	 * @param string $name, function name
	 * @return boolean
	 */
	public function installWidget($pluginName,$funcName,$name,$value=0){
		$orm = db\orm::singleton();
		$plugin = $orm->findOne('plugins','name=?',[$pluginName]);
		if($orm->count('blocks','plugin=? and handel=?',[$plugin->id,$funcName]) == 0){
			$block = $orm->dispense('blocks');
			$block->name = $name;
			$block->value = $value;
			$block->plugin = $plugin->id;
			$block->position = 'Off';
			$block->permissions = null;
			$block->pages = null;
			$block->pages_ad = 0;
			$block->rank = 0;
			$block->handel = $funcName;
			$block->show_header = 1;
			$block->localize = 'all';
			$block->visual = 1;
			$orm->store($block);
			return true;
		}
		return false;
	}
}
