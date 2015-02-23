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

	/*
	* this statics load plugin and action and localize;
	*/
	$orm = \core\cls\db\orm::singleton();
	$localize = $orm->find('localize');
	$activeLang = $localize[0];
	$control = [];
	if(isset($_GET['q'])){
		if(count($localize) != 1){
			$control = explode('/', $_GET['q'],4);
			define('LOCALIZE',$control[0]);
			define('PLUGIN',$control[1]);
			define('ACTION',$control[2]);
			if(isset($control[3])) define('PLUGIN_OPTIONS',$control[3]);
		}
		else{
			$control = explode('/', $_GET['q'],3);
			$localize = \core\cls\core\localize::singleton();
			define('LOCALIZE',$localize->language());
			define('PLUGIN',$control[0]);
			define('ACTION',$control[1]);
			if(isset($control[2])) define('PLUGIN_OPTIONS',$control[2]);
		}
	}
	else{
		$localize = \core\cls\core\localize::singleton();
		$defaultLocalize = $localize->localize();
		header('Location:' . SiteDomain . $defaultLocalize->home);
		exit();
	}
	
}
catch(Exception $e){
	exit(_('Internal error!'));
}
ob_start("render");
$registry = \core\cls\core\registry::singleton();
require_once(AppPath . 'themes/' . $registry->get('administrator', 'active_theme') . '/index.php');
ob_end_flush();
?>
