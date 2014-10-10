<?php
/*
 * This plugin is for controll content like news,article,wiki pageas,and ...
 * Author:Babak alizadeh
 */
 
namespace addon\plugin;
use addon\plugin\content as content;
use core\cls\core as core;
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
		
}

?>
