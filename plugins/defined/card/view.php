<?php
namespace addon\plugin\card;
use \core\control as control;
use \core\cls\core as core;

class view{

/* show page insert type and price cards
/*
/*
*/
	protected function view_card(){
		$form=new control\form('hsm');
		
		$user=new control\textbox;
		$user->configure('NAME','usercard');
		$user->configure('SIZE',6);
		$user->configure('LABEL',_('نام کارت'));
		$user->configure('PLACE_HOLDER',_(' نام کارت را وارد کنید'));
		
		$price=new control\textbox;
		$price->configure('NAME','numbercard');
		$price->configure('SIZE',6);
		$price->configure('LABEL',_('قیمت'));
		$price->configure('PLACE_HOLDER',_(' قیمت را به عدد وارد کنید'));
		$price->configure('IN_PUT_TYPE','N');
		$price->configure('HELP','مثال:2300000');
		$price->configure('ADDON',_('ریال'));
		
		$button=new control\button;
		$button->configure('LABEL',_('ثبت'));
		$button->configure('TYPE','primary');
		$button->configure('P_ONCLICK_PLUGIN','card');
		$button->configure('P_ONCLICK_FUNCTION','insert');

		$form->add_array(array($user,$price,$button));

		return array(_('ثبت کارت'),$form->draw(),true);
	}
	//show all liste of buy with edite, active or disactiv for admin
	/*
	/*
	*/
	protected function view_showcard($cards){
        		$form = new control\form("menus_menus_list");
		$table = new control\table;
		$counter = 0;
		foreach($cards as $key=>$card){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			
			$lbl_id = new control\label($counter);
			$row->add($lbl_id,1);
			
			$lbl_menu_name = new control\label($card->cardname);
			$row->add($lbl_menu_name,2);

			$lbl_menu_localize = new control\label($card->price);
			$row->add($lbl_menu_localize,2);
					
		if($card->state !=0){
			$btn_add_link = new control\button;
			$btn_add_link->configure('LABEL',_('disactive'));
			$btn_add_link->configure('TYPE','danger');
			$btn_add_link->configure('VALUE',$card->id);
			$btn_add_link->configure('P_ONCLICK_PLUGIN','card');
			$btn_add_link->configure('P_ONCLICK_FUNCTION','Onac_try_card');
			$row->add($btn_add_link,1);

			$btn_buy = new control\button;
			$btn_buy->configure('LABEL',_('buy'));
			$btn_buy->configure('TYPE','success');
			$btn_buy->configure('HREF',core\general::create_url(['plugin','card','action','preview','id',$card->id]));
			$row->add($btn_buy,1);
			
			$btn_Edite = new control\button;
			$btn_Edite->configure('LABEL',_('Edite'));
			$btn_Edite->configure('TYPE','warning');
			$btn_Edite->configure('HREF',core\general::create_url(['plugin','card','action','Edite_card','id',$card->id]));
			$row->add($btn_Edite,1);

			$table->add_row($row);
			
		}else{
			$btn_add_link = new control\button;
			$btn_add_link->configure('LABEL',_('active'));
			$btn_add_link->configure('TYPE','success');
			$btn_add_link->configure('VALUE',$card->id);
			$btn_add_link->configure('P_ONCLICK_PLUGIN','card');
			$btn_add_link->configure('P_ONCLICK_FUNCTION','Onac_try_card');
			$row->add($btn_add_link,1);	

			$btn_buy = new control\button;
			$btn_buy->configure('LABEL',_('buy'));
			$btn_buy->configure('TYPE','default');
			$row->add($btn_buy,1);
			
			$btn_Edite = new control\button;
			$btn_Edite->configure('LABEL',_('Edite'));
			$btn_Edite->configure('TYPE','warning');
			$btn_Edite->configure('HREF',core\general::create_url(['plugin','card','action','Edite_card','id',$card->id]));
			$row->add($btn_Edite,1);

	
			$table->add_row($row);
			}
		}
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Price'),_('Position'),_('Buy'),_('Edite')));
		$table->configure('HEADERS_WIDTH',[1,5,3,1,1,1]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);
		return [_(''),$form->draw()];
        	
    }
	/*show liste of buy for user
	/
	/
	*/
	protected function view_show_card($cardd){
	

        $form = new control\form("menus_menus_list");
		$table = new control\table;
		$counter = 0;
		foreach($cardd as $key=>$card){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			$lbl_id = new control\label($counter);
			$row->add($lbl_id,1);
			
			$lbl_menu_name = new control\label($card->cardname);
			$row->add($lbl_menu_name,2);

			$lbl_menu_price = new control\label($card->price);
			$row->add($lbl_menu_price,2);
			
          

			$btn_buy = new control\button;
			$btn_buy->configure('LABEL',_('buy'));
			$btn_buy->configure('TYPE','success');
			$btn_buy->configure('HREF',core\general::create_url(['plugin','card','action','preview','id',$card->id]));
			$row->add($btn_buy,1);

			$table->add_row($row);
			
			}
        $table->configure('HEADERS',array(_('ID'),_('Name'),_('Price'),_('Buy')));
		$table->configure('HEADERS_WIDTH',[1,5,3,1]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);
		return [_('List sale of cards'),$form->draw()];

        }	
   
    // show page of card that choosed
	/*
	/*
	*/
    protected function view_preview($findcard){
	
    	$form_preview=new control\form('frm_preview');
        $form_preview->configure('PANEL', TRUE);
       
        $img_card = new control\image;
        $img_card->configure('SRC','./plugins/defined/card/images/card.gif');
        $img_card->configure('SIZE',12);
        
        $row = new control\row;
        
        
        $lbl_card_name = new control\label(_('نوع کارت: ') . $findcard->cardname);
        $lbl_card_price = new control\label(_('قیمت: ') . $findcard->price);
        
        $btn_buy = new control\button;
        $btn_buy->configure('HREF',core\general::create_url(['plugin','card','action','prebuy','id', $findcard->id]));
        $btn_buy->configure('LABEL', _('تکمیل فرآیند خرید') );
		$btn_buy->configure('TYPE','success');
        $row->add($btn_buy,4);
        
        $row->add($lbl_card_price,4);
        $row->add($lbl_card_name,4);
        
        
        
        $tbl_info = new control\table;
        $tbl_info->configure('HEADERS',[_('price'),_('name'),_('buy')]);
        $tbl_info->configure('HEADERS_WIDTH',[1,1,2]);
        $tbl_info->configure('ALIGN_CENTER',[FALSE,TRUE,FALSE]);
        $tbl_info->configure('BORDER',true);

        $tbl_info->add_row($row);
         
        $form_preview->add_array(array($img_card,$tbl_info) );
		
        return array(_('مشخصات کارت شما'),$form_preview->draw()); 
    	
    }
   
	/*
	/this is for edit information of card
	/
	*/
	protected function view_Editecard($Edit){
	
	$hid_id = new control\hidden('hid_id');
	$hid_id->configure('VALUE',$Edit->id);
	
	
	$txt_nam = new control\textbox('nam_card');
	$txt_nam->configure('LABEL',_('نام کارت'));
	$txt_nam->configure('VALUE',$Edit->cardname);
	$txt_nam->configure('SIZE',5);
	
	$txt_pric = new control\textbox('pric_card');
	$txt_pric->configure('LABEL',_('قیمت کارت'));
	$txt_pric->configure('VALUE',$Edit->price);
	$txt_pric->configure('SIZE',5);
	
	$bot_chang = new control\button;
	$bot_chang->configure('LABEL',_('ثبت ویرایش'));
	//$bot_chang->configure('VALUE',$Edit->id);
	$bot_chang->configure('TYPE','success');
	$bot_chang->configure('P_ONCLICK_PLUGIN','card');
	$bot_chang->configure('P_ONCLICK_FUNCTION','change_card');
	//$bot_chang->configure('SIZE',11);
	
	$bot_cancl = new control\button;
	$bot_cancl->configure('LABEL',_('انصراف'));
	$bot_cancl->configure('TYPE','default');
	$bot_cancl->configure('HREF',core\general::create_url(['plugin','card','action','showcard']));
	//$bot_cancl->configure('SIZE',1);
	
	$row = new control\row;
	$row->configure('IN_TABLE',false);
	$row->add($bot_chang,11);
	$row->add($bot_cancl,1);
	
	$form = new control\form('edite_show');
	$form->add_array([$txt_nam,$txt_pric,]);
	$form->add($row);
	 $form->add($hid_id);
	
	return array(_('صفحه ویرایش'),$form->draw(),true);
	
	}
	/*
	/this is function for catch 
	/tiny information about users 
	/and than pay money from translat of bank
	*/
	protected function view_prebuy($e){
		$form = new control\form('fainl');
		
		$hid = new control\hidden('hiden');
		$hid->configure('VALUE',$_GET['id']);
		
		$queue = new control\row;
		
		$text_n = new control\textbox;
		$text_n->configure('NAME','name_us');
		$text_n->configure('LABEL',_('نام خریدار'));
		$text_n->configure('ADDON','*');
		$text_n->configure('PLACE_HOLDER',_('نام خریدار وارد شود'));
		$text_n->configure('IN_PUT_TYPE','A');
		$text_n->configure('SIZE',6);
		
		$text_f = new control\textbox;
		$text_f->configure('NAME','name_f_us');
		$text_f->configure('LABEL',_('نام خانوادگی خریدار'));
		$text_f->configure('ADDON','*');
		$text_f->configure('PLACE_HOLDER',_('نام خانوادگی خریدار'));
		$text_f->configure('IN_PUT_TYPE','A');
		$text_f->configure('SIZE',6);
		
		$text_ph = new control\textbox;
		$text_ph->configure('NAME','number_ph_us');
		$text_ph->configure('LABEL',_('تلفن با کد شهر'));
		$text_ph->configure('ADDON','*');
		$text_ph->configure('PLACE_HOLDER',_('تلفن خریدار'));
		$text_ph->configure('IN_PUT_TYPE','N');
		$text_ph->configure('SIZE',4);
		
		$text_e = new control\textbox;
		$text_e->configure('NAME','email');
		$text_e->configure('LABEL',_('ایمیل خریدار'));
		$text_e->configure('PLACE_HOLDER',_('ایمیل خریدار در صورت وجود'));
		$text_e->configure('IN_PUT_TYPE','E');
		$text_e->configure('SIZE',8);
		
		$bot_c = new control\button;
		$bot_c->configure('NAME','bot_cacl');
		$bot_c->configure('LABEL',_('انصراف'));
		$bot_c->configure('HREF',core\general::create_url(['plugin','card','action','showcard']));
		$bot_c->configure('TYPE','danger');
		
		
		$bot = new control\button;
		$bot->configure('NAME','bot_buy');
		$bot->configure('LABEL',_('پرداخت آنلاین'));
		$bot->configure('P_ONCLICK_PLUGIN','card');
		$bot->configure('P_ONCLICK_FUNCTION','pay_mony');
		$bot->configure('TYPE','primary');
		
		
		$queue->add($bot,3);
		$queue->add($bot_c,3);
		
		
		
		$form->add_array(array($text_n,$text_f,$text_ph,$text_e,$queue,$hid));
		
		return array(_('final of card'),$form->draw(),true);
	}
}		
?>	 
