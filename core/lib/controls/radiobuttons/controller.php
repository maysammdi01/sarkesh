<?php
namespace core\control;
use \core\control as control;
class radiobuttons extends control\radiobuttons\module{
	private $e;
	private $config;
	function __construct($name){
		parent::__construct();
		
		$this->e = array()];
		$this->config = array()];
		$this->config['NAME'] = $name;
		$this->config['SIZE'] = 12;
		$this->config['HELP'] = '';
		$this->config['LABEL'] = 'Form Label';
		$this->config['INLINE'] = FALSE;
		$this->config['FORM'] = 'FORM';
	}
	
	public function draw(){
		return $this->module_draw($this->e,$this->config);
	}
	
	//this function configure control//
	public function configure($key, $value){
		// checking for that key is exists//
		if(key_exists($key, $this->config)){		
			$this->config[$key] = $value;
			return TRUE;
		}
		//key not exists//
		return FALSE;
	}
	public function add_array($element){
		foreach($element as $control){
			$this->add($control);
		}
	}
	public function add($element){
		
		//change form name of element
		call_user_func(array($element,"configure"),'FORM',$this->config['FORM']);
		call_user_func(array($element,"configure"),'NAME',$this->config['NAME']);
		array_push($this->e, $element);

	}

	public function get($key){
		if(key_exists($key, $this->config)){
			return $this->config[$key];
		}
		die('Index is out of range form');
	}
	public function childs($property,$value){
		foreach($this->e as $e){
			call_user_func(array($e,"configure"),$property,$value);
		}
	}
}
