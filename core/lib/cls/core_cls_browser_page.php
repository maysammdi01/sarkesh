<?php
#this class show website and replace blocks
namespace core\cls\browser;
use core\cls\db as db;
use core\cls\core as core;
use core\cls\patterns as patterns;
class page{
	use patterns\singleton;
	/*
	* @var array,administrator settings from registry
	*/
	private static $settings;

	/*
	* @var array,localize settings
	*/
	private static $localSettings;
	
	/*
	* @var string,store page title
	*/
	private static $pageTittle;
	
	/*
	* @var array,plugin blocks
	*/
	private static $blocks;
	
	/*
	* @var array,all header tags store in it
	*/
	static private $headerTags;
	
	/*
	* @var object core\cls\core\plugin
	*/
	static private $plugin;
	
	/*
	* construct
	*/
	function __construct(){
		$localize = core\localize::singleton();
		self::$localSettings = $localize->localize();
	}
	
	/*
	* check for that page ir right to left
	* @return boolean(RTL:TRUE , ELSE:FALSE)
	*/
	static public function isRtl(){
		if(self::$localSettings->direction == 'RTL') return true;
		else return false;	
	}

	/*
	* store default headers for attech all pages in  $headerTags
	*/
	static function defHeaders () {
		
		if(is_null(self::$settings)){
			$registry = core\registry::singleton();
			self::$settings = $registry->getPlugin('administrator');
			$localize = core\localize::singleton();
			self::$localSettings = $localize->localize();
			self::$pageTittle = self::$localSettings->name;
		}
		if(is_null(self::$headerTags)){
			self::$headerTags = array();
		}
		$defaultHeaders = [];
		#LOAD Sarkesh GENERATOR META TAG
		array_push($defaultHeaders, '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
		array_push($defaultHeaders, '<meta name="generator" content=" Sarkesh CMF! - Open Source Content Management Framework" />');
		//add default header tags
		if(self::$localSettings->headerTags != ''){
			array_push($defaultHeaders, '<meta name="description" content="' . self::$localSettings->header_tags . '" />');
		}
		//cache control
		array_push($defaultHeaders, '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">') ;
		array_push($defaultHeaders, '<meta name="viewport" content="width=device-width, initial-scale=1.0">');
		#load jquery and bootstrap
		array_push($defaultHeaders, '<script src="' . SiteDomain . '/core/ect/scripts/jquery.js"></script>');
		array_push($defaultHeaders, '<script src="' . SiteDomain . '/core/ect/scripts/bootstrap.min.js"></script>');
		array_push($defaultHeaders, '<script src="' . SiteDomain . '/core/ect/scripts/bootstrap-dialog.js"></script>');
		array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/core/ect/styles/bootstrap.min.css" />');
		array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/core/ect/styles/bootstrap-theme.min.css" />');
		#load rtl bootstrap
		if (self::isRtl()){ 
			array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/core/ect/styles/bootstrap-rtl.min.css" />');
		}
		array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/core/ect/styles/bootstrap-dialog.css" />');
		#load style sheet pages (css)
		
		if(PLUGIN != 'administrator'){
			$theme_name = self::$settings['active_theme'];
			array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/themes/'  . $theme_name . '/style.css" />');
			#load rtl stylesheets
			if (self::isRtl())
				array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/themes/'  . $theme_name . '/rtl-style.css" />');
			#load favicon
			if(file_exists("./themes/"  . $theme_name . "/favicon.ico")){ 
				array_push($defaultHeaders, '<link rel="shortcut icon" href="' . SiteDomain . '/themes/'. $theme_name .'/favicon.ico" type="image/x-icon">');
				array_push($defaultHeaders, '<link rel="icon" href="' . SiteDomain . '/themes/'.$theme_name .'/favicon.ico" type="image/x-icon">');
			}
		}
		array_push($defaultHeaders, '<link rel="stylesheet" type="text/css" href="' . SiteDomain . '/core/ect/styles/default.css" />');
		#load nessasery java script functions
		array_push($defaultHeaders, '<script src="' . SiteDomain . '/core/ect/scripts/functions.js"></script>');
		
		self::$headerTags = $defaultHeaders + self::$headerTags;	
	}
	
	/*
	* for add headers to page
	* @param string $header,html header tag
	* @return void
	*/
	static public function addHeader($header){
		echo $header;
		if(is_null(self::$headerTags))
			self::$headerTags = [];
		if(!array_key_exists($header, self::$headerTags))
			self::$headerTags[$header] = $header;	
	}
	
	/*
	* show headers or return all headers
	* @param boolean $show,(echo:true,return:false)
	* @return string,html header tags
	*/
	static function loadHeaders($show=true){
		self::defHeaders();
		#show header tags
		if($show) foreach(self::$headerTags as $header) echo $header . "\n";
		//return $headerTags
		return  implode("\n",self::$headerTags);
	}
	
	/*
	* add recived string to page title
	* @param string $tittle
	* @return string page tittle
	*/
	static public function setPageTitle($tittle = ''){
		//get site name in localize selected
		if(is_null(self::$localSettings)){
			$localize = core\localize::singleton();
			self::$localSettings = $localize->get_localize();
			self::$pageTittle = self::$localSettings->name;
		}
		self::$pageTittle = self::$localSettings->name . ' | ' . $tittle;
		return self::$pageTittle;
	}
	
	/*
	* return page tittle
	* @return string,page tittle
	*/
	static public function getPageTitle(){
		return self::$pageTittle;
	}
	
	/*
	* this function atteche some tags to blocks and show that.
	* @param string $header,header of block
	* @param string $body, body of page
	* $param string $view, view of page (NONE || BLOCK || MAIN || MSG || MODAL)
	* @param string $type, type of block(success || info || primary || danger || warning || default)
	* @param string $result, set value in block (if $view = MODAL then this work)
	* @return string ,created block
	*/
	static public function showBlock($header, $body, $view='NONE' ,$type = null, $result = 0){
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
	
	/*
	* this function set and show blocks
	* @param string $position, position that set from theme class
	* @return string, block content
	*/
	static public function position($position){
		$localize = core\localize::singleton();
		self::$localSettings = $localize->localize();
		
		if(self::$blocks == null){
			//load all blocks data from database
			$orm = db\orm::singleton();
			$query_string = "SELECT b.id as 'id' ,b.name AS 'name',";
			$query_string .= "b.localize AS 'localize', b.position AS 'position', b.permissions AS 'permissions', b.visual AS 'visual', b.handel AS 'handel', b.value AS 'value',";
			$query_string .= "b.pages AS 'pages', b.show_header AS 'show_header', b.plugin AS 'plugin', p.name AS 'p_name', b.rank FROM blocks b INNER JOIN plugins p ON b.plugin = p.id ORDER BY b.rank DESC;";
			self::$blocks = $orm->exec($query_string);
			self::$plugin = core\plugin::singleton();
		}
		
		/*
		* search blocks for position matched
		* if add 'MAIN' to cls_router::showContent that's show like main content that come with url
		* and if add 'BLOCK' tag , sarkesh show that content like block
		* and if Send 'NONE' sarkesh do not show that(just run without view
		* get showing permissions from block settings from localize
		*/
		//var_dump(self::$blocks);
		
		foreach( self::$blocks as $block){
			if($block->position == $position){
				
				//going to process block
				if($block->p_name == 'administrator' && $block->visual == '0'){
					//going to show content;
					$router = core\router::singleton();
					$router->showContent();
				}
				else{
					//block is widget
					if(self::hasAllow($block->name)){
						//checking that plugin is enabled
						if(self::$plugin->enabled($block->p_name)){
							if($block->localize == 'all' || self::$localSettings->language == $block->localize){
								
								$ClassName = '\\core\\plugin\\' . $block->p_name . '\\widgets';
								if(!file_exists(AppPath . 'plugins/system/' . $block->p_name . '/widgets.php')){
									$ClassName = '\\addon\\plugin\\' . $block->p_name . '\\widgets' ;
								}
								$plugin = new $ClassName; 
								//run action method for show block
								//all blocks name should be like  'blk_blockname'
								$content = array();
								if($block->visual == '0'){
									$content = call_user_func(array($plugin, $block->name),$position);
								}
								else{
									//plugin is visual
									$content = call_user_func(array($plugin, $block->handel),$position,$block->value);
								}
								if(!is_null($content)){
									if($block->show_header == 1)
										echo self::showBlock($content[0], $content[1], 'BLOCK');
									else{
										if(is_array($content)) echo $content[1];
										else echo $content;
									}
								}
							}
							
						}
					}
				}
			
			}
		
		}
	}
		
	/*
	* this function show developers options
	*/
	public static function devPanel(){
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
	
	/*
	* check for that block has permission to show
	* @param string $block ,name of block
	* @return boolean (allow:true , else:false)
	*/
	static public function hasAllow($blockName){
		
		//get block options
		$orm = db\orm::singleton();
		if($orm->count('blocks','name=?',[$blockName]) != 0){
			$blockInfo = $orm->findOne('blocks','name=?',[$blockName]);
			if($blockInfo->pages != ''){
				$pages = explode(',',$blockInfo->pages);
				//check for show or not
				if($blockInfo->pages_ad == '1'){
					//check for allowed pages
					foreach($pages as $page){
						if($page == $_SERVER['REQUEST_URI']){
							return false;
							break;
						}
						elseif($page == 'frontpage'){
							if($_SERVER['REQUEST_URI'] == '/' . self::$localSettings->home){
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
							if($_SERVER['REQUEST_URI'] == '/' . self::$localSettings->home){
								return true;
								break;
							}
						}
					}
					return false;
				}
			}
			return true;	
		}
		//somethig happen that we can not controll that
		return false;
	}
	
}
