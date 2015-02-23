<?php

//this include file has autoload function
require_once(AppPath . 'core/inc/autoload.php');

//start session system
$sess_id = session_id();
if(empty($sess_id)){ session_start();}

if(file_exists(AppPath . "db-config.php")) {
	//going to run sarkesh!
	include_once(AppPath . "config.php");
	//LOAD INC Files
	//require_once( AppPath . 'core/functions/debug.php');
	require_once( AppPath . 'core/defines.php');
	require_once(AppPath . 'core/inc/localize.php');
	
	//load parts in action mode
	if(isset($_REQUEST['q'])){
		require_once(AppPath . 'core/inc/load.php');
	}
	//check for that want work with controls
	elseif(isset($_REQUEST['control'])){
		#run system in service mode
		$obj_router = new \core\cls\core\router($_REQUEST['plugin'], $_REQUEST['event']);
		$obj_router->runControl();
	}
	else{
		//jump to home page
		$localize = \core\cls\core\localize::singleton();
		$local = $localize->localize();
		
		\core\cls\core\router::jump($local->home ,true);
	}
}
else{
	//jump to installing page
	require_once(AppPath . "install/index.php");
}
		
?>
