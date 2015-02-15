<?php

namespace theme;
	
	//array below should movet to class in the future;
	//in this file we define screen shot and some info about this theme
	$theme['img'] = './themes/simple/screenshot.png';
	$theme['author'] = _('Sarkesh Developers');
	$theme['name'] = _('Basic');
	$theme['version'] = '0.0.1';
	
	
	
	class basic{
		
		public function get_places(){
			return ['sidebar1','main_menu','slide_show','footer','top_footer1','top_footer2','top_footer3','top_footer4'];
		}
	}
?>
