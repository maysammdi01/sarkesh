<?php
namespace core\plugin\hello;
use \core\control as control;

class action extends module{
	
	function __construct(){
		
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function sample(){
		return $this->moduleSample();
	 }
	 
	 public function ali(){
		 $a = new control\textbox('txt');
		 return ['ali',$a->draw()];
	 }
}
