<?php
class users extends users_module{
	function __construct(){
		parent::__construct();
	}
	
	//this function return login form
	public function login(){
		return $this->module_login();
	}
	
	//this function show register form
	public function register(){
		return array(2,2);
	}
	
	//this function in ligin button onclick event
	public function btn_login_onclick($e){
		$e['txt_username']['VALUE'] = "mmm";
		$e['txt_password']['VALUE'] = "";
		RETURN $e;
	}
}
?>
