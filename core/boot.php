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
	require_once( AppPath . 'core/defines.php');
	require_once(AppPath . 'core/inc/localize.php');
		
	//load system in gui normal mode
	require_once(AppPath . "core/inc/load.php");

}
else{
	//jump to installing page
	require_once(AppPath . "install/index.php");
}
		
?>
