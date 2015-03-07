<?php
namespace core\data;
//check data type
class type{
	
	/*
	 * check for that variable is numeric
	 * @param string $value
	 * @return boolean
	 */
	public static function isNumber($value){
		if(is_numeric($value))
			return true;
		return false;
	}
}
?>
