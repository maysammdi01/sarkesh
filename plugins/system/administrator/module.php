<?php
namespace core\plugin\administrator;
use \core\cls\db as db;
use \core\cls\browser as browser;
use \core\cls\core as core;

class module extends view{
	use addons;

	/*
	 * construct
	 */
	function __construct(){
		
	}
	
	//this function return back menus for use in admin area
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','administrator','plugins']);
		array_push($menu,[$url, _('Plugins')]);
		$url = core\general::createUrl(['service','administrator','load','administrator','regAndLang']);
		array_push($menu,[$url, _('Regional and Languages')]);
		$url = core\general::createUrl(['service','administrator','load','administrator','basicSettings']);
		array_push($menu,[$url, _('Basic Settings')]);
		$url = core\general::createUrl(['service','administrator','load','administrator','themes']);
		array_push($menu,[$url, _('Apperance')]);
		$url = core\general::createUrl(['service','administrator','load','administrator','blocks']);
		array_push($menu,[$url, _('Blocks')]);
		$url = core\general::createUrl(['service','administrator','load','administrator','coreSettings']);
		array_push($menu,[$url, _('Core Settings')]);
		$url = core\general::createUrl(['service','administrator','load','administrator','checkUpdate']);
		array_push($menu,[$url, _('Update Center')]);
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
			if($this->hasPermission('adminPanel')){
				$opt = explode('/',$opt);
				$registry = core\registry::singleton();
				$router = new core\router($opt[0],$opt[1]);
				$content = $router->showContent(false);
				return $this->viewLoad($this->getMenus(),$content,$this->getCurrentUserInfo(),$registry->getPlugin('administrator'));
			}
			//show access denied message
			return browser\msg::serviceAccessDenied();
		}
		return core\router::jump(['service','users','login','service/administrator/load/administrator/dashboard']);
	}
	
	/*
	 * show dashboard administrator form
	 * @return string, html content
	 */
	protected function moduleDashboard(){
		if($this->hasAdminPanel())
			return $this->viewDashboard();
		return browser\msg::pageAccessDenied();
	}
	
	/*
	 * check for updates
	 * @return string, html content
	 */
	protected function moduleCheckUpdate(){
		if($this->hasAdminPanel()){
			$registry = core\registry::singleton();
			return $this->viewCheckUpdate($registry->get('administrator','build_num'),file_get_contents(S_SERVER_INFO . 'last_version.txt'));
		}
		return browser\msg::pageAccessDenied();
	}
	
	/*
	 * show core settings page
	 * @return string, html content
	 */
	protected function moduleCoreSettings(){
		if($this->hasAdminPanel()){
			$registry = core\registry::singleton();
			return $this->viewCoreSettings($registry->getPlugin('administrator'));
		}
		return browser\msg::pageAccessDenied();		
	}
	
	/*
	 * show manage block form
	 * @return string, html content
	 */
	protected function moduleBlocks(){
		if($this->hasAdminPanel()){
			//get all blocks from database 
			$sql = "SELECT b.id, b.plugin, p.id AS 'plugin_id', b.name as 'block_name', b.position, b.rank, b.handel, b.visual , p.name FROM blocks b INNER JOIN plugins p ON b.plugin=p.id WHERE b.name != 'content';";
			$orm = db\orm::singleton();
			$blocks = $orm->exec($sql,[],SELECT);
			//get placess from active theme
			$theme = $this->activeTheme();
			//get places from theme file
			$themeAdr = '\\theme\\' . $theme;
			$themeObj = new $themeAdr;
			$places = $themeObj->getPlaces();
			return $this->viewBlocks($blocks,$places);
		}
		return browser\msg::pageAccessDenied();	
		
	}
	
	/*
	 * show manage block form
	 * @return string, html content
	 */
	protected function moduleBasicSettings(){
		if($this->hasAdminPanel()){
			//get all localize from database
			$orm = db\orm::singleton();
			$locals = $orm->findAll('localize');
			return $this->viewBasicSettings($locals);
		}
		return browser\msg::pageAccessDenied();
	}
	
	/*
	 * edite localize settings
	 * @return string, html content
	 */
	protected function moduleBasicSettingsEdite(){
		$options = explode('/',PLUGIN_OPTIONS);
		if(count($options) == 3){
			if($this->hasAdminPanel()){
				//get all localize from database
				$orm = db\orm::singleton();
				return $this->viewBasicSettingsEdite($orm->findOne('localize','id=?',[$options[2]]));
			}
			return browser\msg::pageAccessDenied();	
		}
		return browser\msg::pageError();
	}
	
	/*
	 * show form for add new static block
	 * @return string, html content
	 */
	protected function moduleNewStaticBlock(){
		if($this->hasAdminPanel()){
			return $this->viewNewStaticBlock();
		}
		return browser\msg::pageAccessDenied();		
	}
	
	/*
	 * show form for edite
	 * @return string, html content
	 */
	public function moduleEditeBlock(){
		$options = explode('/',PLUGIN_OPTIONS);
		if(count($options) == 3){
			if($this->hasAdminPanel()){
				//check for that is id cerrect
				$orm = db\orm::singleton();
				if($orm->count('blocks','id=?',[$options[2]]) != 0){
				
					//get locations from theme file
					$activeTheme = $this->activeTheme();
					$places = array();
					if(method_exists('\\theme\\' . $activeTheme,'getPlaces'))
						$places = call_user_func(array('\\theme\\' . $activeTheme,'getPlaces'),'content');
					array_push($places,'Off');
					
					//id is cerrect
					$block = $orm->findOne('blocks','id=?',[$options[2]]);
					//get all localizes
					$locals = $orm->findAll('localize');
					$languages = [];
					foreach ($locals as $key => $local)
						array_push($languages, [$local->language,$local->language_name]);
					//add all block
					array_push($languages, ['all',_('All languages')]);
					return $this->viewEditeBlock($block,$places,$languages);
				}
				return browser\msg::pageNotFound();
			}
			return browser\msg::pageAccessDenied();	
		}
		return browser\msg::pageError();
	}
	
	/*
	 * edite static block
	 * @return string, html content
	 */
	protected function moduleEditeStaticBlock(){
				$options = explode('/',PLUGIN_OPTIONS);
		if(count($options) == 3){
			if($this->hasAdminPanel()){
				//check for that is id cerrect
				$orm = db\orm::singleton();
				if($orm->count('blocks','id=?',[$options[2]]) != 0)
					return $this->viewNewStaticBlock($orm->findOne('blocks','id=?',[$options[2]]));
				return browser\msg::pageNotFound();
			}
			return browser\msg::pageAccessDenied();	
		}
		return browser\msg::pageError();
	}
}
