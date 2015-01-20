<?php
namespace addon\plugin\card;
use \core\control as control;

class view{
// نمایش  اطلاعات درج کارت
	protected function view_card(){
		$form=new control\form();
		
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
		$button->configure('SIZE',9);
		$button->configure('LABEL',_('ثبت'));
		$button->configure('TYPE','primary');
		$button->configure('P_ONCLICK_PLUGIN','card');
		$button->configure('P_ONCLICK_FUNCTION','insert');

		$form->add_array(array($user,$price,$button));

		return array(_('ثبت کارت'),$form->draw());
	}
	//توابع مربوط به نمایش کارت
	protected function view_showcard($cards){
        //$f=متغیر واکشی اطلاعات جدول اینسرت
        //ساخت جدول
        
        $l3=new control\LABEL;
        $l4=new control\LABEL;
        $l3->configure('LABEL',_('نام کارت'));
        $l4->configure('LABEL',_('قیمت کارت'));
        
        $row2=new control\row;
        $row2->add($l4,2);
        $row2->add($l3,2);
        
        $tb = new control\table;
        //Draw table
        foreach($cards as $ecard){
            $row=new control\row;
            $l=new control\LABEL;	
            $l2=new control\LABEL;	
            //چسباندن لیبل برای هر سطر
            $l->configure('LABEL',$ecard['cardname']);
            $l2->configure('LABEL',$ecard['price']);
			
            $btn_buy=new control\button;
            $btn_buy->configure('LABEL','خرید');
            $btn_buy->configure('HREF',SiteDomain .'?plugin=card&action=preview&id='.$ecard['id']);
			$btn_buy->configure('TYPE','success');
		
				
			$btn_Del = new control\button;
			$btn_Del->configure('LABEL',_('غیرفعال'));
			$btn_Del->configure('NAME','Unac');
			$btn_Del->configure('P_ONCLICK_PLUGIN','card');
			$btn_Del->configure('P_ONCLICK_FUNCTION','Unac_ty_card');
			$btn_Del->configure('TYPE','danger');
			$btn_Del->configure('VALUE',$ecard->id);
			
            $row->add($l,2);
            $row->add($l2,2);
            $row->add($btn_buy,2);
			$row->add($btn_Del,2);
            $tb->add_row($row);
        }
         $form=new control\form('frm_show_cards');
         $form->add_array(array($row2,$tb));
		
         return array(_('انتخاب کارت'),$form->draw(),true);
        	
    }
    // نمایش جزئیات هر صفحه
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
        $btn_buy->configure('HREF','?plugin=card&action=prebuy&id='. $findcard->id);
        $btn_buy->configure('LABEL', _('تکمیل فرآیند خرید') );
		$btn_buy->configure('TYPE','success');
        $row->add($btn_buy,4);
        
        $row->add($lbl_card_price,4);
        $row->add($lbl_card_name,4);
        
        
        
        $tbl_info = new control\table;
        $tbl_info->configure('BORDER', true);
        $tbl_info->add_row($row);
         
        $form_preview->add_array(array($img_card,$tbl_info) );
		
        return array(_('مشخصات کارت شما'),$form_preview->draw());
    		   
    	
    }
    
    
    /*
     * This action is for manage cards disable and edite cards
     *
    */
    protected function view_manage($cards){
        
        //$cards is cards table that loaded from database;
        $tbl_cards = new control\table;
        
        foreach($cards as $key=>$card){
			$row_card = new control\row;
			$lbl_id = new control\label($key);
			$row_card->add($lbl_id,2);
			
			$lbl_name = new control\label($card->cardname);
			$row_card->add($lbl_name,5);
			
			//check for that plugin is active or not
			if($card->state == '1'){
				//going to show buttons
				//first show disable button
				
				$btn_disable = new control\button;
				$btn_disable->configure('NAME','btn_delete');
				$btn_disable->configure('LABEL',_('Disable'));
				
				$row_card->add($btn_disable,2);
				
				//show edit button
				$btn_edite = new control\button;
				
			}
			else{
			
			
			}
			$tbl_cards->add_row($row_card);
           
        }
		echo $tbl_cards->draw();
    }
	protected function view_prebuy($e){
		$form = new control\form('fainl');
		//$tbl = new control\table;
		
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
		$bot_c->configure('HREF','?plugin=card&action=showcard');
		$bot_c->configure('TYPE','danger');
		$bot_c->configure('SIZE',8);
		
		$bot = new control\button;
		$bot->configure('NAME','bot_buy');
		$bot->configure('LABEL',_('پرداخت آنلاین'));
		$bot->configure('P_ONCLICK_PLUGIN','card');
		$bot->configure('P_ONCLICK_FUNCTION','pay_mony');
		$bot->configure('TYPE','primary');
		$bot->configure('SIZE',5);
		
		$queue->add($bot,2);
		$queue->add($bot_c,2);
		
		//$tbl->add_row($queue);
		
		
		$form->add_array(array($text_n,$text_f,$text_ph,$text_e,$queue,$hid));
		
		return array(_('final of card'),$form->draw(),true);
	}
}		
?>	 
