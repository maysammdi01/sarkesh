<?php
namespace core\plugin\reports;
use \core\cls\browser as browser;
use \core\cls\core as core;

class event extends module{
	
	/*
	 * clear php errors
	 * @param array $e, form properties
	 * @return array, form properties
	 */
	public function onclickBtnClearPhpErrors($e){
		unlink(S_Error_Log_Place);
		return browser\msg::modalSuccessfull($e,['service','administrator','load','administrator','dashboard']);
	}
}
