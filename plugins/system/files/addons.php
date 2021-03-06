<?php
namespace core\plugin\files;
use \core\cls\core as core;
use \core\cls\network as network;
use \core\cls\db as db;

trait addons {
	
	/*
	 * check for that file is exist
	 * @param string $sid , special id of file
	 * @return boolean
	 */
	public function fileExists($sid){
		$orm = db\orm::singleton();
		if($orm->count('files','sid=?',[$sid]) != 0)
			return true;
		return false;
	}
	
	/*
	 * create address to access to file
	 * @param string $sid, special id for access to file
	 * @return string, file address, null=notfound
	 */
	public function getFileAddress($sid){
		if(!$this->fileExists($sid))
			return null;
		return core\general::createUrl(['service','files','load',$sid]);
	}
	
	/*
	 * request for remove file
	 * @param string $sid, special id for access to file
	 * @return boolean
	 */
	public function fileRemove($sid){
		if($this->fileExists($sid)){
			$orm = db\orm::singleton();
			$file = $orm->findOne('files','sid=?',[$sid]);
			if(file_exists(AppPath . $file->address))
				unlink(AppPath . $file->address);
			$orm->exec('DELETE FROM files WHERE sid=?',[$sid],NON_SELECT);
			return true;
		}
		return false;
	}
}
