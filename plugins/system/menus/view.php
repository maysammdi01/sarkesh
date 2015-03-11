<?php
namespace core\plugin\menus;
use \core\control as control;
use \core\cls\core as core;

class view {
	
	function __construct(){}
	
	/*
	 * show list of menus
	 * @param array of menus $menus
	 * @return array [title,content]
	 */
	public function viewListMenus($menus){
		$form = new control\form("menus_menus_list");
		$table = new control\table;
		$counter = 0;
		foreach($menus as $key=>$menu){
			$counter ++ ;
			$row = new control\row;
			
			//add id to table for count rows
			$lblID = new control\label($counter);
			$row->add($lblID,1);
			
			//add menu name
			$lblMenuName = new control\label($menu->name);
			$row->add($lblMenuName,2);

			//add menu name
			$lblMenuLocalize = new control\label($menu->localize);
			$row->add($lblMenuLocalize,2);
					

			$btnAddLink = new control\button;
			$btnAddLink->configure('LABEL',_('Add link'));
			$btnAddLink->configure('TYPE','success');
			$btnAddLink->configure('HREF',core\general::createUrl(['service','administrator','load','menus','addLink',$menu->id]));
			$row->add($btnAddLink,1);

			$btnManageLinks = new control\button;
			$btnManageLinks->configure('LABEL',_('Manage links'));
			$btnManageLinks->configure('TYPE','default');
			$btnManageLinks->configure('HREF',core\general::createUrl(['service','administrator','load','menus','listLinks',$menu->id]));
			$row->add($btnManageLinks,1);

			$btnEdite = new control\button;
			$btnEdite->configure('LABEL',_('Edite'));
			$btnEdite->configure('TYPE','default');
			$btnEdite->configure('HREF',core\general::createUrl(['service','administrator','load','menus','doMenu',$menu->id]));
			$row->add($btnEdite,1);

			$btnDelete = new control\button;
			$btnDelete->configure('LABEL',_('Delete'));
			$btnDelete->configure('TYPE','danger');
			$btnDelete->configure('HREF',core\general::createUrl(['service','administrator','load','menus','sureDeleteMenu',$menu->id]));
			$row->add($btnDelete,1);

			$table->add_row($row);
			
		}
		
		//add headers to table
		$table->configure('HEADERS',array(_('ID'),_('Name'),_('Localize'),_('Add link'),_('Manage'),_('Edite'),_('Delete')));
		$table->configure('HEADERS_WIDTH',[1,5,2,1,1,1,1]);
		$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$form->add($table);

		$btnNewMenu = new control\button;
		$btnNewMenu->configure('LABEL',_('New menu'));
		$btnNewMenu->configure('TYPE','success');
		$btnNewMenu->configure('HREF',core\general::createUrl(['service','administrator','load','menus','doMenu']));
		$form->add($btnNewMenu,1);
		return [_('List of menus'),$form->draw()];
	}
	
	/*
	 * insert or edite menu
	 * @param array $localize, all localizes info
	 * @param array $menu, menu info
	 * @return array [title,content]
	 */
	public function viewDoMenu($localizes,$menu = null){
		$form = new control\form('frm_new_menu');

		$txtMenuName = new control\textbox('txtName');
		$txtMenuName->configure('LABEL',_('Menu name'));
		$txtMenuName->configure('ADDON','*');
		$txtMenuName->configure('SIZE',3);
		
		$txtMenuLabel = new control\textbox('txtHeader');
		$txtMenuLabel->configure('LABEL',_('Header label'));
		$txtMenuLabel->configure('HELP',_('This text show above of menu in template.'));
		$txtMenuLabel->configure('ADDON','*');
		$txtMenuLabel->configure('SIZE',3);

		$ckbShowHeader = new control\checkbox('ckbShowHeader');
		$ckbShowHeader->configure('LABEL',_('Show header label'));
		$ckbShowHeader->configure('HELP',_('This option use for show or hide label of menu.'));
		

		//$form = new control\form('languages');
		$cobLang = new control\combobox('cobLang');
		$cobLang->configure('LABEL',_('Localize'));
		$cobLang->configure('HELP',_('Created menu just will showed in selected localize.'));
		$cobLang->configure('TABLE',$localizes);
		$cobLang->configure('SIZE',4);
		$cobLang->configure('COLUMN_LABELS','language_name');
		$cobLang->configure('COLUMN_VALUES','language');

		$ckbHorizontal = new control\checkbox('ckbHorizontal');
		$ckbHorizontal->configure('LABEL',_('Horizontal menu'));
		$ckbHorizontal->configure('HELP',_('if this option checked,menu show in horizontal mode.'));

		//add insert and cancel buttons
		$btn_add = new control\button('btn_add');
		$btn_add->configure('LABEL',_('Add'));
		$btn_add->configure('P_ONCLICK_PLUGIN','menus');
		$btn_add->configure('P_ONCLICK_FUNCTION','onclick_btn_add_menu');
		$btn_add->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::createUrl(['service','administrator','load','menus','listMenus']));
		$header = _('New menu');
		if(!is_null($menu)){
			$header = sprintf(_('Edite: %s'),$menu->name);
			$txtMenuName->configure('VALUE',$menu->name);
			$txtMenuLabel->configure('VALUE',$menu->header);
			if($menu->horiz == '1')
				$ckbHorizontal->configure('CHECKED',TRUE);
			if($menu->show_header == '1')
				$ckbShowHeader->configure('CHECKED',TRUE);
			$cobLang->configure('SELECTED_INDEX',$menu->localize);

			//add id of menu to form
			$hidID = new control\hidden('hidID');
			$hidID->configure('VALUE',$menu->id);
			$form->add($hidID);

			//change label of button
			$btn_add->configure('LABEL',_('Edite'));
		}
		$form->add($txtMenuName);
		$form->add($txtMenuLabel);
		$form->add($ckbShowHeader);
		$form->add($cobLang);
		$form->add($ckbHorizontal);
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_add,1);
		$row->add($btn_cancel,11);
		$form->add($row); 
		return [$header,$form->draw()];
	}
}
