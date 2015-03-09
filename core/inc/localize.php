<?php
/*
 * This file get system language and translate all _() function with po and mo difined files
 * for add tranlations create your language folder in languages like fa_IR
 * step 2 create LC_MESSAGES folder on it and put your mo and po files inside it
 */
$orm = \core\cls\db\orm::singleton(); 
if($orm->count('localize') != 1)
	define('MULTI_LANG',TRUE);
else
	define('MULTI_LANG',FALSE);
	
$localize = \core\cls\core\localize::singleton();
$language = $localize->language();
putenv("LANG=" . $language );
setlocale(LC_ALL, $language );
bindtextdomain($language, AppPath . "languages/");
textdomain($language);
//SET DEFINES STATIC VARIABLES
define('SITE_LANG',$language);
?>
