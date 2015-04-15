<?php
namespace addon\plugin\page;
use \core\cls\browser as browser;
use \core\cls\core as core;
use \core\cls\db as db;

class event extends module{
	use addons;
	/*
	 * add new catalogue
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function btnOnclickAddCat($e){
		if($this->isLogedin() && $this->hasAdminPanel() ){
			if($e['txtName']['VALUE'] == '')
				return browser\msg::modalNotComplete($e);
			$orm = db\orm::singleton();
			//check for exists
			if($orm->count('page_catalogue','name=?',[$e['txtName']['VALUE']]) == 0){
				$cat = $orm->dispense('page_catalogue');
				$cat->name = $e['txtName']['VALUE'];
				$cat->adr = core\general::randomString(4,'C') . str_replace(' ', '_', $e['txtName']['VALUE']);
				$cat->canComment = 1;
				if($e['ckbCanComment']['CHECKED'] == 0)
					$cat->canComment = 0;
				$cat->localize = $e['cobLang']['SELECTED'];
				$orm->store($cat);
				return browser\msg::modalSuccessfull($e,['service','administrator','load','page','catalogues']);
			}
			else{
				$e['txtName']['VALUE'] = '';
				return browser\msg::modal($e,_('Message'),_('This catalogue is exists before.please try another one.'),'warning');
			}
		}
		return browser\msg::modalNoPermission($e);
	}
	
	
	/*
	 * delete catalogue
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function btnOnclickDeleteCat($e){
		if($this->isLogedin() && $this->hasAdminPanel() ){
			$orm = db\orm::singleton();
			$orm->exec('DELETE FROM page_catalogue WHERE id=?;',[$e['hidID']['VALUE']],NON_SELECT);
			return browser\msg::modalSuccessfull($e,['service','administrator','load','page','catalogues']);
		}
		return browser\msg::modalNoPermission($e);
	}
	
	
	/*
	 * edite catalogue
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function btnOnclickEditeCat($e){
		if($this->isLogedin() && $this->hasAdminPanel() ){
			if($e['txtName']['VALUE'] == '')
				return browser\msg::modalNotComplete($e);
			$orm = db\orm::singleton();
			//check for exists
			if($orm->count('page_catalogue','id=?',[$e['hidID']['VALUE']]) != 0){
				$cat = $orm->load('page_catalogue',$e['hidID']['VALUE']);
				$cat->name = $e['txtName']['VALUE'];
				$cat->adr = core\general::randomString(4,'C') . str_replace(' ', '_', $e['txtName']['VALUE']);
				$cat->canComment = 1;
				if($e['ckbCanComment']['CHECKED'] == 0)
					$cat->canComment = 0;
				$cat->localize = $e['cobLang']['SELECTED'];
				$orm->store($cat);
				return browser\msg::modalSuccessfull($e,['service','administrator','load','page','catalogues']);
			}
			else{
				$e['txtName']['VALUE'] = '';
				return browser\msg::modal($e,_('Message'),_('This catalogue is exists before.please try another one.'),'warning');
			}
		}
		return browser\msg::modalNoPermission($e);
	}
	
	
	/*
	 * save settings
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function btnOnclickSaveSettings($e){
		if($this->isLogedin() && $this->hasAdminPanel() ){
			$registry = core\registry::singleton();
			$registry->set('page','postPerPage',$e['cobPerPage']['SELECTED']);
			$registry->set('page','showAuthor',$e['ckbShowAuthor']['CHECKED']);
			$registry->set('page','showDate',$e['ckbShowDate']['CHECKED']);
			return browser\msg::modalSuccessfull($e);
		}
		return browser\msg::modalNoPermission($e);
	}
		
	/*
	 * submit new page
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function btnOnclickSubmitPage($e){
		if($this->isLogedin() && $this->hasAdminPanel() ){
			if($e['txtTitle']['VALUE'] == '' || $e['txtBody']['VALUE'] == '<br>' || $e['txtBody']['VALUE'] == '')
				return browser\msg::modalNotComplete($e);
			$orm = db\orm::singleton();
			$page = $orm->dispense('page_posts');
			if(array_key_exists('hidID',$e))
				if($orm->count('page_posts','id=?',[$e['hidID']['VALUE']]) != 0)
					$page = $orm->load('page_posts',$e['hidID']['VALUE']);
			$userInfo = $this->getCurrentUserInfo();
			$page->username = $userInfo->id;

			$page->photo = $e['uplPhoto']['VALUE'];
			$page->title = $e['txtTitle']['VALUE'];
			$page->body = $e['txtBody']['VALUE'];
			$page->catalogue = $e['cobCatalogue']['SELECTED'];
			$page->date = time();
			$page->adr = str_replace(' ','_',$e['txtTitle']['VALUE']);
			//load catalogue
			$cat = $orm->load('page_catalogue',$e['cobCatalogue']['SELECTED']);
			$page->canComment = $cat->canComment;
			$page->publish = 0;
				if($e['ckbPublish']['CHECKED'] == 1)
					$page->publish = 1;
			$page->tags = $e['txtTags']['VALUE'];
			$orm->store($page);
			return browser\msg::modalSuccessfull($e,['service','administrator','load','page','listPages']);
		}
		return browser\msg::modalNoPermission($e);
	}
	
	/*
	 * delete catalogue
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function btnOnclickDeletePost($e){
		if($this->isLogedin() && $this->hasAdminPanel() ){
			$orm = db\orm::singleton();
			$orm->exec('DELETE FROM page_Posts WHERE id=?;',[$e['hidID']['VALUE']],NON_SELECT);
			return browser\msg::modalSuccessfull($e,['service','administrator','load','page','listPages']);
		}
		return browser\msg::modalNoPermission($e);
	}
	
}
