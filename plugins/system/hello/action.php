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
		 $form = new control\form('fgn');
		 
		 $a = new control\textbox('txtAli');
		 $form->add($a);
		 
		 $b = new control\button('btn');
		 $b->P_ONCLICK_PLUGIN = 'hello';
		 $b->P_ONCLICK_FUNCTION = 'tt';
		 $b->type = 'success';
		 $form->add($b);
		 return ['ali',$form->draw()];
	 }
}
