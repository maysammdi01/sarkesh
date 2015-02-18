<?php
//this function is for general works

namespace core\cls\core;
class general{

	/*
	 * create random string
	 * @param integer $length,len of string
	 * @param string $type,(Numberic:N , Character:C, numeric and character:NC)
	 * @return string
	 */	
	static function randomString($length = 10 ,$type = 'NC') {
		if($type == 'NC') $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		elseif($type == 'N') $characters = '0123456789';
		else $characters = 'abcdefghijklmnopqrstuvwxyz';
	    $string = '';    
	    for ($p = 0; $p < $length; $p++) $string .= $characters[mt_rand(0, strlen($characters)-1)];
	    return $string;
	}
	
	/*
	 * create internal url
	 * @param array $parameters,URL Parameters (example: ['plugin','users','action','register']
	 * @return string url, (example 'http://sitedomain.com/?plugin=users&action=register
	 */
	static function createUrl($parameters){
		$url = '?';
		for($i = 1; $i<= (max(array_keys($parameters))+1) ; $i +=2){
			if($i != 1) $url .= '&';
			$url .= $parameters[$i -1];
			$url .= '=';
			$url .= $parameters[$i];
		}
		return SiteDomain . '/' . $url;	
	}
}
?>
