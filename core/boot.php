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
	//LOAD INC Files
	
	
	//include core defines
	require_once( AppPath . 'core/defines.php');
	require_once(AppPath . 'core/inc/localize.php');
	
	#include functions
	require_once("./core/functions/render.php");  
	
	// config and setup cls_orm // RedBeanphp
	\core\cls\db\orm::run();
	
	//check for that want work with services or normal use
	if(isset($_REQUEST['service'])){
		#run system in service mode
		$obj_router = new \core\cls\core\router();
		$obj_router->run_service();
	}
	//check for that want work with controls
	elseif(isset($_REQUEST['control'])){
		#run system in service mode
		$obj_router = new \core\cls\core\router;
		$obj_router->run_control();
	}
	else{
		if(S_DEV_MODE){
			//core\cls\browser\page::show_dev_panel();
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
