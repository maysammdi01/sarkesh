<?php
namespace core\plugin\hello;
use \core\control as control;

class event{
	
	function __construct(){
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function sampleOnclickEvent($e){
		 $e['txt_sample']['VALUE'] = 'YOU CLICKED ME!';
		 return $e;
	 }
}
