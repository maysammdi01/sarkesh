<?php
namespace core\plugin\users;


class widgets extends module{
	use view;
	
	/*
	 * construct
	 */
	function __construct(){}
	
	/*
	 * show login form
	 * @return string, html content
	 */
	public function login(){
		return $this->moduleFrmLogin('block');
	}

}
