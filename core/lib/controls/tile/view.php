<?php
namespace core\control\tile;
use \core\cls\template as template;
use \core\cls\browser as browser;
class view{
	
	
	function __construct(){

	}
	public function view_draw($items,$config,$places){
		
		if($config['SCRIPT_SRC'] != ''){browser\page::addHeader('<script src="' . $config['SCRIPT_SRC'] . '"></script>'); }		
		if($config['CSS_FILE'] != ''){ browser\page::addHeader('<link rel="stylesheet" type="text/css" href="' . $config['CSS_FILE'] . '" />');}
		
		$raintpl = new template\raintpl;
		if($config['TEMPLATE'] != ''){
			$raintpl->configure("tpl_dir",$config['TEMPLATE_DIR']);
			//assign places
			foreach($places as $key=>$place){
				$raintpl->assign($place,$items[$key]);
			}
			return $raintpl->draw($config['TEMPLATE'],true);
		}
		else{
			$raintpl->configure("tpl_dir","./core/lib/controls/tile/");
			$raintpl->assign("items",$items);
			return $raintpl->draw("ctr_tile",true);
		}
		
		
		
	}
}
?>
