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
	function __construct(){}
	
	//this function return back menus for use in admin area
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','users','listPeople']);
		array_push($menu,[$url, _('People')]);
		$url = core\general::createUrl(['service','administrator','load','users','listGroups']);
		array_push($menu,[$url, _('Groups')]);
		$url = core\general::createUrl(['service','administrator','load','users','listPermissions']);
		array_push($menu,[$url, _('Permissions')]);
		$url = core\general::createUrl(['service','administrator','load','users','accountSettings']);
		array_push($menu,[$url, _('Account settings')]);
		$url = core\general::createUrl(['service','administrator','load','users','ipBlockList']);
		array_push($menu,[$url, _('IP address blocking')]);
		$ret = array();
		array_push($ret, ['<span class="glyphicon glyphicon-user" aria-hidden="true"></span>' , _('Users')]);
		array_push($ret,$menu);
		return $ret;
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
			if($e['ckbRemember']['CHECKED'] == '1') $validID = $validator->set('USERS_LOGIN',true);
			else $validID = $validator->set('USERS_LOGIN',false);

			//INSERT VALID ID IN USER ROW
			$user = $orm->load('users',$this->getUserID($e['username']['VALUE']));
			$user->login_key = $validID;
			$user->last_login = time();
			$orm->store($user);
			if(array_key_exists('hidJump',$e))
				$e['RV']['URL'] = core\general::createUrl([$e['hidJump']['VALUE']]);
			else
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
				return $this->viewWidgetProfile($user,$this->hasPermission('adminPanel'));
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
	
	/*
	 * show active form or active user
	 * @return string, html content
	 */
	public function moduleActiveAcount(){
		if(PLUGIN_OPTIONS == '')
			return browser\msg::pageNotFound();
		//going to active user account
		$orm = db\orm::singleton();
		$validator = new network\validator;
		if($validator->checkSid(PLUGIN_OPTIONS) && $orm->count('users','state=?',['A:'. PLUGIN_OPTIONS]) != 0){
			$user = $orm->findOne('users','state=?',['A:'. PLUGIN_OPTIONS]);
			$user->permission = $this->settings->defaultPermission;
			$user->state = '';
			$orm->store($user);
			//login user to system
			$this->loginWithUsername($user->username);
			//jump to change password form
			return core\router::jump('users','changePassword','newUser');
			
		}
		//show fail message
		return $this->viewFailActiveAccount();
	}
	
	/*
	 * show login form in single page
	 */
	protected function moduleLoginSinglePage(){
		$loginForm = $this->frmLogin();
		$page = browser\page::simplePage($loginForm[0],browser\page::showBlock($loginForm[0],$loginForm[1],'BLOCK'),5,true);
		return $page;
	}
	
	/*
	 * show list of blocked ips
	 * @return string, html content
	 */
	public function moduleIpBlockList(){
		if($this->hasAdminPanel()){
			$orm = db\orm::singleton();
			return $this->viewIpBlockList($orm->getAll('ipblock'));
		}
		return browser\msg::pageAccessDenied();
	}
	
	/*
	 * add new ip to block list
	 * @return string, html content
	 */
	public function moduleNewIpBlock(){
		if($this->hasAdminPanel()){
			return $this->viewNewIpBlock();
		}
		return browser\msg::pageAccessDenied();
	}
}
