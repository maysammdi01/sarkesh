<?php

namespace addon\plugin\content;
use \core\cls\db as db;
use \core\cls\core as core;
use \core\cls\calendar as calendar;
use \core\plugin as plugin;
use \core\cls\browser as browser;
class module extends view{
	
	
	//This function is for show content
	protected function module_show(){
		//check for that content is exist
		if(db\orm::count('contentcontent','id=?',[$_GET['id']]	) != 0){
			$content = db\orm::findOne('contentcontent','id=?',[$_GET['id']] );
			//content is exist
			//check permasion for see this
			if(1==1){
				$parts = db\orm::find('contentparts','content=? ORDER BY rank',[$content->id]	);
				$page = array();
				foreach($parts as $key=>$part){
					array_push($page, $this->module_compile_part($part) );
				}
				
				//get username
				if($content->user == 0){
					$username = _('Guest');
				}
				else{
					$str_date = '';
					//check for show time
					//get settings
					$registry = new core\registry;
					$setting = $registry->get_plugin('content');
					if($content->show_date == '1'){
						$calendar = new calendar\calendar;
						$str_date = $calendar->cdate($setting['date_format'], $content->date);
					}
					$content->date = $str_date;
					//check for that can show author
					if($content->show_author == '1'){
						$users = new plugin\users;
						$user_info = $users->get_info_with_id($content->id);
						$content->user = $user_info->username;
					}
					
					
				}
				return $this->view_show($content,$page);
				
			}
			else{
				//show access denied message
			}
		}
		else{
			//content not found
		}
		
	}
	
	
	//this function get row of parts table and return an array
	//first index is html element and second index in position of element on page
	protected function module_compile_part($part){
		if($part->type == 'textarea'){
			return [$part->options,$part->position];
		}
	}
	
	//this function is for insert new catalogue
	protected function module_insert_cat(){
		//check for that user has permission for insert catalogues
		$users = new plugin\users;
		if($users->has_permission('content_cat_insert')){
			//show page
			//check for that user can edite cats
			if($users->has_permission('content_cat_edit')){
				
				//get table of cats
				$tbl_cats = $this->module_list_cats();
				return $this->view_insert_cat(true,$tbl_cats);
			}
			else{
				//don't has permission
				return $this->view_insert_cat(false);
			}
		}
		else{
			//show access denied message
			return [1,1];
		}
	}
	
	//this function return table of catalogue with edit and delete buttons
	protected function module_list_cats(){
		//check for that user has permission for insert catalogues
		$users = new plugin\users;
		if($users->has_permission('content_cat_edit')){
			$cats = db\orm::findAll('contentcatalogue');
			return $this->view_list_cats($cats);
		}
		else{
			//return access denied message
			
		}
	}
	
	//this function insert new catalogue button event
	protected function module_onclick_btn_insert_cat($e){
		//check for that catalogue is exist before
		if(db\orm::count('contentcatalogue','name=?',[$e['txt_name']['VALUE']]) != 0){
			//IS EXIST BEFORE
			$e['RV']['MODAL'] = browser\page::show_block('Message','Entered catalogue is exists before.please enter another one and try again.','MODAL','type-warning');
			$e['txt_name']['VALUE'] = '';
			return $e;
		}
		else{
			//going to save catalogue
			$cat = db\orm::dispense('contentcatalogue');
			$cat->name = $e['txt_name']['VALUE'];
			$cat->access_name = $e['txt_name']['VALUE'];
			db\orm::store($cat);
			$e['RV']['MODAL'] = browser\page::show_block('Success','Successfully saved.','MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			return $e;
		}
		
	}
	
	//function for edit catalogue id send by url
	public function module_cat_edit(){
		//check for that id is set
		if(isset($_REQUEST['id'])){
			
			//check for that id is exist
			if(db\orm::count('contentcatalogue','id=?',[$_REQUEST['id']]) != 0){
				$cat = db\orm::findOne('contentcatalogue','id=?',[$_REQUEST['id']]);
				return $this->view_cat_edit($cat);
			}
			else{
				//Show not found message
				echo 404;
			}
		}
		else{
			//id not set .going to jump to catalogue list.
			
		}
	}
	
	//FUNCTION FOR BTN EDIT CATALOGUE ONCLICK EVENT
	public function module_onclick_btn_edit_cat($e){
		if(db\orm::count('contentcatalogue','id=?',[$e['hid_id']['VALUE']]) != 0	){
			$cat = db\orm::load('contentcatalogue',$e['hid_id']['VALUE']);
			$cat->name = $e['txt_name']['VALUE'];
			$cat->access_name = $e['txt_name']['VALUE'];
			db\orm::store($cat);
			$e['RV']['MODAL'] = browser\page::show_block('Message','Update successful','MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = '?plugin=users';
		
		}
		else{
			//show system error message
			$e['RV']['MODAL'] = browser\page::show_block('Error','An Error Has Occurred.','MODAL','type-danger');
			$e['RV']['JUMP_AFTER_MODAL'] = 'R';
		}
		return $e;
	}
	
}

?>
