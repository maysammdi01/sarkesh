<?php
namespace core\plugin;
use \core\plugin\administrator as administrator;
use \core\cls\core as core;
use \core\cls\browser as browser;
class administrator extends administrator\module{

	private $msg;
	private $users;
	function __construct(){
		parent::__construct();
		$this->msg = new msg;
		$this->users = new users;
	}
	
	//this function return back menus for use in admin area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']);
		array_push($menu,[$url, _('Dashboard')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','plugins']);
		array_push($menu,[$url, _('Plugins')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','themes']);
		array_push($menu,[$url, _('Apperance')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','blocks']);
		array_push($menu,[$url, _('Blocks')]);
		$ret = array();
		array_push($ret,_('Administrator'));
		array_push($ret,$menu);
		return $ret;
	}
	//this service load basic of html page of admin panel.
	//it's service and just return html elements of basic page
	//$content is an array [title of page,content of page]
	private function load($content){
			return $this->module_load($content);
	}
	
	
	///this function show administrator panel
	//it's service
	public function main(){
		//first check for permission
		if($this->users->is_logedin() && $this->users->has_permission('administrator_admin_panel')	){
			
			//check for that user come from login process
			if($_GET['p'] == 'users' && $_GET['a'] == 'login'){
				//user come from login process and now should jump to default administrator page
				core\router::jump_page(core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','dashboard')	)	);
			}
			else{
				//going to show content
				return $this->module_main();
			}
		}
		elseif(!$this->users->is_logedin()){
			//user do not has any permission to access  to administrator area
			if($_GET['p'] == 'users' && $_GET['a'] == 'login'){
				//show login page
				return $this->module_login_page();
			}
			else{
				//jump to login page
				core\router::jump_page(core\general::create_url(array('service','1','plugin','administrator','action','main','p','users','a','login')	)	);
			}
			
		}
		//show no permission message
		elseif($this->users->has_permission('administrator_admin_panel') != true){
			return $this->module_no_permission();
		}
		
		
	}
	
	
	
	//This function show themes panel for manage and select
	public function themes(){
		
		return $this->module_themes();
	}
	
	#This function return back dashboard of administrator area
	public function dashboard(){
		
		return $this->module_dashboard();
	}
	
	//with this function change selected theme
	public function btn_change_theme($e){
		
		return $this->mudule_btn_change_theme($e);
	}
	
	//this function start installing system
	//it's service 
	public function install(){
		
	}
	
	//function for manage plugins of system
	public function plugins(){
		//first check for permission
		if($this->users->is_logedin() && $this->users->has_permission('administrator_admin_panel')	){
			return $this->module_plugins();
		}
		else{
			//access denied
			return $this->module_no_permission();
		}
		
	}
	
	//function for event holder for button that change plugin state
	public function btn_change_plugin($e){
		//first check for permission
		if($this->users->is_logedin() && $this->users->has_permission('administrator_admin_panel')	){
			return $this->mudule_btn_change_plugin($e);
		}
		else{
			//access denied
			$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
		}
		
	}
	
	//this function return boolean value
	//if user has permission to admin panel return false and else return false
	public function has_admin_panel(){
		return $this->module_has_admin_panel();
	}
    
    //this function show basic settings in admin panel
    public function basic_settings(){
        if($this->has_admin_panel()){
            return $this->module_basic_settings();
        }
        else{
            //access denied
           	return $this->module_no_permission();
        }
    }
    
    //this function is for handle onclick event and store basic settings
    public function onclick_btn_update_basic_settings($e){
        if($this->has_admin_panel()){
            return $this->module_onclick_btn_update_basic_settings($e);
        }
        else{
            //access denied
			$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
        }
    }
    
    //this function show regional and languages page
    public function regandlang(){
        if($this->has_admin_panel()){
            return $this->module_regandlang();
        }
        else{
            //access denied
            return $this->module_no_permission();
        }
    }
    
    //this function handle event and save regional and language settings
    public function onclick_btn_update_regandlang($e){
        if($this->has_admin_panel()){
            return $this->module_onclick_btn_update_regandlang($e);
        }
        else{
            //access denied
			$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
        }
    }
    
    //this function show blocks for manage in theme
    public function blocks(){
		if($this->has_admin_panel()){
            return $this->module_blocks();
        }
        else{
            //access denied
            return $this->module_no_permission();
        }
	}
	
	//this function is for edite block
	//id of block get with $GET
	public function edite_block(){
		if(isset($_GET['id'])){
			return $this->module_edite_block($_GET['id']);
		}
		else{
			//jump to not found page
			core\router::jump_page(404);

		}
	}
	
	
	//this function is button event handel for edite block
	public function onclick_btn_update_block($e){
		if($this->has_admin_panel()){
            return $this->module_onclick_btn_update_block($e);
        }
        else{
            //access denied
			$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
        }
	}
	
	
}
	
	
