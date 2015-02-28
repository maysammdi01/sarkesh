<?php
namespace core\plugin\administrator;
use \core\control as control;
use \core\cls\browser as browser;
use \core\cls\template as template;
use \core\cls\core as core;

class view {
	
	/*
	 * construct
	 */
	function __construct(){
		
	}
	
	/*
	 * load basic administrator panel
	 * @param array(2) $content, (0=>title, 1=>content)
	 * @param array $user, user info
	 * @param array $settings, plugin settings
	 * @return string, html content
	 */
	protected function viewLoad($menus,$content,$user,$settings){
		$raintpl = template\raintpl::singleton();
		//configure raintpl //
		$raintpl->configure('tpl_dir', AppPath . '/plugins/system/administrator/tpl/');
		
		browser\page::addHeader('<link href="./plugins/system/administrator/style/core_content.css" rel="stylesheet">');
		$localize = core\localize::singleton();
		$local = $localize->localize();
		if($local->direction = 'rtl'){
			browser\page::addHeader('<link href="./plugins/system/administrator/style/rtl_core_content.css" rel="stylesheet">');
		}
		//Assign variables
		$raintpl->assign( "menu", $menus);
		$raintpl->assign( "content", $content[0]);
		$raintpl->assign( "title", $content[1]);
		
		$raintpl->assign( "user_logout", _('Log Out')	);
		$raintpl->assign( "user_logout_url", core\general::createUrl(array('plugin','users','action','btn_logout_onclick')	)	);
		$raintpl->assign( "user_name", $user['username']	);
		$raintpl->assign( "powered_by", _('Powered by SarkeshMVC')	);
		$raintpl->assign( "view_site", _('View website')	);
		$raintpl->assign( "view_site_url",SiteDomain);
		$raintpl->assign( "dashboard", _('Dashboard')	);
		$raintpl->assign( "user_profile", _('Profile'));
		$raintpl->assign( "core_version", sprintf(_('Version:%s'),$settings->core_version));
		$raintpl->assign( "build_num", sprintf(_('Build Number:%s'),$settings->build_num));
		$raintpl->assign( "user_profile_url", core\general::createUrl(array('plugin','users','action','profile')	)	);
	
		$raintpl->assign( "sarkesh_admin", _('Sarkesh Administrator')	);
		$raintpl->assign( "sarkesh_admin_url", core\general::createUrl(array('service','1','plugin','administrator','action','main','p','administrator','a','dashboard')	));
		
		//draw and return back menus
		return $raintpl->draw('core_content', true );
	}
	
}
