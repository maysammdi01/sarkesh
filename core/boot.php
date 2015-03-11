<?php
//start session system
session_start();
//this include file has autoload function
require_once(AppPath . 'core/inc/autoload.php');
if(file_exists(AppPath . "db-config.php")) {
	//going to run sarkesh!
	include_once(AppPath . "config.php");
	//LOAD INC Files
	require_once( AppPath . 'core/functions/debug.php');
	require_once( AppPath . 'core/defines.php');
	require_once(AppPath . 'core/inc/localize.php');
	
	//load parts in action mode
	if(isset($_REQUEST['q'])){
		require_once(AppPath . 'core/inc/load.php');
	}
	else{
		//jump to home page
		$localize = \core\cls\core\localize::singleton();
		$local = $localize->localize();
		\core\cls\core\router::jump(\core\cls\core\general::createUrl([$local->home],$local->language) ,true);
		exit();
	}
}
else{
	//jump to installing page
	require_once(AppPath . "install/index.php");
}
		
?>
