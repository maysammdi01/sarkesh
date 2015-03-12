<?php
namespace core\plugin\menus;
use \core\cls\db as db;
use \core\cls\browser as browser;
class event{
	use addons;
	function __construct(){}
	
	/*
	 * save add or update new menu
	 * @param array $e,form properties
	 * @return array $e,form properties
	 */
	public function onclickBtnDoMenu($e){
		if($this->hasAdminPanel()){
			if($e['txtName']['VALUE'] != '' && $e['txtHeader']['VALUE'] != '' ){
				$orm = db\orm::singleton();
				$menu = $orm->dispense('menus');
				$update = false;
				if(array_key_exists('hidID', $e)){
					//edite mode
					//check for that menu is exists
					if($orm->count('menus','id=?',[$e['hidID']['VALUE']]) != 0){
						$menu = $orm->findOne('menus','id=?',[$e['hidID']['VALUE']]);
						$update = true;
					}
					else{
						return $this->msg->error_message_modal(true,$e);
					}
				}
				
				$menu->name = $e['txtName']['VALUE'];
				$menu->localize = $e['cobLang']['SELECTED'];
				$menu->header = $e['txtHeader']['VALUE'];

				$menu->horiz = 0;
				if($e['ckbHorizontal']['CHECKED'] == 1){
					$menu->horiz = 1;
				}

				$menu->show_header = 0;
				if($e['ckbShowHeader']['CHECKED'] == 1){
					$menu->show_header = 1;
				}
				$menuID = $orm->store($menu);
				//add menu to blocks
				if(!$update){
						$block = $orm->dispense('blocks');
						$menus_plg = $orm->findOne('plugins','name=?',['menus']);
						$block->plugin = $menus_plg->id;
						$block->name = $menu->name;
						$block->value = $menuID;
						$block->visual = 1;
						$block->position = 'Off';
						$block->show_header = 0;
						$block->handel = 'draw_menu';
						$orm->store($block);

				}

				return browser\msg::modalSuccessfull($e,['service','administrator','load','menus','listMenus']);
			}
			
			return browser\msg::modalNotComplete($e);
		}
		return browser\msg::modalNoPermission($e);
	}
	
	/*
	 * Delet emnu from database
	 * @param array $e,form properties
	 * @return array $e,form properties
	 */
	public function onclickBtnDeleteMenu($e){
		if($this->hasAdminPanel()){
			//first delete block
			$orm = db\orm::singleton();
			$plugin = $orm->findOne('plugins',"name='menus'");
			$orm->exec("DELETE FROM blocks WHERE plugin=? and handel='draw_menu' and value=?;",[$plugin->id,$e['hidID']['VALUE']],NON_SELECT);
			//DELETE MENU
			$orm->exec("DELETE FROM menus WHERE id=?;",[$e['hidID']['VALUE']],NON_SELECT);
			//DELETE LINKS
			$orm->exec("DELETE FROM links WHERE ref_id=?;",[$e['hidID']['VALUE']],NON_SELECT);
			return browser\msg::modalSuccessfull($e,['service','administrator','load','menus','listMenus']);
		}
		return browser\msg::modalNoPermission($e);
	}
	
}
