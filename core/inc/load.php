<?php
/*	this file is perpare application of user for start working
 *	in this file set functions for start and use in themes
*/
require_once(AppPath . 'core/functions/render.php');
//check for blocked ips
if(!empty($_SERVER['REMOTE_ADDR'])){
	$orm = \core\cls\db\orm::singleton();
	if( $orm->count('ipblock',"ip=?",[ip2long($_SERVER['REMOTE_ADDR'])]) != 0){
		//show access denied message
		header("HTTP/1.1 403 Unauthorized" );
		$body = _("403! You are blocked by SarkeshMVC internal firewall.");
		$title = _('SarkeshMVC Firewall!');
		$message = '<!DOCTYPE html><html><head><title>' . $title . '</title></head><body>' . $body . '</body></html>';
		exit($message);
	}
}
try{


	#with enable this option sarkesh use clean url in system
	$registry = \core\cls\core\registry::singleton();
	$cleanUrl = $registry->get('administrator','cleanUrl');
	if($cleanUrl == 1)
		define('CLEAN_URL',TRUE);
	else
		define('CLEAN_URL',FALSE);
		
	/*
	* this statics load plugin and action and localize;
	*/
	$orm = \core\cls\db\orm::singleton();
	$localize = \core\cls\core\localize::singleton();
	$control = [];
	//normal mode
	if(isset($_REQUEST['q'])){
		if($localize->isMultiLang()){
			$control = explode('/', $_REQUEST['q'],4);
			if($control[1] == 'service'){
				$control = explode('/', $_REQUEST['q'],5);
				define('LOCALIZE',$control[0]);
				define('SERVICE',$control[2]);
				define('ACTION',$control[3]);
				if(isset($control[4])) define('PLUGIN_OPTIONS',$control[4]);
			}
			elseif($control[0] == 'control'){
				$control = explode('/', $_REQUEST['q'],3);
				define('LOCALIZE',$localize->language());
				define('CONTROL',$control[1]);
				define('ACTION',$control[2]);
				if(isset($control[3])) define('PLUGIN_OPTIONS',$control[3]);
			}
			else{
				define('LOCALIZE',$control[0]);
				define('PLUGIN',$control[1]);
				define('ACTION',$control[2]);
				if(isset($control[3])) define('PLUGIN_OPTIONS',$control[3]);
			}
		}
		else{
			$control = explode('/', $_REQUEST['q'],3);
			$localize = \core\cls\core\localize::singleton();
			if($control[0] == 'service'){
				$control = explode('/', $_REQUEST['q'],4);
				define('LOCALIZE',$localize->language());
				define('SERVICE',$control[1]);
				define('ACTION',$control[2]);
				if(isset($control[3])) define('PLUGIN_OPTIONS',$control[3]);
			}
			elseif($control[0] == 'control'){
				$control = explode('/', $_REQUEST['q'],4);
				define('LOCALIZE',$localize->language());
				define('CONTROL',$control[1]);
				define('ACTION',$control[2]);
				if(isset($control[3])) define('PLUGIN_OPTIONS',$control[3]);
			}
			else{
				define('LOCALIZE',$localize->language());
				define('PLUGIN',$control[0]);
				define('ACTION',$control[1]);
				if(isset($control[2])) define('PLUGIN_OPTIONS',$control[2]);
			}
		}
	}
	else{
		$localize = \core\cls\core\localize::singleton();
		$defaultLocalize = $localize->localize();
		header('Location:' . \core\cls\core\general::createUrl([$defaultLocalize->home],$defaultLocalize->language));
		exit();
	}
}
catch(Exception $e){
	exit(_('Internal error!'));
}
/*
 * check for that start system in normal mode or service mode
 */
if(defined('SERVICE')){
	#run system in service mode
	$router = new \core\cls\core\router(SERVICE, ACTION);
	$router->runService();
	exit();
}
if(defined('CONTROL')){
	#run system in service mode
	$router = new \core\cls\core\router(CONTROL, ACTION);
	$router->runControl();
	exit();
}
else{
	
	#run in normal mode
	ob_start("render");
	$registry = \core\cls\core\registry::singleton();
	require_once(AppPath . 'themes/' . $registry->get('administrator', 'active_theme') . '/index.php');
	ob_end_flush();
}
?>
