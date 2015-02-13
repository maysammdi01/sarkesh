<?php
namespace addon\plugin;
use core\cls\core as core;
use core\cls\browser as browser;
class org extends org\module{

	public function show_search_card(){
		
		return $this->module_show_search_card();
	
	}
	
	public function agree_card($e){
	
		if(trim($e['securi']['VALUE'])==''||trim($e['random']['VALUE'])==''){
			$e['RV']['MODAL']=browser\page::show_block(_('پیام'),_('کاردها خالی است'),'MODAL','type-warning');
			return $e;
		}
		return $this->module_agree_card($e);
	}
	
	public function info_car(){
	
			return $this->module_info_car();
		     
		 }

	
	public function first_check($e){
	
		if(trim($e['name_user']['VALUE'])==''||trim($e['nam_f_user']['VALUE'])==''||trim($e['num_prsonal']['VALUE'])==''||trim($e['num_sr_sh']['VALUE'])==''||trim($e['alph_sr_sh']['SELECTED'])==''||trim($e['smal_sr_sh']['SELECTED'])==''||trim($e['num_prs_sh']['VALUE'])==''||trim($e['nu_shasi']['VALUE'])==''||trim($e['nu_badanh']['VALUE'])==''){
	
          $e['RV']['MODAL']=browser\page::show_block(_('هشدار'),_('همه ای کادرها را پر کنید'),'MODAL','type-danger');
	      return $e;
	    }
	   return $this->module_first_check($e);
   }
   public function final_check(){
   
	  if(isset($_GET['id'])){
			return $this->module_final_check($_GET['id']);
		}
		 else{
			//show not found message
			$msg = new plugin\msg;
			return $msg->msg404();
		 }
		
	}
	
}
?>
