<?php
namespace addon\plugin\blog;
use \core\control as control;
use \core\cls\core as core;
use \core\cls\template as template;
use \core\cls\calendar as calendar;

class view{
	
	protected function view_settings($settings,$current_type,$content_types){

		$form = new control\form('blog_settings');
		//for select content type that user want to work with that
		$cob_content_type = new control\combobox('cob_content_type');
        $cob_content_type->configure('LABEL',_('Content type'));
        $cob_content_type->configure('HELP',_('blog plugin work with selected content type that you set in this combobox.you can manage content types from content plugin.'));
        $cob_content_type->configure('TABLE',$content_types);
        $cob_content_type->configure('COLUMN_VALUES','id');
        $cob_content_type->configure('COLUMN_LABELS','name');
        $cob_content_type->configure('SELECTED_INDEX',$current_type->id);
        $cob_content_type->configure('SIZE',4);
        $form->add($cob_content_type);

        //show author of post
        $ckb_show_author = new control\checkbox('ckb_show_author');
		$ckb_show_author->configure('LABEL',_('Show author') );
		//$ckb_show_author->configure('SWITCH',TRUE);
		$ckb_show_author->configure('HELP',_('If checked,author of post show in posts and catlogues.'));
		if($settings['show_author'] == 1){
			$ckb_show_author->configure('CHECKED',TRUE);
		}
		$form->add($ckb_show_author);
		//set number of post per page
		$cob_per_page = new control\combobox('cob_per_page');
        $cob_per_page->configure('LABEL',_('Posts per page'));
        $cob_per_page->configure('HELP',_('This option set number of post that can show per page.'));
        $cob_per_page->configure('SOURCE',[1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20]);
        $cob_per_page->configure('SELECTED_INDEX',$settings['post_per_page']);
        $cob_per_page->configure('SIZE',4);
        $form->add($cob_per_page);

        $radCanComment = new control\radiobuttons('radCanComment');
		$radCanComment->configure('LABEL',_('Who can comment on blog posts?'));

		$raditJustUsers = new control\radioitem('raditJustUsers');
		$raditJustUsers->configure('LABEL',_('Users only'));
		if($settings['canComment'] == 0){
			$raditJustUsers->configure('CHECKED',TRUE);
		}
		$radCanComment->add($raditJustUsers);
			
		$raditGuestCan  = new control\radioitem('raditGuestCan');
		$raditGuestCan->configure('LABEL',_('Registered users and guests.(Administrators can manage guest comments)'));
		if($settings['canComment'] == 1){
			$raditGuestCan->configure('CHECKED',TRUE);
		}
		$radCanComment->add($raditGuestCan);
		$form->add($radCanComment);

		//show date of post
        $ckb_show_date = new control\checkbox('ckb_show_date');
		$ckb_show_date->configure('LABEL',_('Show date') );
		//$ckb_show_date->configure('SWITCH',TRUE);
		$ckb_show_date->configure('HELP',_('If checked,date will showed in post content.'));
		if($settings['show_date'] == 1){
			$ckb_show_date->configure('CHECKED',TRUE);
		}
		$form->add($ckb_show_date);
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
	protected function view_show($post_core,$post_data,$settings,$comments){
		
		$content_post = [];
		foreach($post_data as $post){
			$content_post['username'] = $post['username'];
			$content_post['date'] = $post['date'];
			$content_post['header'] = $post['header'];
			break;
		}
		//create an object from raintpl class//
		$raintpl = new template\raintpl;
		//configure raintpl //
		$raintpl->configure('tpl_dir','plugins/defined/blog/tpl/');
		$raintpl->assign( "settings", $settings);
		$raintpl->assign( "show_header", FALSE);
		$raintpl->assign( "by", _('by'));
		$raintpl->assign( "posts", $post_data);
		$raintpl->assign( "username", $content_post['username']);
		//SHOW POST DATE
		$calendar = new calendar\calendar;
		$raintpl->assign( "post_date", sprintf(_('Posted on %s'),$calendar->cdate($settings['post_date_format'],$content_post['date'] )));
		$raintpl->assign( "header", $content_post['header']);
		$raintpl->assign( "post_url", core\general::create_url(['plugin','blog','action','show','id',$post_core->id]));
		//draw and return back content
		$blogPost = $raintpl->draw('blog_post', true );

		$raintpl->assign( "leaveCommentLabel", _('Leave a comment!'));
		$formBlogLeaveComment = new control\form('formBlogLeaveComment');

		#show submit form for comments
		$users = new \core\plugin\users\module;
		$userInfo = $users->getInfo();
		$showSubmitForm = false;

		//save blog id in form
		$hidFormID = new control\hidden('hidPostID');
		$hidFormID->configure('VALUE',$post_core->id);
		$formBlogLeaveComment->add($hidFormID);
		if($users->isLogedin()){
			$showSubmitForm = true;
			$hidId = new control\hidden('hidUserId');
			$hidId->configure('VALUE',$userInfo->id);
			$formBlogLeaveComment->add($hidId);
			$lblUsername = new control\label(sprintf(_('You are logedin as %s.'),$userInfo->username));
			$formBlogLeaveComment->add($lblUsername);
		}
		elseif($settings['canComment'] == 1){
			
			$showSubmitForm = true;
			$row = new control\row;
			$row->configure('IN_TABLE',false);

			$txtUsername = new control\textbox('txtUsername');
			$txtUsername->configure('ADDON',_('Name'));
			$row->add($txtUsername,6);

			$txtEmail = new control\textbox('txtEmail');
			$txtEmail->configure('ADDON',_('Email'));
			$row->add($txtEmail,6);

			$formBlogLeaveComment->add($row);
		}
		if($showSubmitForm){

			$txtComment = new control\textarea('txtComment');
			$txtComment->configure('EDITOR',FALSE);
			$txtComment->configure('LABEL',_('Comment:'));
			$txtComment->configure('ROWS',4);
			$formBlogLeaveComment->add($txtComment);

			$btnSubmit = new control\button('btnSubmit');
			$btnSubmit->configure('LABEL',_('Submit'));
			$btnSubmit->configure('TYPE','primary');
			$btnSubmit->configure('P_ONCLICK_PLUGIN','blog');
			$btnSubmit->configure('P_ONCLICK_FUNCTION','onclickBtnSubmitComment');
			$formBlogLeaveComment->add($btnSubmit);
		}
		$raintpl->assign( "canComment", $showSubmitForm);
		$raintpl->assign( "form", $formBlogLeaveComment->draw());
		#perpare comments
		$perComments = [];
		$calendar = new calendar\calendar;
		foreach($comments as $comment){
			$com = [];
			$com['comment'] = $comment->comment;
			$com['username'] = $comment->username;
			$com['date'] = $calendar->cdate($settings['post_date_format'],$comment->date );
			array_push($perComments, $com);
		}
		$raintpl->assign( "comments",$perComments);
		$blogPost .= $raintpl->draw('blog_comments', true );

		return [$content_post['header'],$blogPost];
	}

	//function for show blog posts that stored in catalogue
	protected function view_show_cat($posts,$settings,$cat,$all_posts,$with_next,$with_back,$page_num){
		//create an object from raintpl class//
		$raintpl = new template\raintpl;
		$calendar = new calendar\calendar;
		//configure raintpl //
		$raintpl->configure('tpl_dir','plugins/defined/blog/tpl/');
		$raintpl->assign( "by", _('by'));
		$raintpl->assign( "settings", $settings);
		$raintpl->assign( "show_header", TRUE);
		$content_post = [];
		//SHOW POST DATE
		$cat_content = '';
		foreach ($posts as $key => $post_data) {
			foreach($post_data as $post){
				$content_post['username'] = $post['username'];
				$content_post['date'] = $post['date'];
				$content_post['header'] = $post['header'];
				break;
			}
			$raintpl->assign( "posts", $post_data);
			$raintpl->assign( "username", $content_post['username']);
			$raintpl->assign( "header", $content_post['header']);
			$key_all_post = key($all_posts);
			$po = $all_posts[$key_all_post];
			$raintpl->assign( "post_url", core\general::create_url(['plugin','blog','action','show','id',$po->id]));
			next($all_posts);
			$raintpl->assign( "post_date", sprintf(_('Posted on %s'),$calendar->cdate($settings['post_date_format'],$content_post['date'] )));
			$cat_content .= $raintpl->draw('blog_post', true );
		}
		$raintpl->assign( "with_next", $with_next);
		$raintpl->assign( "with_back", $with_back);
		$raintpl->assign( "back_label", _('Older &rarr;'));
		$raintpl->assign( "next_label", _('&larr; Newer'));
		$raintpl->assign( "pre_url", core\general::create_url(['plugin','blog','action','show_cat','id',$po->catalogue,'page',$page_num - 1]));
		$raintpl->assign( "next_url", core\general::create_url(['plugin','blog','action','show_cat','id',$po->catalogue,'page',$page_num + 1]));
		$cat_content .= $raintpl->draw('blog_nav', true );
		
		//draw and return back content
		return [$cat->name,$cat_content];
	}
}
