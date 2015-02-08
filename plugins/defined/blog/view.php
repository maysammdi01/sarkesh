<?php
namespace addon\plugin\blog;
use \core\control as control;
use \core\cls\core as core;
use \core\cls\template as template;
use \core\cls\calendar as calendar;

class view{
	
	protected function view_settings($settings,$current_type,$content_types){

		$form = new control\form('blog_settings');

		$cob_content_type = new control\combobox('cob_content_type');
        $cob_content_type->configure('LABEL',_('Content type'));
        $cob_content_type->configure('HELP',_('blog plugin work with selected content type that you set in this combobox.you can manage content types from content plugin.'));
        $cob_content_type->configure('TABLE',$content_types);
        $cob_content_type->configure('COLUMN_VALUES','id');
        $cob_content_type->configure('COLUMN_LABELS','name');
        $cob_content_type->configure('SELECTED_INDEX',$current_type->id);
        $cob_content_type->configure('SIZE',4);
        $form->add($cob_content_type);

        //add update and cancel buttons
		$btn_update = new control\button('btn_update');
		$btn_update->configure('LABEL',_('Update'));
		$btn_update->configure('P_ONCLICK_PLUGIN','blog');
		$btn_update->configure('P_ONCLICK_FUNCTION','onclick_btn_save_settings');
		$btn_update->configure('TYPE','primary');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','administrator','a','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_update,1);
		$row->add($btn_cancel,11);
		$form->add($row);   

		return [_('Blog settings'),$form->draw()];
	}

	//function for show catalogues list
	protected function view_list_cats($cats){
		$form = new control\form('blog_cat_table');
		$table = new control\table('blog_cat_table');
		$counter = 0;
		foreach($cats as $key=>$cat){
			$counter += 1;
			$row = new control\row('blog_cat_row');
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$counter);
			$row->add($lbl_id,1);
			
			$lbl_cat = new control\label('lbl');
			$lbl_cat->configure('LABEL',$cat->name);
			$row->add($lbl_cat,1);

			$lbl_cat = new control\label('lbl');
			$lbl_cat->configure('LABEL',$cat->localize);
			$row->add($lbl_cat,1);
			
			$btn_edite = new control\button('btn_content_cats_edite');
			$btn_edite->configure('LABEL',_('Edit'));
			$btn_edite->configure('VALUE',$cat->id);
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','edite_cat','id',$cat->id]));
			$row->add($btn_edite,2);
			
			$btn_delete = new control\button('btn_content_cats_delete');
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','sure_delete_cat','id',$cat->id]));
			$btn_delete->configure('TYPE','danger');
			$row->add($btn_delete,2);
			
			$table->add_row($row);
			$table->configure('HEADERS',[_('ID'),_('Name'),_('Localize'),_('Edit'),_('Delete')]);
			$table->configure('HEADERS_WIDTH',[1,7,2,1,1]);
			$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE]);
			$table->configure('BORDER',true);
			$table->configure('SIZE',9);
		}
		$form->add($table);	

		$btn_add_cats = new control\button('btn_add_cats');
		$btn_add_cats->configure('LABEL',_('Add new catalogue'));
		$btn_add_cats->configure('TYPE','success');
		$btn_add_cats->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','add_cat']));
		$form->add($btn_add_cats);
		
		return [_('Catalogues'),$form->draw()];
	}

	protected function view_add_cats($default_language,$locals){		
		$form = new control\form('blog_new_catalogue');

		$txt_name = new control\textbox('txt_name');
		$txt_name->configure('LABEL','Name');
		$txt_name->configure('ADDON','*');
		$txt_name->configure('HELP',_('Enter name of catalogue that you want to use that.'));
		$txt_name->configure('SIZE',4);
		
		//language of catalogue
        $cob_language = new control\combobox('cob_language');
        $cob_language->configure('LABEL',_('Localize'));
        $cob_language->configure('HELP',_('content of catalogue just show in selected localize.'));
        $cob_language->configure('TABLE',$locals);
        $cob_language->configure('SELECTED_INDEX',$default_language->language);
        $cob_language->configure('COLUMN_VALUES','language');
        $cob_language->configure('COLUMN_LABELS','language_name');
        $cob_language->configure('SIZE',4);

		$btn_insert = new control\button('btn_insert');
		$btn_insert->configure('LABEL',_('Insert'));
		$btn_insert->configure('TYPE','primary');
		$btn_insert->configure('P_ONCLICK_PLUGIN','blog');
		$btn_insert->configure('P_ONCLICK_FUNCTION','onclick_btn_add_cat');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_insert,1);
		$row->add($btn_cancel,11);

		$form->add_array([$txt_name,$cob_language,$row]);
		return [_('New catalogue'),$form->draw(),true];
		
	}

	//function for show edite catalogues form
	protected function view_edite_cat($cat,$locals){
		$form = new control\form('blog_edite_catalogue');

		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$cat->id);

		$txt_name = new control\textbox('txt_name');
		$txt_name->configure('LABEL','Name');
		$txt_name->configure('VALUE',$cat->name);
		$txt_name->configure('ADDON','*');
		$txt_name->configure('HELP',_('Enter name of catalogue that you want to use that.'));
		$txt_name->configure('SIZE',4);
		
		//language of catalogue
        $cob_language = new control\combobox('cob_language');
        $cob_language->configure('LABEL',_('Localize'));
        $cob_language->configure('HELP',_('content of catalogue just show in selected localize.'));
        $cob_language->configure('TABLE',$locals);
        $cob_language->configure('SELECTED_INDEX',$cat->localize);
        $cob_language->configure('COLUMN_VALUES','language');
        $cob_language->configure('COLUMN_LABELS','language_name');
        $cob_language->configure('SIZE',4);

		$btn_insert = new control\button('btn_update');
		$btn_insert->configure('LABEL',_('Update'));
		$btn_insert->configure('TYPE','primary');
		$btn_insert->configure('P_ONCLICK_PLUGIN','blog');
		$btn_insert->configure('P_ONCLICK_FUNCTION','onclick_btn_edite_cat');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_insert,1);
		$row->add($btn_cancel,11);

		$form->add_array([$hid_id,$txt_name,$cob_language,$row]);
		return [_('New catalogue'),$form->draw(),true];
	}

	//SHOW SURE DELETE CATALOGUE
	protected function view__sure_delete_cat($cat){
		$form = new control\form('blog_delete_catalogue');

		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$cat->id);

		$lbl_msg = new control\label;
		$lbl_msg->configure('LABEL',sprintf(_('Are you sure for delete %s ?'),$cat->name));

		$btn_delete = new control\button('btn_delete');
		$btn_delete->configure('LABEL',_('Delete'));
		$btn_delete->configure('TYPE','danger');
		$btn_delete->configure('P_ONCLICK_PLUGIN','blog');
		$btn_delete->configure('P_ONCLICK_FUNCTION','onclick_btn_delete_cat');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_cats']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_delete,1);
		$row->add($btn_cancel,11);

		$form->add_array([$hid_id,$lbl_msg,$row]);
		return [_('Delete catalogue'),$form->draw()];

	}

	//show blog catalog in theme
	protected function view_new_post($content_elements,$blogcats){
		$form = new control\form('blog_new_post');
		$form->add_array($content_elements);

		//ADD BLOG COMPONNENTS
		//catalogue of post
        $cob_cats = new control\combobox('cob_cats');
        $cob_cats->configure('LABEL',_('Catalogue'));
        $cob_cats->configure('HELP',_('content of catalogue just show in selected localize.'));
        $cob_cats->configure('TABLE',$blogcats);
        $cob_cats->configure('COLUMN_VALUES','id');
        $cob_cats->configure('COLUMN_LABELS','name');
        $cob_cats->configure('SIZE',9);

		//add textbox for add tags
        $txt_tags = new control\textbox('txt_tags');
		$txt_tags->configure('LABEL','Tags');
		$txt_tags->configure('HELP',_('you can seperate tags with ",".'));
		$txt_tags->configure('SIZE',9);
        $row = new control\row;
		$row->configure('IN_TABLE',false);
		

		$row->add($txt_tags,6);
		$row->add($cob_cats,6);
		$form->add($row);
		$btn_new_post_submit = new control\button('btn_new_post_submit');
		$btn_new_post_submit->configure('LABEL',_('Publish'));
		$btn_new_post_submit->configure('TYPE','success');
		$btn_new_post_submit->configure('P_ONCLICK_PLUGIN','blog');
		$btn_new_post_submit->configure('P_ONCLICK_FUNCTION','onclick_btn_new_post_submit');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_posts']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_new_post_submit,1);
		$row->add($btn_cancel,11);
		$form->add($row);
		return [_('New post'),$form->draw()];
	
	}

	//function for show posts in administrator area
	protected function view_list_posts($posts){
		$form = new control\form('blog_list_posts');
		$table = new control\table('blog_list_posts');
		$counter = 0;
		foreach($posts as $key=>$post){
			$counter += 1;
			$row = new control\row('blog_cat_row');
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$counter);
			$row->add($lbl_id,1);
			
			$btn_header = new control\button('lbl');
			$btn_header->configure('LABEL',$post->header);
			$btn_header->configure('TYPE','link');
			$btn_header->configure('HREF',core\general::create_url(['plugin','blog','action','show','id',$post->id]));
			$row->add($btn_header,1);

			$lbl_loc = new control\label('lbl');
			$lbl_loc->configure('LABEL',$post->cat_name);
			$row->add($lbl_loc,1);
			
			$btn_edite = new control\button('btn_content_cats_edite');
			$btn_edite->configure('LABEL',_('Edit'));
			$btn_edite->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','edite_posts','id',$post->id]));
			$row->add($btn_edite,2);
			
			$btn_delete = new control\button('btn_content_cats_delete');
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','sure_delete_post','id',$post->id]));
			$btn_delete->configure('TYPE','danger');
			$row->add($btn_delete,2);
			
			$table->add_row($row);
			$table->configure('HEADERS',[_('ID'),_('Header'),_('Catalogue'),_('Edit'),_('Delete')]);
			$table->configure('HEADERS_WIDTH',[1,7,2,1,1]);
			$table->configure('ALIGN_CENTER',[TRUE,FALSE,TRUE,TRUE,TRUE]);
			$table->configure('BORDER',true);
			$table->configure('SIZE',9);
		}
		$form->add($table);	

		$btn_add_post = new control\button('btn_add_post');
		$btn_add_post->configure('LABEL',_('New post'));
		$btn_add_post->configure('TYPE','success');
		$btn_add_post->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','new_post']));
		$form->add($btn_add_post);
		
		return [_('Blog posts'),$form->draw()];
	}

	//this function show sure delete post form
	protected function view_sure_delete_post($post){
		$form = new control\form('blog_delete_post');

		$hid_id = new control\hidden('hid_id');
		$hid_id->configure('VALUE',$post->id);

		$lbl_msg = new control\label;
		$lbl_msg->configure('LABEL',sprintf(_('Are you sure for delete %s ?'),$post->header));

		$btn_delete = new control\button('btn_delete');
		$btn_delete->configure('LABEL',_('Delete'));
		$btn_delete->configure('TYPE','danger');
		$btn_delete->configure('P_ONCLICK_PLUGIN','blog');
		$btn_delete->configure('P_ONCLICK_FUNCTION','onclick_btn_delete_post');
		
		$btn_cancel = new control\button('btn_cancel');
		$btn_cancel->configure('LABEL',_('Cancel'));
		$btn_cancel->configure('HREF',core\general::create_url(['service','1','plugin','administrator','action','main','p','blog','a','list_posts']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btn_delete,1);
		$row->add($btn_cancel,11);

		$form->add_array([$hid_id,$lbl_msg,$row]);
		return [_('Delete catalogue'),$form->draw()];
	}

	//function for show blog post
	protected function view_show($post_core,$post_data,$settings){
		//create an object from raintpl class//
		$content_post = [];
		foreach($post_data as $post){
			$content_post['username'] = $post['username'];
			$content_post['date'] = $post['date'];
			$content_post['header'] = $post['header'];
			break;
		}
		$raintpl = new template\raintpl;
		//configure raintpl //
		$raintpl->configure('tpl_dir','plugins/defined/blog/tpl/');
		$raintpl->assign( "by", _('by'));
		$raintpl->assign( "posts", $post_data);
		$raintpl->assign( "username", $content_post['username']);
		//SHOW POST DATE
		$calendar = new calendar\calendar;
		$raintpl->assign( "post_date", sprintf(_('Posted on %s'),$calendar->cdate($settings['post_date_format'],$content_post['date'] )));
		$raintpl->assign( "links", '$links');
		//draw and return back content
		return [$content_post['header'],$raintpl->draw('blog_post', true )];
	}

	//function for show blog posts that stored in catalogue
	protected function view_show_cat($posts_data,$settings){

		return [1,1];
	}

	
}
