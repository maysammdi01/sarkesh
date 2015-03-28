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
		 $txt = new control\checkbox('txt_you');
		 $txt->index = 0;
		 $form->add($txt);
		 $txt->index = 1;
		 $form->add($txt);
		 
		 $button = new control\button('btn');
		 $button->p_onclick_function = 'hello';
		 $button->p_onclick_plugin = 'hello';
		 $form->add($button);
		 return ['test uploader',$form->draw()];
		 
	 }
}
