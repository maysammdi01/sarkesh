<?php
namespace addon\plugin\page;
use \core\control as control;
use \core\cls\template as template;
use \core\cls\core as core;

trait view {
	use \core\plugin\files\addons;
	/*
	 * show page with id
	 * @param object $post, post information
	 * @param array $settings, plugin settings
	 * @RETURN html content [title,body]
	 */
	protected function viewShow($post,$settings){
		$raintpl = template\raintpl::singleton();
		//configure raintpl //
		$raintpl->configure('tpl_dir', AppPath . '/plugins/defined/page/tpl/');
		//Assign variables
		$raintpl->assign( "TITLE", $post->title);
		$raintpl->assign( "BODY", $post->body);
        $hasImage = false;
        $fileAdr = '';
        $raintpl->assign( "image", '');
        if($post->photo != ''){
            $hasImage = true;
            $fileAdr = $this->getFileAddress($post->photo);
            $raintpl->assign( "image", $hasImage);
            $raintpl->assign( "fileAdr", $fileAdr);

        }
		$calendar = \core\cls\calendar\calendar::singleton();
        $infoString = '';
        if($settings->showAuthor == 1)
            $infoString = sprintf(_('Post by %s'),$post->username);
        if($settings->showDate == 1)
            $infoString .=  ' ' .sprintf(_('in %s'),$calendar->cdate($settings->postDateFormat,$post->date));
		$raintpl->assign( "INFO", $infoString);
		return [$post->title,$raintpl->draw('post',true)];
	}
	
	/*
	 * show list of catalogues
	 * @param array $cats , all catalogues information
	 * @RETURN html content [title,body]
	 */
	protected function viewCatalogues($cats){
		$form = new control\form('blog_cat_table');
		$table = new control\table('blog_cat_table');
		$counter = 0;
        if(!is_null($cats)) {
            foreach ($cats as $key => $cat) {
                $counter += 1;
                $row = new control\row('blog_cat_row');

                $lbl_id = new control\label('lbl');
                $lbl_id->configure('LABEL', $counter);
                $row->add($lbl_id, 1);

                $lbl_cat = new control\label('lbl');
                $lbl_cat->configure('LABEL', $cat->name);
                $row->add($lbl_cat, 1);

                $lbl_cat = new control\label('lbl');
                $lbl_cat->configure('LABEL', $cat->language_name);
                $row->add($lbl_cat, 1);

                $btn_edite = new control\button('btn_content_cats_edite');
                $btn_edite->configure('LABEL', _('Edit'));
                $btn_edite->configure('VALUE', $cat->id);
                $btn_edite->configure('HREF', core\general::createUrl(['service', 'administrator', 'load', 'page', 'editeCat', $cat->id]));
                $row->add($btn_edite, 2);

                $btn_delete = new control\button('btn_content_cats_delete');
                $btn_delete->configure('LABEL', _('Delete'));
                $btn_delete->configure('HREF', core\general::createUrl(['service', 'administrator', 'load', 'page', 'sureDeleteCat', $cat->id]));
                $btn_delete->configure('TYPE', 'danger');
                $row->add($btn_delete, 2);

                $table->add_row($row);
                $table->configure('HEADERS', [_('ID'), _('Name'), _('Localize'), _('Edit'), _('Delete')]);
                $table->configure('HEADERS_WIDTH', [1, 7, 2, 1, 1]);
                $table->configure('ALIGN_CENTER', [TRUE, FALSE, TRUE, TRUE, TRUE]);
                $table->configure('BORDER', true);
                $table->configure('SIZE', 9);
            }
            $form->add($table);
        }
        else{
            //catalogues not found
            $abelNotFound = new control\label(_('No catalogue added.first add a catalogue.'));
            $form->add($abelNotFound);
        }

		$btn_add_cats = new control\button('btn_add_cats');
		$btn_add_cats->configure('LABEL',_('Add new catalogue'));
		$btn_add_cats->configure('TYPE','success');
		$btn_add_cats->configure('HREF',core\general::createUrl(['service','administrator','load','page','newCat']));
		$form->add($btn_add_cats);
		
		return [_('Catalogues'),$form->draw()];
	}


    /*
	 * show form for add new catalogue
     * @param object $local, localize information
     * @param object $settings, plugin settings
	 * @RETURN html content [title,body]
	 */
    protected function viewNewCat($languages,$local){
        $form = new control\form('frmNewCatalogue');

        $txtName = new control\textbox('txtName');
        $txtName->label = _('Catalogue name');
        $txtName->place_holder = _('Catalogue name');
        $txtName->size = 4;
        $form->add($txtName);

        $btnAddCat = new control\button('btnAddCat');
        $btnAddCat->configure('LABEL',_('Add catalogue'));
        $btnAddCat->configure('TYPE','primary');
        $btnAddCat->p_onclick_plugin = 'page';
        $btnAddCat->p_onclick_function = 'btnOnclickAddCat';

        $ckbCanComment = new control\checkbox('ckbCanComment');
        $ckbCanComment->label = _('Allow users and guests for submit comment?');
        $form->add($ckbCanComment);

        $cobLang = new control\combobox('cobLang');
        $cobLang->configure('LABEL',_('Default users roll'));
        $cobLang->configure('HELP',_('New users get roll that you select in above.'));
        $cobLang->configure('TABLE',$languages);
        $cobLang->configure('COLUMN_VALUES','id');
        $cobLang->configure('COLUMN_LABELS','language_name');
        $cobLang->configure('SELECTED_INDEX',$local->id);
        $cobLang->configure('SIZE',3);
        $form->add($cobLang);

        $btn_cancel = new control\button('btn_cancel');
        $btn_cancel->configure('LABEL',_('Cancel'));
        $btn_cancel->configure('HREF',core\general::createUrl(['service','administrator','load','page','catalogues']));

        $row = new control\row;
        $row->configure('IN_TABLE',false);

        $row->add($btnAddCat,2);
        $row->add($btn_cancel,10);
        $form->add($row);

        return [_('New catalogue'),$form->draw()];
    }
    
    /*
	 * show form for delete catalogue
	 * @param object $cat, catalogue information
	 * @RETURN html content [title,body]
	 */
    protected function viewSureDeletCat($cat){
       $form = new control\form('frmSureDeletCat');
       
       $hidID = new control\hidden('hidID');
       $hidID->value = $cat->id;
       $form->add($hidID);
       
       $label = new control\label(sprintf(_('Are you sure for delete %s'),$cat->name));
       $form->add($label);
       
       $btnDelete = new control\button('btnDelete');
       $btnDelete->configure('LABEL',_('Yes, Delete'));
       $btnDelete->configure('TYPE','primary');
       $btnDelete->p_onclick_plugin = 'page';
       $btnDelete->p_onclick_function = 'btnOnclickDeleteCat';
        
       $btn_cancel = new control\button('btn_cancel');
       $btn_cancel->configure('LABEL',_('Cancel'));
       $btn_cancel->configure('HREF',core\general::createUrl(['service','administrator','load','page','catalogues']));

       $row = new control\row;
       $row->configure('IN_TABLE',false);

       $row->add($btnDelete,1);
       $row->add($btn_cancel,11);
       $form->add($row);
       
       return [sprintf(_('Delete %s'),$cat->name),$form->draw()];
    }
    
    /*
	 * edite catalogue form
	 * @param object $cat, catalogue information
	 * @param object $local, localize information
     * @param object $settings, plugin settings
	 * @RETURN html content [title,body]
	 */
    protected function viewEditeCat($cat,$languages,$local){
        $form = new control\form('frmNewCatalogue');
		
		$hidID = new control\hidden('hidID');
		$hidID->value = $cat->id;
		$form->add($hidID);
		
        $txtName = new control\textbox('txtName');
        $txtName->label = _('Catalogue name');
        $txtName->value = $cat->name;
        $txtName->place_holder = _('Catalogue name');
        $txtName->size = 4;
        $form->add($txtName);


        $btnAddCat = new control\button('btnEditeCat');
        $btnAddCat->configure('LABEL',_('Save changes'));
        $btnAddCat->configure('TYPE','primary');
        $btnAddCat->p_onclick_plugin = 'page';
        $btnAddCat->p_onclick_function = 'btnOnclickEditeCat';

        $ckbCanComment = new control\checkbox('ckbCanComment');
        $ckbCanComment->label = _('Allow users and guests for submit comment?');
        $ckbCanComment->checked = false;
        if($cat->canComment == 1)
			$ckbCanComment->checked = true;
        $form->add($ckbCanComment);

        $cobLang = new control\combobox('cobLang');
        $cobLang->configure('LABEL',_('Default users roll'));
        $cobLang->configure('HELP',_('New users get roll that you select in above.'));
        $cobLang->configure('TABLE',$languages);
        $cobLang->configure('COLUMN_VALUES','id');
        $cobLang->configure('COLUMN_LABELS','language_name');
        $cobLang->configure('SELECTED_INDEX',$cat->localize);
        $cobLang->configure('SIZE',3);
        $form->add($cobLang);

        $btn_cancel = new control\button('btn_cancel');
        $btn_cancel->configure('LABEL',_('Cancel'));
        $btn_cancel->configure('HREF',core\general::createUrl(['service','administrator','load','page','catalogues']));

        $row = new control\row;
        $row->configure('IN_TABLE',false);

        $row->add($btnAddCat,2);
        $row->add($btn_cancel,10);
        $form->add($row);

        return [sprintf(_('Edite %s'),$cat->name),$form->draw()];
	}
	
	 /*
     * show settings page
     * @param object $settings, plugin settings that stored in registry
     * @RETURN html content [title,body]
     */
    protected function viewSettings($settings){
		$form = new control\form('blog_settings');
		
        //show author of post
        $ckbShowAuthor = new control\checkbox('ckbShowAuthor');
		$ckbShowAuthor->configure('LABEL',_('Show author') );
		
		$ckbShowAuthor->configure('HELP',_('If checked,author of post show in posts and catlogues.'));
		if($settings->showAuthor == 1)
			$ckbShowAuthor->configure('CHECKED',TRUE);
		$form->add($ckbShowAuthor);
		
		
		//show date of post
        $ckbShowDate = new control\checkbox('ckbShowDate');
		$ckbShowDate->configure('LABEL',_('Show date') );
		$ckbShowDate->configure('HELP',_('If checked,date will showed in post content.'));
		if($settings->showDate == 1)
			$ckbShowDate->configure('CHECKED',TRUE);
		$form->add($ckbShowDate);
		//set number of post per page
		$cobPerPage = new control\combobox('cobPerPage');
        $cobPerPage->configure('LABEL',_('Posts per page'));
        $cobPerPage->configure('HELP',_('This option set number of post that can show per page.'));
        $cobPerPage->configure('SOURCE',[1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20]);
        $cobPerPage->configure('SELECTED_INDEX',$settings->PostPerPage);
        $cobPerPage->configure('SIZE',4);
        $form->add($cobPerPage);
		
        //add update and cancel buttons
		$btnUpdate = new control\button('btnUpdate');
		$btnUpdate->configure('LABEL',_('Update'));
		$btnUpdate->configure('P_ONCLICK_PLUGIN','page');
		$btnUpdate->configure('P_ONCLICK_FUNCTION','btnOnclickSaveSettings');
		$btnUpdate->configure('TYPE','primary');
		
		$btnCancel = new control\button('btnCancel');
		$btnCancel->configure('LABEL',_('Cancel'));
		$btnCancel->configure('HREF',core\general::createUrl(['service','administrator','load','administrator','dashboard']));
		
		$row = new control\row;
		$row->configure('IN_TABLE',false);
		
		$row->add($btnUpdate,1);
		$row->add($btnCancel,11);
		$form->add($row);  

		return [_('page settings'),$form->draw()];
	}

    /*
     * for submit new post
     * @param array $settings, plugin settings
     * @return array html content [ title,content]
     */
    protected function viewDoPage($settings,$cats,$page = null){
        $form = new control\form('frmNewPost');

        $txtTitle = new control\textbox('txtTitle');
        $txtTitle->label = _('Title:');	
        $txtTitle->PLACE_HOLDER = _('Your title in here!');

        $txtBody = new control\textarea('txtBody');
        $txtBody->label = _('Body:');
        $txtBody->editor = true;

        $txtTags = new control\textbox('txtTags');
        $txtTags->label = _('Tags:');
        $txtTags->help = _("seperate tags with ','.");

        $uplPhoto = new control\uploader('uplPhoto');
        $uplPhoto->label = _('Featured image');
        $uplPhoto->max_file_size = 65536 * 1024;
        $uplPhoto->help = _('Set featured image');

        $cobCatalogue = new control\combobox('cobCatalogue');
        $cobCatalogue->configure('LABEL',_('Catalogue'));
        $cobCatalogue->configure('TABLE',$cats);
        $cobCatalogue->configure('COLUMN_VALUES','id');
        $cobCatalogue->configure('COLUMN_LABELS','name');
        $cobCatalogue->configure('SIZE',3);
        
        $ckbPublish = new control\checkbox('ckbPublish');
        $ckbPublish->label = _('Publish page?');
        $ckbPublish->help = _('Uncheck for save page without publish for other.');
        $ckbPublish->checked = true;

		if(! is_null($page)){
			$hidID = new control\hidden('hidID');
			$hidID->value = $page->id;
			$form->add($hidID);
			$txtTitle->value = $page->title;
			$txtBody->value = $page->body;
			$txtTags->value = $page->tags;
			$uplPhoto->value = $page->photo;
			$cobCatalogue->selected_index = $page->catalogue;
			$ckbPublish->checked = true;
			if($page->publish == 1)
				$ckbPublish->checked = true;
		}
		$form->addArray([$txtTitle,$txtBody,$txtTags,$uplPhoto,$cobCatalogue,$ckbPublish]);
        //add update and cancel buttons
        $btnSubmit = new control\button('btnSubmit');
        $btnSubmit->configure('LABEL',_('Submit'));
        $btnSubmit->configure('P_ONCLICK_PLUGIN','page');
        $btnSubmit->configure('P_ONCLICK_FUNCTION','btnOnclickSubmitPage');
        $btnSubmit->configure('TYPE','primary');

        $btnCancel = new control\button('btnCancel');
        $btnCancel->configure('LABEL',_('Cancel'));
        $btnCancel->configure('HREF',core\general::createUrl(['service','administrator','load','page','listPages']));

        $row = new control\row;
        $row->configure('IN_TABLE',false);

        $row->add($btnSubmit,1);
        $row->add($btnCancel,11);
        $form->add($row);
        return [_('New Page'), $form->draw()];
    }
    
    /*
     * this function show message to user to add catalogue
     * @return array [title,msg]
     */
    protected function viewMsgAddCatalogue(){
		$form = new control\form('frmMsgAddCat');
		$label = new control\label(_('Please add catalogue before submit new page!'));
		$form->add($label);
		
		$btnAddCat = new control\button('btnAddCat');
		$btnAddCat->label = _('Add catalogue');
		$btnAddCat->type = 'default';
		$btnAddCat->href = core\general::createUrl(['service','administrator','load','page','newCat']);
		$form->add($btnAddCat);
		return [_('Error!'),$form->draw()];
	}
	
	 /*
	 * show list of all pages
	 * @param array $posts, pages for show
	 * $param boolean $hasPre, has priveus page
	 * @param boolean $hasNext, has next page
	 * @param integer $pageNum, page number
	 * @RETURN html content [title,body]
	 */
    protected function viewListPages($posts,$hasPre,$hasNext,$pageNum){
        $form = new control\form('blog_list_posts');
        
        $btn_add_post = new control\button('btn_add_post');
		$btn_add_post->configure('LABEL',_('New post'));
		$btn_add_post->configure('TYPE','success');
		$btn_add_post->configure('HREF',core\general::createUrl(['service','administrator','load','page','newPage']));
		$form->add($btn_add_post);
		
		$table = new control\table('blog_list_posts');
		$counter = 0;
		foreach($posts as $key=>$post){
			$counter += 1;
			$row = new control\row('blog_cat_row');
			
			$lbl_id = new control\label('lbl');
			$lbl_id->configure('LABEL',$counter);
			$row->add($lbl_id,1);
			
			$btn_header = new control\button('lbl');
			$btn_header->configure('LABEL',$post->title);
			$btn_header->configure('TYPE','link');
			$btn_header->configure('HREF',core\general::createUrl(['page','show',$post->adr]));
			$row->add($btn_header,1);

			$lbl_loc = new control\label('lbl');
			$lbl_loc->configure('LABEL',$post->name);
			$row->add($lbl_loc,1);
			
			$btn_edite = new control\button('btn_content_cats_edite');
			$btn_edite->configure('LABEL',_('Edit'));
			$btn_edite->configure('HREF',core\general::createUrl(['service','administrator','load','page','editePost',$post->id]));
			$row->add($btn_edite,2);
			
			$btn_delete = new control\button('btn_content_cats_delete');
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('HREF',core\general::createUrl(['service','administrator','load','page','sureDeletePage',$post->id]));
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
		
		$row = new control\row;
        $row->configure('IN_TABLE',false);
		if($hasPre){
			//add update and cancel buttons
			$btnPre = new control\button('btnPre');
			$btnPre->configure('LABEL',_('Privius'));
			$btnPre->configure('HREF',core\general::createUrl(['service','administrator','load','page','listPages',$pageNum - 1]));
			$row->add($btnPre,6);
		}
		if($hasNext){
			$btnNext = new control\button('btnNext');
			$btnNext->configure('LABEL',_('Next'));
			$btnNext->configure('HREF',core\general::createUrl(['service','administrator','load','page','listPages',$pageNum + 1]));
			$row->add($btnNext,6);
		}
		$form->add($row);
		return [_('Blog posts'),$form->draw()];
    }
    
    /*
	 * show page for delete page
	 * @param object $post, post information
	 * @RETURN html content [title,body]
	 */
    protected function viewSureDeletPost($post){
        $form = new control\form('frmSureDeletCat');
       
       $hidID = new control\hidden('hidID');
       $hidID->value = $post->id;
       $form->add($hidID);
       
       $label = new control\label(sprintf(_('Are you sure for delete %s'),$post->title));
       $form->add($label);
       
       $btnDelete = new control\button('btnDelete');
       $btnDelete->configure('LABEL',_('Yes, Delete'));
       $btnDelete->configure('TYPE','primary');
       $btnDelete->p_onclick_plugin = 'page';
       $btnDelete->p_onclick_function = 'btnOnclickDeletePost';
        
       $btn_cancel = new control\button('btn_cancel');
       $btn_cancel->configure('LABEL',_('Cancel'));
       $btn_cancel->configure('HREF',core\general::createUrl(['service','administrator','load','page','listPages']));

       $row = new control\row;
       $row->configure('IN_TABLE',false);

       $row->add($btnDelete,1);
       $row->add($btn_cancel,11);
       $form->add($row);
       
       return [sprintf(_('Delete %s'),$post->name),$form->draw()]; 
    }
    
    /*
	 * show pages in catalogue
	 * @param array $pages, all pages in catalogue
	 * @param object $cat, catalogue infoemation
	 * @RETURN html content [title,body]
	 */
    protected function viewShowCtatlogePages($pages,$cat){
		$raintpl = template\raintpl::singleton();
		//configure raintpl //
		$raintpl->configure('tpl_dir', AppPath . '/plugins/defined/page/tpl/');
		//Assign variables
		$raintpl->assign( "pages", $pages);
		$raintpl->assign( "baseAdr", core\general::createUrl(['page','show']));
		
		return [$cat->name,$raintpl->draw('catalogue',true)];
    }
}
