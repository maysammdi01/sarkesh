<?php
namespace core\cls\network;
use \core\cls\core as core;
use \core\cls\db as db;

class validator{
	/*
	 * @var object from orm class
	 */
	private $orm;

	/*
	 * @var array store settings
	 */
	private $settings;
	
	/*
	 * construct
	 */
	function __construct(){
		$this->orm = db\orm::singleton();
		$registry = core\registry::singleton();
		$this->settings = $registry->getPlugin('administrator');
		$lastCheck = $this->settings['validator_max_time'] + $this->settings['validator_last_check'];
		//we use this for save in database;
		if($lastCheck < time()){
			#refresh database for delete old validator keys
			$this->refresh();
		}
	}
	/*
	 *  set validator with source and save that in cookie and session
	 * @param string #source,name of source
	 * @param boolean $cookie,for save in cookie
	 * @param boolean $session,save is session
	 * @param string $back(just return id:id, return special id:sid, else array of validator)
	 * @return integer, id of validator
	 */
	public function set( $source,$cookie = false, $session = true, $back = 'id'){
		//check for that is this source saved before
		if(!$this->check($source)){

			//not set before now we want to save that
			//first create random spicial_id
			$id=core\general::randomString();
			//save source in session
			if($session) session::set($source,$id);
			//set in cookie
			if ($cookie) cookie::set($source , $id);
			//save source in database
			$id = $this->orm->exec('INSERT INTO validator (source,valid_time,special_id) VALUES (?,?,?);' , [$source,time() + $this->settings['validator_max_time'], $id],NON_SELECT);
			if($back == 'id') return $id;
			elseif($back == 'sid') return $id; 
			else{
				//RETURN ALL ROW
				return $this->orm->findOne('validator', 'special_id=?;',[$id]);
			}
		}
		else{
			//return updated id
			return $this->getID($source , $back);
		}
		
	}
	
	/* 
	 * check for that is source validated before
	 * @param string $source, key of validator
	 * @return boolean (set:true, else:false)
	 */
	public function check($source){
		$id = $this->getID($source);
		if($id == '0') return false;
		if($this->orm->count('validator','id=?',[$id]) != 0){
			//source is validated
			$this->update($id);
			return true;
		}
		//source is not valid 
		return false;
	}
	//this function delete validator
	public function delete($source, $id =0){
		if($id == 0){ $id = $this->getID($source); }
		if($id != 0){
			//going to delete that
			return $this->orm->do_query("DELETE FROM validator WHERE id=?;" ,array(trim($id)));

		}
		return false;
	}
	/*
	 * get spicial id from user client
	 * @param string $source, key of source
	 * @param string $back, (id || sid)
	 * @return integer, id of validator ( null == not found)
	 */
	public function getID($source, $back = 'id'){
		$id = 0;
		if(isset($_GET[$source])) $id = $_GET[$source];
		elseif(isset($_SESSION[$source])) $id = session::get($source);
		elseif(isset($_COOKIE[$source])) $id = $_COOKIE[$source];
		
		if($id != '0'){
			$orm = db\orm::singleton();
			if($orm->count('validator','special_id=?',[trim($id)]) == 0)
				return 0;
			else{
				$result = $orm->findOne('validator','special_id=?',[trim($id)]);
				if($back == 'id') return $result->id; 
				elseif($back == 'sid') return $id;
			}
		}
		return null;
	}
	
	/*
	 * update source
	 * @param string $id , id of validator
	 */
	private function update($id){
		$this->orm->exec('UPDATE validator SET valid_time=? WHERE special_id=?;', [time() + 3600, $id], NON_SELECT); 
	}
	
	/*
	 * refresh and delete invalid validator keys that stored in database
	 */
	private function refresh(){
		#clear old data from database
		$this->orm->exec("delete from validator where valid_time<?;", [time()], NON_SELECT);
		#update next check for refresh database
		$registry = core\registry::singleton();
		$registry->set('core', 'validator_last_check' , time());
	}
}
	

?>
