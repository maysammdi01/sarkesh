<?php
namespace core\plugin\reports;
use \core\control as control;
use \core\cls\core as core;

class view{
	
	//show php errors
	//input argument is error file that converted to array
	protected function view_php_error_logs($file){
		$content='';
		foreach($file as $line){
			$content =$content . $line . '</br>';
		}
		$label = new control\label('lbl_logs');
		$label->configure('LABEL',$content);
		
		$form = new control\form('reports_php_error_logs');
		$form->add($label);
		//add update and cancel buttons
		$btn_update = new control\button('btn_clear');
		$btn_update->configure('LABEL',_('Clear Errors'));
		$btn_update->configure('P_ONCLICK_PLUGIN','reports');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_clear_php_logs');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,3);
		$row->add($btn_cancel,3);
		$form->add($row);                
		return [_('PHP Error logs'),$form->draw()];
	}
	
}
