<?php
namespace core\control\uploader;
use \core\cls\template as template;
use \core\cls\browser as browser;
use \core\cls\core as core;
class view{
	
	private $raintpl;
	private $page;
	
	/*
	 * construct
	 */
	function __construct(){
		$this->raintpl = new template\raintpl;
		$this->page = new browser\page;
	}
	
	//this function draw control
	protected function view_draw($config){
		//configure raintpl //
		$this->raintpl->configure('tpl_dir','core/lib/controls/uploader/');
		
		//add headers to page//
		browser\page::addHeader('<script src="' . SiteDomain . '/core/ect/scripts/events/functions.js"></script>');		
		browser\page::addHeader('<script src="' . SiteDomain . '/core/lib/controls/uploader/ctr_uploader.js"></script>');
		
		if($config['SCRIPT_SRC'] != ''){browser\page::addHeader('<script src="' . $config['SCRIPT_SRC'] . '"></script>'); }		
		if($config['CSS_FILE'] != ''){ browser\page::addHeader('<link rel="stylesheet" type="text/css" href="' . $config['CSS_FILE']) . '" />';}

		$this->raintpl->assign( "uploadUrl", core\general::createUrl(['service','files','doUpload']));
		$this->raintpl->assign( "filePort", $config['FORM'] . $config['NAME']);
		$this->raintpl->assign( "form", $config['FORM']);
		$this->raintpl->assign( "name", $config['NAME']);
		$this->raintpl->assign( "label", $config['LABEL']);
		$this->raintpl->assign( "help", $config['HELP']);
		$this->raintpl->assign( "size", $config['SIZE']);
		$this->raintpl->assign( "type", $config['TYPE']);
		//return control
		
		return $this->raintpl->draw('ctr_uploader',true);
	
	}
	
}
?>
