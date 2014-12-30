<?php
#This file is Sarkesh web framework main configration file

#database type. now only mysql supported
define ("DatabaseType","mysql");

#mysql connect information
define ("DatabaseUser","root");
define ("DatabasePassword","");
define ("DatabaseHost","localhost");
define ("DatabaseName","sarkeshMVC");

#save  domain for load system
define ("SiteDomain",'http://' . $_SERVER['SERVER_NAME'] );
#config file system
define('AppPath',dirname(__file__) . '/');

#this url use for installing plugin 
#in this address sore plugins
define('PluginsCenter','http://plugins.sarkesh.org/');

#error reporting state. for more info about this variable see php documents
define('ERROR_REPORTING',E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

#With this config you can set where php error logs will be stored
define('S_Error_Log_Place',AppPath . 'error_log.txt');
?>
