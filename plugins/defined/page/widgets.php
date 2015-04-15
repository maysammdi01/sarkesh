<?php
namespace addon\plugin\page;
class widgets extends module{
	
	/*
	 * show list of catalogue
	 * @return array [title,body]
	 */
	public function widgetCatalogues(){
		return $this->moduleWidgetCatalogues();
	}
}
