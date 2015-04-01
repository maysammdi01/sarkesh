<?php
namespace addon\plugin\page;
use core\cls\browser as browser;
use core\cls\network as network;
use core\cls\core as core;
use core\cls\db as db;

class module{
	use view;
	use addons;
	
	/*
	 * construct
	 */
	function __construct(){}
	
	//this function return back menus for use in admin area
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','page','newPage']);
		array_push($menu,[$url, _('New Page')]);
		$url = core\general::createUrl(['service','administrator','load','page','listPages']);
		array_push($menu,[$url, _('List Pages')]);
		$url = core\general::createUrl(['service','administrator','load','page','catalogues']);
		array_push($menu,[$url, _('Catalogues')]);
		$url = core\general::createUrl(['service','administrator','load','page','settings']);
		array_push($menu,[$url, _('Page Settings')]);

		$ret = [];
		array_push($ret, ['<span class="glyphicon glyphicon-book" aria-hidden="true"></span>' , _('Pages')]);
		array_push($ret,$menu);
		return $ret;
	}
	
	/*
	 * show page with id
	 * @RETURN html content [title,body]
	 */
	protected function moduleShow(){	
		if(defined('PLUGIN_OPTIONS')){
			$orm = db\orm::singleton();
			if($orm->count('page_posts','adr=?',[PLUGIN_OPTIONS]) != 0){
				$post = $orm->exec('SELECT u.username,p.title,p.body,p.date FROM page_posts p INNER JOIN users u ON u.id = p.username WHERE p.adr=?',[PLUGIN_OPTIONS],SELECT_ONE_ROW);
				$registry = core\registry::singleton();
				return $this->viewShow($post,$registry->getPlugin('page'));
			}
		}
		return browser\msg::pageNotFound();
	}
	
	/*
	 * show list of catalogues
	 * @RETURN html content [title,body]
	 */
	protected function moduleCatalogues(){
		if($this->hasAdminPanel()){
			$orm = db\orm::singleton();
			$cats = $orm->exec("SELECT c.id,l.language_name,c.name,c.canComment,c.adr FROM page_catalogue c INNER JOIN localize l ON c.localize = l.id;",[],SELECT);
			return $this->viewCatalogues($cats);
		}
		return browser\msg::pageAccessDenied();	
	}
	
}
