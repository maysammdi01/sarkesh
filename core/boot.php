<?php
//error reporting
if(!isset($_REQUEST['service']) && !isset($_REQUEST['control'])){
		//ENABLE OR DISABLE SHOW ERRORS AND DEVELOPERS MODE
		require_once(AppPath . 'core/functions/debug.php');
}
//this include file has autoload function
require_once(AppPath . 'core/inc/autoload.php');
//start session system
$sess_id = session_id();
if(empty($sess_id)){ session_start();}

if(file_exists(AppPath . "db-config.php")) {
	//going to run sarkesh!
	include_once(AppPath . "config.php");
	// config and setup cls_orm // RedBeanphp
	\core\cls\db\orm::run();
	//LOAD INC Files
	require_once( AppPath . 'core/defines.php');
	require_once(AppPath . 'core/inc/localize.php');
	
	//check for blocked ips
	if(!empty($_SERVER['REMOTE_ADDR'])){
		if( \core\cls\db\orm::count('ipblock',"ip=?",[ip2long($_SERVER['REMOTE_ADDR'])]) != 0){
			//show access denied message
			header("HTTP/1.1 403 Unauthorized" );
			$body = _("403! You are blocked by SarkeshMVC internal firewall.");
			$title = _('SarkeshMVC Firewall!');
			$message = '<!DOCTYPE html><html><head><title>' . $title . '</title></head><body>' . $body . '</body></html>';
			exit($message);
		}
	}

	#include functions
	require_once("./core/functions/render.php"); 

	//check for that want work with services or normal use
	if(isset($_REQUEST['service'])){
		#run system in service mode
		$obj_router = \core\cls\core\router::singleton();
		$obj_router->run_service();
	}
	//check for that want work with controls
	elseif(isset($_REQUEST['control'])){
		#run system in service mode
		$obj_router = \core\cls\core\router::singleton();
		$obj_router->run_control();
	}
	else{
		if(S_MEM_USAGE){
			echo core\cls\browser\page::show_dev_panel();
		}
		
		#load system in gui normal mode
		require_once(AppPath . "core/inc/load.php");
	}
}
else{
	//jump to installing page
	require_once(AppPath . "install/index.php");
}
		
?>
