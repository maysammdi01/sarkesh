<?php
namespace core\plugin\administrator;


class module extends view{
	
	/*
	 * construct
	 */
	function __construct(){
		parent::_construct();
	}
	
	/*
	 * load basic administrator panel
	 * @param array(2) $content, (0=>title, 1=>content)
	 * @return string, html content
	 */
	protected function moduleLoad($content){
		
	}
}
