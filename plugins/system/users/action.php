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
	 * show resed password proccess
	 * @return string, html content
	 */
	public function resetPassword(){
		if(!$this->isLogedin())
			return $this->viewResetPassword();
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
	
	
}
