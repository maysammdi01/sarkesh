<?php
namespace core\plugin\i18n;
use \core\control as control;

class widgets extends module{
	
	/*
	 * show form for change site language
	 * @return array [title,content]
	 */
	public function selectLanguage(){
		return $this->moduleSelectLanguage();
	}
}
