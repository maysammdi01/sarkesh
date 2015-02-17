<?php
#this function render theme file and replace contents with tags that defined in that.
function sys_render($buffer){
	/* replace headers 
	* like css java scripts and ect
	*/
	$buffer = str_replace("</#PAGE_TITTLE#/>", \core\cls\browser\page::getPageTitle(), $buffer);
	//LOAD HEADERS
	$buffer = str_replace("</#HEADERS#/>",  \core\cls\browser\page::loadHeaders(false), $buffer);
	$buffer = str_replace("</#SITE_NAME#/>", \core\cls\browser\page::getPageTitle(), $buffer);
	return $buffer;
}
?>
