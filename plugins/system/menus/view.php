<?php
namespace core\plugin\menus;
use \core\control as control;
use \core\cls\core as core;
use \core\cls\template as template;

class view{
	protected function view_new_menu($languages,$menu='',$edite=false){
		$form = new control\form('frm_new_menu');

		$txt_menu_name = new control\textbox('txt_name');
		$txt_menu_name->configure('LABEL',_('Menu name'));
		$txt_menu_name->configure('ADDON','*');
		$txt_menu_name->configure('SIZE',3);
		
		$txt_menu_label = new control\textbox('txt_header');
		$txt_menu_label->configure('LABEL',_('Header label'));
		$txt_menu_label->configure('HELP',_('This text show above of menu in template.'));
		$txt_menu_label->configure('ADDON','*');
		$txt_menu_label->configure('SIZE',3);

		$ckb_show_header = new control\checkbox('ckb_show_header');
		$ckb_show_header->configure('LABEL',_('Show header label'));
		$ckb_show_header->configure('HELP',_('This option use for show or hide label of menu.'));
		

		//$form = new control\form('languages');
		$cob_lang = new control\combobox('cob_lang');
		$cob_lang->configure('LABEL',_('Localize'));
		$cob_lang->configure('HELP',_('Created menu just will showed in selected localize.'));
		$cob_lang->configure('TABLE',$languages);
		$cob_lang->configure('SIZE',4);
		$cob_lang->configure('COLUMN_LABELS','language_name');
		$cob_lang->configure('COLUMN_VALUES','language');

		$ckb_horizontal = new control\checkbox('ckb_horizontal');
		$ckb_horizontal->configure('LABEL',_('Horizontal menu'));
		$ckb_horizontal->configure('HELP',_('if this option checked,menu show in horizontal mode.'));

		//add insert and cancel buttons
		$btn_add = new control\button('btn_add');
		$btn_add->configure('LABEL',_('Add'));
		$btn_add->configure('P_ONCLICK_PLUGIN','menus');
		$btn_add->configure('P_ONCLICK_FUNCTION','onclick_btn_add_menu');
		$btn_add->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_menus']));
		
		if($edite){
			$txt_menu_name->configure('VALUE',$menu->name);
			$txt_menu_label->configure('VALUE',$menu->header);
			if($menu->horiz == '1'){
				$ckb_horizontal->configure('CHECKED',TRUE);
			}
			if($menu->show_header == '1'){
				$ckb_show_header->configure('CHECKED',TRUE);
			}
			$cob_lang->configure('SELECTED_INDEX',$menu->localize);

			//add id of menu to form
			$hid_id = new control\hidden('hid_id');
			$hid_id->configure('VALUE',$menu->id);
			$form->add($hid_id);

			//change label of button
			$btn_add->configure('LABEL',_('Edite'));
		}
		$form->add($txt_menu_name);
		$form->add($txt_menu_label);
		$form->add($ckb_show_header);
		$form->add($cob_lang);
		$form->add($ckb_horizontal);
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_add,1);
		$row->add($btn_cancel,11);
		$form->add($row); 
		return [_('New menu'),$form->draw()];
	}

	//this function show list of menus
	protected function view_list_menus($menus){
		$form = new control\form("menus_menus_list");
		$table = new control\table;
		$counter = 0;
		foreach($menus as $key=>$menu){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			$lbl_id = new control\label($counter);
			$row->add($lbl_id,1);
			
			//add menu name
			$lbl_menu_name = new control\label($menu->name);
			$row->add($lbl_menu_name,2);

			//add menu name
			$lbl_menu_localize = new control\label($menu->localize);
			$row->add($lbl_menu_localize,2);
					

			$btn_add_link = new control\button;
			$btn_add_link->configure('LABEL',_('Add link'));
			$btn_add_link->configure('TYPE','success');
			$btn_add_link->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','add_link','menu',$menu->id]));
			$row->add($btn_add_link,1);

			$btn_edite = new control\button;
			$btn_edite->configure('LABEL',_('Manage links'));
			$btn_edite->configure('TYPE','default');
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_links','id',$menu->id]));
			$row->add($btn_edite,1);

			$btn_edite = new control\button;
			$btn_edite->configure('LABEL',_('Edite'));
			$btn_edite->configure('TYPE','default');
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','new_menu','id',$menu->id]));
			$row->add($btn_edite,1);

			$btn_delete = new control\button;
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('TYPE','danger');
			$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','sure_delete_menu','id',$menu->id]));
			$row->add($btn_delete,1);

			$table->add_row($row);
			
		}
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Localize'),_('Add link'),_('Manage'),_('Edite'),_('Delete')));
		$table->configure('HEADERS_WIDTH',[1,5,2,1,1,1,1]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);

		$btn_new_menu = new control\button;
		$btn_new_menu->configure('LABEL',_('New menu'));
		$btn_new_menu->configure('TYPE','success');
		$btn_new_menu->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','new_menu']));
		$form->add($btn_new_menu,1);		

		return [_('List of menus'),$form->draw()];
	}

	//function for add link
	protected function view_add_link($menu_id,$link=''){
		$form = new control\form('menus_add_link');

		$hid_menu_id = new control\hidden('hid_menu_id');
		$hid_menu_id->configure('VALUE',$menu_id);

		$txt_link_label = new control\textbox('txt_label');
		$txt_link_label->configure('LABEL',_('Link label'));
		$txt_link_label->configure('HELP',_('This option set label for link.'));
		$txt_link_label->configure('ADDON','*');
		$txt_link_label->configure('SIZE',3);
		
		$txt_link_url = new control\textbox('txt_url');
		$txt_link_url->configure('LABEL',_('URL'));
		$txt_link_url->configure('HELP',_('This option set address of link.'));
		$txt_link_url->configure('ADDON','*');
		$txt_link_url->configure('SIZE',4);

		$ckb_enable = new control\checkbox('ckb_enable');
		$ckb_enable->configure('LABEL',_('Enable link'));
		$ckb_enable->configure('HELP',_('With this, you can show or hide link in menu.'));

		//create combobox for ranking
		$cob_rank = new control\combobox('cob_rank');
        $cob_rank->configure('LABEL',_('Rank'));
        $cob_rank->configure('SOURCE',[0,1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20]);
        $cob_rank->configure('HELP',_('use for set position of link in menu.'));
        $cob_rank->configure('SIZE',3);

		//add update and cancel buttons
		$btn_do = new control\button('btn_do');
		$btn_do->configure('LABEL',_('Add'));
		$btn_do->configure('P_ONCLICK_PLUGIN','menus');
		$btn_do->configure('P_ONCLICK_FUNCTION','onclick_btn_do_edite_add_link');
		$btn_do->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_menus']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_do,1);
		$row->add($btn_cancel,11);

		if($link != ''){
			//edite mode
			$txt_link_label->configure('VALUE',$link->label);
			$txt_link_url->configure('VALUE',$link->url);
			$hid_id = new control\hidden('hid_id');
			$hid_id->configure('VALUE',$link->id);
			$btn_do->configure('LABEL',_('Save'));
			$cob_rank->configure('SELECTED_INDEX',$link->rank);
			$form->add($hid_id);

		}
		$form->add_array([$txt_link_label,$txt_link_url,$hid_menu_id,$cob_rank,$ckb_enable]);

		$form->add($row);   
		return [('Add new link'),$form->draw()];

	}

	protected function view_list_links($links){
		$form = new control\form("menus_list_links");
		$table = new control\table;
		$counter = 0;
		foreach($links as $key=>$link){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			$lbl_id = new control\label($counter);
			$row->add($lbl_id,1);
			
			//add menu name
			$lbl_link_name = new control\label($link->label);
			$row->add($lbl_link_name,2);
		

			$btn_edite = new control\button;
			$btn_edite->configure('LABEL',_('Edite'));
			$btn_edite->configure('TYPE','default');
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','add_link','menu',$link->ref_id,'id',$link->id]));
			$row->add($btn_edite,1);

			$btn_delete = new control\button;
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('TYPE','danger');
			$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','sure_delete_link','id',$link->id]));
			$row->add($btn_delete,1);

			$table->add_row($row);
			
		}
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Edite'),_('Delete')));
		$table->configure('HEADERS_WIDTH',[1,5,1,1,]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);

		//add insert_link and cancel buttons
		$btn_new_link = new control\button('btn_new_link');
		$btn_new_link->configure('LABEL',_('New link'));
		$btn_new_link->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','add_link','menu',$_REQUEST['id']]));
		$btn_new_link->configure('TYPE','success');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_menus']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_new_link,1);
		$row->add($btn_cancel,11);
		$form->add($row);
		return [_('List of links'),$form->draw()];
	}

	//this function draw menu
	protected static function view_draw_menu($menu,$links){

		//create an object from raintpl class//
		$raintpl = new template\raintpl;
		//configure raintpl //
		$raintpl->configure('tpl_dir','plugins/system/menus/');

		$raintpl->assign( "horiz", $menu->horiz);
		$raintpl->assign( "show_header", $menu->show_header);
		$raintpl->assign( "header", $menu->header);
		$raintpl->assign( "links", $links);
		//draw and return back content
		return $raintpl->draw('menu', true );
	}
	//dunction for show sure message and delete menu
	protected function view_sure_delete_menu($menu){
		$form = new control\form('menus_sure_delete_menu');

		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$menu->id);
		$form->add($hid_id);

		$lbl = new control\label;
		$lbl->configure('LABEL',_('Are you sure for delete menu?'));
		$lbl_menu_name = new control\label;
		$lbl_menu_name->configure('LABEL',sprintf(_('Menu name: %s'),$menu->name));
		$form->add($lbl);
		$form->add($lbl_menu_name);

		//add update and cancel buttons
		$btn_delete = new control\button('btn_delete');
		$btn_delete->configure('LABEL',_('Delete'));
		$btn_delete->configure('P_ONCLICK_PLUGIN','menus');
		$btn_delete->configure('P_ONCLICK_FUNCTION','onclick_btn_delete_menu');
		$btn_delete->configure('TYPE','danger');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','menus','a','list_menus']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_delete,1);
		$row->add($btn_cancel,11);
		$form->add($row);
		return [_('Delete menu'),$form->draw()];
	}

	//function for create menu by other plugins
	protected function view_create_menu($links,$show_header,$horizontal){
		//create an object from raintpl class//
		$raintpl = new template\raintpl;
		//configure raintpl //
		$raintpl->configure('tpl_dir','plugins/system/menus/');
		$raintpl->assign( "horiz", $horizontal);
		$raintpl->assign( "show_header", $show_header);
		$raintpl->assign( "links", $links);
		//draw and return back content
		return $raintpl->draw('create_menu', true );
	}

}
