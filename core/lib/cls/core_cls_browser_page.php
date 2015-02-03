<?php
#this class show website and replace blocks
namespace core\cls\browser;
use core\cls\db as db;
use core\cls\core as core;
use core\cls\patterns as patterns;
class page{
	use patterns\singleton;
	//settings will be saved in this varible
	private static $localize_settings;
	private static $settings ;
	private static $page_tittle;
	private static $blocks;
	private static $plugin;
	
	//this varible store headers of page
	static private $header_tags;
	
	function __construct(){
		$obj_localize = core\localize::singleton();
		self::$localize_settings = $obj_localize->get_localize();
	}
	
	#if active language is RTL this function return true else return false
	
	static public function is_rtl(){
		
		if(self::$localize_settings['direction']=='RTL') {
			return true;
		}
		else {
			return false;
		}
	}

	static function difault_headers () {
		
		if(is_null(self::$settings)){
			$registry = core\registry::singleton();
			self::$settings = $registry->get_plugin('administrator');
			$obj_localize = core\localize::singleton();
			self::$localize_settings = $obj_localize->get_localize();
			self::$page_tittle = self::$localize_settings['name'];
		}
		if(is_null(self::$header_tags)){
			self::$header_tags = array();
		}
		$default_headers = array();
		#LOAD HEEFAL GENERATOR META TAG
		array_push($default_headers, '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
		array_push($default_headers, '<meta name="generator" content=" Sarkesh CMF! - Open Source Content Management Framework" />');
		//add default header tags
		if(self::$localize_settings['header_tags'] != ''){
			array_push($default_headers, '<meta name="description" content="' . self::$localize_settings['header_tags'] . '" />');
		}
		//cache control
		array_push($default_headers, '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">') ;
		array_push($default_headers, '<meta name="viewport" content="width=device-width, initial-scale=1.0">');
		#load jquery
		if(self::$settings['jquery'] == '1'){
			array_push($default_headers, '<script src="./core/ect/scripts/jquery.js"></script>');
			array_push($default_headers, '<script src="./core/ect/scripts/bootstrap.min.js"></script>');
			array_push($default_headers, '<script src="./core/ect/scripts/bootstrap-dialog.js"></script>');
			array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./core/ect/styles/bootstrap.min.css" />');
			#load rtl bootstrap
			if (self::is_rtl()){ 
				array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./core/ect/styles/bootstrap-rtl.min.css" />');
			}
			array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./core/ect/styles/bootstrap-dialog.css" />');
		}
		#load style sheet pages (css)
		//if(isset($_REQUEST['plugin'])){
			if($_REQUEST['plugin'] != 'administrator'){
				$theme_name = self::$settings['active_theme'];
				array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./themes/'  . $theme_name . '/style.css" />');
				#load rtl stylesheets
				if (self::is_rtl()){ 
					array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./themes/'  . $theme_name . '/rtl-style.css" />');
				}

				#load favicon
				if(file_exists("./themes/"  . $theme_name . "/favicon.ico")){ 
					array_push($default_headers, '<link rel="shortcut icon" href="./themes/'. $theme_name .'/favicon.ico" type="image/x-icon">');
					array_push($default_headers, '<link rel="icon" href="./themes/'.$theme_name .'/favicon.ico" type="image/x-icon">');
				}
		
			}
			array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./core/ect/styles/default.css" />');
		//}
		

		//load first bootstrap skin
		if(self::$settings['1st_template'] != '0'){
			array_push($default_headers, '<link rel="stylesheet" type="text/css" href="./core/ect/styles/' . self::$settings['1st_template'] . '.min.css" />');
		}
		#load nessasery java script functions
		array_push($default_headers, '<script src="./core/ect/scripts/functions.js"></script>');
		
		self::$header_tags = $default_headers + self::$header_tags;
		
	}
	//this function add headers to page
	static public function add_header($header){
		if(is_null(self::$header_tags)){
			self::$header_tags = array();
		}
		if(!array_key_exists($header, self::$header_tags)){
			self::$header_tags[$header] = $header;
		}
		
		
	}
	
	static function load_headers($show=true){
	      self::difault_headers();
	      #show header tags
		if($show){
			foreach(self::$header_tags as $header){
			      echo $header . "\n";
			}
		}
		else{
			//return $header_tags
			$str_header = '';
			foreach(self::$header_tags as $header){
			      $str_header .=  $header . "\n";
			}
			return $str_header;
		}
	}
	//this function add recived string to page title
	static public function set_page_tittle($tittle = ''){
		//get site name in localize selected
		if(is_null(self::$localize_settings)){
			$obj_localize = core\localize::singleton();
			self::$localize_settings = $obj_localize->get_localize();
			self::$page_tittle = self::$localize_settings['name'];
		}
		self::$page_tittle = self::$localize_settings['name'] . ' | ' . $tittle;
		return self::$page_tittle;
		//now we want to send title to render function.
	}
	//this function return page title usually for runder.php
	static public function get_page_tittle(){
	
		return self::$page_tittle;
	}
	//this function atteche some tags to blocks and show that.
	static public function show_block($header, $body, $view='NONE' ,$type = null, $result = 0){
		$content = '';
		//create special value for access to that
		if($view == 'BLOCK'){
			$content .=  '<div class="panel panel-default panel-small">';
				$content .=  '<div class="panel-heading">';
					$content .=  '<h3 class="panel-title">';
					      //block header show in here
					      $content .=  $header;
					$content .=  '</h3>';
				$content .=  '</div>';
				$content .=  '<div class="panel-body">';
				      //block content show in here
				      $content .=  $body;
				$content .=  '</div>';
			$content .=  '</div>';

		}
		elseif($view == 'MAIN'){
			$content .=  '<div class="">';
					$content .=  '<h3>';
					      //block header show in here
					      $content .=  $header;
					$content .=  '</h3>';
				$content .=  '<div class="">';
				      //block content show in here
				      $content .=  $body;
				$content .=  '</div>';
				
			$content .=  '</div>';
		}
		elseif($view == 'MSG'){
				$content .=  '<div class="alert alert-' . $type . '"> ';
				$content .=  '  <a class="close" data-dismiss="alert">×</a>';
				$content .=  '<strong>'; 
 					//block header show in here
					$content .=  $header;
				$content .=  '</strong>';  
				//block content show in here
				$content .=  $body;
			$content .=  '</div>';
		}
		elseif($view == 'NONE'){;  
			//this type do not support title and ect.
			$content .=  $body;

		}
		elseif($view == 'MODAL'){
			//else it's modal
			$content .=  '<?xml version="1.0"?>' . "\n";
				$content .=  '<message>' . "\n";
					$content .=  '<result>';
						$content .=  $result;
					$content .=  '</result>' . "\n";
					$content .=  '<type>';
						$content .=  $type;
					$content .=  '</type>' . "\n";
					$content .=  '<header>';
						$content .=  $header;
					$content .=  '</header>' . "\n";
					$content .=  '<content>';
						$content .=  $body;
					$content .=  '</content>' . "\n";
					$content .=  '<btn-ok>';
						$content .=  _('Ok');
					$content .=  '</btn-ok>' . "\n";
					$content .=  '<btn-back>';
						$content .=  _('Back');
					$content .=  '</btn-back>' . "\n";
					$content .=  '<btn-cancel>';
						$content .=  _('Cancel');
					$content .=  '</btn-cancel>' . "\n";
				$content .=  '</message>' . "\n";

		}

		return html_entity_decode($content);
	}
	//this function set and show blocks
	static public function set_position($position){
		$obj_localize = core\localize::singleton();
		self::$localize_settings = $obj_localize->get_localize();
		if(self::$blocks == null){
			//load all blocks data from database
			$db = db\mysql::singleton();
			$query_string = "SELECT b.name AS 'b.name',";
			$query_string .= "b.position AS 'b.position', b.permissions AS 'b.permissions', b.visual AS 'b.visual', b.handel AS 'b.handel', b.value AS 'b.value',";
			$query_string .= "b.pages AS 'b.pages', b.show_header AS 'b.show_header', b.plugin AS 'b.plugin', p.id AS 'p.id', p.name AS 'p.name', b.rank FROM blocks b INNER JOIN plugins p ON b.plugin = p.id ORDER BY b.rank DESC;";
			$db->do_query($query_string);
			self::$blocks = $db->get_array();
			self::$plugin = core\plugin::singleton();

		}
		
		
		//search blocks for position matched
		//if add 'MAIN' to cls_router::show_content that's show like main content that come with url
		//and if add 'BLOCK' tag , sarkesh show that content like block
		//and if Send 'NONE' sarkesh do not show that(just run without view
		
		//get showing permissions from block settings from localize
		
		foreach( self::$blocks as $block){
		
			if($block['b.position'] == $position){
				//going to process block
				if($block['p.name'] == 'administrator'){
					//going to show content;
					$obj_router = core\router::singleton();
					$obj_router->show_content();
				}
				else{
					//block is widget
					if(self::show_has_allow($block['b.name'])){
						//checking that plugin is enabled
						if(self::$plugin->is_enabled($block['p.name'])){
							$ClassName = '\\core\\plugin\\' . $block['p.name'] ;
							$plugin = new $ClassName; 
							//run action method for show block
							//all blocks name should be like  'blk_blockname'
							$content = array();
							if($block['b.visual'] == '0'){
								$content = call_user_func(array($plugin, $block['b.name']),$position);
							}
							else{
								//plugin is visual
								$content = call_user_func(array($plugin, $block['b.handel']),$position,$block['b.value']);
							}
							if($block['b.show_header'] == 1){
								echo self::show_block($content[0], $content[1], 'BLOCK');
							}
							else{
								echo $content;
							}
						}
					}
				}
			
			}
		
		}
	}
	
	//this function return content for show in custombox for show on page
	static public function show_in_box($header, $content, $type = 'warning', $result = '0'){
		$type = 'type-' . $type;
		self::show_block(true, $header,$content,'MODAL', $type, $result);
	
	}
	static public function show_message($header, $content, $type = 'warning', $result = '0'){
		self::show_block(true, $header,$content,'MSG', $type, $result);
	}
	
	//this function show developers options
	public static function show_dev_panel(){
		echo _('Memory usage by system:') . memory_get_peak_usage() . '</br>';
		echo _('Real usage by system:') . memory_get_usage() . '</br>';
		echo _('CPU usage by system:');
		if( php_uname('s') == 'Windows NT'){
			echo 'Not supported in windows';
		}
		else{
			$load = sys_getloadavg();
			echo $load[0];
		}
	}
	static public function show_has_allow($block_name){
		
		//get block options
		if(db\orm::count('blocks','name=?',[$block_name]) != 0){
			$block_info = db\orm::findOne('blocks','name=?',[$block_name]);
			if($block_info->pages != ''){
				$pages = explode(',',$block_info->pages);
				//check for show or not
				if($block_info->pages_ad == '1'){
					//check for allowed pages
					foreach($pages as $page){
						if($page == $_SERVER['REQUEST_URI']){
							return false;
							break;
						}
						elseif($page == 'frontpage'){
							if($_SERVER['REQUEST_URI'] == '/' . self::$localize_settings['home']){
								return false;
								break;
							}
						}
					}
				}
				else{
					
					//check for denied pages
					//check for allowed pages
					foreach($pages as $page){
						
						if($page == $_SERVER['REQUEST_URI']){
							return true;
							break;
						}
						elseif($page == 'frontpage'){
							if($_SERVER['REQUEST_URI'] == '/' . self::$localize_settings['home']){
								return true;
								break;
							}
						}
					}
					return false;
				}
			}	
		}
		//somethig happen that we can not controll that
		return true;
		
	}
	
}
