<?php
namespace addon\plugin;
use core\cls\core as core;
use addon\plugin\blog as blog;

class blog extends blog\module{
	 
	 function __construct(){
		parent::__construct();
	}

	//add menu to administrator area
	public static function core_menu(){
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','new_post']);
		array_push($menu,[$url, _('New Post')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_posts']);
		array_push($menu,[$url, _('Posts')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);
		array_push($menu,[$url, _('Catalogues')]);
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','settings']);
		array_push($menu,[$url, _('Blog Settings')]);
		$ret = array();
		array_push($ret,['<span class="glyphicon glyphicon-bold" aria-hidden="true"></span>' , _('Blog')]);
		array_push($ret,$menu);
		return $ret;
	}

	//this function is for set basic settings of blog plugins
	public function settings(){
		return $this->module_settings();
	}

	//this function save blog settings
	public function onclick_btn_save_settings($e){
		return $this->module_onclick_btn_save_settings($e);
	}

	//this function show cats for manage
	public function list_cats(){
		return $this->module_list_cats();
	}

	//this function show blog add catalogue form
	public function add_cat(){
		return $this->module_add_cats();
	}

	//function for add catalogue
	public function onclick_btn_add_cat($e){
		return $this->module_onclick_btn_add_cat($e);
	}

	//function for show edite catalogue form 
	public function edite_cat(){
		return $this->module_edite_cat();
	}

	//function for edite catalogue
	public function onclick_btn_edite_cat($e){
		return $this->module_onclick_btn_edite_cat($e);
	}

	//show sure delete cat 
	public function sure_delete_cat(){
		return $this->module_sure_delete_cat();
	}

	//btn onclick event for delete catalogue
	public function onclick_btn_delete_cat($e){
		return $this->module_onclick_btn_delete_cat($e);
	}
	
	//this function show catalogue list in widget block
	public function block_show_catalog(){
		return $this->module_block_show_catalog();
	}
	
	//this function is for submit new post
	public function new_post(){
		return $this->module_new_post();
	}

	//THIS FUNCTION SUBMIT BLOG POST AND SAVE THAT
	public function onclick_btn_new_post_submit($e){
		return $this->module_onclick_btn_new_post_submit($e);
	}

	//this function show list of posts in administrator area
	public function list_posts(){
		return $this->module_list_posts();
	}

	//this function show sure delete post form
	public function sure_delete_post(){
		return $this->module_sure_delete_post();
	}

	//function for delete post with onclick event
	public function onclick_btn_delete_post($e){
		return $this->module_onclick_btn_delete_post($e);
	}

	//this function show blog post
	public function show(){
		return $this->module_show();		
	}

	//this function show blog catalogue posts
	public function show_cat(){
		return $this->module_show_cat();		
	}

	/*
	 * save comment in database
	 * @param:elements on browser
	 * return: elements
	 */
	public function onclickBtnSubmitComment($e){
		return $this->ModuleOnclickBtnSubmitComment($e);
	}

	
}


