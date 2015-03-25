<?php
namespace core\plugin\hello;
use \core\control as control;

class module extends view{
	
	function __construct(){
		
	}
	
	/*
	 * action for show hello word
	 * @return array content
	 */
	 public function moduleSample(){
         $mail = new \core\cls\network\mail();
         $mail->simpleSend('babak','alizadeh.babak@gmail.com','hello0','body');
         // The message
         $message = "Line 1\r\nLine 2\r\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
         $message = wordwrap($message, 70, "\r\n");

// Send
         mail('alizadeh.babak@gmail.com', 'My Subject', $message);
		 $form = new control\form('text_form');
		 $uploader = new control\uploader('hello_uploader');
		 $form->add($uploader);
		 
		 return [1,$form->draw()];
		 
	 }
}
