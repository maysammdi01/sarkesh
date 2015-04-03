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
}
