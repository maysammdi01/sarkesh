<?php
namespace core\plugin\users;
use \core\control as control;
use \core\cls\core as core;

trait view {
	
	/*
	 * show login form
	 * @return string, html content
	 */
	protected function frmLogin($position = 'block'){
		$form = new control\form('frmUsersLogin');
		$username = new control\textbox('username');
		$username->INLINE = TRUE;
		$username->ADDON = _('U');
		$username->PLACE_HOLDER = _('Username or e-mail address');
		
		$password = new control\textbox('password');
		$password->INLINE = TRUE;
		$password->label = _('Password:');
		$password->ADDON = 'P';
		$password->PLACE_HOLDER = _('Password');
		$password->PASSWORD = true;
		
		$remember = new control\checkbox('ckbRemember');
		$remember->LABEL = _('Remember me!');
		
		$login = new control\button('btnLogin');
		$login->LABEL = _('Sign in');
		$login->P_ONCLICK_PLUGIN = 'users';
		$login->P_ONCLICK_FUNCTION = 'login';
		$login->TYPE = 'primary';
		
		$forget = new control\button('btnResetPassword');
		$forget->LABEL = _('Reset Password');
		$forget->HREF = core\general::createUrl(['users','resetPassword']);
		$forget->TYPE = 'link';
		
		$r = new control\row;
		$r->add($login,3);
		$r->add($forget,9);
		
		$form = new control\form;
		$form->NAME = 'usersLoginFrm';
		if($position == 'block') $form->NAME = 'usersLoginWidget';
		$form->add_array([$username,$password,$remember,$r]);
		//users can register?
		$registry = core\registry::singleton();
		
		if($registry->get('users','register') == '1'){
			$form->add_spc();
			$lbl = new control\label(_("Don't have account?"));
			$register = new control\button;
			$register->configure('NAME','btn_register');
			$register->configure('LABEL', _('Sign up'));
			$register->configure('HREF',core\general::createUrl(['users','register']));
			$register->configure('TYPE','success');
			$r1 = new control\row;
			$r1->add($lbl,7);
			$r1->add($register,5);
			$form->add($r1);
		}
		
		return [_('Sign in'),$form->draw()];		
	}
	
	/*
	 * show minimal profile in widget mode
	 * @param object $user, user info
	 * @param boolean $adminAccess, can user access to administrator area
	 * @return string, html content
	 */
	protected function viewWidgetProfile($user,$adminAccess){
		$form = new control\form('usersProfileWidget');
		 
		 $label = new control\label(sprintf(_('Hello %s !'),$user->username));
		 $form->add($label);
		 
		 $row = new control\row;
		 $btn_logout = new control\button;
		 $btn_logout->configure('NAME','btn_logout');
		 $btn_logout->configure('LABEL',_('Sign Out!'));
		 $btn_logout->configure('TYPE','info');
		 $btn_logout->configure('P_ONCLICK_PLUGIN','users');
		 $btn_logout->configure('P_ONCLICK_FUNCTION','logout');
		 $row->add($btn_logout,6);
		 
		 if($adminAccess){
			$btn_admin = new control\button;
			$btn_admin->configure('NAME','JUMP_ADMIN');
			$btn_admin->configure('LABEL',_('Admin panel'));
			$btn_admin->configure('HREF',core\general::createUrl(['service','administrator','load','administrator','dashboard']));
			$row->add($btn_admin,6);
		 }
		 
		 $form->add($row);
		 return array(_('User Profile'),$form->draw());
	}
	
	/*
	 * show register form
	 * @return string, html content
	 */
	public function viewFrmRegister(){
		$form = new control\form('frmUsersRegister');
		
		$txtUsername = new control\textbox('txtUsername');
		$txtUsername->label = _('Username:');
		$txtUsername->size = 5;
		$txtUsername->place_Holder = _('Username');
		$txtUsername->help = _('Only letters and digits allowed. special characters and space not allowed.');
		$txtUsername->P_ONBLUR_PLUGIN = 'users';
		$txtUsername->P_ONBLUR_FUNCTION = 'checkUsernameExists';
		$form->add($txtUsername);
		
		$txtEmail = new control\textbox('txtEmail');
		$txtEmail->label = _('Email:');
		$txtEmail->help = _('A valid e-mail address. All e-mails from the system will be sent to this address. The e-mail address is not made public and will only be used if you wish to receive a new password or wish to receive certain news or notifications by e-mail.');
		$txtEmail->size = 6;
		$txtEmail->P_ONBLUR_PLUGIN = 'users';
		$txtEmail->P_ONBLUR_FUNCTION = 'checkEmailExists';
		$txtEmail->place_Holder = _('Email');
		$form->add($txtEmail);
		
		
		$btnSignup = new control\button('btn_signup');
		$btnSignup->type = 'primary';
		$btnSignup->label = _('Create new account');
		$btnSignup->P_ONCLICK_PLUGIN = 'users';
		$btnSignup->P_ONCLICK_FUNCTION = 'register';
		 
		$btnCancel = new control\button;
		$btnCancel->configure('NAME','btn_cancel');
		$btnCancel->configure('TYPE','warning');
		$btnCancel->configure('LABEL',_('Cancel'));
		$btnCancel->configure('HREF',SiteDomain);
		 
		$row = new control\row();
		$row->add($btnSignup,3);
		$row->add($btnCancel,2);
		$form->add($row);
		
		return [_('Register'),$form->draw()];
	}
	
	/*
	 * show active form
	 * @param string $activator,activator code
	 * @return string, html content
	 */
	protected function viewActiveAccount($msg = ''){
		$form = new control\form('usersActiveAcount');
		
		$txtCode = new control\textbox('txtCode');
		$txtCode->size = 4;
		$txtCode->label = _('Activator code:');
		$txtCode->help = _('Enter activator code that you get from your email');
		$form->add($txtCode);
		
		$btnSubmit = new control\button('btnSubmit');
		$btnSubmit->label = _('Active account');
		$btnSubmit->type = 'primary';
		$btnSubmit->P_ONCLICK_PLUGIN = 'users';
		$btnSubmit->P_ONCLICK_FUNCTION = 'activeAcount';
		
		$form->add($btnSubmit);
		
		return [_('Active account'),$form->draw()];
	}
	
	/*
	 * show active form
	 * @param string $activator,activator code
	 * @return string, html content
	 */
	protected function viewResetPassword(){
		$form = new control\form('UsersResetPassword');
		
		$txtCode = new control\textbox('txtEmail');
		$txtCode->size = 5;
		$txtCode->label = _('Email:');
		$txtCode->help = _('Enter your email address that you register with that.');
		$form->add($txtCode);
		
		$btnSubmit = new control\button('btnSubmit');
		$btnSubmit->label = _('Send request');
		$btnSubmit->type = 'primary';
		$btnSubmit->P_ONCLICK_PLUGIN = 'users';
		$btnSubmit->P_ONCLICK_FUNCTION = 'resetPassword';
		
		$form->add($btnSubmit);
		
		return [_('Reset password'),$form->draw()];
	}
	
	protected function viewFailActiveAccount(){
		return [_('Error!','Your enterd activator code is invalid or be expaird.')];
	}
}
