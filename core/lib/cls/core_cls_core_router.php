<?php
/*
 * this class seperate url addrress
 * for doing process we have some parameters that send with GET 
 * 1- plugin parameter for finding what plugin do this process
 * 2- action parameter for run that action on plugin
 * and some etc parameters that plugin process that.
 * if nothing send with action with GET class change that to 'default' and send that to plugin
 */
namespace core\cls\core;
use \core\cls\core as core;
use \core\cls\network as network;
use \core\cls\browser as browser;
use \core\plugin as plg;
use \core\cls\db as db;
use core\cls\patterns as patterns;

class router{
	use patterns\singleton;
	
	/*
	* @var string, plugin name
	*/
	private $plugin;
	/*
	* @var string, action name
	*/
	private $action;
	
	/*
	* @var array, core settings
	*/
	private $localize;
	
	/*
	* @var object,plugin for controll plugins
	*/
	private $objPlugin;
	
	/*
	* construct
	* @param string $plugin, plugin name
	* @param string $action, action name
	*/
	function __construct($plugin='' ,$action=''){
		$this->objPlugin = new core\plugin;
		//set last page that user see
		$this->setLastPage();
		//get localize
		$localize = core\localize::singleton();
		$this->localize = $localize->localize();
		if($plugin == '' && $action == ''){
			if(!is_null(PLUGIN)){
				$this->plugin = PLUGIN;
				//now we check action
				if(!is_null(ACTION)) $this->action = ACTION;
				else $this->action = 'default';
			}
		}
		else{
			$this->plugin = $plugin;
			$this->action = $action;
		}			
	}
	
	/*
	* show main requested content
	* @param boolean $show ,(echo:true, else:false)
	* @return string,requested content
	*/
	public function showContent($show = true){
		//this function run from page class.
	    // this function load plugin and run controller
	    //checking for that plugin is enabled
	    $content = browser\msg::pageNotFound();
	    if($this->objPlugin->enabled($this->plugin)){
			if(file_exists('./plugins/system/' . $this->plugin . '/action.php')){
				$PluginName = '\\core\\plugin\\' . $this->plugin . '\\action';
			}
			elseif(file_exists('./plugins/defined/' . $this->plugin . '/action.php')){
				$PluginName = '\\addon\\plugin\\' . $this->plugin . '\\action';
			}

	     	$plugin = new $PluginName;
	     	//run action directly
			if(method_exists($plugin,$this->action))
			$content = call_user_func(array($plugin,$this->action),'content');	
	      }
	      browser\page::setPageTitle($content[0]);
          //show header in up of content or else
          $outputContent = null;
          if(sizeof($content) == 2)
            $outputContent = browser\page::showBlock($content[0],$content[1],'MAIN');
          elseif(sizeof($content) == 3 && $content[2] == true)
            $outputContent = browser\page::showBlock($content[0],$content[1],'BLOCK','default');
		  //show content id show was set
		  if($show && !is_null($outputContent)) echo $outputContent;
		  return $content; 
	}
	
	/*
	* run services and jump request do plugin
	*/
	public function runService(){ 
		 $result = _('Warning:Your requested service not found!');
		 if($this->objPlugin->enabled($this->plugin)){
	 	    	 $PluginName = '\\core\\plugin\\' . $this->plugin . '\\service';
	     		 $plugin = new $PluginName;			
	     		 if(method_exists($plugin,$this->action))
					 $result = call_user_func(array($plugin,$this->action),'content');

	     }
	     echo $result;   
	}
	
	/*
	* runing services from controls
	*/
	public function runControl(){
		$options = str_replace('_a_n_d_','&',$_REQUEST['options']);
		$elements = new core\uiobjects($options);
		if(file_exists('./plugins/system/' . $this->plugin . '/event.php'))
					$PluginName = '\\core\\plugin\\' . $this->plugin . '\\event';
		elseif(file_exists('./plugins/defined/' . $this->plugin . '/event.php'))
			$PluginName = '\\addon\\plugin\\' . $this->plugin . '\\event';
		else
			exit('plugin not found');
		//run event,going to run function
		$plugin = new $PluginName;
		$result = call_user_func(array($plugin, $this->action),$elements->get_elements());
		
		foreach($result as $r=>$row){
			foreach($row as $c=>$col){
				$result[$r][$c] = str_replace('&','_a_n_d_',$result[$r][$c]);
			}
		}
		//now show result in xml for use in javascript
		$xml = new db\xml($result);
		echo $xml->arrayToXml($result, "root");
	}
	
	/*
	* refresh page and jump to address
	* @param string $url,page address(refresh:0)
	* @param boolean $inner , (inner url:true , else:false)
	* @param integer $time,set time for jumping time
	*/
	public function refresh($url='0',$inner_url=true , $time=5){
		if($url=='0') $url= SiteDomain;
		elseif($inner_url && $url != '0') $url= SiteDomain . $url;
		header("Refresh: $time ; url=$url");
	}
	
	/*
	* jump to address
	* @param string $url,page address(refresh:0)
	* @param boolean $inner , (inner url:true , else:false)
	*/
	public static function jump($url,$inner_url=true){
		//check for show 404 not found message
		if($url == '404')
			$url = ['service','1','plugin','msg','action','msg_404'];
		if(!$inner_url && $url != SiteDomain) $url= SiteDomain . $url;
		elseif($url==SiteDomain) $url= SiteDomain;
		elseif(is_array($url)) $url = core\general::createUrl($url);
		header("Location:$url");
		return ['',''];
		/* Make sure that code below does not get executed when we redirect. */
		exit;
	}
	
	/*
	* set last page that visited in cookie
	* @param string $url, page URI
	*/
	public function setLastPage($url=''){
		$lastUrl ='';
		if($url!='') $lastUrl=$url;
		else
			if(isset($_SERVER['HTTP_REFERER']))
				$last_url = $_SERVER['HTTP_REFERER'];
		
		if($lastUrl!=''){
			setcookie('SYS_LAST_PAGE',$lastUrl);
		}
	}
	
	/*
	* jump page that set in cookie
	*/
	public function jumpLastPage(){
		if(isset($_COOKIE['SYS_LAST_PAGE'])) header('Location: '. $_COOKIE['SYS_LAST_PAGE']);
		else header('Location: ' . SiteDomain );
	}
	
	/*
	* return last page that viewed
	*/
	public static function getLastPage(){
		$obj_io = new network\io;
		if(isset($_COOKIE['SYS_LAST_PAGE'])) return  $obj_io->cin('SYS_LAST_PAGE','cookie');
		return SiteDomain ;
	}
	
	/*
	* return cerrent address of page(cerent url)
	* @return string page url
	*/
	public function thisUrl(){
		return $_SERVER['REQUEST_URI'];	
	}
}
?>
