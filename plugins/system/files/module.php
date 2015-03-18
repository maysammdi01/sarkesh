<?php
namespace core\plugin\files;
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
		$url = core\general::createUrl(['service','administrator','load','reports','ports']);
		array_push($menu,[$url, _('File upload ports')]);
		$url = core\general::createUrl(['service','administrator','load','reports','places']);
		array_push($menu,[$url, _('Places of files')]);
		$ret = [];
		array_push($ret, ['<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span>' , _('Files')]);
		array_push($ret,$menu);
		return $ret;
	}
	
	/*
	 * function for do upload file operation
	 * @return string xml content
	 */
	public function moduleDoUpload(){
		if(array_key_exists('uploads',$_FILES)){
			$orm = db\orm::singleton();
			$activePlace = $orm->findOne('file_places','state=1');
			$targetDir = AppPath . $activePlace->options;
			$fileName = $targetDir . core\general::randomString(10,'NC') . $_FILES["uploads"]["name"])
			move_uploaded_file($_FILES["uploads"]["tmp_name"],$targetDir . core\general::randomString(10,'NC') . $_FILES["uploads"]["name"]);
			$orm->disponse('files');
			echo 'successssful';
		}
	}
	
}
