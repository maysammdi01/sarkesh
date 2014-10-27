<?php
namespace core\plugin\log;
use core\plugin as plugin;

class module extends view{
	
	protected function module_insert($plugin,$key,$options,$username){
	
		//get id of cerrent user
		$PlugUser = new plugin\users;
		$UserID = $PlugUser->get_id($username);

		$log = db\orm::dispense('log');
		$log->plugin = $plugin;
		$log->key = $key;
		$log->date = time();
		$log->options = $UserID;
		
		db\orm::store($log);
		return true;
	}
}
