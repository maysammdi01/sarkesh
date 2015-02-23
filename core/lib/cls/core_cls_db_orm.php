<?php
/*
 * Class for working with mysql
 * Author:Babak Alizadeh
 * Email:alizadeh.babak@gmail.com
 * Published under LGPL V3 license
 */

namespace core\cls\db;
use core\cls\patterns as patterns;
use core\data as data;
class orm{
    use patterns\singleton;
    
    /*
     * @var object The PDO Object
     */
    private  $pdoObj;
 
    /*
     * @var object The PDO statement prepared from the given query
     */
    private  $query;

    /*
     * @var resource The result of query execution
     */
    private  $result;
     
    /*
     * Constructor
     */
    function __construct(){
        $this->connect();
    }
    
     /**
     * Connect to DB engine
     */
    private function connect(){
		try {
			$options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''];
			$this->pdoObj = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME , DB_USER, DB_PASS, $options);
			$this->pdoObj->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		}
		catch(PDOException $e) {
            echo sprintf(_('Error in query execution!Reason: %s') , $e->getMessage());
            exit;
        }
    }
    
    /**
     * query from database and return back just one row
     * @param string $table, table for select
     * @param string $sql
     * @param array $bindings,default = empty array
     * @return \core\data\dbRow object
     */
     
    public function findOne($table,$sql = NULL, $bindings = array()){
		$this->run($table,$sql, $bindings);
		$this->result = $this->query->fetch();
		$dbRow = new data\dbRow;
		$dbRow->load($this->result,$table,$this->result->id);
		return  $dbRow;
	 }
	 
	 /**
     * query from database and return back all result
     * @param string $table, table for select
     * @param string $sql
     * @param array $bindings,default = empty array
     * @return array pdo fetch object
     */
     
    public function find($table,$sql = NULL, $bindings = array()){
		$this->run($table,$sql, $bindings);
		$this->result = $this->query->fetchAll();
		return  $this->result;
	}
	/*
	 * load row with id
	 * @param string $table, table for select
     * @param integer $id,id of row in table
     * @return \core\data\dbRow object
     */
    public function load($table,$id){
		return $this->findOne($table,'id=?',[$id]);
	}
	
	/*
	 * return all rows in table
	 * @param string $table, table for select
     * @param integer $id,id of row in table
     * @return array pdo fetch object
     */
    public function getAll($table){
		return $this->find($table);
	}
	/*
	 * return all rows in table
	 * @param string $table, table for select
     * @param integer $id,id of row in table
     * @return array pdo fetch object
     */
    public function findAll($table){
		return $this->find($table);
	}
	 /**
     * query from database and return back all result
     * @param string $query, all query string
     * @param int $select,(select query:0, update,delete,insert:1, return first column:2)
     * @return array pdo fetch object if no row returned :null
     */
     
    public function exec($sql, $bindings = array(),$type = SELECT){
		$this->runWithSql($sql, $bindings);
		if($type == SELECT){
			if($this->query->rowCount() == 0) return null;
			$this->result = $this->query->fetchAll();
		}
		elseif($type == NON_SELECT) $this->result = $this->query->rowCount();
		else{
			//RETURN ONE ROW
			if($this->query->rowCount() == 0) return null;
			$this->result = $this->query->fetch();
		}
		return  $this->result;
	}
	
	 /*
	  * prepare query string and bindings and set in $this->query and execute
	  * @param string $table, table for select
      * @param string $sql
      */
    private function run($table,$sql = NULL, $bindings = array()){
		$queryString = 'SELECT * FROM ' . $table;
		if(!is_null($sql)) $queryString .= ' WHERE ' . $sql;
		$this->runWithSql($queryString,$bindings);
	}
	
	 /*
	  * prepare sql string and bindings and set in $this->query and execute
	  * @param string $table, table for select
      * @param string $sql
      */
    private function runWithSql($sql, $bindings = array()){
		$this->query = $this->pdoObj->prepare($sql);
		$this->query->setFetchMode(\PDO::FETCH_OBJ);
		$this->query->execute($bindings);
	}
	
	/*
	* find count of rows
    * @param string $table, table for select
    * @param string $sql
    * @param array $bindings,default = empty array
    * @return integer
    */
     
    public function count($table,$sql = NULL, $bindings = array()){
		$this->run($table,$sql, $bindings);
		$this->result = $this->query->rowCount();
		return  $this->result;
	}
	
	/*
	*create object from db type
	* @param string $table,name of table that you want to insert or update that
	* @return object from core\data\type\db
	*/
	public function dispense($table){
		return new data\dbRow($table);
	}
	
	/*
	* save dbRow object in database
	* @$param object $row,object with type core\data\dbRow
	* @return int id of row in table (unsaccessful:null)
	*/
	public function store($dbRow){
		$row = $dbRow->fetch();
		$colsValue = [];
		if($dbRow->getId() == null){
			$colsName = array_keys($row);
			$qSign = [];
			foreach($row as $key => $col){
				array_push($qSign ,'?');
				array_push($colsValue ,$col);
			}
			//going to insert
			$queryString = 'INSERT INTO ' . $dbRow->getTable() . ' (' . implode(',',$colsName) . ') VALUES (' . implode(',',$qSign) . ');';

		}
		else{
			$set = [];
			foreach($row as $key => $col){
				array_push($set ,$key . '=?');
				array_push($colsValue ,$col);
			}
			//going to update
			$queryString = 'UPDATE ' . $dbRow->getTable() . ' SET ' . implode(',',$set) . ' WHERE id=?';
			array_push($colsValue ,$dbRow->getId());
		}
		$this->exec($queryString,$colsValue,NON_SELECT);
		return $this->result;
	}

}
?>
