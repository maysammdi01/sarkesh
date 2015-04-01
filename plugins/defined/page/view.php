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
			$lbl_cat->configure('LABEL',$cat->language_name);
			$row->add($lbl_cat,1);
			
			$btn_edite = new control\button('btn_content_cats_edite');
			$btn_edite->configure('LABEL',_('Edit'));
			$btn_edite->configure('VALUE',$cat->id);
			$btn_edite->configure('HREF',core\general::createUrl(['service','1','plugin','administrator','action','main','p','blog','a','edite_cat','id',$cat->id]));
			$row->add($btn_edite,2);
			
			$btn_delete = new control\button('btn_content_cats_delete');
			$btn_delete->configure('LABEL',_('Delete'));
			$btn_delete->configure('HREF',core\general::createUrl(['service','1','plugin','administrator','action','main','p','blog','a','sure_delete_cat','id',$cat->id]));
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
		$btn_add_cats->configure('HREF',core\general::createUrl(['service','1','plugin','administrator','action','main','p','blog','a','add_cat']));
		$form->add($btn_add_cats);
		
		return [_('Catalogues'),$form->draw()];
	}
}
