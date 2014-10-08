<?php
namespace core\control\tile;
use \core\cls\template as template;
use \core\cls\browser as browser;
class view{
	
	private $raintpl;
	
	function __construct(){
		$this->raintpl = new template\raintpl;
		$this->raintpl->configure("tpl_dir","./core/lib/controls/tile/");
	}
	public function view_draw($items){
		$this->raintpl->assign("items",$items);
		return $this->raintpl->draw("ctr_tile",true);
	}
}
?>
