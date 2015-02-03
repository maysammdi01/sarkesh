<?php
namespace core\plugin\administrator;
use \core\cls\template as template;
use \core\cls\browser as browser;
use \core\cls\core as core;
use \core\control as control;

class view{
	private $raintpl;
	
	function __construct(){
		//create an object from raintpl class//
		$this->raintpl = new template\raintpl;
		//configure raintpl //
		$this->raintpl->configure('tpl_dir','plugins/system/administrator/tpl/');
		
	}
	
	protected function view_load($title,$content,$single_panel=false){

		//Assign variables
		$this->raintpl->assign( "page_headers", browser\page::load_headers(false));
		$this->raintpl->assign( "page_title", $title);
		$this->raintpl->assign( "page_content", $content);
		$this->raintpl->assign( "single_panel", $single_panel);

		
		//draw and return back content
		return $this->raintpl->draw('core_panel', true );
	}
	
	//this function show main part of core plug
	//$menu is plugins special menu
	protected function view_main($menu,$content,$title,$user,$registry){
		
		browser\page::add_header('<link href="./plugins/system/administrator/style/core_content.css" rel="stylesheet">');
		$localize = new core\localize;
		$local = $localize->get_localize();
		if($local['direction'] = 'rtl'){
			browser\page::add_header('<link href="./plugins/system/administrator/style/rtl_core_content.css" rel="stylesheet">');
		}
		//Assign variables
		$this->raintpl->assign( "menu", $menu);	
		$this->raintpl->assign( "content", $content);
		$this->raintpl->assign( "title", $title);
		
		$this->raintpl->assign( "user_logout", _('Log Out')	);
		$this->raintpl->assign( "user_logout_url", core\general::create_url(array('plugin','users','action','btn_logout_onclick')	)	);
		$this->raintpl->assign( "user_name", $user['username']	);
		$this->raintpl->assign( "powered_by", _('Powered by SarkeshMVC')	);
		$this->raintpl->assign( "view_site", _('View website')	);
		$this->raintpl->assign( "view_site_url",SiteDomain);
		$this->raintpl->assign( "dashboard", _('Dashboard')	);
		$this->raintpl->assign( "user_profile", _('Profile'));
		$this->raintpl->assign( "core_version", sprintf(_('Version:%s'),$registry['core_version']));
		$this->raintpl->assign( "build_num", sprintf(_('Build Number:%s'),$registry['build_num']));
		$this->raintpl->assign( "user_profile_url", core\general::create_url(array('plugin','users','action','profile')	)	);
	
		$this->raintpl->assign( "sarkesh_admin", _('Sarkesh Administrator')	);
		$this->raintpl->assign( "sarkesh_admin_url", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','dashboard')	));
		
		//draw and return back menus
		return $this->raintpl->draw('core_content', true );
	
	}
	
	#This function show login page 
	protected function view_login_page($url){
		
	
	}
	
	#This function show themes panel
	protected function view_themes($themes,$themes_info,$active_theme){
		
		$form = new control\form("core_manage_themes");
		$form->configure('LABEL',_('Themes'));
		$tab = new control\tabbar;
		$table = new control\table;
		
		foreach($themes as $key=>$theme){
			$row = new control\row;
			
			//add id to table for count rows
			$lbl_id = new control\label($key+1);
			$row->add($lbl_id,1);
			
			//add theme name
			$lbl_theme_name = new control\label($themes_info[$key]['name']);
			$row->add($lbl_theme_name,2);
			
			//add author of theme
			$lbl_author = new control\label($themes_info[$key]['author']);
			$row->add($lbl_author,2);
			
			
			
			//add active theme button
			if($theme != $active_theme){
				$btn_active = new control\button;
				$btn_active->configure('LABEL',_('Active this'));
				$btn_active->configure('TYPE','success');
				$btn_active->configure('VALUE',$theme);
				$btn_active->configure('P_ONCLICK_PLUGIN','administrator');
				$btn_active->configure('P_ONCLICK_FUNCTION','btn_change_theme');
				$row->add($btn_active,1);
			}
            
			$table->add_row($row);
			
		}
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Author'),_('Options')));
		$table->configure('HEADERS_WIDTH',[1,5,3,3]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,FALSE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);
		
		$tab->add($form);
		
		//create install form
		$ins_form = new control\form('core_install_theme');
		$ins_form->configure('LABEL',_('Install'));
		
		$tab->add($ins_form);
		return array(_('Appearance'),$tab->draw());
	}
	
	//this function return dashboard of administrator area
	protected function view_dashboard(){
		//Assign variables
		$this->raintpl->assign( "BasicSettings", _('Basic Settings'));
		$this->raintpl->assign( "RegionalandLanguages", _('Regional and Languages'));
		$this->raintpl->assign( "Appearance", _('Appearance'));
		$this->raintpl->assign( "Plugins",_('Plugins'));
		$this->raintpl->assign( "Blocks", _('Blocks'));
		$this->raintpl->assign( "Usersandpermissions", _('Users and permissions'));
		$this->raintpl->assign( "url_regional", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','regandlang')	));
		
		$this->raintpl->assign( "system_update", _('Update center'));
		$this->raintpl->assign( "url_system_update", core\general::create_url(array('service','1','plugin','administrator','action','main','p','log','a','updates')	));

		$this->raintpl->assign( "core_settings", _('Core settings'));
		$this->raintpl->assign( "url_core_settings", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','core_settings')	));

		$this->raintpl->assign( "url_appearance", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','themes')	));
		$this->raintpl->assign( "url_plugins", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','plugins')	));
		
		$this->raintpl->assign( "url_blocks", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','blocks')) );
		$this->raintpl->assign( "url_uap",core\general::create_url(array('service','1','plugin','administrator','action','main','p','users','a','list_people')	));
		
		$this->raintpl->assign( "url_basic", core\general::create_url(array('service','1','plugin','administrator','action','main','p','administrator','a','basic_settings')	));
		
		//draw and return back content
		return array(_('Dashboard'),$this->raintpl->draw('core_dashboard', true )	);
	}
	
	//function for show plugins and options for manage thats
	protected function view_plugins($plugins){
		
		$form = new control\form("core_manage_plugins");
		$form->configure('LABEL',_('Plugins'));
		$tab = new control\tabbar;
		$table = new control\table;
		$counter = 0;
		foreach($plugins as $key=>$plugin){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			$lbl_id = new control\label($counter);
			$row->add($lbl_id,1);
			
			//add plugin name
			$lbl_plugin_name = new control\label($plugin->name);
			$row->add($lbl_plugin_name,2);
					
			//add plugin state			
			if($plugin->enable != 1){
				$btn_active = new control\button;
				$btn_active->configure('LABEL',_('Active'));
				$btn_active->configure('TYPE','success');
				$btn_active->configure('VALUE',$plugin->id);
				$btn_active->configure('P_ONCLICK_PLUGIN','administrator');
				$btn_active->configure('P_ONCLICK_FUNCTION','btn_change_plugin');
				
			}
			else{
				
				$btn_active = new control\button;
				$btn_active->configure('LABEL',_('Disactive'));
				$btn_active->configure('TYPE','danger');
				$btn_active->configure('VALUE',$plugin->id);
				$btn_active->configure('P_ONCLICK_PLUGIN','administrator');
				$btn_active->configure('P_ONCLICK_FUNCTION','btn_change_plugin');
				
			}
			$row->add($btn_active,1);
			$table->add_row($row);
			
		}
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Options')));
		$table->configure('HEADERS_WIDTH',[1,5,3,3]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);
		
		$tab->add($form);
		
		//create install form
		$ins_form = new control\form('core_install_plugin');
		$ins_form->configure('LABEL',_('Install'));
		
		$upload_plugin = new control\uploader('upload_plugin');
		$ins_form->add($upload_plugin);
		
		//install and cancel buttons
		//add update and cancel buttons
		$btn_update = new control\button('btn_install');
		$btn_update->configure('LABEL',_('Install'));
		$btn_update->configure('P_ONCLICK_PLUGIN','administrator');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_install_plugin');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$ins_form->add($row); 
		$tab->add($ins_form);
		return array(_('Plugins'),$tab->draw());
	}
    
    //show list of localize for edite basic settings
    protected function view_basic_settings($locals){
    	$form = new control\form('admin_list_basic_settings');
		$table = new control\table('admin_list_basic_settings');
		$counter = 0;
		foreach($locals as $key=>$local){
			$counter += 1;
			$row = new control\row('content_catalogue_row');
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$counter);
			$row->add($lbl_id,1);
			
			$lbl_cat = new control\label('lbl');
			$lbl_cat->configure('LABEL',$local->language_name);
			$row->add($lbl_cat,1);
			
			$btn_edite_local = new control\button('btn_edite_local');
			$btn_edite_local->configure('LABEL',_('Edite'));
			$btn_edite_local->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','basic_settings_edite','id',$local->id]));
			$row->add($btn_edite_local,2);
			if($local->can_delete != '0'){
				$btn_delete = new control\button('btn_delete');
				$btn_delete->configure('LABEL',_('Delete'));
				$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','sure_delete_local','id',$local->id]));
				$btn_delete->configure('TYPE','danger');
				$row->add($btn_delete,2);
			}
			
			
			$table->add_row($row);
			$table->configure('HEADERS',[_('ID'),_('Name'),_('Edit'),_('Delete')]);
			$table->configure('HEADERS_WIDTH',[1,9,1,1]);
			$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE]);
			$table->configure('BORDER',true);
		}
		
		$form->add($table);
		
		return [_('Locals'),$form->draw()];
    }
    //this function show general settings
    protected function view_basic_settings_edite($local,$default_local){
        $form = new control\form('administrator_basic_settings_edite');
        
        $hid_id = new control\hidden('hid_id');
        $hid_id->configure('VALUE',$local->id);
        $form->add($hid_id);
        
        $txt_sitename = new control\textbox('txt_sitename');
        $txt_sitename->configure('LABEL',_('Site name'));
        $txt_sitename->configure('VALUE',$local->name);
        $txt_sitename->configure('ADDON','*');
        $txt_sitename->configure('SIZE',3);
        $form->add($txt_sitename);
        
        $txt_slogan = new control\textbox('txt_slogan');
        $txt_slogan->configure('LABEL',_('Slogan'));
        $txt_slogan->configure('VALUE',$local->slogan);
        $txt_slogan->configure('HELP',_("How this is used depends on your site's theme."));
        $txt_slogan->configure('ADDON','O'); //O -> OPTIONAL
        $txt_slogan->configure('SIZE',3);
        $form->add($txt_slogan);
        
        $txt_email = new control\textbox('txt_email');
        $txt_email->configure('LABEL',_('Email address'));
        $txt_email->configure('VALUE',$local->email);
        $txt_email->configure('ADDON','*');
        $txt_email->configure('SIZE',5);
        $txt_email->configure('HELP',_("The From address in automated e-mails sent during registration and new password requests, and other notifications. (Use an address ending in your site's domain to help prevent this e-mail being flagged as spam.)"));
        $form->add($txt_email);
        
        $txt_frontpage = new control\textbox('txt_frontpage');
        $txt_frontpage->configure('LABEL',_('Front page'));
        $txt_frontpage->configure('VALUE',$local->home);
        $txt_frontpage->configure('ADDON',SiteDomain . '/');
        $txt_frontpage->configure('SIZE',5);
        $txt_frontpage->configure('HELP',_("Optionally, specify a relative URL to display as the front page. be careful for that this address be correct!"));
        $form->add($txt_frontpage);

        //add description to head of page
        $txt_des = new control\textarea('txt_des');
        $txt_des->configure('EDITOR',FALSE);
        $txt_des->configure('VALUE',$local->header_tags);
        $txt_des->configure('LABEL',_('Description'));
        $txt_des->configure('HELP',_('your text show in header of page for use in search engines.'));
        $txt_des->configure('EDITOR',FALSE);
        $txt_des->configure('ROWS',5);
		$txt_des->configure('SIZE',7);
        $form->add($txt_des);
        
        //add update and cancel buttons
		$btn_update = new control\button('btn_update');
		$btn_update->configure('LABEL',_('Update'));
		$btn_update->configure('P_ONCLICK_PLUGIN','administrator');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_update_basic_settings_edite');
		$btn_update->configure('TYPE','primary');

		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','basic_settings']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$form->add($row);                
        
        
        return [_('General Settings'),$form->draw()];
    }
    
    //this function show regional and languages setttings
    protected function view_regandlang($countries,$timezones,$locals,$default_language){
        
        $form = new control\form('administrator_regandlang_settings');
        
        //show default countries
        $cob_countries = new control\combobox('cob_contries');
        $cob_countries->configure('LABEL',_('Default country'));
        $cob_countries->configure('TABLE',$countries);
        $cob_countries->configure('COLUMN_VALUES','country_name');
        $cob_countries->configure('COLUMN_LABELS','country_name');
        $cob_countries->configure('SIZE',3);
        $form->add($cob_countries);
        
        //default language
        $cob_language = new control\combobox('cob_language');
        $cob_language->configure('LABEL',_('Default Localize'));
        $cob_language->configure('TABLE',$locals);
        $cob_language->configure('SELECTED_INDEX',$default_language->id);
        $cob_language->configure('COLUMN_VALUES','id');
        $cob_language->configure('COLUMN_LABELS','language_name');
        $cob_language->configure('SIZE',3);
        $form->add($cob_language);
         
        //show default timezones
        $cob_timezone = new control\combobox('cob_timezones');
        $cob_timezone->configure('LABEL',_('Default Timezone'));
        $cob_timezone->configure('TABLE',$timezones);
        $cob_timezone->configure('COLUMN_VALUES','timezone_name');
        $cob_timezone->configure('COLUMN_LABELS','timezone_name');
        $cob_timezone->configure('SIZE',3);
        $form->add($cob_timezone);
        
        //add update and cancel buttons
		$btn_update = new control\button('btn_update');
		$btn_update->configure('LABEL',_('Update'));
		$btn_update->configure('P_ONCLICK_PLUGIN','administrator');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_update_regandlang');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$form->add($row);   
        
        return[_('Regional and languages'),$form->draw()];
    }
    
    //this function show blocks for that user can manage widgets
    public function view_blocks($blocks,$places){
		
		$form = new control\form("core_manage_blocks");
		$form->configure('LABEL',_('Blocks'));
		$table = new control\table;
		$counter = 0;
		foreach($blocks as $key=>$block){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			$lbl_id = new control\label($counter);
			$row->add($lbl_id,1);
			
			//add block name
			$lbl_block_name = new control\label($block->name);
			$row->add($lbl_block_name,2);
					
			//add plugin state			
			if($block->position == 'off'){
				//block is disabled
				$lbl_block_state = new control\label(_('Off'));

			}
			else{
				//show position
				$lbl_block_state = new control\label($block->position);
	
			}
			$row->add($lbl_block_state,1);
			
			//add rank of block
			$lbl_block_rank = new control\label($block->rank);
			$row->add($lbl_block_rank,2);
			
			
			//ADD EDITE BUTTON
			//add update and cancel buttons
			$btn_edite = new control\button('btn_edite');
			$btn_edite->configure('LABEL',_('Edite'));
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','edite_block','id',$block->id]));
			$btn_edite->configure('TYPE','primary');
			$row->add($btn_edite);
			
			//ADD ROW TO TABLE
			$table->add_row($row);
		}
		
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Place'),_('Rank'),_('Edite')));
		$table->configure('HEADERS_WIDTH',[1,3,2,1,2]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);
	
		
		//add cancel buttons
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		$form->add($btn_cancel); 
		
		return array(_('Manage Blocks'),$form->draw());
	}
	
	public function view_edite_block($block,$places){
		$form = new control\form("core_edite_block");
		
		//Create hidden id of block
		$hid_id = new control\hidden('hid_id');
        $hid_id->configure('VALUE',$block->id);
        $form->add($hid_id);
        
		//create combobox for ranking
		$cob_rank = new control\combobox('cob_rank');
        $cob_rank->configure('LABEL',_('Rank'));
        $cob_rank->configure('SELECTED_INDEX',$block->rank);
        $cob_rank->configure('SOURCE',[0,1,2,3,4,5,6,7,8,9]);
        $cob_rank->configure('SIZE',3);
		$form->add($cob_rank);
		
		//create textarea for pages
		$txt_pages = new control\textarea('txt_pages');
		$txt_pages->configure('EDITOR',FALSE);
		$txt_pages->configure('VALUE',$block->pages);
		$txt_pages->configure('LABEL',_('Pages'));
		$txt_pages->configure('HELP',_('Use \',\' for seperate page urls.'));
		$txt_pages->configure('ROWS',5);
		$txt_pages->configure('SIZE',7);
		$form->add($txt_pages);
		
		//ADD RADIO BUTTON FOR SELECT PAGES
		$rad_bot = new control\radiobuttons('rad_show_option');
		$rad_bot->configure('LABEL',_('With this option you can select pages for show.'));
		$radit_al_pages = new control\radioitem('rad_it_allow');
		$radit_al_pages->configure('LABEL',_('All pages espect that comes above.'));
		if($block->pages_ad == '1'){
			$radit_al_pages->configure('CHECKED',TRUE);
		}
		$rad_bot->add($radit_al_pages);
		
		$radit_ex_pages = new control\radioitem('rad_it_deny');
		$radit_ex_pages->configure('LABEL',_('show in pages that comes above.'));
		if($block->pages_ad == '0'){
			$radit_ex_pages->configure('CHECKED',TRUE);
		}
		$rad_bot->add($radit_ex_pages);
		$form->add($rad_bot);
		
		//create combobox for positions
		$cob_position = new control\combobox('cob_position');
        $cob_position->configure('LABEL',_('Position'));
        $cob_position->configure('SELECTED_INDEX',$block->position);
        $cob_position->configure('SOURCE',$places);
        $cob_position->configure('SIZE',3);
		$form->add($cob_position);
		
		
		//add update and cancel buttons
		$btn_update = new control\button('btn_update');
		$btn_update->configure('LABEL',_('Update'));
		$btn_update->configure('P_ONCLICK_PLUGIN','administrator');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_update_block');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','blocks']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$form->add($row);   
		
		
		return [_('Edite Block:').$block->name,$form->draw()];
		
	}
	//function for show core_settings
	protected function view_core_settings($settings){
		$form = new control\form('administrator_core_settings');
		//enable clean url
		$ckb_clean_url = new control\checkbox('ckb_clean_url');
		$ckb_clean_url->configure('LABEL',_('Enable clean url') );
		$ckb_clean_url->configure('CHECKED', FALSE);
		$ckb_clean_url->configure('HELP',_('With this option,pages with parameters will be replaced with clean address.'));
		if($settings['clean_url'] == 1){
			$ckb_clean_url->configure('CHECKED',TRUE);
		}
		$form->add($ckb_clean_url);
		//add update and cancel buttons
		$btn_update = new control\button('btn_update');
		$btn_update->configure('LABEL',_('Update'));
		$btn_update->configure('P_ONCLICK_PLUGIN','administrator');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_update_core_settings');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$form->add($row);  
		return [_('Core settings'),$form->draw()];
	}

	//function for show sure delete local
	protected function view_sure_delete_local($local){
		$form = new control\form('administartor_sure_delete_local');

		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$local->id);

		$lbl_msg = new control\label;
		$lbl_msg->configure('LABEL',sprintf(_('Are you sure for delete %s ?'),$local->language_name));
		$lbl_des = new control\label;
		$lbl_des->configure('LABEL',_('Be careful! after delete this local setting all content that has relation with this,will be removed.'));

		$btn_delete = new control\button('btn_delete');
		$btn_delete->configure('LABEL',_('Delete'));
		$btn_delete->configure('TYPE','danger');
		$btn_delete->configure('P_ONCLICK_PLUGIN','administrator');
		$btn_delete->configure('P_ONCLICK_FUNCTION','onclick_btn_delete_local');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','basic_settings']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_delete,1);
		$row->add($btn_cancel,11);

		$form->add_array([$hid_id,$lbl_msg,$lbl_des,$row]);
		return [_('Delete local'),$form->draw()];
	}
}
?>
