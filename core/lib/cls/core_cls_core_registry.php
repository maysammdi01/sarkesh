<?php
//this classs for using registry table in database
namespace core\cls\core;
use \core\cls\db as db;
use core\cls\patterns as patterns;
	
class registry{
	use patterns\singleton;
	
	/*
	* @var object orm
	*/
	private $orm;
	
	/*
	* construct
	*/
	function __construct(){
		$this->orm = db\orm::singleton();
	}
	
	/*
	* get key value from registry
	* @param string $plugin,name of plugin
	* @param string $key
	* @return string (not found: null)
	*/ 
	public function get($plugin, $key){
		$result = $this->orm->exec('SELECT r.id, r.a_key, r.value, p.name FROM registry r INNER JOIN plugins p ON p.id = r.plugin  WHERE p.name = ? and r.a_key = ?;', [$plugin, $key],SELECT_ONE_ROW);
		if(!is_null($result)) return $result->value;
		return null;
	}
	
	/*
	* find all settings for one plugin
	* @param string $plugin,name of plugin
	* @return array string (no settings found:null)
	*/ 
	public function getPlugin($plugin){
		$result = $this->orm->exec('SELECT r.a_key, r.value, p.name FROM registry r INNER JOIN plugins p ON p.id = r.plugin  WHERE p.name = ?;', array($plugin));
		foreach($result as $row) $result[$row->a_key] = $row->value;
		return $result;
	}
	
	/*
	* update key value in registry
	* @param string $plugin,name of plugin
	* @param string $key
	* @param string $value,new value for key
	* @return boolean (successfull:true , fail:false)
	*/
	public function set($plugin, $key, $value){
		if($this->orm->count('plugins','name=?',[$plugin])	){
			$plugin = $this->orm->findOne('plugins','name=?',[$plugin]);
			$item = $this->orm->findOne('registry','plugin=? && a_key=?',[$plugin->id,$key]);
			$item->value = $value;
			$this->orm->store($item);
			return true;
		}
		//plugin not found 
		return false;
	}
	
	/*
	* delete all settings from plugin
	* @param string $plugin,plugin name
	* return void
	*/ 
	public function deletePlugin($plugin){
		$this->orm->exec('DELETE FROM registry WHERE plugin=?;', [$plugin],NON_SELECT); 
	}
	
}
?>
