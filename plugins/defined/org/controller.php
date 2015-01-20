<?php

namespace addon\plugin;
use core\cls\core as core;
use core\cls\browser as browser;
use addon\plugin as plugin;

class org extends org\module{

	public function show_serch_card(){
		
		return $this->module_show_serch_card();
	
	}
	
	public function agree_card($e){
	
		if(trim($e['securi']['VALUE'])==''||trim($e['random']['VALUE'])==''){
			$e['RV']['MODAL']=browser\page::show_block(_('پیام'),_('کاردها خالی است'),'MODAL','type-warning');
			return $e;
		}
		return $this->module_agree_card($e);
	}
	
	
	


}



?>
