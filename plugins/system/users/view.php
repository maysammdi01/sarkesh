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
	
}
