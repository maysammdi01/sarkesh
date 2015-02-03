<?php
namespace addon\plugin\blog;
use addon\plugin as plugin;
use \core\plugin as coreplugin;
use \core\cls\db as db;
use \core\cls\core as core;

class module extends view{
	private $users;
	private $msg;

	function __construct(){
		$this->users= coreplugin\users::singleton();
		$this->msg= coreplugin\msg::singleton();
	}
	//this function is for set basic settings of blog plugins
	protected function module_settings(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get all localizes
			$registry = core\registry::singleton();
			$settings = $registry->get_plugin('blog');
			$content_types = db\orm::find('contentcatalogue');
			$current_type = db\orm::findOne('contentcatalogue','id=?',[$settings['content_type']]);
			return $this->view_settings($settings,$current_type,$content_types);
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	//this function save blog settings
	protected function module_onclick_btn_save_settings($e){
		if($this->users->has_permission('administrator_admin_panel')){
			$registry = core\registry::singleton();
			$registry->set('blog','content_type',$e['cob_content_type']['SELECTED']);
			return $this->msg->successfull_modal($e,'N');
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//this function show cats for manage
	protected function module_list_cats(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get list of catalogues
			$cats = db\orm::findAll('blogcats');
			return $this->view_list_cats($cats);
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}

	}

	protected function module_add_cats(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get localize
	        $localize = new core\localize;
	        $locals = $localize->get_all();
	        
	        //get default language
	        $default_language = $localize->get_default_language();
			return $this->view_add_cats($default_language,$locals);
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	protected function module_onclick_btn_add_cat($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('txt_name',$e) && array_key_exists('cob_language',$e)){
				$cat = db\orm::dispense('blogcats');
				$cat->name = $e['txt_name']['VALUE'];
				$cat->machin_name = $e['txt_name']['VALUE'];
				$cat->localize = $e['cob_language']['SELECTED'];
				db\orm::store($cat);
				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);

			}
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//function for show edite cataloguue form 
	protected function module_edite_cat(){
		if($this->users->has_permission('administrator_admin_panel')){
			//check for that id is set
			if(isset($_REQUEST['id'])){
				if(db\orm::count('blogcats','id=?',[$_REQUEST['id']]) != 0){
					$localize = new core\localize;
	        		$locals = $localize->get_all();
	        		$cat = db\orm::findOne('blogcats','id=?',[$_REQUEST['id']]);
	        		return $this->view_edite_cat($cat,$locals);
				}
			}
			return $this->msg->error();
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}
	
	//function for edite catalogue
	protected function module_onclick_btn_edite_cat($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('txt_name',$e) && array_key_exists('cob_language',$e) && array_key_exists('hid_id',$e)){
				if($e['txt_name']['VALUE'] == '' || $e['cob_language']['SELECTED'] == ''){
					return $this->msg->not_complete_modal($e);
				}
				$cat = db\orm::load('blogcats',$e['hid_id']['VALUE']);
				$cat->name = $e['txt_name']['VALUE'];
				$cat->machin_name = $e['txt_name']['VALUE'];
				$cat->localize = $e['cob_language']['SELECTED'];
				db\orm::store($cat);

				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);

			}
			return $this->msg->error_modal($e);
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//show sure delete catalogue
	protected function module_sure_delete_cat(){
		if($this->users->has_permission('administrator_admin_panel')){
			//check for that id is set
			if(isset($_REQUEST['id'])){
				if(db\orm::count('blogcats','id=?',[$_REQUEST['id']]) != 0){
	        		$cat = db\orm::findOne('blogcats','id=?',[$_REQUEST['id']]);
	        		return $this->view__sure_delete_cat($cat);
				}
			}
			return $this->msg->error();
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	//btn event handel for delete catalogue
	protected function module_onclick_btn_delete_cat($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('hid_id',$e)){
				db\orm::exec('DELETE FROM blogcats WHERE id=?',[$e['hid_id']['VALUE']]);
				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);
			}
			return $this->msg->error_modal($e);
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//show list of catalogue in block
	protected function module_block_show_catalog(){
		//get all 
	}
}

