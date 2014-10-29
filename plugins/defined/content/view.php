<?php

namespace addon\plugin\content;
use \core\control as control;
use \core\cls\calendar as calendar;
use \core\cls\core as core;
class view{
	
	protected function view_show($content,$page){
		
		$tile = new control\tile;
		$tile->configure('TEMPLATE_DIR','./plugins/defined/content/templates/');
		$tile->configure('TEMPLATE','simple_content');
		$tile->configure('CSS_FILE','./plugins/defined/content/templates/css/simple_content.css');
		foreach($page as $part){
			$tile->add($part[0],$part[1]);
		}
		$info_content = sprintf(_('%s by %s'),$content->date,$content->user) ;
		$tile->add($info_content,'meta');
		
		return array($content->header,$tile->draw());
	}
	
	protected function view_insert_cat($with_list,$cats=''){
		$tile = new control\tile;
		
		$form = new control\form('content_insert_catalogue');

		$txt_name = new control\textbox('txt_name');
		$txt_name->configure('LABEL','Name');
		$txt_name->configure('ADDON','*');
		$txt_name->configure('HELP',_('Enter name of catalogue that you want to use that.'));
		$txt_name->configure('SIZE',4);
		
		$btn_insert = new control\button('btn_insert');
		$btn_insert->configure('LABEL',_('Insert'));
		$btn_insert->configure('HELP',_('Enter name of catalogue that you want to use that.'));
		$btn_insert->configure('TYPE','primary');
		$btn_insert->configure('P_ONCLICK_PLUGIN','content');
		$btn_insert->configure('P_ONCLICK_FUNCTION','onclick_btn_insert_cat');
		
		$form->add_array([$txt_name,$btn_insert]);
		
		$tile->add($form->draw());
		if($with_list){
			$tile->add_spc();
			$tile->add($cats[1]);
		}
		
		return [_('New catalogue'),$tile->draw(),true];
		
	}
	
	//this function return back table of catalogues with edit and delete buttons
	//$cats is bean of table content_cotologue
	protected function view_list_cats($cats){
		
		$form = new control\form('content_cat_table');
		$table = new control\table('content_cats_table');
		$counter = 0;
		foreach($cats as $key=>$cat){
			$counter += 1;
			$row = new control\row('content_catalogue_row');
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$counter);
			$row->add($lbl_id,1);
			
			$lbl_cat = new control\label('lbl');
			$lbl_cat->configure('LABEL',$cat->name);
			$row->add($lbl_cat,1);
			
			$btn_edite = new control\button('btn_content_cats_edite');
			$btn_edite->configure('LABEL',_('Edit'));
			$btn_edite->configure('VALUE',$cat->id);
			$btn_edite->configure('P_ONCLICK_PLUGIN','content');
			$btn_edite->configure('P_ONCLICK_FUNCTION','btn_cat_go_edite');
			$row->add($btn_edite,2);
			
			$btn_delete = new control\button('btn_content_cats_delete');
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','sure_delete','id',$cat->id]));
			$btn_delete->configure('TYPE','warning');
			$row->add($btn_delete,2);
			
			$btn_patterns = new control\button('btn_content_cats_patterns');
			$btn_patterns->configure('LABEL',_('Manage patterns'));
			$btn_patterns->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_patterns','id',$cat->id]));
			$btn_patterns->configure('TYPE','primary');
			$row->add($btn_patterns,2);
			
			$table->add_row($row);
			$table->configure('HEADERS',[_('ID'),_('Name'),_('Edit'),_('Delete'),_('patterns')]);
			$table->configure('HEADERS_WIDTH',[1,8,1,1,1]);
			$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE]);
			$table->configure('BORDER',true);
		}
		
		$form->add($table);
		
		return [_('Catalogues'),$form->draw()];
	}
	
	//show eit page
	protected function view_cat_edit($cat){
		
		$form = new control\form('content_update_catalogue');
		
		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$cat->id);
		
		$txt_name = new control\textbox('txt_name');
		$txt_name->configure('LABEL','Name');
		$txt_name->configure('VALUE',$cat->name);
		$txt_name->configure('ADDON','*');
		$txt_name->configure('SIZE',4);
		
		$btn_update = new control\button('btn_update');
		$btn_update->configure('LABEL',_('Update'));
		$btn_update->configure('P_ONCLICK_PLUGIN','content');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_edit_cat');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,3);
		$row->add($btn_cancel,3);
		$form->add_array([$txt_name,$row,$hid_id]);
		
		return [_('Update catalogue:') . $cat->name,$form->draw(),true];
		
		
	}
	
	protected function view_sure_page($cat){
		
		$form = new control\form('content_catalogue_delete');
		
		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$cat->id);
		
		$label = new control\label('txt_sure_msg');
		$label->configure('LABEL',sprintf(_('Are you sure for delete %s ?'),$cat->name));
		
		$btn = new control\button('btn_catalog_delete');
		$btn->configure('P_ONCLICK_PLUGIN','content');
		$btn->configure('P_ONCLICK_FUNCTION','onclick_btn_delete_cat');
		$btn->configure('LABEL',_('Delete!'));
		$btn->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn,1);
		$row->add($btn_cancel,3);
		
		$form->add_array([$hid_id,$label,$row]);
		
		return [_('Delete catalogue'),$form->draw()];
	}
	
	//function for show patterns and manage that
	protected function view_list_patterns($cat,$patterns){
		$form = new control\form('CONTENT_MANAGE_PATTERNS');
		
		$lbl_cat_info = new control\label;
		$lbl_cat_info->configure('LABEL',sprintf(_('%s Patterns'),$cat->name));
		
		$form->add($lbl_cat_info);
		
		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$patterns[0]->catalogue);
		$form->add($hid_id);
		
		//draw table
		$table = new control\table;
		$table->configure('HEADERS',[_('ID'),_('Name'),_('Type'),_('Rank'),_('Edit'),_('Delete')]);
		$table->configure('HEADERS_WIDTH',[1,6,2,1,1,1]);
		$table->configure('ALIGN_CENTER',[TRUE,TRUE,TRUE,TRUE,TRUE,TRUE]);
		$table->configure('BORDER',true);
		$table->configure('SIZE',5);
		
		$counter = 0;
		foreach($patterns as $key=>$pattern){
			$counter ++;
			$row = new control\row;
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$counter);
			$row->add($lbl_id);
			
			$lbl_name = new control\label('lbl');
			$lbl_name->configure('LABEL',$pattern->label);
			$row->add($lbl_name);
			
			$lbl_type = new control\label('lbl');
			$lbl_type->configure('LABEL',$pattern->type);
			$row->add($lbl_type);
			
			$txt_rank = new control\textbox('txt_rank');
			$txt_rank->configure('IN_TABLE',TRUE);
			$txt_rank->configure('VALUE',$pattern->rank);
			$row->add($txt_rank);
			
			$btn_edite = new control\button('btn_edite');
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','edite_pattern','id',$pattern->id]));

			$btn_edite->configure('LABEL',_('Edite'));
			$btn_edite->configure('TYPE','default');			
			$row->add($btn_edite);
			
			$btn_delete = new control\button('btn_delete');
			$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','sure_delete_pattern','id',$pattern->id]));
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('TYPE','warning');			
			$row->add($btn_delete);
			
			$table->add_row($row);
		}
		
		$form->add($table);
		
		//new button with combobox
		$row_new_pattern = new control\row;
		$row_new_pattern->configure('IN_TABLE',FALSE);
		
		$combo_items = new control\combobox('cob_item');
		$combo_items->configure('SIZE',2);
		$combo_items->configure('INLINE',TRUE);
		$combo_items->configure('SOURCE',[['Textarea',_('Textarea')]]);
		$row_new_pattern->add($combo_items,4);
		
		$btn_new = new control\button('btn_new');
		$btn_new->configure('LABEL',_('Add new'));
		$btn_new->configure('P_ONCLICK_PLUGIN','content');
		$btn_new->configure('P_ONCLICK_FUNCTION','onclick_btn_add_new');
		$btn_new->configure('TYPE','primary');
		$row_new_pattern->add($btn_new,1);
				
		$form->add($row_new_pattern);
		
		
		$row_buttons = new control\row;
		$row_buttons->configure('IN_TABLE',FALSE);
		
		$btn_applay = new control\button('btn_applay');
		$btn_applay->configure('LABEL',_('Applay'));
		$row_buttons->add($btn_applay,1);
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_cats']));
		$btn_cancel->configure('TYPE','default');
		$row_buttons->add($btn_cancel,1);
		
		$form->add($row_buttons);
		
		return [$lbl_cat_info->get('LABEL'), $form->draw()];
		
	}
	
	//function for show sure to delete pattern message
	protected function view_sure_delete_pattern($pattern){
		
		$form = new control\form('content_pattern_delete');
		
		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$pattern->id);
		
		$label = new control\label('txt_sure_msg');
		$label->configure('LABEL',sprintf(_('Are you sure for delete %s ?'),$pattern->label));
		
		$btn = new control\button('btn_pattern_delete');
		$btn->configure('P_ONCLICK_PLUGIN','content');
		$btn->configure('P_ONCLICK_FUNCTION','onclick_btn_delete_pattern');
		$btn->configure('LABEL',_('Delete!'));
		$btn->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn,1);
		$row->add($btn_cancel,1);
		
		$form->add_array([$hid_id,$label,$row]);
		
		return [_('Delete pattern'),$form->draw()];
	}
	
	//this function get options of patterns and return array of this options
	protected function get_options($str_options){
		
		$f_options = explode(';',$str_options);
		$options = array();
		foreach($f_options as $option){
			$op = explode(':',$option);
			$options[$op[0]] = $op[1];
		}
		return $options;
	}
	//this function return textarea pattern for insert and edite
	protected function pt_textarea($pattern){
		if($pattern != ''){
			//return in edite mode
			$options = $this->get_options($pattern->options);
			$form = new control\form('CONTENT_EDITE_PATTERN');
				
			$hid_id = new control\hidden('hid_id');
			$hid_id->configure('VALUE',$pattern->id);
			$form->add($hid_id);
		
			$txt_label = new control\textbox('txt_label');
			$txt_label->configure('LABEL',_('Lable'));
			$txt_label->configure('VALUE',$pattern->label);
			$txt_label->configure('HELP',_('Lable show to user in insert new content page'));
			$form->add($txt_label);
			
			$txt_rank = new control\textbox('txt_rank');
			$txt_rank->configure('LABEL',_('Rank'));
			$txt_rank->configure('VALUE',$pattern->rank);
			$txt_rank->configure('HELP',_('When set rank you can set position of pattern in content.ranks show from DEC values'));
			$form->add($txt_rank);
			
			$ckb_editor = new control\checkbox('ckb_editor');
			$ckb_editor->configure('LABEL',_('Editor') );
			$ckb_editor->configure('HELP',_('With this option you can enable or disable editor when user want to insert new content.'));
			if($options['editor'] == 1){
				$ckb_editor->configure('CHECKED',TRUE);
			}
			$form->add($ckb_editor);
			
			$row = new control\row;
			$row->configure('IN_TABLE',false);
			
			$btn_edite = new control\button('btn_update');
			$btn_edite->configure('LABEL',_('Update'));
			$btn_edite->configure('P_ONCLICK_PLUGIN','content');
			$btn_edite->configure('P_ONCLICK_FUNCTION','onclick_btn_edite_pattern');
			$btn_edite->configure('TYPE','primary');
			$row->add($btn_edite);

			$btn_cancel = new control\button('btn_cancel');
			$btn_cancel->configure('LABEL',_('Cancel'));
			$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','content','a','list_patterns','id',$pattern->catalogue]));
			$row->add($btn_cancel);
			
			$form->add($row);
			
			return [1,$form->draw()];
		}
		else{
			//return in edite mode
			
		}
		
	}
	//function for edite pattern
	protected function view_edite_pattern($pattern){
		
		if($pattern->type == 'textarea'){
			return $this->pt_textarea($pattern);
		}
	}
	
}

?>
