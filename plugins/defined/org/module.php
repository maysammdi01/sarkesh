<?php

namespace addon\plugin\org;
use \core\cls\core as core;
use \core\cls\browser as browser;
use \core\cls\db as db;
use \addon\plugin as plugin;
class module extends view{

	/*	private $valid;
		function __construct(){
		$this->valid = new plugin\card;
		parent::__construct();
		}*/
		
	protected function module_show_serch_card(){
	
		return $this->view_show_serch_card();
	}

	protected function module_agree_card($e){
		
		
		$valid = new plugin\card;
	    $validy=$valid->is_exist_card($e['securi']['VALUE'],$e['random']['VALUE']);
		
		 if($validy){
			
		 $buyy = db\orm::dispense('orgcardinfo');
		 $buyy->card_id = $e['securi']['VALUE'];
		 $buyy->random_bnk = $e['random']['VALUE'];
		 db\orm::store($buyy);
		$e['RV']['URL'] = SiteDomain;
		return $e;
		 
		 }
		   $e['RV']['MODAL']=browser\page::show_block(_('هشدار'),_('چنین کارتی اعتبار وجود ندارد'),'MODAL','type-danger');
			 return $e;
	}

}

?>
