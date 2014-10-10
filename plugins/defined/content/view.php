<?php

namespace addon\plugin\content;
use \core\control as control;
use \core\cls\calendar as calendar;
class view{
	
	protected function view_show($content,$page){
		
		$tile = new control\tile;
		$tile->configure('TEMPLATE_DIR','./plugins/defined/content/templates/');
		$tile->configure('TEMPLATE','simple_content');
		$tile->configure('CSS_FILE','./plugins/defined/content/templates/css/simple_content.css');
		foreach($page as $part){
			$tile->add($part[0],$part[1]);
		}
		$info_content = sprintf(_('%s by %s'),$content->date,$content->user) ;
		$tile->add($info_content,'meta');
		
		return array($content->header,$tile->draw());
	}
	
}

?>
