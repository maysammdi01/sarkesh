<?php
namespace addon\plugin\rss;
use core\cls\browser as browser;
use core\cls\network as network;
use core\cls\core as core;
use core\cls\db as db;

class module{
	use view;
	use addons;
	
	/*
	 * construct
	 */
	function __construct(){}
	
	//this function return back menus for use in admin area
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','rss','newFeed']);
		array_push($menu,[$url, _('New Feed')]);
		$url = core\general::createUrl(['service','administrator','load','rss','listFeeds']);
		array_push($menu,[$url, _('List feeds')]);

		$ret = [];
		array_push($ret, ['<span class="glyphicon glyphicon-star" aria-hidden="true"></span>' , _('RssReader')]);
		array_push($ret,$menu);
		return $ret;
	}
	
	/*
	 * show php error log
	 * @return 2D array [title,content]
	 */
	public function modulePhpErrors(){
		if($this->hasAdminPanel()){
			if(file_exists(S_Error_Log_Place)){
				//get errors
				$file = file(S_Error_Log_Place);
				return $this->viewPhpErrors($file);
			}
			//log not found this mean no error was acoured or error log file is empty
			return [_('PHP Errors'),_('No error was ecured.')];
		}
		return browser\msg::pageAccessDenied();	
	}
	
}
