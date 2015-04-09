<?php
namespace addon\plugin\page;
use \core\control as control;
use \core\cls\template as template;
use \core\cls\core as core;

trait view {
	
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
		$calendar = \core\cls\calendar\calendar::singleton();
		$raintpl->assign( "INFO", sprintf(_('Post by %s in %s'),$post->username,$calendar->cdate($settings->postDateFormat,$post->date)));		
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
    protected function viewNewPage($settings){
        $form = new control\form('frmNewPost');

        $txtTitle = new control\textbox('txtTitle');
        $txtTitle->label = _('Title:');
        $form->add($txtTitle);

        $txtBody = new control\textarea('txtBody');
        $txtBody->editor = true;
        $form->add($txtBody);

        return [_('New Page'), $form->draw()];
    }
}
