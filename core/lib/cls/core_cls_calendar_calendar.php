<?php
/*
 * This class is for working with date and time and show calendars
 * now it's just support gregorian and jallali calendars.
 * 
 * author:Babak Alizadeh
 * email :alizadeh.babak@live.com
 */
 
 namespace core\cls\calendar;
 use \core\cls\core as core;
 
 class calendar{
	 
	 /*
	  * @var string,cerrent system calendar name
	  */
	 public $calendar;
	 
	 /*
	  * construct
	  */
	 function __construct(){
		 //get selected system calendar type;
		 $localize = new core\localize;
		 $localSettings = $localize->get_localize(); 
		 $this->calendar = $localSettings['calendar'];
	 }
	 
	 
	public function cdate($format, $time ){
		if($this->calendar == 'jallali'){
			//create object from jallali calndar
			$jallali = new jallali;
			return $jallali->jdate($format,$time);
		}
		elseif($this->calendar == 'gregorian'){
			return date($format ,$time);
		}
		
	}
	 
	 
 }

?>
