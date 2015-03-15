<?php
namespace core\plugin\hello;
use \core\control as control;

class module extends view{
	
	function __construct(){
		
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function moduleSample(){
		 $form = new control\form('text_form');
		 
		 $uploader = new control\uploader('hello_uploader');
		 $form->add($uploader);
		 
		 return [1,$form->draw()];
		 
	 }
}
