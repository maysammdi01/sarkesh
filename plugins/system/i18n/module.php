<?php
namespace core\plugin\i18n;
use \core\control as control;

class module extends view{
	use addons;
	/*
	 * show form for change site language
	 * @return array [title,content]
	 */
	protected function moduleSelectLanguage(){
		return $this->viewSelectLanguage($this->getLanguages());
	}
}
