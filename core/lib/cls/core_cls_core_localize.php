<?php
namespace core\cls\core;
use \core\cls\network as network;
use \core\cls\db as db;
use core\cls\patterns as patterns;

// this class is for translate parameters in theme and plugins
class localize{
	use patterns\singleton;
	private $db;
	private $localize;
	private $obj_cookie;
	private $session;
	
	function __construct(){
		$this->obj_cookie = new network\cookie;
		$this->obj_session = new network\session;
		$this->db = db\mysql::singleton();
		$this->db->do_query("select * from localize where main ='1';");
		$this->localize = $this->db->get_first_row_array();
		$this->get_language();
	}
	//this function return difined localize settings in cookie
	public function get_localize($dif = false){
		if($dif){
			return $this->localize;
		}
		$local = db\orm::findOne('localize','language=?',[$this->get_language()]);
		return $local;
	}

	//this function set cms language on cookie
	public function set_language($language){

		if($language != ''){
			$this->obj_cookie->set('core_language' , $language);
			$this->obj_session->set('core_language', $language);
			return true;
		}
		return false;
	}
	//this function get language name from cookie if that not defined return system default localize language
	public function get_language(){

		$obj_io = new network\io;
		if($this->obj_session->is_set('core_language')){
			
			return $this->obj_session->get('core_language');		
		}
		elseif($this->obj_cookie->is_set('core_language')){
			
			return $obj_io->cin('core_language','cookie');
		}
		else{
		//return difault language
		
		return $this->localize['language'];
		}
	}
	//this function return language code that can use in tinymce editor and ect
	public function convert_language_code($language){
		switch ($language) {
			case 'fa_IR':
			    return 'fa';
			    break;
			case 'en_US':
			    return "en";
			    break;
		}
	
	}

	//this function return all localize that exists in system
	public function get_all(){
		return db\orm::findAll('localize');
	}
	
	//this function return default language of system
	public function get_default_language(){
		return db\orm::findOne('localize','main=1');
	}
//END CLASS
}
?>
