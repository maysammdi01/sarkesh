<?php
#This file is Sarkesh web framework main configration file

if(file_exists('db-config.php')){
	require_once("db-config.php");
}
#save  domain for load system
define ("SiteDomain",'http://' . $_SERVER['SERVER_NAME'] );
#config file system
define('AppPath',dirname(__file__) . '/');

#this url use for installing plugin 
#in this address sore plugins
define('PluginsCenter','http://plugins.sarkesh.org/');

#THIS URL SET SERVER FOR GET AVALABEL NEW VERSIONS AND SOME MORE INFORMATIONS
define('S_SERVER_INFO','http://service.sarkesh.org/');

#GET NEW VERSIONS FROM THIS DOMAIN
define('S_UPDATE_SERVER','http://service.sarkesh.org/');

#error reporting state. for more info about this variable see php documents
define('ERROR_REPORTING',E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

#With this config you can set where php error logs will be stored
define('S_Error_Log_Place',AppPath . 'error_log.txt');

#define static variable for devalopers mode
define('S_DEV_MODE',TRUE);

#define static variable for show memory and cpu usage
define('S_MEM_USAGE',TRUE);

?>
