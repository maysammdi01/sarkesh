<?php
namespace core\plugin\menus;
class action extends module{
	
	function __construct(){}
	
	/*
	 * show list of menus
	 * @return array [title,content]
	 */
	public function listMenus(){
		return $this->moduleListMenus();
	}
	
	/*
	 * insert or edite menu
	 * @return array [title,content]
	 */
	public function doMenu(){
		return $this->moduleDoMenu();
	}
	
}
