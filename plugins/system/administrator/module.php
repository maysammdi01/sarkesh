<?php
namespace core\plugin\administrator;

use \core\plugin as plugin;
use \core\cls\network as network;
use \core\cls\db as db;
use \core\cls\core as core;
use \core\cls\browser as browser;

class module extends view{

	private $users;
	private $msg;
	private $io;
	private $user;
	function __construct(){
		parent::__construct();
		$this->users = new plugin\users;
		$this->msg = new plugin\msg;
		$this->io = new network\io;
		$user = new plugin\users;
	}
	
	protected function module_load($content,$single_page=false){
		
		return $this->view_load($content[0],$content[1],$single_page);
	}
	
	protected function module_main(){
	
		//get menus from all plugins
		$menu = (array) null;
		$plugins = db\orm::find('plugins','enable=1');
		foreach($plugins as $plugin){
			//now get all menus from plugins
			if(file_exists('./plugins/system/' . $plugin['name'] . '/controller.php')){
				$PluginName = '\\core\\plugin\\' . $plugin['name'];
			}
			else{
				$PluginName = '\\addon\\plugin\\' . $plugin['name'];
			}
			
			$PluginObject = new $PluginName;
			if(method_exists($PluginObject,'core_menu')){
				array_push($menu,call_user_func(array($PluginObject,'core_menu')));
			}
		}
		
		//now $menu is 2d array with plugins menu 
		//show action
		//check for that plugin is set
		if(!isset($_GET['p']) ){
			$_GET['p'] = 'core';
		}
		
		//check for that action is set
		if(!isset($_GET['a']) ){
			$_GET['a'] = 'default';
		}
		
		//now going to do action
		$router = new core\router($_GET['p'], $_GET['a']);
		$plugin_content = $router->show_content(false);
		
		$obj_users = new plugin\users;
		$user_info = $obj_users->get_info();
		$content=$this->module_load(array(_('Administrator:') . $plugin_content[0],$this->view_main($menu,$plugin_content[1],$plugin_content[0],$user_info)));
		return $content;
		
	}
	
	
	// this function show login page and get an input variable that set next page that after login you want to jump
	protected function module_login_page(){
		//get login panel
		$login_panel = $this->users->login();
		$login_panel[1] = browser\page::show_block($login_panel[0],$login_panel[1],'BLOCK');
		return $this->module_load($login_panel,true);
	}
	
	protected function module_no_permission(){
		$message = $this->msg->msg(_('no access'), _('you have no permission'),'danger');
		return $this->module_load($message,true);
	}
	
	protected function module_themes(){
		//check for permission
		if($this->users->has_permission('administrator_admin_panel')){
			//Get all themes that exists
			$directory = scandir(AppPath. '/themes/');
			$themes = (array) null;
			foreach($directory as $files){
				if(is_dir(AppPath . 'themes/' . $files) && $files != '.' && $files != '..'){	
					array_push($themes,$files);
				}
			}
			//get current active theme
			$registry = new core\registry;
			$active_theme = $registry->get('administrator','active_theme');
			
			//get themes info
			$themes_info = (array) null;
			foreach($themes as $theme_file){
				include_once(AppPath . '/themes/' . $theme_file . '/info.php');
				array_push($themes_info,$theme);
			}
			//send to view for show themes
			return $this->view_themes($themes,$themes_info,$active_theme);
		}
		else{
			
			//no permission to access this page
			return $this->msg->access_denied('BLOCK');
		}
	}
	
	//this function return dashboard of administrator area
	protected function module_dashboard(){
	
		return $this->view_dashboard();
	}
	
	//thid function handle btn change theme onclick event
	protected function mudule_btn_change_theme($e){
		
		//first check for permission
		if($this->users->has_permission('administrator_admin_panel')){
			$selected_theme = $e['CLICK']['VALUE'];
			//save new theme in registry
			$registry = new core\registry;
			$registry->set('administrator','active_theme',$selected_theme);
			//successfully changed going to refresh page
			$e['RV']['MODAL'] = browser\page::show_block(_('Change Theme'),_('Successfuly changed!'),'MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
		}
		else{
			//show access denied message
			$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			
		}
		return $e;
	}
	
	//function for manage plugins
	protected function module_plugins(){
		
		//get all plugins from database
		$plugins = db\orm::find('plugins','can_edite != 0');
		return $this->view_plugins($plugins);
		
	}
	
	//function for event holder for change plugin state
	protected function mudule_btn_change_plugin($e){
		
			$selected_plugin = $e['CLICK']['VALUE'];
			$plugin = db\orm::load('plugins',$selected_plugin);
			if($plugin->enable == '1'){
				$plugin->enable = 0;
			}
			else{
				$plugin->enable = 1;
			}
			db\orm::store($plugin);
			//successfully changed going to refresh page
			$e['RV']['MODAL'] = browser\page::show_block(_('Change Plugin state'),_('Successfuly changed!'),'MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
	}
	
	//this function return boolean value
	//if user has permission to admin panel return false and else return false
	public function module_has_admin_panel(){
		if($this->users->has_permission('administrator_admin_panel')){
			return true;
		}
		else{
			return false;
		}
	}
    
    //this function show general settings
    protected function module_basic_settings(){
        //get default site name
        $default_locale = db\orm::findOne('localize','main=1');
        $locales = db\orm::find('localize');
        return $this->view_basic_settings($default_locale,$locales);
    }
    
    //THIS FUNCTION STORE BASIC SETTINGS
    protected function module_onclick_btn_update_basic_settings($e){
        //check for that one of parameters is blank
        if($e['txt_sitename']['VALUE'] == '' || $e['txt_email']['VALUE'] == '' || $e['txt_frontpage']['VALUE'] == ''){
            //FILL BLANK FIELDS
			$e['RV']['MODAL'] = browser\page::show_block(_('System message'),_('Please fill all fields that marked with * .'),'MODAL','type-warning');
			return $e;
        }
        
        //going to save settings
        
        //save default language
        db\orm::exec( 'UPDATE localize SET main=0 WHERE main=1' );
        db\orm::exec( 'UPDATE localize SET main=1 WHERE language_name=?',[$e['cob_language']['SELECTED']] );
        //save settings
        $main_locale = db\orm::findOne('localize','main=1');
        $main_locale->name = $e['txt_sitename']['VALUE'];
        $main_locale->slogan = $e['txt_slogan']['VALUE'];
        $main_locale->home = $e['txt_frontpage']['VALUE'];
        $main_locale->email = $e['txt_email']['VALUE'];
        db\orm::store($main_locale);
        
        //save successfull
        $e['RV']['MODAL'] = browser\page::show_block(_('System message'),_('All changes saved successfuly.'),'MODAL','type-success');
		return $e;
    }
    
    //this function show regional and languages setttings
    protected function module_regandlang(){
        //get all countneries
        //get default country
        $registry = new core\registry;
        $admin_settings = $registry->get_plugin('administrator');
        echo $admin_settings['default_country'];
        $countries = db\orm::find('countries',"ORDER BY country_name=? DESC",[$admin_settings['default_country']]);
        
        //load default timezone
        $timezones = db\orm::find('timezones',"ORDER BY timezone_name=? DESC",[$admin_settings['default_timezone']]);
        return $this->view_regandlang($countries,$timezones);
    }
    
    //function for save regional and language settings
    protected function module_onclick_btn_update_regandlang($e){
        
        //save default country
        $registry = new core\registry;
        $registry->set('administrator','default_country',$e['cob_contries']['SELECTED']);
        //SAVE DEFAULT TIMEZONE
        $registry->set('administrator','default_timezone',$e['cob_timezones']['SELECTED']);
        
        //show successfull message
        $e['RV']['MODAL'] = browser\page::show_block(_('System message'),_('All changes saved successfuly.'),'MODAL','type-success');
		return $e;
        
    }
}	
?>
