<?php
namespace core\plugin;
use \core\plugin\hello as hello;
use \core\control as control;
class hello extends hello\module{
	
	function __construct(){
		parent::__construct();
	}
	

	
	/*
	 * This function and function that defined in module and view is for showing for work with uploader in system
	 */
	public function uploader(){
		return $this->module_uploader();
	}
	
	public function test(){
		$form = new \control\form('test');
		
		$text = new \control\textbox('textbox');
		$text->configure('LABEL','Name');
		$text->configure('ADDON','N');
		
		$btn = new \control\button('BTN');
		$btn->configure('LABEL','Click me!');
		
		$btn->configure('P_ONCLICK_PLUGIN','hello');
		$btn->configure('P_ONCLICK_FUNCTION','btn_onclick');
		
		$form->add_array(array($text,$btn));
		
		return array('TITTLE',$form->draw());
		
	}
	public function btn_onclick($e){
		
		$a = $e['textbox']['VALUE'];
		$e['RV']['MODAL'] = cls_page::show_block('title',$a,'MODAL','type-danger');
		return $e;
		
	}
	public function sw(){
		$form = new control\form('frmmm');
		$ch = new control\checkbox('chk');
		$ch->configure('SWITCH',TRUE);
		$form->add($ch);
		return [1,$form->draw()];
	}
	
}
?>
