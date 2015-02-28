<?php
namespace core\plugin\users;
use core\cls\browser as browser;
use core\cls\network as network;
use core\cls\core as core;
use core\cls\db as db;

class module{
	use view;
	use addons;
	
	/*
	 * construct
	 */
	function __construct(){
		
	}
	
	/*
	 * show login form
	 * @return string, html content(logedin :null)
	 */
	protected function moduleFrmLogin($position){
		if(!$this->isLogedin())
			return $this->frmLogin($position);
		elseif($this->isLogedin() && $position == 'content')
			return core\router::jump(['users','profile']);
		return null;
	}
	
	/*
	 * user login proccess
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	protected function onclickLogin($e){
		$orm = db\orm::singleton();
		$count = $orm->count('users',"username = ? or email=? and password = ?", array($e['username']['VALUE'],$e['username']['VALUE'],md5($e['password']['VALUE'])));
		if($count != 0){
			//login data is cerrect
			$validator = new network\validator;
			if($e['ckbRemember']['CHECKED'] == '1'){
				$validID = $validator->set('USERS_LOGIN',true,true);
				$e['RV']['MODAL'] = browser\page::showBlock(_('Message'), _('Usernect!'), 'MODAL','type-warning');
			}
			else $validID = $validator->set('USERS_LOGIN',false,true);
				
			//INSERT VALID ID IN USER ROW
			$user = $orm->load('users',$this->getUserID($e['username']['VALUE']));
			$user->login_key = $validID;
			$user->last_login = time();
			$orm->store($user);
			$e['RV']['URL'] = 'R';
		}
		else{
			//username or password is incerrect
			$e['username']['VALUE'] = '';
			$e['password']['VALUE'] = '';
			$e['RV']['MODAL'] = browser\page::showBlock(_('Message'), _('Username or Password is incerrect!'), 'MODAL','type-warning');
		}
		return $e;
	}
	
	/*
	 * show minimal profile in widget mode
	 * @return string, html content
	 */
	protected function moduleWidgetProfile(){
		if($this->isLogedin()){
			//get user info
			$orm = db\orm::singleton();
			$user = $this->getCurrentUserInfo();
			if(!is_null($user))
				return $this->viewWidgetProfile($user,true);
		}
		return null;
	}
	
	/*
	 * show register form
	 * @return string, html content
	 */
	public function moduleFrmRegister(){
		if(!$this->isLogedin())
			return $this->viewFrmRegister();
		return core\router::jump(['users','profile']);
	}
}
