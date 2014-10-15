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
		$txt_name->configure('SIZE',4);
		
		$btn_insert = new control\button('btn_insert');
		$btn_insert->configure('LABEL',_('Insert'));
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
		
		foreach($cats as $key=>$cat){
			
			$row = new control\row('content_catalogue_row');
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$key);
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
			$btn_delete->configure('P_ONCLICK_PLUGIN','content');
			$btn_delete->configure('P_ONCLICK_FUNCTION','btn_cat_delete');
			$btn_delete->configure('TYPE','warning');
			$row->add($btn_delete,2);
			
			$table->add_row($row);
			$table->configure('HEADERS',[_('ID'),_('Name'),_('Edit'),_('Delete')]);
			$table->configure('HEADERS_WIDTH',[1,9,1,1]);
			$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE]);
			
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
		$btn_cancel->configure('HREF',core\general::create_url(['plugin','content','action','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,3);
		$row->add($btn_cancel,3);
		$form->add_array([$txt_name,$row,$hid_id]);
		
		return [_('Update catalogue:') . $cat->name,$form->draw(),true];
		
		
	}
	
}

?>
