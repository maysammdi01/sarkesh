<?php
#this class is for auth data
namespace core\cls\network;

use \core\cls\core as core;
use \core\cls\db as db;
use \core\cls\network as network;

class validator{
	/*
	 * @var object from orm class
	 */
	private $orm;

	/*
	 * @var array store settings
	 */
	private $settings;

	function __construct(){

		$this->obj_general = new core\general;
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
		if(!$this->is_set($source)){

			//not set before now we want to save that
			//first create random spicial_id
			$id=core\general::randomString();
			//save source in session
			if($session) $this->obj_session->set($source,$id);
			//set in cookie
			if ($cookie) $this->obj_cookie->set($source , $id);
			//save source in database
			$this->orm->exec('INSERT INTO validator (source,valid_time,special_id) VALUES (?,?,?);' , [$source,time() + $this->settings['validator_max_time'], $id],NON_SELECT);
			if($back == 'id') return  $this->orm->last_insert_id(true);
			elseif($back == 'sid') return $id; 
			else{
				//RETURN ALL ROW
				return $this->orm->findOne('validator', 'special_id=?;',[$id]);
			}
		}
		else{
			//return updated id
			return $this->get_id($source , $back);
		}
		
	}
	//this function check for that is source validated before
	public function is_set($source){
		$id = $this->get_id($source);

		if($id == '0'){
			//we found nothing from cookie and session
			return false;
		}
		//now we want to check spicial id with database
		$this->orm->do_query("SELECT * FROM validator WHERE id=?;" ,array($id));
		if($this->orm->rows_count(true) != 0){
			//source is validated
			$this->update($id);
			return true;
		}
		//source is not valid 
		return false;
	}
	//this function delete validator
	public function delete($source, $id =0){
		if($id == 0){ $id = $this->get_id($source); }
		if($id != 0){
			//going to delete that
			return $this->orm->do_query("DELETE FROM validator WHERE id=?;" ,array(trim($id)));

		}
		return false;
	}
	//this function get spicial id from user client
	public function get_id($source, $back = 'id'){
		$id = 0;
		if(isset($_GET[$source])){
			//going to get id from get metode;
			$id = $this->obj_io->cin($source, 'get');
		}
		elseif(isset($_SESSION[$source])){
			//geting from session
			$id = $this->obj_session->get($source);
		}
		elseif(isset($_COOKIE[$source])){

			//geting from cookie
			$id = $this->obj_io->cin($source, 'cookie');
		}
		if($id != '0'){
		
			$this->orm->do_query("SELECT * FROM validator WHERE special_id=?;" ,array(trim($id)));
			if($this->orm->rows_count() == 0){
				//not found
				return 0;
			}
			else{
				$result = $this->orm->get_first_row_array();
				if($back == 'id'){
					return $result['id']; 
				}
				elseif($back == 'sid'){
					return $id;
				}
				else{
					//return all of result
					return $result;
				}
			}
		}
		//not set
		return 0;
	}
	//this function update source
	private function update($id){
		$this->orm->exec('UPDATE validator SET valid_time=? WHERE special_id=?;', [time() + 3600, $id], NON_SELECT); 
		return true;
	}
	//this function refresh and delete invalid validator keys that stored in database
	private function refresh(){
		#clear old data from database
		$this->orm->exec("delete from validator where valid_time<?;", [time()], NON_SELECT);
		#update next check for refresh database
		$registry = core\registry::singleton();
		$registry->set('core', 'validator_last_check' , time());
	}
}
	

?>
