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
	
	
}
