<?php
namespace core\plugin\hello;
use \core\control as control;

class action{
	
	function __construct(){
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function sample(){
		 $form = new control\form('text_form');
		 
		 $txt = new control\textbox('txt_samplae');
		 
		 $btn = new control\button('btn_ff');
		 $form->add_array([$txt,$btn]);
		 
		 return [1,$form->draw()];
		 
	 }
}
