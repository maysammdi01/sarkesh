<?php
namespace core\plugin\hello;

class action{
	
	function __construct(){
		parent::__construct();
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function sample(){
		 return [1,1];
		 
	 }
}
