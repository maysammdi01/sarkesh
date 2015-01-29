<?php
namespace core\plugin\menus;
use core\plugin as plugin;
use \core\cls\browser as browser;
use \core\cls\db as db;
use \core\cls\core as core;

class module extends view{
	private $users;
	private $msg;
	private $menu;

	function __construct(){
		$this->users= new plugin\users;
		$this->msg= new plugin\msg;
	}
	//this function show page for add new menu
	protected function module_new_menu(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get all localizes
			$localize = db\orm::find('localize');

			if(!isset($_REQUEST['id'])){
				//new menu
				return $this->view_new_menu($localize);
			}
			else{
				//edite menu
				if(db\orm::count('menus','id=?',[$_REQUEST['id']]) != 0){
					$this->menu = db\orm::findOne('menus','id=?',[$_REQUEST['id']]);
					return $this->view_new_menu($localize,$this->menu,true);
				}
				else{
					//show error page
					return $this->msg->error();
				}
				
			}
			
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	protected function module_onclick_btn_add_menu($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if($e['txt_name']['VALUE'] != '' && $e['txt_header']['VALUE'] != '' ){
				$this->menu = db\orm::dispense('menus');
				$update = false;
				if(array_key_exists('hid_id', $e)){
					//edite mode
					//check for that menu is exists

					if(db\orm::count('menus','id=?',[$e['hid_id']['VALUE']]) != 0){
						$this->menu = db\orm::findOne('menus','id=?',[$e['hid_id']['VALUE']]);
						$update = true;
					}
					else{
						return $this->msg->error_message_modal(true,$e);
					}
				}
				
				$this->menu->name = $e['txt_name']['VALUE'];
				$this->menu->localize = $e['cob_lang']['SELECTED'];
				$this->menu->header = $e['txt_header']['VALUE'];

				$this->menu->horiz = 0;
				if($e['ckb_horizontal']['CHECKED'] == 1){
					$this->menu->horiz = 1;
				}

				$this->menu->show_header = 0;
				if($e['ckb_show_header']['CHECKED'] == 1){
					$this->menu->show_header = 1;
				}
				$menu_id = db\orm::store($this->menu);
				//add menu to blocks
				if(!$update){
						$block = db\orm::dispense('blocks');
						$menus_plg = db\orm::findOne('plugins','name=?',['menus']);
						$block->plugin = $menus_plg->id;
						$block->name = $this->menu->name;
						$block->value = $menu_id;
						$block->visual = 1;
						$block->position = 'Off';
						$block->show_header = 0;
						$block->handel = 'draw_menu';
						db\orm::store($block);

				}

				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','menus','a','list_menus']);
			}
			else{
				return $this->msg->not_complete_modal($e);
			}
			
		}
		else{
			//no permission to access this page
			return $this->modal_no_permission($e);
		}
	}

	//this function show list of menus
	protected function module_list_menus(){
		//get all menus
		$this->menus = db\orm::find('menus');
		return $this->view_list_menus($this->menus);
	}

	protected function modal_no_permission($e){
		//show access denied message
		$e['RV']['MODAL'] = browser\page::show_block(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
		$e['RV']['JUMP_AFTER_MODAL'] = 'R';
		return $e;
	}

	//This function is for add link to menu
	protected function module_add_link(){
		if($this->users->has_permission('administrator_admin_panel')){
			if(isset($_REQUEST['menu'])){
				if(db\orm::count('menus','id=?',[$_REQUEST['menu']]) != 0){
					if(!isset($_REQUEST['id'])){
						return $this->view_add_link($_REQUEST['menu']);
					}
					if(db\orm::count('links','id=?',[$_REQUEST['id']]) != 0){
						$link = db\orm::findOne('links','id=?',[$_REQUEST['id']]);
						return $this->view_add_link($_REQUEST['menu'],$link);
					}
				}
			}
			return $this->msg->error();
		}
		return $this->msg->access_denied();
	}

	//this function use for add or update link
	public function module_onclick_btn_do_edite_add_link($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if($e['txt_label']['VALUE'] != '' && $e['txt_url']['VALUE'] != '' ){
				$link = db\orm::dispense('links');
				if(array_key_exists('hid_id', $e)){
					//edite mode
					//check for that link is exists

					if(db\orm::count('links','id=?',[$e['hid_id']['VALUE']]) != 0){
						$link = db\orm::findOne('links','id=?',[$e['hid_id']['VALUE']]);
					}
					else{
						return $this->msg->error_message_modal(true,$e);
					}
				}
				
				$link->label = $e['txt_label']['VALUE'];
				$link->url = $e['txt_url']['VALUE'];
				$link->ref_id = $e['hid_menu_id']['VALUE'];
				$link->enable = 0;
				if($e['ckb_enable']['CHECKED'] == 1){
					$link->enable = 1;
				}
				$link->rank = $e['cob_rank']['SELECTED'];
				db\orm::store($link);
				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','menus','a','list_links','id',$e['hid_menu_id']['VALUE']]);
			}
			else{
				return $this->msg->not_complete_modal($e);
			}
			
		}
		else{
			//no permission to access this page
			return $this->modal_no_permission($e);
		}
	}
	
	//THIS FUNCTION SHOW LINKS OF MENU FOR MANAGE 
	protected function module_list_links(){
		if($this->users->has_permission('administrator_admin_panel')){
			if(isset($_REQUEST['id'])){
				if(db\orm::count('links','ref_id=?',[$_REQUEST['id']]) != 0){
					$link = db\orm::find('links','ref_id=?',[$_REQUEST['id']]);
					return $this->view_list_links($link);
				}
			}
			return $this->msg->error();
		}
		return $this->msg->access_denied();
	}	

	protected function module_sure_delete_menu(){
		if($this->users->has_permission('administrator_admin_panel')){
			if(isset($_REQUEST['id'])){
				$menu = db\orm::load('menus',$_REQUEST['id']);
				return $this->view_sure_delete_menu($menu);
			}
			return $this->msg->error();
		}
		return $this->msg->access_denied();
	}

	protected function module_draw_menu($value){
		//check for localize
		$localize = new core\localize;
		$this_localize = $localize->get_localize(); 
		$menu = db\orm::load('menus',$value);
		if($this_localize['language'] == $menu->localize){
			$links = db\orm::find('links','ref_id=?',[$value]);
			return $this->view_draw_menu($menu,$links);
		}
		return '';
		
	}
}

