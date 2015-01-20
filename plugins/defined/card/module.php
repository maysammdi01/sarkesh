<?php

namespace addon\plugin\card;

use \core\cls\core as core;
use \core\cls\db as db;
use \core\cls\browser as browser;
use \core\plugin as plugin;

class module extends view{
	
	 protected function module_card(){
		return $this->view_card();
	}
    
    
	protected function module_insert($e){
    	//create a table
    	$card=db\orm::dispense('card');
    	$card->cardname=$e['usercard']['VALUE'];
    	$card->price=(int)$e['numbercard']['VALUE'];
    	//کنترل خالی نبودن تکس باکس ها
       if(trim($e['usercard']['VALUE']) == '' || trim($e['numbercard']['VALUE'])==''){
    			$e['RV']['MODAL'] = browser\page::show_block(_('پیام'),_('لطفا کادرهای خالی را پر کنید'),'MODAL','type-warning');
    			return $e;
    		}
    		//برسی میکنیم رشته وارد شده عدد است یا نه
			
			
		
    		if(!(int)$e['numbercard']['VALUE']){
    		
        		$e['RV']['MODAL'] = browser\page::show_block('پیام','لطفا فقط عدد وارد کنید','MODAL','type-warning'); 				
        		$e ['RV']['JUMP_AFTER_MODAL']='R';
				return $e;
				
    
    		}
    	       // شمارش تعداد سطر تکراری
    	
    		$db=db\orm::count('card',"cardname=?",array($e['usercard']['VALUE'])); 
            if($db==0){
                // اگه همچنین نام کارتی وجود نداشت ذخیرش کن در جدول اینسرت
                db\orm::store($card);
                //نمایش پیغام ثبت
    			$e['RV']['MODAL'] = browser\page::show_block('ثبت','عملیات درج کارت با موفقیت انجام شد','MODAL','type-success');
    			//clear textboxes
    			$e['usercard']['VALUE']="";
    			$e['numbercard']['VALUE']="";   
    			return $e;
    		}
    		
    		else {
    			//card is exist before
    			//show message in modal 
    			$e['RV']['MODAL'] = browser\page::show_block('خطا','نام کارت قبلا ثبت شده است','MODAL','type-danger');
    			return $e;
    		}
    	  
	}
	//عملیات مربوط به نمایش کارت
	// تابع واکشی اطلاعات از جدول اینسرت
	protected function  module_showcard(){
    	// واکشی اطلاعات از جدول اینسرت بر اساس نزولی
        $cards=db\orm::find('card','state=1');
        return $this->view_showcard($cards);

    }
	
	protected function module_Unac_ty_card($e){
		if(isset($e['Unac']['VALUE'])){
			
				$Del = db\orm::findOne('card','id=?',[$e['Unac']['VALUE']]);
				$Del->state='0';
				db\orm::store($Del);
				$e['RV']['MODAL']=browser\page::show_block(_('پیام'),_('با موفقیت غیر فعال شد'),'MODAL','type-success');
				$e['RV']['JUMP_AFTER_MODAL']='R';
				return $e;
			 }
				core\router::jump_page(404);
				return['',''];
		}
		
	
    //تابع نمایش هر کارت
    protected function  module_preview(){
        // از این تابع برای گرفتن آی دی هر صفحه استفاده میکنیم
        //first check for that id is set
        if(isset($_GET['id'])){
            
            //check for that id is exists
            if(db\orm::count("card","id = ?" , array( $_GET['id'])) !=0){
                
                //card found. going to show details
                $findcard=db\orm::findOne("card","id = ?" , array( $_GET['id']));
				
                return $this->view_preview($findcard);	
            }
            else{
                //card not found
                //going to show page not found(404) message
				$msg = new plugin\msg;
				return $msg->msg404();
			
            }  
            
        }
        else{
            
            //show not found message
		$msg = new plugin\msg;
		return $msg->msg404();
			//return call_user_func(array($plugin,'msg_404'));
            
        }
       
    }
    
    /*
     * This action is for manage cards disable and edite cards
     *
    */
    protected function module_manage(){
        
        //get all cards from database
        $cards = db\orm::findAll('card');
        return $this->view_manage($cards);
    }
	protected function module_prebuy($e){
		if(isset($_GET['id'])){
			
			if(db\orm::count('card','id=?',[$_GET['id']]) !=0){
				$num = db\orm::findone('card','id=?',[$_GET['id']]);
				return $this->view_prebuy($num);
				}
			else{
				core\router::jump_page(404);
			return ['',''];
			}
		}
		else{
			core\router::jump_page(404);
			return ['',''];
		}
	}
	protected function module_pay_mony($e){
		if(isset($e['hiden']['VALUE'])){
		
		$buuy= db\orm::dispense('buy');
		$buuy->id_card = $e['hiden']['VALUE'];
		$buuy->name_buy =$e['name_us']['VALUE'];
		$buuy->name_f_buy = $e['name_f_us']['VALUE'];
		$buuy->buy_ph = $e['number_ph_us']['VALUE'];
		$buuy->buy_E =$e['email']['VALUE'];
		
		db\orm::store($buuy);
		$e['RV']['MODAL']=browser\page::show_block(_('پیام'),_('درج با موفقیت انجام شد'),'MODAL','type-success');
		$e['RV']['JUMP_AFTER_MODAL']='R';
		return $e;
		}
		core\router::jump_page(404);
		return['',''];
	}
	protected function module_is_exist_card($type,$password){
		
		if(db\orm::count('buy','id_card=? and pass_card=?',[$type,$password]) !=0){
		 return true;
		}
		return false;
	}
}
?>
