<?php
/*
 * Class for working with database table types
 * Author:Babak Alizadeh
 * Email:alizadeh.babak@gmail.com
 * Published under LGPL V3 license
 */

namespace core\data;
class dbRow{
	
	/*
	* @var store row data
	*/
	public $table;
	 
	/*
	* @var store table name
	*/
	public $tableName;
	 
	/*
	* construct
	* @param string $table,name of table
	*/
	 
	function __construct($table = null){
		$this->table = [];
		$this->tableName = $table;
	}
	
	/*
	* load row
	* @param array || object $row,array of rows in table
	*/
	public function load($table){
		$this->row = array_merge($this->table, (array) $table);
	}
	
	/*
	* set table name
	* @param string $table,table name
	*/
	public function setTable($table){
		$this->table = $tableName;
	}
		
}
?>
