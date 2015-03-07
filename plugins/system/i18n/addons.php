<?php
namespace core\plugin\i18n;
use \core\cls\db as db;
use \core\cls\core as core;
trait addons {
	
	/*
	 * get all languages with ordered by
	 * @return array of languages
	 */
	public function getLanguages(){
		$orm = db\orm::singleton();
		$localize = core\localize::singleton();
		return $orm->exec("SELECT language,language_name FROM localize ORDER BY language=? DESC;", [$localize->language()],SELECT);
	}
}
