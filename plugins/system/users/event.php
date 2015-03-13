<?php
namespace core\plugin\users;
use \core\cls\browser as browser;
use \core\cls\network as network;
use \core\cls\core as core;
use \core\cls\db as db;
use \core\data as data;

class event extends module{
	
	private $settings;
	
	function __construct(){
		$registry = core\registry::singleton();
		$this->settings = $registry->getPlugin('users');
	}
	
	/*
	 * user login proccess
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function login($e){
		if(trim($e['username']['VALUE']) == '' || trim($e['password']['VALUE'])==''){
			return browser\msg::modalNotComplete($e);
		}
		return $this->onclickLogin($e);
	}
	
	/*
	 * user logout proccess and refresh page
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function logout($e){
		$validator = new network\validator;
		$validator->delete('USERS_LOGIN');
		$e['RV']['URL'] = 'R';
		return $e;
	}
	
	 /*
	  * check username is exists
	  * @param array $e, form properties
	  * @return array, form properties
	  */
	public function checkUsernameExists($e){
		$e['txtUsername']['MSG'] = '';
		$result = $this->checkUsername($e['txtUsername']['VALUE']);
		if($result == 1)
			$e['txtUsername']['MSG'] = _('Username most be more than 3 character!');
		elseif($result == 2)
			$e['txtUsername']['MSG'] = _('Only letters and digit allowed.');
		elseif($result == 3)
			$e['txtUsername']['MSG'] = _('This username is taken by another.try again!');
		return $e;
	}
	 
	 /*
	  * check email is exists
	  * @param array $e, form properties
	  * @return array, form properties
	  */
	 public function checkEmailExists($e){
		 $e['txtEmail']['MSG'] = '';
		 $result = $this->checkEmail($e['txtEmail']['VALUE']);
		 if($result == 1)
			$e['txtEmail']['MSG'] = _("format of entered email in incerrect.");
		 if($result == 2)
			$e['txtEmail']['MSG'] = _("This email is taken by another.if that's you,use that to login to your account.");
		 return $e;
	 }
	 
	/*
	 * register user
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function register($e){
		if($e['txtUsername']['VALUE'] == '' || $e['txtEmail']['VALUE'] == '')
			return browser\msg::modalNotComplete($e);
		//check username and email is valid
		elseif(!is_null($this->checkUsername($e['txtUsername']['VALUE'])) || !is_null($this->checkEmail($e['txtEmail']['VALUE']))){
			$e['RV']['MODAL'] = browser\page::showBlock(_('Message'),_('one or more of fileds invalid.please look at the messages of fileds.'),'MODAL','type-warning');
			return $e;
		}
		//check for that register is enable
		$registry = core\registry::singleton();
		$settings = $registry->getPlugin('users');
		if($settings->register == 0){
			$e['RV']['MODAL'] = browser\page::showBlock(_('Message'),_('Register new user on this site is disabled!'),'MODAL','type-danger');
			return $e;
		}
		//going to save user data
		$orm = db\orm::singleton();
		$validator = new network\validator;
		$user = $orm->dispense('users');
		$user->username = trim($e['txtUsername']['VALUE']);
		$user->email = trim($e['txtEmail']['VALUE']);
		$user->password = md5(core\general::randomString(10,'NC'));
		$user->permission = $this->settings->notActivePermission;
		$user->registerDate = time();
		$user->state = 'A:' . $validator->set('USERS_ACTIVE',false,false);
		$orm->store($user);
		//send email to user
		
		//send successfull message
		$e['RV']['MODAL'] = browser\page::showBlock(_('Successfull'),_('Your account was created and we send email for you. for active your account please check your inbox.'),'MODAL','type-success');
		$e['RV']['JUMP_AFTER_MODAL'] = core\general::createUrl(['users','activeAccount']);
		return $e;
		
	}
	
	/*
	 * active user account
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function activeAcount($e){
		if($e['txtCode']['VALUE'] == '')
			return browser\msg::modalNotComplete($e);
		//going to active user account
		$orm = db\orm::singleton();
		$validator = new network\validator;
		if($validator->checkSid($e['txtCode']['VALUE']) && $orm->count('users','state=?',['A:'. $e['txtCode']['VALUE']]) != 0){
			$user = $orm->findOne('users','state=?',['A:'. $e['txtCode']['VALUE']]);
			$user->permission = $this->settings->defaultPermission;
			$user->state = '';
			$orm->store($user);
			$e['RV']['MODAL'] = browser\page::showBlock(_('Successfull'),_('Your account was activated.now you can login to your account.'),'MODAL','type-success');
			$e['RV']['JUMP_AFTER_MODAL'] = core\general::createUrl(['users','login']);
			//send active account message to user email
			
			return $e;
		}
		//show message
		$e['txtCode']['VALUE'] = '';
		$e['RV']['MODAL'] = browser\page::showBlock(_('Fail!'),_('Your entered key is invalid please try again!'),'MODAL','type-warning');
		return $e;
	}
	
	/*
	 * Delete ip from black list
	 * @param array $e,form properties
	 * @return array $e,form properties
	 */
	public function onclickBtnDeleteIp($e){
		if($this->hasAdminPanel()){
			$orm = db\orm::singleton();
			$orm->exec('DELETE FROM ipblock WHERE id=?',[$e['CLICK']['VALUE']],NON_SELECT);
			return browser\msg::modalsuccessfull($e,'R');
		}
		return browser\msg::modalNoPermission($e);
	}
	/*
	 * Delete ip from black list
	 * @param array $e,form properties
	 * @return array $e,form properties
	 */
	public function onclickBtnAddIp($e){
		if($this->hasAdminPanel()){
			if(data\type::isIp($e['txtIp']['VALUE']) == FALSE){
				$e['txtIp']['VALUE'] = '';
				return browser\msg::modal($e,_('Error!'),_('Entered ip is invalid please try another one.'),'warning');
			}
			$orm = db\orm::singleton();
			$ip = $orm->dispense('ipblock');
            $ip->ip = ip2long(trim($e['txtIp']['VALUE']));
            $orm->store($ip);
            return browser\msg::modalSuccessfull($e,['service','administrator','load','users','ipBlockList']);
		}
		return browser\msg::modalNoPermission($e);
	}
}
