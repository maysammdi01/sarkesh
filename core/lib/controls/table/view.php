<?php
namespace control\table;

class view{	
	private $raintpl;
	function __construct(){
		$this->raintpl = new template\raintpl;
		$this->raintpl->configure("tpl_dir","./core/lib/controls/table/");
	}
	
	protected function view_draw($config){
	
		if($config['CSS_FILE'] != ''){
			cls_page::add_header($config['CSS_FILE']);
		}
		
		$this->raintpl->assign('headers',$config['HEADERS']);
		$this->raintpl->assign('rows',$config['ROWS']);
		$this->raintpl->assign('headers_width',$config['HEADERS_WIDTH']);
		$this->raintpl->assign('size',$config['SIZE']);
		$this->raintpl->assign('bs_control',$config['BS_CONTROL']);
		$this->raintpl->assign('border',$config['BORDER']);
		$this->raintpl->assign('hover',$config['HOVER']);
		$this->raintpl->assign('striped',$config['STRIPED']);
		$this->raintpl->assign('class',$config['CLASS']);
		
		if($config['TYPE'] == 'NORMAL'){
			return $this->raintpl->draw("ctr_table_normal",true);
		}
		else{
			return $this->raintpl->draw("ctr_table_source",true);
		}
		
	}
}

?>
