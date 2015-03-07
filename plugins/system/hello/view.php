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
		 $form = new control\form('text_form');
		 
		 $txt = new control\textbox('txt_sample');
		 
		 $btn = new control\button('btn_ff');
		 $btn->P_ONCLICK_PLUGIN = 'hello';
		 $btn->configure('P_ONCLICK_FUNCTION','sampleOnclickEvent');
		 $form->addArray([$txt,$btn]);
		 
		 return [1,$form->draw()];
		 
	 }
}
