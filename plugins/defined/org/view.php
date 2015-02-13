<?php

namespace addon\plugin\org;
use \core\control as control;
use \core\cls\core as core;
class view{

	protected function view_show_search_card(){
	
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
		
		$row->add($btn_update,3);
		$row->add($btn_cancel,3);
		$form->add($row); 
		
		return array('first step',$form->draw(),true);
		
	}
	protected function view_info_car(){
	
	   	$hid = new control\hidden('hidd');
		$hid->configure('VALUE',$_REQUEST['id']);
		
		//wwwwwwwww
		$lab_a = new control\label('lab_1');
		$lab_a->configure('LABEL',_('مشخصات کاربر'));
		$lab_a->configure('TYPE','success');
		
		$row_a = new control\row;
		$row_a->configure('IN_TABLE',false);
		$row_a->add($lab_a,7);
		
		//wwwwwwwww
		$txt_nam = new control\textbox('name_user');
		$txt_nam->configure('LABEL',_('نام'));
		$txt_nam->configure('PLACE_HOLDER',_('نام مالک'));
		$txt_nam->configure('ADDON','*');
		$txt_nam->configure('IN_PUT_TYPE','A');
		$txt_nam->configure('SIZE',5);
		
		$txt_f_nam = new control\textbox('nam_f_user');
		$txt_f_nam->configure('LABEL',_('نام خانوادگی'));
		$txt_f_nam->configure('PLACE_HOLDER',_('نام خانوادگی مالک'));
		$txt_f_nam->configure('ADDON','*');
		$txt_f_nam->configure('IN_PUT_TYPE','A');
		$txt_f_nam->configure('SIZE',5);
		
		$txt_nu_p = new control\textbox('num_prsonal');
		$txt_nu_p->configure('LABEL',_('شماره ملی'));
		$txt_nu_p->configure('PLACE_HOLDER',_('شماره ملی مالک'));
		$txt_nu_p->configure('ADDON','*');
		$txt_nu_p->configure('IN_PUT_TYPE','N');
		$txt_nu_p->configure('SIZE',5);
		
		$txt_sr_sh = new control\textbox('num_sr_sh');
		$txt_sr_sh->configure('LABEL',_('سریال شناسنامه'));
		$txt_sr_sh->configure('ADDON','*');
		$txt_sr_sh->configure('IN_PUT_TYPE','N');
		$txt_sr_sh->configure('SIZE',5);
			
		//@@
		$com_alph = new control\combobox('alph_sr_sh');
		$com_alph->configure('LABEL',_('ردیف شناسنامه'));
	    $com_alph->configure('SELECTED_INDEX','');
		$com_alph->configure('SOURCE',['المثنی','الف','ب','پ','ت','ث','ج','ح','خ','چ','د','ذ','ر','ز','س','ش','ص','ض','ط','ظ','ع','غ','ف','ق','ک','گ','ل','م','ن','ه','و','ی']);
		$com_alph->configure('SIZE',5);
		
		$com_sm_sh = new control\combobox('smal_sr_sh');
		$com_sm_sh->configure('LABEL',_('سری شناسنامه'));
		$com_sm_sh->configure('SELECTED_INDEX','');
        $com_sm_sh->configure('SOURCE',['01','02','03','04','05','06','07','08','09',10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40
		,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60]);
		$com_sm_sh->configure('SIZE',5);
		
		
		$row_d = new control\row;
		$row_d->configure('IN_TABLE',false);
		$row_d->add($com_alph,4);
		$row_d->add($com_sm_sh,4);
		//@@
		$txt_nu_sh = new control\textbox('num_prs_sh');
		$txt_nu_sh->configure('LABEL',_('شماره شناسنامه'));
		$txt_nu_sh->configure('PLACE_HOLDER',_('شماره شناسنامه مالک'));
		$txt_nu_sh->configure('ADDON','*');
		$txt_nu_sh->configure('IN_PUT_TYPE','N');
		$txt_nu_sh->configure('SIZE',5);
		
		
		
		//wwwwwwwww
		$lab_b = new control\label;
		$lab_b->configure('NAME','lab_2');
		$lab_b->configure('LABEL',_('مشخصات ماشین'));
		
		
		$row_b = new control\row;
		$row_b->configure('IN_TABLE',false);
		$row_b->add($lab_b,7);
		//wwwwwww
		$txt_nu_sh_ca = new control\textbox('nu_shasi');
		$txt_nu_sh_ca->configure('LABEL',_('شماره شاسی'));
		$txt_nu_sh_ca->configure('PLACE_HOLDER',_('شماره شاسی ماشین'));
		$txt_nu_sh_ca->configure('ADDON','*');
		$txt_nu_sh_ca->configure('IN_PUT_TYPE','AN');
		$txt_nu_sh_ca->configure('SIZE',5);
	
		$txt_nu_bo_ca = new control\textbox('nu_badanh');
		$txt_nu_bo_ca->configure('LABEL',_('شماره بدنه'));
		$txt_nu_bo_ca->configure('PLACE_HOLDER',_('شماره بدنه ماشین'));
		$txt_nu_bo_ca->configure('ADDON','*');
		$txt_nu_bo_ca->configure('IN_PUT_TYPE','AN');
		$txt_nu_bo_ca->configure('SIZE',5);
		////@
		/*$uplo_sa_ca = new control\uploader('upload_sa');
		$uplo_sa_ca->configure('LABEL',_('اسکن سند'));
		$uplo_sa_ca->configure('SIZE',10);
		
		$uplo_gh_ca = new control\uploader('upload_gh');
		$uplo_gh_ca->configure('NAME','upload_gh');
		$uplo_gh_ca->configure('HELP',_(''));
		$uplo_gh_ca->configure('LABEL',_('اسکن قولنامه'));
		$uplo_gh_ca->configure('SIZE',9);
		
		$uplo_ac_us = new control\uploader('upload_ac');
		$uplo_ac_us->configure('LABEL',_('اسکن عکس کاربر'));
		$uplo_ac_us->configure('SIZE',8);*/
		
		//$radio = new control\radiobutton('radio_kh');
		//$radio->configure('LABEL',_('بله'));
		
		
		$but_ok = new control\button('bt_ok');
		$but_ok->configure('LABEL',_('ثبت'));
		$but_ok->configure('P_ONCLICK_PLUGIN','org');
		$but_ok->configure('P_ONCLICK_FUNCTION','first_check');
		$but_ok->configure('TYPE','success');
		$but_ok->configure('SIZE',8);
		
		$but_can= new control\button('bt_can');
		$but_can->configure('LABEL',_('انصراف'));
		$but_can->configure('HREF',core\general::create_url(['plugin','org','action','show_search_card']));
		$but_can->configure('TYPE','warning');
		$but_can->configure('SIZE',4);
		
		$row_c =new control\row;
		//$row_c->configure('IN_TABLE',false);
		$row_c->add($but_ok,2);
		$row_c->add($but_can,2);
		
		
		//$tabl_b = new control\table;
		//$tabl_b->add_row($row_b);
		
		$form = new control\form;
		$form->configure('NAME','iformaition');
		
		$form->add($row_a);
		$form->add_array(array($txt_nam,$txt_f_nam,$txt_nu_p,$txt_nu_sh,$txt_sr_sh));
		$form->add($row_d);
		$form->add($row_b);
		$form->add_array(array($txt_nu_sh_ca,$txt_nu_bo_ca));
		$form->add($row_c);
		$form->add($hid);
		
		return array(_('مشخصات کامل'),$form->draw(),true);
	}
	protected function view_final_check($types,$final){
		
		$form = new control\form('hhh');
		$txt_nam = new control\label('name');
		$txt_nam->configure('LABEL',$final->name_us);
		
		//$labl = new control\label('type');	
		//$labl->configure('LABEL',$types->random_bnk);
		var_dump($types);
		$lablB = new control\label('price');
		$lablB->configure('LABEL',$types->cardname);
		
		$row = new control\row;
		$row->add($txt_nam,1);
		//$row->add($labl,11);
		 
		 $form->add_array(array($row,$lablB));
		 
		 return array(_('خودم'),$form->draw(),true);
	
	}

}

?>
