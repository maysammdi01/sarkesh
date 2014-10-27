<?php
/*
 * This plugin is for controll content like news,article,wiki pageas,and ...
 * Author:Babak alizadeh
 */
 
namespace addon\plugin;
use addon\plugin\content as content;
use core\cls\core as core;
use core\cls\browser as browser;

class content extends content\module{
	
	//this function return back menus for admin area
	public function core_menu(){
		
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','insert_cat']);
		array_push($menu,[$url, _('New catalogue')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_cats']);
		array_push($menu,[$url, _('Manage catalogues')]);
		$ret = array();
		array_push($ret,_('Content'));
		array_push($ret,$menu);
		return $ret;
		
	}
	
	public function show(){
		if(isset($_GET['id'])	){
			//going to search and show content
			return $this->module_show();
		}
		else{
			//show 404 msg
			core\router::jump_page(array('plugin','msg','action','msg_404')	);
		}
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
	
	
	
		
}

?>
