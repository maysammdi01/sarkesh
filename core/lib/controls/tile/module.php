<?php
namespace core\control\tile;

class module extends view{
	function __construct(){
		parent::__construct();
	}
	
	public function module_draw($items){
		return $this->view_draw($items);
	}
	
}
?>
