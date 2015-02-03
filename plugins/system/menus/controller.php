<?php
namespace core\plugin;
use core\cls\core as core;
use core\plugin\menus as menus;

class menus extends menus\module{
	public function __distruct(){
  		parent::__distruct();
	}
	//add menu to administrator area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','new_menu']);
		array_push($menu,[$url, _('New Menu')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_menus']);
		array_push($menu,[$url, _('List Menus')]);
		$ret = array();
		array_push($ret,['<span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>' ,_('Menus')]);
		array_push($ret,$menu);
		return $ret;
	}
	//this function show page for add new menu
	public function new_menu(){
		return $this->module_new_menu();
	}

	//this function control btn event for add new menu
	public function onclick_btn_add_menu($e){
		return $this->module_onclick_btn_add_menu($e);
	}
	
	//this function show list of menus
	public function list_menus(){
		return $this->module_list_menus();
	}

	//This function is for add link to menu
	public function add_link(){
		return $this->module_add_link();
	}
	//this function use for add or update link
	public function onclick_btn_do_edite_add_link($e){
		return $this->module_onclick_btn_do_edite_add_link($e);
	}

	//THIS FUNCTION SHOW LINKS OF MENU FOR MANAGE 
	public function list_links(){
		return $this->module_list_links();
	}

	//delete menu
	public function sure_delete_menu(){
		return $this->module_sure_delete_menu();
	}

	//this function show menu on page
	//$position and $value send from core\cls\core\page
	public static function draw_menu($position,$value){
		return self::module_draw_menu($value);
	}

	//function for delete menu
	public function onclick_btn_delete_menu($e){
		return $this->module_onclick_btn_delete_menu($e);
	}
	
}


