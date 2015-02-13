<?php

namespace addon\plugin;
use core\cls\browser as browser;
use core\cls\core as core;
class card extends card\module{


	/*public function core_menu(){
	
		$menu = array();
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','card','a','insertcard']);
		array_push($menu,[$url,_('insert card')]);	
		$url = core\general::create_url(['service','1','plugin','administrator','action','main','p','card','a','showcard']);
		array_push($menu,[$url,_('show card')]);
		$retu = array();
		array_push($retu,_('card'));
		array_push($retu,$menu);
		return $retu;
		
	}*/

    public function insertcard(){
        return $this->module_card();
    }
    /*
    	INPUT: ELEMENTS
    	THIS FUNCTION INSERT CARD IN DATABASE
    	OUTPUT: ELEMENTS
    */
    public function insert($e){

    	return $this->module_insert($e);
    }
    /*
     * This function is for show cards
     * it's action
    */
    public function showcard(){
      return $this->module_showcard();
    }
	
	public function Onac_try_card($e){
	
		return $this->module_Onac_try_card($e);
	}
    
    /*
     * This action is for preview cards for buy
     * Next step of this action is buy card
	 * to the step should ,connect to electronic bank
    */
    public function preview(){	
    	return $this->module_preview();
    }
    
    /*
     * This action is for manage cards disable and edite cards
     *
    */
    public function manage_cards(){
        
        return $this->module_manage();
    }
	public function prebuy($e){
		return $this->module_prebuy($e);
		
	}
	public function pay_mony($e){
		if(trim($e['name_us']['VALUE'])=='' ||trim($e['name_f_us']['VALUE'])=='' ||trim($e['number_ph_us']['VALUE'])==''){
			$e['RV']['MODAL']=browser\page::show_block(_('پیغام'),_('فیلدهای خالی را پر کنید'),'MODAL','type-warning');
			return $e;
			}
			return $this->module_pay_mony($e);
		
		}
    public function Edite_card(){
	
		return $this->module_Editecard();
	
	}
	public function change_card($e){
	
		if(trim($e['nam_card']['VALUE'])=='' ||trim($e['pric_card']['VALUE'])== ''){
			
			$e['RV']['MODAL']=browser\page::show_block(_('هشدار'),_('کادرها را پر کنید'),'MODAL','type-danger');
			return $e;
		
		}
		
		return $this->module_change_card($e);
	}
	
	public function is_exist_card($type,$password){
	
		return $this->module_is_exist_card($type,$password);
	}
	

}   
?>
