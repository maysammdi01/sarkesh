<?php
namespace addon\plugin\slideshow;
use \core\cls\core as core;
use \core\cls\browser as browser;


class action extends module{
	use view;
	/*
	 * construct
	 */
	function __construct(){
		parent::__construct();
	}
	
	/*
	 * show php error log
	 * @return 2D array [title,content]
	 */
	public function phpErrors(){
		return $this->modulePhpErrors();
	}
}
