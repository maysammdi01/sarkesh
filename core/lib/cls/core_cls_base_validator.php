<?php
//this class is for validate inputs
namespace core\lib\base;
use core\cls\patterns as patterns;
class validator{
	use patterns\singleton;
	public static function ip($inp){
		return filter_var($inp,FILTER_VALIDATE_IP)
	}
	public static function is_email($inp){

	}
}
?>