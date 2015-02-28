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
	 
	/*
	 * get user info
	 * @param string $username
	 * @return object of user info (null = guest)
	 */
	public function getInfo($username = null){
		$orm = db\orm::singleton();
		if($orm->count('users','username=?',[$username]) != 0)
			return $orm->findOne('users','username=?',[$username]);
		return null;
	}
	
	/*
	 * get cerrent user info
	 * @return object of user info (null = guest)
	 */
	public function getCurrentUserInfo(){
		$validator = new network\validator;
		if($validator->check('USERS_LOGIN')){
			$id = $validator->getID('USERS_LOGIN');
			$orm = db\orm::singleton();
			if($orm->count('users','login_key=?',[$id]) != 0)
				return $orm->findOne('users','login_key=?',[$id]);
		}
		return null;
	}
	
	/*
	 * check for that user has permission to do task
	 * @param string $permission, permission name
	 * @param string $username ,(null: current user)
	 * @return boolean
	 */
	public function hasPermission($permission,$username=null){
		
	}
}
