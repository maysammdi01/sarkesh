<?php
namespace core\control\wall;
use \core\cls\template as template;
use \core\cls\browser as browser;
class view{
	private $raintpl;
	
	function __construct(){
		$this->raintpl = new template\raintpl;
		$this->raintpl->configure("tpl_dir",'./core/lib/controls/wall/');
	}
	public function view_draw($e){
		$this->raintpl->assign("style",$e['STYLE']);
		$this->raintpl->assign("value",$e['VALUE']);
		$this->raintpl->assign("type",$e['TYPE']);
		$this->raintpl->assign("class",$e['CLASS']);
		$this->raintpl->assign("bs_control",$e['BS_CONTROL']);
		return $this->raintpl->draw("ctrWall",true);
	}
}
?>
