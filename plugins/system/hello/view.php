<?php
namespace core\plugin\hello;
use \core\control as control;

class view {
	
	function __construct(){
		
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function viewSample(){

		 $uploader = new control\uploader('hello_uploader');

		 return [1,$uploader->draw()];
		 
	 }
}
