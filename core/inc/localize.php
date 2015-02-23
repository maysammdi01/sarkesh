<?php
/*
 * This file get system language and translate all _() function with po and mo difined files
 * for add tranlations create your language folder in languages like fa_IR
 * step 2 create LC_MESSAGES folder on it and put your mo and po files inside it
 */
$localize = \core\cls\core\localize::singleton();
$language = $localize->language();
$codeset = 'UTF8';
putenv("LANG=" . $language . '.' . $codeset);
putenv("LANGUAGE=" . $language . '.' . $codeset);
setlocale(LC_ALL, $language . '.' . $codeset);
bindtextdomain($language, AppPath . "languages/");
textdomain($language);
?>
