<?php
namespace core\plugin;
use \core\plugin\msg as msg;
use \core\cls\browser as browser;
//this plugins show system messages on page .
//messages like 404 not found and access denied msg
class msg extends msg\module{
	//create view and module for working with MVC metode
	private $view;
	private $module;
	
	function __construct(){
		parent::__construct();
	}
	//this function for show content in page
	//if you want to wotk with cls_page->show_block you should send $view to that.
	public function msg_404(){
		//going to show message
		return $this->module_404();
	      
	  
	}
	
	/*
	 * INPUT:STRING:HEADER
	 * INPUT:STRING:BODY OF MESSAGE
	 * INPUT:STRING:TYPE(success,danger,warning,info)
	 * This function show custom message
	 * OUTPUT:ELEMENTS
	 */
	 public function msg($header, $body, $type = 'success'){
		 return $this->module_msg($header,$body,$type);
	 }
	 
	 /*
	  * This function use for show 404 message in service mode
	  */
	  public function msg404($type = 'ELSE'){
		  $msg = $this->msg_404();
		  if($type == 'BLOCK'){
			  return $msg[1];
		  }
		  else{
			return $msg;
		  }
	  } 
	  
	  // this function return access denied message
	   public function access_denied($type='ELSE'){
		   $msg = $this->msg(_('Access Denied!'),_('You have no permission to access this page.'),'danger');
		   if($type == 'BLOCK'){
			   return $msg[1];
			   
		   }
		   else{
			   return $msg;
		   }
		   
	   }

	   public function successfull_modal($e,$page=''){
		   	$e['RV']['MODAL'] = browser\page::show_block(_('Completed'),_('Your request successfully completed.'),'MODAL','type-success');
		   	if($page == ''){
				$e['RV']['JUMP_AFTER_MODAL'] = 'R';
			}
			else{
				$e['RV']['JUMP_AFTER_MODAL'] = urlencode(\core\cls\core\general::create_url($page));
			}
			return $e;
	   }

	   //this function is use for show message for complete blank places
	   public function not_complete_modal($e){
		   	$e['RV']['MODAL'] = browser\page::show_block(_('Message'),_('Please fill all necessary places'),'MODAL','type-warning');
			return $e;
	   }

	   //this function return message with this mean some error was ecured
	   public function error($modal = false,$e=''){
	   		if($modal){
	   			//show in modal mode
	   			$e['RV']['MODAL'] = browser\page::show_block(_('Message'),_('Please fill all necessary places'),'MODAL','type-warning');
				return $e;
	   		}
	   		return $this->msg(_('Error!'),_('Some error was happen!'),'danger');
	   }

	

}

?>
