<?php
namespace addon\plugin\page;
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
	 * show page with id
	 * @RETURN html content [title,body]
	 */
	public function show(){
		return $this->moduleShow();
	}
	
	/*
	 * show list of catalogues
	 * @RETURN html content [title,body]
	 */
	public function catalogues(){
		if($this->isLogedin())
			return $this->moduleCatalogues();
		return core\router::jump(['service','users','login','service/administrator/load/page/catalogues']);
	}
}
