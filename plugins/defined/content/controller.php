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
		$e['RV']['URL'] = core\general::create_url(['plugin','content','action','cat_edit','id',$e['CLICK']['VALUE']],true);
		return $e;
	}
	
	//function for edit catalogue id send by url
	public function cat_edit(){
		
		return $this->module_cat_edit();
	}
	
	//FUNCTION FOR BTN EDIT CATALOGUE ONCLICK EVENT
	public function onclick_btn_edit_cat($e){
		return $this->module_onclick_btn_edit_cat($e);
	}
	
	
		
}

?>
