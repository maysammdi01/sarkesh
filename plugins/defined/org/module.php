<?php

namespace addon\plugin\org;
use \core\cls\core as core;
use \core\cls\browser as browser;
use \core\cls\db as db;
use \addon\plugin as plugin;
use \core\plugin as corePlugin;
class module extends view{


	protected function module_show_search_card(){
	
		return $this->view_show_search_card();
	}

	protected function module_agree_card($e){
		
		$valid = new plugin\card;
	    $validy=$valid->is_exist_card($e['securi']['VALUE'],$e['random']['VALUE']);
		 
		 if($validy){
			
			$state=db\orm::findOne('firstinfo','random_bnk=?',[$e['random']['VALUE']]);
			if($state->fetish == '1'){
				$e['RV']['MODAL']=browser\page::show_block(_('هشدار'),_('با این رمز قبلا اطلاعتی درج شده '),'MODAL','type-danger');
				$e['RV']['JUMP_AFTER_MODAL']='R';
				return $e;
				}
					
					elseif(db\orm::count('orgcardinfo','random_bnk=?',[$e['random']['VALUE']]) !=0){
					$e['RV']['URL'] = core\general::create_url(['plugin','org','action','info_car','id',$e['random']['VALUE']]);
					return $e;
					}
						$buyy = db\orm::dispense('orgcardinfo');
						$buyy->card_id = $e['securi']['VALUE'];
						$buyy->random_bnk = $e['random']['VALUE'];
						db\orm::store($buyy);

						$e['RV']['URL'] = core\general::create_url(['plugin','org','action','info_car','id',$e['random']['VALUE']]);
						return $e;
			
			
		 }
		   $e['RV']['MODAL']=browser\page::show_block(_('هشدار'),_('چنین کارت اعتباری وجود ندارد'),'MODAL','type-danger');
		   $e['RV']['JUMP_AFTER_MODAL']='R';
			 return $e;
	  }
	protected function module_info_car(){
		    if(isset($_GET['id'])){
				if(db\orm::count('orgcardinfo','random_bnk=?',[$_GET['id']]) !=0){
						return $this->view_info_car();
			    }
		    return core\router::jump_page(404);
		 }   
	}
	protected function module_first_check($e){
		
		
	
			$first_info = db\orm::dispense('firstinfo');
		
			$first_info->random_bnk = $e['hidd']['VALUE'];
		
			$first_info->name_us = $e['name_user']['VALUE'];
			$first_info->famil_us = $e['nam_f_user']['VALUE'];
			$first_info->intrnion_num = $e['num_prsonal']['VALUE'];
		
			$first_info->ser_sh = $e['num_sr_sh']['VALUE'];
			$first_info->alph_sh = $e['alph_sr_sh']['SELECTED'];
			$first_info->towe_num = $e['smal_sr_sh']['SELECTED'];
			$first_info->sh_num = $e['num_prs_sh']['VALUE'];
		
			$first_info->shasi_num = $e['nu_shasi']['VALUE'];
			$first_info->body_num = $e['nu_badanh']['VALUE'];
			$first_info->fetish = '0';
		
			db\orm::store($first_info);
		
			$e['RV']['URL']=core\general::create_url(['plugin','org','action','final_check','id',$e['hidd']['VALUE']]);
			return $e;
			}
	
		protected function module_final_check($id){
			
			$final=db\orm::findOne('firstinfo','random_bnk=?',[$id]);
			
			$types = db\orm::getAll('SELECT c.id,c.cardname,c.price FROM orgcardinfo r INNER JOIN card c ON c.id=r.card_id where r.random_bnk=?',[$id]);
			
			$types_obj = db\orm::convertToBeans( 'type', $types );
			echo($types_obj->cardname);
			
			//return $this->view_final_check($types_obj,$final);
		}
			

}

?>
