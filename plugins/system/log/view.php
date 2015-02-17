<?php
namespace core\plugin\log;
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
		$btn_update->configure('P_ONCLICK_PLUGIN','log');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_clear_php_logs');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$form->add($row);                
		return [_('PHP Error logs'),$form->draw()];
	}

	//function for check update
	protected function view_updates($cerrent,$last){
		$form = new control\form('frm_log_updates');

		$lbl_current = new control\label;
		$lbl_current->configure('LABEL',sprintf(_('Your system build number:%s'),$cerrent)  );
		
		$lbl_last = new control\label;
		$lbl_last->configure('LABEL',sprintf(_('Last relased build number:%s'),$last)  );
		$form->add($lbl_current);
		$form->add($lbl_last);

		//show update message
		$lbl_update_msg = new control\label;
		$lbl_update_msg->configure('LABEL',_('Your system is update! noting to do.')  );
		if($cerrent<$last){
			$lbl_update_msg->configure('LABEL',sprintf(_('Your system is out of date.for get latest update go to sarkesh website'),$last)  );
			$btn_jump = new control\button('btn_update');
			$btn_jump->configure('LABEL',_('Jump to release notes'));
			$btn_jump->configure('TYPE','success');
			$btn_jump->configure('HREF',S_SERVER_INFO . 'release_notes/' . $last . '.txt');
			$form->add($lbl_update_msg);
			$form->add($btn_jump);
		}
		else{
			$form->add($lbl_update_msg);
		}
		return [_('Updates'),$form->draw()];
	}
	
}
