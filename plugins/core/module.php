<?php
class core_module{
	private $view;
	private $db;
	private $user;
	private $msg;
	private $io;
	function __construct(){
		$this->view = new core_view;
		$this->db = new cls_database;
		$this->user = new users_module;
		$this->msg = new msg_controller;
		$this->io = new cls_io;
	}

	public function show_core_page($content){
		
		$this->view->show_core_page($this->get_plugins_menu(), $content);
	
	}
	
	#this function return all contents that return from admin_menu function in controller class of all plugins
	#it's most use for return plugin menu for show in admin panel
	public function get_plugins_menu(){
		#first get all active plugins
		$this->db->do_query('SELECT * FROM ' . TablePrefix . "plugins WHERE enable=1;");
		$plugins = $this->db->get_array();
		#this variable store all menus back from plugins->admin_menu()
		$menus = null;
		foreach($plugins as $plugin){
			$plugin_name = $plugin['name'] . '_controller';
			$plugin_object = new $plugin_name;
			if(method_exists($plugin_object, 'admin_menu')){
			      $menus[] = $plugin_object->admin_menu();
			}
			
		}
		return $menus;
	
	}
	//this function return plugins info
	//if active = false then it send back all plugins and else return just activated plugins
	public function get_plugins($active = true){
		if($active){
			$this->db->do_query('SELECT * FROM ' . TablePrefix . 'plugins WHERE enable=1;');
		}
		else{
			$this->db->do_query('SELECT * FROM ' . TablePrefix . 'plugins;');
		}
		//get extra information from info.php files in plugins folder
		$result = $this->db->get_array();
		$num = 0;
		foreach ($result as $plugin) {
			include_once (AppPath . 'plugins/' . $plugin['name'] . '/info.php');	
			$result[$num]['author'] = $plugin_info['author'];
			$result[$num]['description'] = $plugin_info['description'];
			$result[$num]['version'] = $plugin_info['version'];
			$num += 1;
		}
		
		return $result;
	}
	//this function return active theme that is set on site
	public function active_theme(){
		$registry = new cls_registry;
		return $registry->get('core', 'active_theme');
	}
	
	#this function show list of all plugins 
	public function show_plugins_list($view, $show){
		#firs check for permission user access
		if($this->user->has_permation('core_admin_panel')){
					return $this->view->show_plugins_list($this->get_plugins(FALSE), $view, $show);
		}
		else{
			return $this->msg->action(403, $view);
		}	
	}
	#this function disable or enable plugins
	public function plugin_changestate(){
		//checking permission
		if($this->user->has_permation('core_admin_panel')){
			if(isset($_GET['value'])){
				$state = $this->io->cin('value', 'get');	
				$values = explode("*", $state);
				try{
					$this->db->do_query('UPDATE ' .TablePrefix . 'plugins SET enable=? where name=?', array($values[0],$values[1]));
					return 1;
				}
				catch(exception $e){
					return $this->view->error_in_operation($e);
				}
			}	
		}
		//user has no permission to access this method
		else{
			return $this->msg->action(403, 'MAIN');
		}
			
	}
	
}
?>