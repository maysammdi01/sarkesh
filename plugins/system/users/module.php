<?php
namespace core\plugin\users;
use \core\cls\core as core;
use \core\cls\db as db;
use \core\cls\network as network;
use \core\cls\browser as browser;
use \core\plugin as plugin;

class module extends view{
	private $registry;
	private $settings;
	private $validator;
	function __construct(){
		
		$this->registry = new core\registry;
		$this->settings = $this->registry->get_plugin('users');
		$this->validator = new network\validator;
		
		parent::__construct($this->settings);

	}
	/*
	 * this function show login form for input username and password
	 * if user was logedin before return user profile
	 * form
	 */
	protected function module_login_block($pos){

		//checking for that is logedin before
		if($this->module_is_logedin()){
			//show user profile
			//get user information
			$user=$this->module_get_info();
			return $this->view_profile_block($user, $this->module_has_permission('core_admin_panel'));
		}
		else{
			//show login form in block mode
			return $this->view_login_block($pos);
		}
		
	}
	
	/*
	 * OUTPUT:HTML ELEMENTS
	 * Tis function show register form 
	 */
	 protected function module_register(){
		 if($this->module_is_logedin()){
			 header("Location:" . core\general::create_url(array('plugin','users','action','profile')));
		 }
		 elseif($this->settings['register'] == '0'){
			 //new register was closed
			 $msg = new msg;
			 return $msg->msg('Warrning!','Register new user was closed by Administrator.','danger');
			 
		 }
		 return $this->view_register();		 
	 }
	//this function check user login data and do login proccess
	protected function module_btn_login_onclick($e){
		
		$count = db\orm::count('users',"username = ? or email=? and password = ?", array($e['txt_username']['VALUE'],$e['txt_username']['VALUE'],md5($e['txt_password']['VALUE'])));
		if($count != 0){
			//login data is cerrect
			//set validator
			if($e['ckb_remember']['CHECKED'] == 1){
						
				$valid_id = $this->validator->set('USERS_LOGIN',true,true);
							
			}
			else{
				$valid_id = $this->validator->set('USERS_LOGIN',false,true);
							
			}
			//INSERT VALID ID IN USER ROW
			$user = db\orm::load('users',$this->get_user_id($e['txt_username']['VALUE']));
			$user->login_key = $valid_id;
			db\orm::store($user);
			
			//now jump or relod page
			if(isset($_GET['jump'])){
				$e['RV']['URL'] = $_GET['jump'];
			}
			else{
				//refresh page
				$e['RV']['URL'] = 'R';
			}
		}
		else{
			//username or password is incerrect
			$e['txt_username']['VALUE'] = '';
			$e['txt_password']['VALUE'] = '';
			$e['RV']['MODAL'] = browser\page::show_block(_('Message'), _('Username or Password is incerrect!'), 'MODAL','type-warning');
		}
		return $e;
	}
	
	/*
	 * this function check user loged in before or not
	 * boolean 
	 */
	 protected function module_is_logedin(){
		 if($this->validator->is_set('USERS_LOGIN')){
				$id = $this->validator->get_id('USERS_LOGIN');
				if(db\orm::count('users','login_key = ?',array($id)) != 0){
					//user is loged in before
					return true;
				}
		 }
		 //user not loged in before
		 return false;
	 }
	 
	 /*
	  * INPUT:STRING > username | NULL > for get logedin user id
	  * this function return user id
	  * it's AI primary key
	  * OUTPUT > integer | FALSE > not found
	  */
	  protected function get_user_id($username){
		  $res = db\orm::findOne('users',"username = ?",array($username));
		  return $res->id;
	  }
	  
	 
	  /*
	  * INPUT: ELEMENTS | NULL
	  * this function do logout proccess
	  * OUTPUT: boolean | ELEMENTS
	  */
	  protected function module_logout($e = ''){
		  $this->validator->delete('USERS_LOGIN');
		  //if this action requested by content mode jump user to home page
		  if($e == 'content'){
				core\router::jump_page(SiteDomain);
				
		  }
		  else{
			//jump page with events controller 
			$e['RV']['URL'] = SiteDomain;
		  }
		  
		  return $e;
	  }
	  
	  /*
	   * * INPUT:STRING >permation
	   * INPUT: STRING >USERNAME | NULL >for get cerrent user
	   * this function check permission of user
	   * OUTPUT:boolean
	   */
	   protected function module_has_permission($permission,$username=''){
			 if($username == ''){
				//get cerrent user info
				$user = $this->module_get_info();
				if($user == null){
					//user is guest
					//4 = guest primary key
					$id = 4;
				}
				else{
					//get user permission id
					$id = $user['permission'];
				}
				$per = db\orm::findOne('permissions',"id = ?", array($id));
				if($per[$permission] == '1'){return true;}
				return false;
			}
			else{
				//get permission with username
				//check for that user exists
				if(db\orm::count('users',"username = ?",array($username)) != 0){
					//going to find permission
					$res = db\orm::getRow('SELECT * FROM users s INNER JOIN permissions p ON s.permission=p.id where s.username=?',array($username));
					//checking for that permission is exist
					if(array_key_exists($permission,$res)){
					if($res[$permission] == '1'){
						return true;
					}
				}
			}
			//user not found return false
			return false;
			}
	   }
	   
	   /*
	    * This function return reset password form
	    * OUTPUT:elements
	    */
	    protected function module_reset_password(){
			return $this->view_reset_password();
		}
		
		/*
	    * INPUT:ELEMENTS
	    * This function run with botton that's in reset password form
	    * OUTPUT:ELEMENTS
	    */
	    protected function module_btn_reset_password_onclick($e){
			
			$e['RV']['MODAL'] = browser\page::show_block(1,1,'MODAL','type-warning');
			return $e;
		}
		
		//this function return back user information
		protected function module_get_info($username = ''){
		
			//first check for that what type of user info you want
			if($username == ''){
				//you want user information that now in loged in
				if($this->is_logedin()){
					$id = $this->validator->get_id('USERS_LOGIN');
					if(db\orm::count('users','login_key = ?',array($id)) != 0){
						return db\orm::findOne('users','login_key = ?', array($id));
					}
				}
				else{
					//user is guest
					return null;
				}
			}
			else{
				//check for username and return back information if exists
				if(db\orm::count('users','username = ?',array($username)) != 0){
						return db\orm::findOne('users','login_key = ?', array($username));
				}
				else{
					//username not found
					return 0;
				}
			}
		}
		
		//this function is for do user registeration
		//INPUT:ELEMENTS
		public function module_btn_signup_onclick($e){
			//this variable is for store errors
			$error = array(null);
			//first checking for input informations
			//check for that username is exist before
			if(strlen($e['txt_username']['VALUE']) > 4){
				if(max(array_keys(explode(' ',$e['txt_username']['VALUE'])	)	)	!= 0){
					array_push($error,browser\page::show_block(_('Message'),_('Format of username is not cerrect.'), 'NONE'));
				}
				else{
					//check username for that exists or not
					if($this->module_is_exists($e['txt_username']['VALUE'],'USERNAME')){
						array_push($error,browser\page::show_block(_('Message'),_('The username you entered is already taken by another user.'), 'NONE'));
					}
					//check email for that exists or not
					if($this->module_is_exists($e['txt_email']['VALUE'],'EMAIL')){
						array_push($error,browser\page::show_block(_('Message'),_('Email is already taken by another user.'), 'NONE'));
					}
				}	
			}
			else{
				array_push($error,browser\page::show_block(_('Message'),_('Your usernameis too short.'), 'NONE'));
			}
			
			//Check passwords length and match.
			if(strlen($e['txt_password']['VALUE']) > 5){
				if($e['txt_password']['VALUE'] != $e['txt_repassword']['VALUE']){
					//input passwords are not match
					array_push($error,browser\page::show_block(_('Message'),_('The new passwords you entered do not match.'), 'NONE'));
				}
			}
			else{
				array_push($error,browser\page::show_block(_('Message'),_('Your password is to short.'), 'NONE'));
			} 

			
			//check for that entered email is cerrect.
			//for more info about filter_var visit http://php.net/manual/en/function.filter-var.php
			if(!filter_var($e['txt_email']['VALUE'] , FILTER_VALIDATE_EMAIL)){
				array_push($error,browser\page::show_block(_('Message'),_('Your email is not cerrect.'), 'NONE'));
			}
			
			//check for that error is exist
			if(max(array_keys($error)) != 0){
				$msg_modal = '';
				foreach($error as $msg){
					$msg_modal = $msg_modal . '   ' . $msg;
				}
				$e['RV']['MODAL'] = browser\page::show_block(_('Message'),$msg_modal , 'MODAL','type-warning');
				
			}
			else{
					//going to register user.
					$user = db\orm::dispense('users');
					$user->username = $e['txt_username']['VALUE'];
					$user->password = md5($e['txt_password']['VALUE']);
					$user->email = $e['txt_email']['VALUE'];
					//set default permation
					$user->permation = $this->settings['default_permission'];
					
					/*
					 * check for that administrator want to validate users with email or not
					 * all states is E=>enabled B=>baned D=>Disabled NA=>not activated
					 */
					 
					 //perpare email class for send email
					$mail = new network\mail;
					
					if($this->settings['active_from_email'] == 1){
						//active with email
						$user->state = 'NA';
						
						//send active code to email
						//create random code
						$user->code = core\general::random_string(10,'NC');
						
						$email_title = _('Active account');
						$email_body = sprintf(_('Your activation code is:%s'),$user->code) . _('first go to link that comes below and enter your activation code.') . '</br>' . core\general::create_url(SiteDomain . '/?plugin=users&action=ActiveAccount',false);
						
						//save in database
						$UserId = db\orm::store($user);
						
						if(!$mail->simple_send($e['txt_username']['VALUE'],$e['txt_email']['VALUE'],$email_title,$email_body)){
							//user activated
							$e['RV']['URL'] = core\general::create_url(array('plugin','users','action','show_msg','id','register_active'),true);
						}
						else{
							//error in sending email
							$e['RV']['MODAL'] = browser\page::show_block('Error','Error in sending activation code to your email.please tell administrator!','MODAL','type-danger');
							
							//remove user from database
							$usr = db\orm::load('users',$UserId);
							db\orm::trash($usr);
						}
						
						
					}
					else{
						//user active now
						$user->state = 'E';
						
						//save user in database
						db\orm::store($user);
						
						//send register information to email
						$email_title = _('Registration complete');
						$email_body = sprintf(_('Your registration in %s was completed.'),'sarkesh.org');
						if(!$mail->simple_send($e['txt_username']['VALUE'],$e['txt_email']['VALUE'],$email_title,$email_body)){
							//user activated
							//show register successful message for registeration in sarkesh
							$header = _('Active account');
							$body = _('We sent an email with an activation code to activate your account, check your email for more information.');
							$type = 'info';
							$e['RV']['MODAL'] = browser\page::show_block($header,$body,'MODAL','type-success');
							$e['RV']['JUMP_AFTER_MODAL'] = core\general::create_url(array('plugin','users','action','ActiveAcount'),true);
						}
						else{
							//error in sending email
							$e['RV']['MODAL'] = browser\page::show_block('Warrning','Error in sending your information to your email but your account was activated. you can now log in with your information','MODAL','type-warrning');
						}
						
					}
					
					
			}
			return $e;
		}
		
		//this function check for that username is exist before or not
		public function module_is_exists($parameter , $type){
			if($type == 'username' || $type == 'USERNAME'){
				$UserNum = db\orm::count('users','username=?',array($username));
				if($UserNum != 0){	return true;}
				return false;
			}
			elseif($type == 'email' || $type == 'EMAIL'){
				$EmailNUM = db\orm::count('users','email=?',array($username));
				if($EmailNUM != 0){	return true;}
				return false;
			}
			
		}
		
		/*
		 * This function is for show active acount page to user
		 */
		protected function module_ActiveAcount(){
			return $this->view_ActiveAcount();
		}
		
		//this function is for check activation code and active user account
		protected function module_btn_active_account($e){
			//check for that code is exist
			if( db\orm::count('users','code=?',[$e['txt_code']['VALUE']]	)	!= 0){
					
					//get user
					$user = db\orm::findOne('users','code=?',[$e['txt_code']['VALUE']]);
					// E => Enabled
					$user->state = 'E';
					db\orm::store($user);
					$e['RV']['MODAL'] = browser\page::show_block('Registration completed','your account activated successfully. Now click on OK to jump to home page.','MODAL','success');
			}
			else{
				//active code not found
				$e['RV']['MODAL'] = browser\page::show_block('Error','Activation Code Invalid','MODAL','danger');
			}
			return $e;
		}
		
		//this function show meesage
		protected function module_show_msg($id){
			
			//create object from msg plugin
			$msg = new plugin\msg;
			
			$header = '';
			$body = '';
			$type='';
			
			if($id == 'register_successful'){
				//show register successful message for registeration in sarkesh
				$header = _('Registration completed!');
				$body = _('Your registration was successful.now you can login to system with your login information that you entered');
				$type = 'success';
			}
			elseif($id == 'register_active'){
				//show register successful message for registeration in sarkesh
				$header = _('Active account');
				$body = _('We sent an email with an activation code to activate your account, check your email for more information.');
				$type = 'info';
			}
			
			//return back message
			return $msg->msg($header,$body,$type);
		}
}
?>
