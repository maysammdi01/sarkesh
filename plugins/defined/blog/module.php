<?php
namespace addon\plugin\blog;
use addon\plugin as plugin;
use \core\plugin as coreplugin;
use \core\cls\db as db;
use \core\cls\core as core;
use \core\cls\browser as browser;

class module extends view{
	private $users;
	private $msg;

	function __construct(){
		$this->users= coreplugin\users::singleton();
		$this->msg= coreplugin\msg::singleton();
	}
	//this function is for set basic settings of blog plugins
	protected function module_settings(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get all localizes
			$registry = core\registry::singleton();
			$settings = $registry->get_plugin('blog');
			$content_types = db\orm::find('contentcatalogue');
			$current_type = db\orm::findOne('contentcatalogue','id=?',[$settings['content_type']]);
			return $this->view_settings($settings,$current_type,$content_types);
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	//this function save blog settings
	protected function module_onclick_btn_save_settings($e){
		if($this->users->has_permission('administrator_admin_panel')){
			$registry = core\registry::singleton();
			$registry->set('blog','post_per_page',$e['cob_per_page']['SELECTED']);
			$registry->set('blog','content_type',$e['cob_content_type']['SELECTED']);
			$show_author = 0;
			if($e['ckb_show_author']['CHECKED'] == '1') $show_author = 1;
			$registry->set('blog','show_author',$show_author);
			$show_date = 0;
			if($e['ckb_show_date']['CHECKED'] == '1') $show_date = 1;
			$registry->set('blog','show_date',$show_date);

			//save settings for that who can comment
			$canComment = 0;
			if($e['raditGuestCan']['CHECKED'] == 1) $canComment = 1;
			$registry->set('blog','canComment',$canComment);
			return $this->msg->successfull_modal($e,'N');
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//this function show cats for manage
	protected function module_list_cats(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get list of catalogues
			$cats = db\orm::findAll('blogcats');
			return $this->view_list_cats($cats);
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}

	}

	protected function module_add_cats(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get localize
	        $localize = new core\localize;
	        $locals = $localize->get_all();
	        
	        //get default language
	        $default_language = $localize->get_default_language();
			return $this->view_add_cats($default_language,$locals);
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	protected function module_onclick_btn_add_cat($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('txt_name',$e) && array_key_exists('cob_language',$e)){
				$cat = db\orm::dispense('blogcats');
				$cat->name = $e['txt_name']['VALUE'];
				$cat->machin_name = $e['txt_name']['VALUE'];
				$cat->localize = $e['cob_language']['SELECTED'];
				db\orm::store($cat);
				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);

			}
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//function for show edite cataloguue form 
	protected function module_edite_cat(){
		if($this->users->has_permission('administrator_admin_panel')){
			//check for that id is set
			if(isset($_REQUEST['id'])){
				if(db\orm::count('blogcats','id=?',[$_REQUEST['id']]) != 0){
					$localize = new core\localize;
	        		$locals = $localize->get_all();
	        		$cat = db\orm::findOne('blogcats','id=?',[$_REQUEST['id']]);
	        		return $this->view_edite_cat($cat,$locals);
				}
			}
			return $this->msg->error();
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}
	
	//function for edite catalogue
	protected function module_onclick_btn_edite_cat($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('txt_name',$e) && array_key_exists('cob_language',$e) && array_key_exists('hid_id',$e)){
				if($e['txt_name']['VALUE'] == '' || $e['cob_language']['SELECTED'] == ''){
					return $this->msg->not_complete_modal($e);
				}
				$cat = db\orm::load('blogcats',$e['hid_id']['VALUE']);
				$cat->name = $e['txt_name']['VALUE'];
				$cat->machin_name = $e['txt_name']['VALUE'];
				$cat->localize = $e['cob_language']['SELECTED'];
				db\orm::store($cat);

				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);

			}
			return $this->msg->error_modal($e);
		}
		else{
			//no permission to access this page
			return $this->msg->modal_no_permission($e);
		}
	}

	//show sure delete catalogue
	protected function module_sure_delete_cat(){
		if($this->users->has_permission('administrator_admin_panel')){
			//check for that id is set
			if(isset($_REQUEST['id'])){
				if(db\orm::count('blogcats','id=?',[$_REQUEST['id']]) != 0){
	        		$cat = db\orm::findOne('blogcats','id=?',[$_REQUEST['id']]);
	        		return $this->view__sure_delete_cat($cat);
				}
			}
			return $this->msg->error();
		}
		else{
			//no permission to access this page
			return $this->msg->access_denied();
		}
	}

	//btn event handel for delete catalogue
	protected function module_onclick_btn_delete_cat($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('hid_id',$e)){
				db\orm::exec('DELETE FROM blogcats WHERE id=?',[$e['hid_id']['VALUE']]);
				return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_cats']);
			}
			return $this->msg->error_modal($e);
		}
		//no permission to access this page
		return $this->msg->modal_no_permission($e);
	}

	//show list of catalogue in block
	protected function module_block_show_catalog(){
		//get catalogues
		$localize = core\localize::singleton();
		$local = $localize->get_localize();
		$cats = db\orm::find('blogcats','localize = ?',[$local->language]);
		$ccats = [];
		foreach($cats as $cat){
			array_push($ccats, ['label' => $cat->name,'url' => core\general::create_url(array('plugin','blog','action','show_cat','id',$cat->id)	)]);
		}
		$menus = new \core\plugin\menus;
		return [_('Blog catalogues'),$menus->create_menu($ccats,0,FALSE)];
	}

	protected function module_new_post(){
		if($this->users->has_permission('administrator_admin_panel')){
			//get default type for publish
			$registry = core\registry::singleton();
			//get publish page from content plugin
			$content = new \addon\plugin\content;
			$content_elements = $content->get_form_submit($registry->get('blog','content_type'));
			$blogcats = db\orm::findAll('blogcats');
			return $this->view_new_post($content_elements,$blogcats);
		}
		//no permission to access this page
		return $this->msg->access_denied();
	}

	//THIS FUNCTION SUBMIT BLOG POST AND SAVE THAT
	protected function module_onclick_btn_new_post_submit($e){
		if($this->users->has_permission('administrator_admin_panel')){
			//save data in content plugin
			$content = new \addon\plugin\content;
			//save other in blogposts table
			$post = db\orm::dispense('blogposts');
			$post->content = $content->save_content($e,$e['cob_cats']['SELECTED']);
			$post->catalogue = $e['cob_cats']['SELECTED'];
			$post->tags = $e['txt_tags']['VALUE'];

			db\orm::store($post);
			return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_posts']);
		}
		//no permission to access this page
		return $this->msg->modal_no_permission($e);
	}

	//this function show list of posts in administrator area
	public function module_list_posts(){
		if($this->users->has_permission('administrator_admin_panel')){
			$result = db\orm::getAll("SELECT bp.id,cc.header,cc.date,cc.user,bc.name AS 'cat_name' FROM blogposts bp INNER JOIN contentcontent cc ON bp.content=cc.id INNER JOIN blogcats bc ON bc.id=bp.catalogue ORDER BY bp.id DESC;");
			$posts = db\orm::convertToBeans( 'post', $result );
			return $this->view_list_posts($posts);
		}
		//no permission to access this page
		return $this->msg->access_denied();
	}

	//this function show sure delete post form
	protected function module_sure_delete_post(){
		if($this->users->has_permission('administrator_admin_panel')){
			if(isset($_REQUEST['id'])){
				if(db\orm::count('blogposts','id=?',[$_REQUEST['id']]) != 0){
					$result = db\orm::getAll("SELECT bp.id,cc.header,cc.date,cc.user,bc.name AS 'cat_name' FROM blogposts bp INNER JOIN contentcontent cc ON bp.content=cc.id INNER JOIN blogcats bc ON bc.id=bp.catalogue WHERE bp.id=?;",[$_REQUEST['id']]);
					$posts = db\orm::convertToBeans( 'post', $result );
					$post = [];
					foreach($posts as $p) $post = $p;
					return $this->view_sure_delete_post($post);
				}
			}
			
		}
		//no permission to access this page
		return $this->msg->access_denied();
	}

	//FUNCTION FOR DELETE BLOG POST WITH ONCLICK EVENT
	protected function module_onclick_btn_delete_post($e){
		if($this->users->has_permission('administrator_admin_panel')){
			if(array_key_exists('hid_id', $e)){
				db\orm::exec('DELETE FROM blogposts WHERE id=?',[$e['hid_id']['VALUE']]);
			}
			return $this->msg->successfull_modal($e,['service','1','plugin','administrator','action','main','p','blog','a','list_posts']);
		}
		//no permission to access this page
		return $this->msg->modal_no_permission($e);
	}

	//this function show blog post
	protected function module_show(){
		if(isset($_REQUEST['id'])){
			if(db\orm::count('blogposts','id=?',[$_REQUEST['id']]) != 0){
				//get post from database
				$post_core = db\orm::load('blogposts',$_REQUEST['id']);
				$content = new \addon\plugin\content;
				$post_data = $content->get_content($post_core->content);
				//get all comments
				$comments = db\orm::find('blogcomments','blogpost=? ORDER BY id DESC',[$_REQUEST['id']]);
				//get settings of blog plugin
				$registry = core\registry::singleton();
				return $this->view_show($post_core,$post_data,$registry->get_plugin('blog'),$comments);
			}
		}
		//not found
		return core\router::jump_page(404);
	}

	//function for show blog catalogue posts
	protected function module_show_cat(){
		if(isset($_REQUEST['id'])){
			if(db\orm::count('blogcats','id=?',[$_REQUEST['id']]) != 0){
				$page_num = 1;
				if(isset($_REQUEST['page'])){
					$page_num = $_REQUEST['page'];
				}
				//get post from database
				$registry = core\registry::singleton();
				//get post per page
				$per_page = $registry->get('blog','post_per_page');
				$start = ($page_num - 1) * $per_page;
				$postCount = db\orm::count('blogposts','catalogue=?;',[$_REQUEST['id']]);
				if($postCount != 0){
					$all_posts = db\orm::find('blogposts','catalogue=? ORDER BY id DESC limit ' . $start .',' . $per_page,[$_REQUEST['id']]);
					$next_start = $start + $per_page;
					$result = db\orm::getAll('SELECT id FROM blogposts WHERE catalogue=? limit ' . $next_start .',' . $per_page,[$_REQUEST['id']]);
					$next_page_post_count = count($result);
					$with_next = true;
					$with_back = true;
					if( $next_page_post_count == 0) $with_next = false;
					if($page_num == 1) $with_back = false;
					$posts_catalogue = db\orm::load('blogcats',$_REQUEST['id']);
					$content = new \addon\plugin\content;
					//get settings of blog plugin
					
					$posts = [];
					foreach($all_posts as $post){
						array_push($posts, $content->get_content($post->content));
					}
					return $this->view_show_cat($posts,$registry->get_plugin('blog'),$posts_catalogue,$all_posts,$with_next,$with_back,$page_num);
				}
				$posts_catalogue = db\orm::load('blogcats',$_REQUEST['id']);
				return [$posts_catalogue->name,''];
			}
		}
		//not found
		return core\router::jump_page(404);
	}

	/*
	 * save comment in database
	 * @param:elements on browser
	 * return: elements
	 */
	public function ModuleOnclickBtnSubmitComment($e){
		if(isset($e['hidPostID']['VALUE'])){
			if($e['txtComment']['VALUE'] != ''){

				if(db\orm::count('blogcomments','blogpost=?',[$e['hidPostID']['VALUE']])){
					if(isset($e['hidPostID'])){
						//is registered user
						$users = new coreplugin\users\module;
						if($users->isLogedin()){
							$userInfo = $users->getInfo();
							$comment = db\orm::dispense('blogcomments');
							$comment->username = $userInfo->username;
							$comment->comment = $e['txtComment']['VALUE'];
							$comment->email = $userInfo->email;
							$comment->date = time();
							$comment->blogpost = $e['hidPostID']['VALUE'];
							$comment->approve = 1;
							db\orm::store($comment);
							$e['RV']['MODAL'] = browser\page::show_block(_('Successfull'), _('Your comment successfuly submited.'), 'MODAL','type-success');
							$e['RV']['JUMP_AFTER_MODAL'] = 'R';
						}
					}
					elseif(isset($e['']) & isset($e[''])){
						return $this->msg->not_complete_modal($e);
						if($e['txtUsername']['VALUE'] != '' &&  $e['txtEmail']['VALUE'] != ''){
							//user is guest
							$registry = core\registry::singleton();
							if($registry->get('blog','canComment') == '1'){
								//guest can comment
								$comment = db\orm::dispense('blogcomments');
								$comment->blogpost = $comment->blogpost = $e['hidPostID']['VALUE'];
								$comment->name = $e['txtUsername']['VALUE'];
								$comment->username = 0;
								$comment->email = $e['txtEmail']['VALUE'];
								$comment->comment = $e['txtComment']['VALUE'];
								$comment->date = time();
								$comment->approve = 0;
								db\orm::store($comment);
								$e['RV']['MODAL'] = browser\page::show_block(_('Successfull'), _('Your comment was submited.waiting for administrators for approve that.'), 'MODAL','type-success');
								$e['txtUsername']['VALUE'] = '';
								$e['txtEmail']['VALUE'] = '';
								$e['txtComment']['VALUE'] = '';
							}
						}
					}
				}
			}
			else{
				//show fill blanks
				return $this->msg->not_complete_modal($e);
			}
		}
		return $e;
	}
		
}

