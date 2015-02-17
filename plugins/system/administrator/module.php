<?php
namespace core\plugin\administrator;

use \core\plugin as plugin;
use \core\cls\network as network;
use \core\cls\db as db;
use \core\cls\core as core;
use \core\cls\browser as browser;

class module extends view{
	private $msg;
	private $io;
	function __construct(){
		parent::__construct();
		$this->msg = new plugin\msg;
		$this->io = new network\io;
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
		
		//get administrator settings from registry
		$registry = core\registry::singleton();
		$registry = $registry->getPlugin('administrator');
		$obj_users = new plugin\users;
		$user_info = $obj_users->get_info();
		return $this->module_load(array(_('Administrator:') . $plugin_content[0],$this->view_main($menu,$plugin_content[1],$plugin_content[0],$user_info,$registry)));
	}
	
	
	// this function show login page and get an input variable that set next page that after login you want to jump
	protected function module_login_page(){
		//get login panel
		$users = new plugin\users;
		$login_panel = $users->login();
		$user = null;
		$login_panel[1] = browser\page::show_block($login_panel[0],$login_panel[1],'BLOCK');
		return $this->module_load($login_panel,true);
	}
	
	protected function module_no_permission(){
		$message = $this->msg->msg(_('no access'), _('you have no permission'),'danger');
		return $this->module_load($message,true);
	}
	
	protected function module_themes(){
		//check for permission
		$users = new plugin\users;
		if($users->has_permission('administrator_admin_panel')){
			//Get all themes that exists
			$directory = scandir(AppPath. '/themes/');
			$themes = (array) null;
			foreach($directory as $files){
				if(is_dir(AppPath . 'themes/' . $files) && $files != '.' && $files != '..'){	
					array_push($themes,$files);
				}
			}
			//get current active theme
			$active_theme = $this->active_theme();
			
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
		$users = null;
	}
	
	//this function return dashboard of administrator area
	protected function module_dashboard(){
	
		return $this->view_dashboard();
	}
	
	//thid function handle btn change theme onclick event
	protected function mudule_btn_change_theme($e){
		
		//first check for permission
		$users = new plugin\users;
		if($users->has_permission('administrator_admin_panel')){
			$selected_theme = $e['CLICK']['VALUE'];
			//save new theme in registry
			$registry = new core\registry;
			$registry->set('administrator','active_theme',$selected_theme);
			db\orm::exec("UPDATE blocks SET position='Off' WHERE name != 'content'");
			//successfully changed going to refresh page
			$e['RV']['MODAL'] = browser\page::show_block(_('Change Theme'),_('Successfuly changed!'),'MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
		}
		//show access denied message
		$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
		$e['RV']['JUMP_AFTER_MODAL'] = 'R';
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
		$users = new plugin\users;
		if($users->has_permission('administrator_admin_panel')){
			return true;
		}
		else{
			return false;
		}
		$users=null;
	}
    //show list of localize for edite basic settings
    protected function module_basic_settings(){
    	//get all localize from database
    	$locals = db\orm::findAll('localize');
    	return $this->view_basic_settings($locals);
    }
    //this function show general settings
    protected function module_basic_settings_edite(){
    	if(isset($_REQUEST['id'])){
    		if(db\orm::count('localize','id=?',[$_REQUEST['id']])){
    			//get all localize from database
		    	$local = db\orm::load('localize',$_REQUEST['id']);
		    	$default_locale = db\orm::findOne('localize','main=1');
		    	return $this->view_basic_settings_edite($local,$default_locale);
    		}
    	}
        //get default site name
        $default_locale = db\orm::findOne('localize','main=1');
        //get description value from registry
        $registry = new core\registry;
        return $this->view_basic_settings_edite($default_locale,$locales,$registry->getPlugin('administrator'));
    }
    
    //THIS FUNCTION STORE BASIC SETTINGS
    protected function module_onclick_btn_update_basic_settings_edite($e){

        //check for that one of parameters is blank
        if($e['txt_sitename']['VALUE'] == '' || $e['txt_email']['VALUE'] == '' || $e['txt_frontpage']['VALUE'] == ''){
            //FILL BLANK FIELDS
			$e['RV']['MODAL'] = browser\page::show_block(_('System message'),_('Please fill all fields that marked with * .'),'MODAL','type-warning');
			return $e;
        }
        
        //going to save settings
        //save settings
        $main_locale = db\orm::load('localize',$e['hid_id']['VALUE']);
        $main_locale->name = $e['txt_sitename']['VALUE'];
        $main_locale->slogan = $e['txt_slogan']['VALUE'];
        $main_locale->home = $e['txt_frontpage']['VALUE'];
        $main_locale->email = $e['txt_email']['VALUE'];
        $main_locale->header_tags = $e['txt_des']['VALUE'];
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
        $admin_settings = $registry->getPlugin('administrator');
        $countries = db\orm::find('countries',"ORDER BY country_name=? DESC",[$admin_settings['default_country']]);
        
        //load default timezone
        $timezones = db\orm::find('timezones',"ORDER BY timezone_name=? DESC",[$admin_settings['default_timezone']]);
        
        //get localize
        $localize = new core\localize;
        $locals = $localize->get_all();
        
        //get default language
        $default_language = $localize->get_default_language();
        return $this->view_regandlang($countries,$timezones,$locals,$default_language);
    }
    
    //function for save regional and language settings
    protected function module_onclick_btn_update_regandlang($e){
        
        //save default country
        $registry = new core\registry;
        $registry->set('administrator','default_country',$e['cob_contries']['SELECTED']);
        //SAVE DEFAULT TIMEZONE
        $registry->set('administrator','default_timezone',$e['cob_timezones']['SELECTED']);
        
        //save default localize
        //disactive old localize
        $localize = db\orm::findOne('localize','main=\'1\'');
        $localize->main = 0;
        db\orm::store($localize);
        //active new localize
        $localize = db\orm::findOne('localize','id=?',[$e['cob_language']['SELECTED']]);
        $localize->main = 1;
        db\orm::store($localize);
        
        
        //show successfull message
        $e['RV']['MODAL'] = browser\page::show_block(_('System message'),_('All changes saved successfuly.'),'MODAL','type-success');
		return $e;
        
    }
    
    //this function show blocks
    protected function module_blocks(){
		
		//get all blocks from database 
		$sql = "SELECT b.id, b.plugin, p.id AS 'plugin_id', b.name as 'block_name', b.position, b.rank, b.handel, b.visual , p.name FROM blocks b INNER JOIN plugins p ON b.plugin=p.id WHERE b.name != 'content';";
		$rows = db\orm::getAll( $sql );
    	$blocks = db\orm::convertToBeans( 'blocks', $rows );

		//get placess from active theme
		$theme = $this->active_theme();
		
		//get places from theme file
		$theme_adr = '\\theme\\' . $theme;
		$obj_theme = new $theme_adr;
		$places = $obj_theme->get_places();
		
		//send to view to show
		return $this->view_blocks($blocks,$places);
	}
	
	//function for edite blocks
	protected function module_edite_block($id){
		
		//check for that is id cerrect
		if(db\orm::count('blocks','id=?',[$id]) != 0){
			
			//get locations from theme file
			$active_theme = $this->active_theme();
			$places = array();
			if(method_exists('\\theme\\' . $active_theme,'get_places')){
				$places = call_user_func(array('\\theme\\' . $active_theme,'get_places'),'content');
			} 
			array_push($places,'Off');
			
			//id is cerrect
			$block = db\orm::findOne('blocks','id=?',[$id]);
			//get all localizes
			$locals = db\orm::findAll('localize');
			$languages = [];
			foreach ($locals as $key => $local) {
				array_push($languages, [$local->language,$local->language_name]);
			}
			//add all block
			array_push($languages, ['all',_('All languages')]);
			return $this->view_edite_block($block,$places,$languages);
		}
		else{
			//show not found message
			core\router::jump_page(404);
		} 
	}
	
	//function for handel edite blocks
	protected function module_onclick_btn_update_block($e){
		if(db\orm::count('blocks','id=?',[$e['hid_id']['VALUE']]) != 0){
			$block = db\orm::findOne('blocks','id=?',[$e['hid_id']['VALUE']]);
			$block->rank = $e['cob_rank']['SELECTED'];
			$block->position = $e['cob_position']['SELECTED'];
			$block->localize = $e['cob_language']['SELECTED'];
			$block->pages = $e['txt_pages']['VALUE'];
			$block->pages_ad = '0';
			if($e['rad_it_allow']['CHECKED'] == '1'){
				$block->pages_ad = '1';
			}
			
			//SHOW HEADER SAVE
			$block->show_header = '0';
			if($e['ckb_show_header']['CHECKED'] == '1'){
				$block->show_header = '1';
			}

			//save changes
			db\orm::store($block);
			$e['RV']['MODAL'] = browser\page::show_block(_('Update Block'),_('Successfuly Updated.'),'MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = urlencode(core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','blocks']));
			return $e;
		}
	}
	
	//this function return cerrect active theme of system
	protected function active_theme(){
		//get current active theme
		$registry = new core\registry;
		return $registry->get('administrator','active_theme');
	}

	//function for control core settings
	protected function module_core_settings(){
		//get settings
		$registry = core\registry::singleton();
		$settings = $registry->getPlugin('administrator');
		return $this->view_core_settings($settings);
	}

	//function for save core settings
	protected function module_onclick_btn_update_core_settings($e){
		if(array_key_exists('ckb_clean_url', $e)){
			$registry = core\registry::singleton();
			$registry->set('administrator','clean_url',$e['ckb_clean_url']['CHECKED']);
			return $this->msg->successfull_modal($e,'N');
		}
		return $this->msg->error_modal($e);
	}

	//Fuction for show sure delete localize
	public function module_sure_delete_local(){
		if(isset($_REQUEST['id'])){
			if(db\orm::count('localize','id=?',[$_REQUEST['id']])){
				return $this->view_sure_delete_local(db\orm::load('localize',$_REQUEST['id']));
			}
		}
		return $this->msg->error();
	}
	//function for delete local
	protected function module_onclick_btn_delete_local($e){
		if(array_key_exists('hid_id', $e)){
			$local = db\orm::load('localize',$e['hid_id']['VALUE']);
			//delete local
			if($local->can_delete != 0)
				db\orm::exec('DELETE FROM localize WHERE id=?',[$e['hid_id']['VALUE']]);
			return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','administrator','a','basic_settings']);
		}
		return $this->msg->error_modal($e);
	}

	//this function is for add static block
	protected function module_add_static_block(){
        return $this->view_static_block();
	}

	//this function is for add static function 
	protected function module_onclick_btn_do_block($e){
		if(array_key_exists('txt_block_name', $e) && array_key_exists('txt_block_label', $e) && array_key_exists('ckb_show_header', $e) && array_key_exists('txt_content', $e)){
			if($e['txt_content']['VALUE'] != ''){
				//get id of administrator in plugins
				$plugin = db\orm::findOne('plugins','name=?',['administrator']);
				$block = db\orm::dispense('blocks');
				if(array_key_exists('hid_id', $e)){
					$block = db\orm::load('blocks',$e['hid_id']['VALUE']);
				}
				else{
					$block->position = 'Off';
				}
				$block->name = $e['txt_block_name']['VALUE'];
				$block->plugin = $plugin->id;
				$block->value = $e['txt_block_label']['VALUE'] . '<::::>' . $e['txt_content']['VALUE'];
				$block->visual = '1';
				$block->handel = 'static_block';
				$block->show_header = '0';
				if($e['ckb_show_header']['CHECKED'] == '1'){
					$block->show_header = '1';
				}
				db\orm::store($block);
				$local = db\orm::load('localize',$e['hid_id']['VALUE']);
				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','administrator','a','blocks']);
			}
			//fill all message
			return $this->msg->not_complete_modal($e);
		}
		return $this->msg->error_modal($e);
	}

	//this function show static block
	protected function module_static_block($position,$value){
		return explode('<::::>',$value);
	}

	//function for show delete static block
	protected function module_sure_delete_block(){
		if(isset($_REQUEST['id'])){
			if(db\orm::count('blocks','id=?',[$_REQUEST['id']])){
				$block = db\orm::load('blocks',$_REQUEST['id']);
				$plugin = db\orm::findOne('plugins',"name='administrator'");
				if($block->visual == '1' && $block->handel == 'static_block' && $block->plugin == $plugin->id){
					return $this->view_sure_delete_block($block);
				}
			}
		}
		return $this->msg->error();
	}

	//function for delete block
	protected function module_onclick_btn_delete_static_block($e){
		if(array_key_exists('hid_id', $e) ){
			if(db\orm::count('blocks','id=?',[$e['hid_id']['VALUE']])){
				$block = db\orm::load('blocks',$e['hid_id']['VALUE']);
				$plugin = db\orm::findOne('plugins',"name='administrator'");
				if($block->visual == '1' && $block->handel == 'static_block' && $block->plugin == $plugin->id){
					db\orm::exec('DELETE FROM blocks WHERE id=?',[$e['hid_id']['VALUE']]);
					return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','administrator','a','blocks']);
				}
			}
			//fill all message
			return $this->msg->not_complete_modal($e);
		}
		return $this->msg->error_modal($e);
	}

	//function for edite static blocks
	protected function module_edite_static_block(){
		if(isset($_REQUEST['id'])){
			if(db\orm::count('blocks','id=?',[$_REQUEST['id']])){
				$block = db\orm::load('blocks',$_REQUEST['id']);
				$plugin = db\orm::findOne('plugins',"name='administrator'");
				if($block->visual == '1' && $block->handel == 'static_block' && $block->plugin == $plugin->id){
					return $this->view_static_block($block);
				}
			}
		}
		return $this->msg->error();
	}
}	
?>
