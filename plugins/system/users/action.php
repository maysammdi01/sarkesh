<?php
namespace core\plugin\users;
use \core\cls\core as core;
use \core\cls\browser as browser;


class action extends module{
	use view;
	/*
	 * construct
	 */
	function __construct(){
		parent::__construct();
	}
	
	/*
	 * show login form
	 * @return string, html content
	 */
	public function login(){
		return $this->moduleFrmLogin('content');
	}
	
	/*
	 * show register form
	 * @return string, html content
	 */
	public function register(){
		return $this->moduleFrmRegister();
	}
	
	/*
	 * show active form or active user
	 * @return string, html content
	 */
	public function activeAccount(){
		if(defined('PLUGIN_OPTIONS'))
			return $this->moduleActiveAccount();
		return browser\msg::pageNotFound();
	}
	
	/*
	 * show resed password proccess or reset code defined proccess to define new password
	 * @return string, html content
	 */
	public function resetPassword(){
		if(!$this->isLogedin() && !defined('PLUGIN_OPTIONS'))
			return $this->viewResetPassword();
		elseif(!$this->isLogedin() && defined('PLUGIN_OPTIONS'))
			return $this->moduleResetPassword();
		return browser\msg::pageNotFound();
	}
	
	/*
	 * show list of blocked ips
	 * @return string, html content
	 */
	public function ipBlockList(){
		return $this->moduleIpBlockList();	
	}
	
	/*
	 * add new ip to block list
	 * @return string, html content
	 */
	public function newIpBlock(){
		return $this->moduleNewIpBlock();	
	}
	
	/*
	 * change user password
	 * @return string, html content
	 */
	public function changePassword(){
		if($this->isLogedin())
			return $this->viewChangePassword();
		return browser\msg::pageNotFound();
	}
	
	/*
	 * show user profile
	 * @return string, html content
	 */
	public function profile(){
		return $this->moduleProfile();
	}
	
	/*
	 * show page to user for select new or change avatar
	 * @return array, [title,body]
	 */
	public function changeAvatar(){
		if($this->isLogedin())
			return $this->moduleChangeAvatar();
		return browser\msg::pageNotFound();
	}
	
	/*
	 * show list of people in administrator area
	 * @return array, [title,body]
	 */
	public function listPeople(){
		if($this->isLogedin())
			return $this->moduleListPeople();
		return core\router::jump(['service','users','login','service/administrator/load/users/listPeople']);
	}
	
	/*
	 * SHOW USER PLUGIN SETTINGS
	 * @return array, [title,body]
	 */
	public function accountSettings(){
		if($this->isLogedin())
			return $this->moduleAccountSettings();
		return core\router::jump(['service','users','login','service/administrator/load/users/listPeople']);
	}
	
}
