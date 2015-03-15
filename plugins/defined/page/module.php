<?php
namespace addon\plugin\page;
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
		$url = core\general::createUrl(['service','administrator','load','page','newPage']);
		array_push($menu,[$url, _('New Page')]);
		$url = core\general::createUrl(['service','administrator','load','page','listPages']);
		array_push($menu,[$url, _('List Pages')]);
		$url = core\general::createUrl(['service','administrator','load','page','catalogues']);
		array_push($menu,[$url, _('Catalogues')]);
		$url = core\general::createUrl(['service','administrator','load','page','settings']);
		array_push($menu,[$url, _('Page Settings')]);

		$ret = [];
		array_push($ret, ['<span class="glyphicon glyphicon-book" aria-hidden="true"></span>' , _('Pages')]);
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
