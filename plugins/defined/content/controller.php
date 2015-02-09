<?php
/*
 * This plugin is for controll content like news,article,wiki pageas,and ...
 * Author:Babak alizadeh
 */
 
namespace addon\plugin;
use addon\plugin\content as content;
use core\cls\core as core;
use core\cls\browser as browser;
use core\plugin as plugin;

class content extends content\module{
	private $admin;
	private $msg;
	public function __construct(){
		$this->msg = new plugin\msg;
		$this->admin = new plugin\administrator;
	}
	//this function return back menus for admin area
	public function core_menu(){
		
		$menu = array();
		$url = core\general::create_url(array('service','1','plugin','administrator','action','main','p','content','a','insert_cat'));
		array_push($menu,[$url, _('New catalogue')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_cats']);
		array_push($menu,[$url, _('Manage catalogues')]);
		$ret = array();
		array_push($ret,['<span class="glyphicon glyphicon-book" aria-hidden="true"></span>' , _('Content')]);
		array_push($ret,$menu);
		return $ret;
		
	}
	
	//this function return parts of content in an array
	//if content not found this function return false
	public function get_content($id){
		return $this->module_get_content($id);
	}
	
	//this function is for add catalogue
	public function insert_cat(){
		
		return $this->module_insert_cat();
	}
	
	//this function is event handeler for insert catalogue button
	public function onclick_btn_insert_cat($e){
		if($e['txt_name']['VALUE'] != ''){
			return $this->module_onclick_btn_insert_cat($e);
		}
		else{
			//show fill catalogue name message
			$e['RV']['MODAL'] = browser\page::show_block(_('Message'),_('Please fill in all of the required fields'),'MODAL','type-warning');
			return $e;
		}
	}
	
	
	//this function return thable of catalogues with edit and delete button
	public function list_cats(){
		
		return $this->module_list_cats();
	}
	
	//this action is for jump to edit catalogue
	public function btn_cat_go_edite($e){
		$e['RV']['URL'] = core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','cat_edit','id',$e['CLICK']['VALUE']],true);
		return $e;
	}
	
	//function for edit catalogue id send by url
	public function cat_edit(){
		
		return $this->module_cat_edit();
	}
	
	//FUNCTION FOR BTN EDIT CATALOGUE ONCLICK EVENT
	public function onclick_btn_edit_cat($e){
		if(trim($e['txt_name']['VALUE']) != ''){
			return $this->module_onclick_btn_edit_cat($e);
		}
		else{
			$e['RV']['MODAL'] = browser\page::show_block('Message','Please fill in all of the required fields','MODAL','type-warning');
			$e['txt_name']['VALUE'] = '';
			return $e;
		}
	}
	
	//function for show insert catalog page
	public function show_cats_insert(){
		
		return $this->module_show_cats_insert();
	}
	
	//this function is for sure page for delete catalogue
	public function sure_delete(){
		if(isset($_GET['id'])){
			return $this->module_sure_page($_GET['id']);
		}
		
		
	}
	
	//THIS FUNCTION IS EVENT HANDELER FOR DELETE CATALOGUE
	public function onclick_btn_delete_cat($e){
		return $this->module_onclick_btn_delete_cat($e);
	}
	
	//function for manage patterns of content
	public function list_patterns(){
		if(isset($_GET['id'])){
			return $this->module_list_patterns($_GET['id']);
		}
		else{
			//show not found message
			core\router::jump_page('404');
			return ['',''];
		}
		
	}
	
	//this function is for sure page for delete patterns
	public function sure_delete_pattern(){
			if($this->admin->has_admin_panel()){
				
				return $this->module_sure_delete_pattern();
			}
			//access denied
			return $this->msg->access_denied();
		
	}
	
	//function for handle event for delete pattern
	public function onclick_btn_delete_pattern($e){
		return $this->mudule_btn_delete_pattern($e);
	}
	
	//function for edit pattern
	public function edite_pattern(){
		return $this->module_edite_pattern();
		
	}
	
	//function for edite pattern with onclick button
	public function onclick_btn_edite_pattern($e){
		return $this->module_onclick_btn_edite_pattern($e);
	}
	
	//FUNCTION FOR JUMP TO INSERT NEW PATTERN
	public function onclick_btn_add_new_pattern($e){
		$e['RV']['URL'] = core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','add_new_pattern','type',$e['cob_item']['SELECTED'],'id',$e['hid_id']['VALUE']]);
		return $e;
	}
	
	//this function is for add new pattern
	public function add_new_pattern(){
			if($this->admin->has_admin_panel()){
				
				return $this->module_add_new_pattern();
			}
			//access denied
			return $this->msg->access_denied();
	}
	
	//this function is for insert new patterns
	public function onclick_btn_insert_pattern($e){
			return $this->module_onclick_btn_insert_pattern($e);		
	}
	
	//this function is for add content
	public function insert_content(){
		
		if(isset($_GET['id'])){
			return $this->module_insert_content();
		}
	}
	
	//this function do onclick function 
	public function onclick_btn_insert_content($e){
		return $this->module_onclick_btn_insert_content($e);
	}

	//this part is for get patterns in insert mode
	public function get_form_submit($cat_id){
		return $this->module_get_form_submit($cat_id);
	}
	public function save_content($e,$special_value){
		return $this->module_save_content($e,$special_value);
	}		
}

?>
