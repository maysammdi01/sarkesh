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
	protected function moduleDoUpload(){
		if(array_key_exists('uploads',$_FILES)){
			$orm = db\orm::singleton();
			$port = $orm->findOne('file_ports','name=?',[$_REQUEST['port']]);
			//check file size
			if($_FILES["uploads"]["size"] < $port->maxFileSize){
				$activePlace = $orm->findOne('file_places','state=1');
				$targetDir = AppPath . $activePlace->options;
				$fileName = $targetDir . core\general::randomString(10,'NC') . $_FILES["uploads"]["name"];
				move_uploaded_file($_FILES["uploads"]["tmp_name"],$fileName);
				$file = $orm->dispense('files');
				$file->name = $fileName;
				$file->place = $activePlace->id;
				$file->date = time();
				$file->size = $_FILES["uploads"]["size"];
				$user = $this->getCurrentUserInfo();
				$userID = null;
				if(!is_null($user)) $userID = $user->id;
				$file->user = $userID;
				$file->address = $fileName;
				$orm->store($file);
				echo 'successssful';
			}
		}
	}
	
	/*
	 * this service return back and show file
	 * @return image file and ...
	 */
	protected function moduleLoad(){
		$orm = db\orm::singleton();
		if($orm->count('files','id=?',[PLUGIN_OPTIONS]) != 0){
			$file = $orm->load('files',PLUGIN_OPTIONS);
			return $this->moduleLoadFile($file);
		}
	}
	
	/*
	 * find file extention and send back on browser
	 * @return null
	 */
	protected function moduleLoadFile($file){
		$fileInfo = pathinfo($file->name);
		//check and send back files
		if($fileInfo['extension'] == 'png'){
			//show png picture
			header('Content-Type: image/png');
		}
		elseif($fileInfo['extension'] == 'jpg' || $fileInfo['extension'] == 'jpeg'){
			//show jpg image
			header('Content-Type: image/jpeg');
		}
		elseif($fileInfo['extension'] == 'gif'){
			//show jpg image
			header('Content-Type: image/gif');
		}
		
		else{
			//download file
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment, filename=" . $file->name );
		}
		readfile($file->name);
	}
	
}
