<?php
namespace core\plugin\users;
use \core\cls\browser as browser;
use \core\cls\network as network;

class event extends module{
	
	function __construct(){
		
	}
	
	/*
	 * user login proccess
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function login($e){
		if(trim($e['username']['VALUE']) == '' || trim($e['password']['VALUE'])==''){
			return browser\msg::modalNotComplete($e);
		}
		return $this->onclickLogin($e);
	}
	
	/*
	 * user logout proccess and refresh page
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function logout($e){
		$validator = new network\validator;
		$validator->delete('USERS_LOGIN');
		$e['RV']['URL'] = 'R';
		return $e;
	}
	
	
	
}
