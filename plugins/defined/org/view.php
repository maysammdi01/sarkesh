<?php

namespace addon\plugin\org;
use \core\control as control;
class view{

	protected function view_show_serch_card(){
	
		$form = new control\form('first_form');
		
		$txt_type = new control\textbox('securi');
		$txt_type->configure('LABEL','security user');
		$txt_type->configure('ADDON','*');
		$txt_type->configure('IN_PUT_TYPE','N');
		$txt_type->configure('SIZE',5);
		
		$txt_num = new control\textbox('random');
		$txt_num->configure('LABEL','password');
		$txt_num->configure('ADDON','*');
		$txt_num->configure('IN_PUT_TYPE','AN');
		$txt_num->configure('SIZE',5);
		
		$form->add_array([$txt_type,$txt_num]);
		
		$btn_update = new control\button('btn_ok');
		$btn_update->configure('LABEL',_('Login'));
		$btn_update->configure('P_ONCLICK_PLUGIN','org');
		$btn_update->configure('P_ONCLICK_FUNCTION','agree_card');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',SiteDomain);
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,3);
		$row->add($btn_cancel,3);
		$form->add($row); 
		
		return array('first step',$form->draw(),true);
		
	}
	protected function view_agree_card(){
		//wwwwwwwww
		$lab_a = new control\label;
		$lab_a->configure('NAME','lab_1');
		$lab_a->configure('LABEL',_('مشخصات کاربر'));
		$lab_a->configure('TYPE','success');
		
		$row_a = new control\row;
		$row_a->add($lab_a,2);
		//wwwwwwwww
		$txt_nam = new control\textbox;
		$txt_nam->configure('NAME','name_user');
		$txt_nam->configure('LABEL',_('نام'));
		$txt_nam->configure('PLACE_HOLDER',_('نام کاربر'));
		$txt_nam->configure('ADDON','*');
		$txt_nam->configure('IN_PUT_TYPE','A');
		$txt_nam->configure('SIZE',5);
		
		$txt_f_nam = new control\textbox;
		$txt_f_nam->configure('NAME','nam_f_user');
		$txt_f_nam->configure('LABEL',_('نام خانوادگی'));
		$txt_f_nam->configure('PLACE_HOLDER',_('نام خانوادگی کاربر'));
		$txt_f_nam->configure('ADDON','*');
		$txt_f_nam->configure('IN_PUT_TYPE','A');
		$txt_f_nam->configure('SIZE',5);
		
		$txt_nu_p = new control\textbox;
		$txt_nu_p->configure('NAME','num_prsonal');
		$txt_nu_p->configure('LABEL',_('شماره ملی'));
		$txt_nu_p->configure('PLACE_HOLDER',_('شماره ملی کاربر'));
		$txt_nu_p->configure('ADDON','*');
		$txt_nu_p->configure('IN_PUT_TYPE','N');
		$txt_nu_p->configure('SIZE',5);
		
		$txt_nu_sh = new control\textbox;
		$txt_nu_sh->configure('NAME','num_prs_sh');
		$txt_nu_sh->configure('LABEL',_('شماره شناسنامه'));
		$txt_nu_sh->configure('PLACE_HOLDER',_('شماره شناسنامه کاربر'));
		$txt_nu_sh->configure('ADDON','*');
		$txt_nu_sh->configure('IN_PUT_TYPE','N');
		$txt_nu_sh->configure('SIZE',4);
		//wwwwwwwww
		$lab_b = new control\label;
		$lab_b->configure('NAME','lab_2');
		$lab_b->configure('LABEL',_('مشخصات ماشین'));
		$lab_b->configure('TYPE','success');
		
		$row_b = new control\row;
		$row_b->add($lab_b,2);
		//wwwwwww
		$txt_nu_sh_ca = new control\textbox;
		$txt_nu_sh_ca->configure('NAME','nu_shasi');
		$txt_nu_sh_ca->configure('LABEL',_('شماره شاسی'));
		$txt_nu_sh_ca->configure('PLACE_HOLDER',_('شماره شاسی ماشین'));
		$txt_nu_sh_ca->configure('ADDON','*');
		$txt_nu_sh_ca->configure('IN_PUT_TYPE','AN');
		$txt_nu_sh_ca->configure('SIZE',5);
	
		$txt_nu_bo_ca = new control\textbox;
		$txt_nu_bo_ca->configure('NAME','nu_badanh');
		$txt_nu_bo_ca->configure('LABEL',_('شماره بدنه'));
		$txt_nu_bo_ca->configure('PLACE_HOLDER',_('شماره بدنه ماشین را وارد کنید'));
		$txt_nu_bo_ca->configure('ADDON','*');
		$txt_nu_bo_ca->configure('IN_PUT_TYPE','AN');
		$txt_nu_bo_ca->configure('SIZE',5);
		
		$uplo_sa_ca = new control\uploader;
		$uplo_sa_ca->configure('NAME','upload_sa');
		$uplo_sa_ca->configure('LABEL',_('اسکن سند'));
		$uplo_sa_ca->configure('SIZE',10);
		
		$uplo_gh_ca = new control\uploader;
		$uplo_gh_ca->configure('NAME','upload_gh');
		$uplo_gh_ca->configure('HELP',_(''));
		$uplo_gh_ca->configure('LABEL',_('اسکن قولنامه'));
		$uplo_gh_ca->configure('SIZE',9);
		
		$uplo_ac_us = new control\uploader;
		$uplo_ac_us->configure('NAME','upload_ac');
		$uplo_ac_us->configure('LABEL',_('اسکن عکس کاربر'));
		$uplo_ac_us->configure('SIZE',8);
		
		/*$radio = new control\radiobutton;
		$radio->configure('NAME','radio_kh');
		$radio->configure('LABEL',_('بله'));
		$radio->configure('SIZE',12);*/
		
		$but_ok = new control\button;
		$but_ok->configure('NAME','bt_ok');
		$but_ok->configure('LABEL',_('ثبت'));
		$but_ok->configure('HREF','');
		$but_ok->configure('TYPE','success');
		$but_ok->configure('SIZE',5);
		
		$but_can= new control\button;
		$but_can->configure('NAME','bt_can');
		$but_can->configure('LABEL',_('انصراف'));
		$but_can->configure('HREF','');
		$but_can->configure('TYPE','warning');
		$but_can->configure('SIZE',5);
		
		$row_c =new control\row;
		$row_c->add($but_ok,2);
		$row_c->add($but_can,2);
		
		
		$tabl_a = new control\table;
		$tabl_a->add_row($row_a);
		
		$tabl_b = new control\table;
		$tabl_b->add_row($row_b);
		
		$tabl_c = new control\table;
		$tabl_c->add_row($row_c);
		
		$form = new control\form;
		$form->configure('NAME','iformaition');
		$form->add_array(array($tabl_a,$txt_nam,$txt_f_nam,$txt_nu_p,$txt_nu_sh,$tabl_b,$txt_nu_sh_ca,$txt_nu_bo_ca,$uplo_sa_ca,$uplo_gh_ca,$uplo_ac_us,$tabl_c));
		
		return array(_('مشخصات کامل'),$form->draw(),true);
	}


}

?>
