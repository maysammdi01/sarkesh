<?php

namespace theme;
	
	//array below should movet to class in the future;
	//in this file we define screen shot and some info about this theme
	$theme['img'] = './themes/simple/screenshot.png';
	$theme['author'] = _('Sarkesh Developers');
	$theme['name'] = _('simple');
	$theme['version'] = '0.0.3';
	
	
	
	class simple{
		
		public function get_places(){
			return ['sidebar1','main_menu'];
		}
	}
?>
