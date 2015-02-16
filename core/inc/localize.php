<?php
//This file get system language and translate all _() function with po and mo difined files
//for add tranlations create your language folder in languages like fa_IR
//step 2 create LC_MESSAGES folder on it and put your mo and po files inside it
$obj_localize = \core\cls\core\localize::singleton();
$sys_language = $obj_localize->language();
$codeset = 'UTF8';
putenv("LANG=" . $sys_language . '.' . $codeset);
putenv("LANGUAGE=" . $sys_language . '.' . $codeset);
setlocale(LC_ALL, $sys_language . '.' . $codeset);
bindtextdomain($sys_language, AppPath . "languages/");
textdomain($sys_language);
?>
