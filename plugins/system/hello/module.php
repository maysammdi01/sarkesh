<?php
namespace core\plugin\hello;
class module extends view{
	
	function __construct(){
		parent::__construct();
	}
	
	protected function module_uploader(){
		return $this->view_uploader();
	}
	
	protected function module_show(){
		$db = db\mysql::singleton();
		$db->do_query('SELECT * FROM rr');
		$res = $db->get_array();
		return $this->view_show($res);
	}
	
	protected function module_table(){
		return $this->view_table();
	}
	
	protected function module_abc($r){
		$db = new cls_database;
		$db->do_query("INSERT INTO rr (user,pass) VALUES ('?','?')",array($r['username']['VALUE'],$r['password']['VALUE']));
		$r['RV']['VALUE'] = cls_page::SHOW_BLOCK('YOHO','YOUR MSG WAS RECIVED','MODAL','type-danger');
		$r['RV']['URL'] = "HTTP://GOOGLE.COM";
		return $r;
	}

}
?>
