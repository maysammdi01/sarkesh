<?php
namespace core\control;
use \core\control as control;
class tile extends control\tile\module{
	
	private $items;
	
	function __construct(){
		parent::__construct();
		$this->items = array();
	}
	
	public function draw(){
		return $this->module_draw($this->items);
	}
	
	public function add($item){
		array_push($this->items,$item);
	}
	
	
}
?>
