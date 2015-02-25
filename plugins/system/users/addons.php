<?php
namespace core\plugin\users;
use \core\cls\core as core;
use \core\cls\network as network;
use \core\cls\db as db;

trait addons {
	
	/*
	 * check for that user is logedin or not
	 * @return boolean (logedin:true, else:false)
	 */
	public function isLogedin(){
		$validator = new network\validator;
		if($validator->check('USERS_LOGIN')){
			$id = $validator->getID('USERS_LOGIN');
			$orm = db\orm::singleton();
			if($orm->count('users','login_key = ?',array($id)) != 0)
				return true;
		}
		return false;
	}
	
	/*
	 * get user id
	 * @param string $username, username
	 * @return integer user id,(not found:null)
	 */
	 public function getUserID($username){
		 $orm = db\orm::singleton();
		 if($orm->count('users','username=?',[$username]) != 0){
			 $user = $orm->findOne('users','username=?',[$username]);
			 return $user->id;
		 }
		 return null;
	 }
}
