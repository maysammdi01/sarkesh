<?php
namespace core\plugin\hello;
use \core\control as control;

class event{
	
	function __construct(){
	}
	
	/*
	 * action for show hello word
	 * @param array $e, form elements
	 * @return array content
	 */
	 public function hello($e){
		var_dump($e);
		 return $e;
	 }
	 public function tt($e){
		 $e['txtAli']['VALUE'] = 'ALI';
		 return $e;
	 }
	 
	 
}
