<?php
namespace core\plugin\log;
use \core\plugin as plugin;
use \core\cls\browser as browser;
use \core\cls\core as core;

class module extends view{
	private $users;
	private $msg;

	function __construct(){
		$this->users = plugin\users::singleton();
		$this->msg = plugin\msg::singleton();
	}
	protected function module_insert($plugin,$key,$options,$username){
	
		//get id of cerrent user
		$PlugUser = new plugin\users;
		$UserID = $PlugUser->get_id($username);
		$log = db\orm::dispense('logs');
		$log->plugin = $plugin;
		$log->key = $key;
		$log->date = time();
		$log->options = $UserID;
		
		db\orm::store($log);
		return true;
	}
	
	public function module_php_error_logs(){
		if(file_exists(S_Error_Log_Place)){
			//get errors
			$file = file(S_Error_Log_Place);
			return $this->view_php_error_logs($file);
		}
		//log not found this mean no error was acoured or error log file is empty
		return [_('PHP Errors'),_('No error was ecured.')];
		
	}
	
	public function module_onclick_btn_clear_php_logs($e){

		unlink(S_Error_Log_Place);
		//successfully changed going to refresh page
		$e['RV']['MODAL'] = browser\page::show_block(_('Clear logs'),_('PHP error logs cleared successfuly'),'MODAL','type-success');
		$e['RV']['JUMP_AFTER_MODAL'] = 'R';
		return $e;
	}
	//this function check for new versions from sarkesh rep servers
	protected function module_updates(){
		if($this->users->has_permission('administrator_admin_panel')){
			$registry = core\registry::singleton();
			return $this->view_updates($registry->get('administrator','build_num'),file_get_contents(S_SERVER_INFO . 'last_version.txt'));
		}
		return $this->msg->access_denied();
	}
}

