<?php
namespace core\control\button;
use \core\cls\template as template;
use \core\cls\browser as browser;

class view{
	/*
	 * @var object raintpl engine
	 */
	private $raintpl;
	
	/*
	 * @var object browser\page object
	 */
	private $page;
	
	/*
	 * construct
	 */
	function __construct(){
		$this->page = browser\page::singleton();
		$this->raintpl = template\raintpl::singleton();
	}
	
	/*
	 * dunction for draw control
	 * @param array string, configs
	 * @return string, html content
	 */
	public function view_draw($config){
		//configure raintpl //
		$this->raintpl->configure('tpl_dir','core/lib/controls/button/tpl/');
		
		//add headers to page//
		browser\page::addHeader('<script src="' . SiteDomain . '/core/ect/scripts/events/functions.js"></script>');		
		if($config['SCRIPT_SRC'] != ''){browser\page::addHeader('<script src="' . $config['SCRIPT_SRC'] . '"></script>'); }		
		if($config['CSS_FILE'] != ''){ browser\page::addHeader('<link rel="stylesheet" type="text/css" href="' . $config['CSS_FILE'] . '" />');}
		
		//Assign variables
		$this->raintpl->assign( "id", $config['NAME']);
		$this->raintpl->assign( "href", $config['HREF']);
		//if href config is enabled we are sorry for do onclick events
		if($config['HREF'] != ''){
			$config['J_ONCLICK'] = '0';
			$config['P_ONCLICK_FUNCTION'] = '0';
			$config['P_ONCLICK_PLUGIN'] = '0';
			$config['J_AFTER_ONCLICK'] = '0';
		}
		$this->raintpl->assign( "form", $config['FORM']);
		$this->raintpl->assign( "value", $config['VALUE']);
		$this->raintpl->assign( "bs_control", $config['BS_CONTROL']);
		$this->raintpl->assign( "size", $config['SIZE']);
		$this->raintpl->assign( "label", $config['LABEL']);
		$this->raintpl->assign( "type", 'btn btn-' . $config['TYPE']);
		$this->raintpl->assign( "styles", $config['STYLE']);
		$this->raintpl->assign( "class", $config['CLASS']);
		$this->raintpl->assign( "tooltip_place", $config['TOOLTIP_PLACE']);
		$this->raintpl->assign( "tooltip_text", $config['TOOLTIP_TEXT']);
		$this->raintpl->assign( "j_onclick", $config['J_ONCLICK']);
		$this->raintpl->assign( "p_onclick_f", $config['P_ONCLICK_FUNCTION']);
		$this->raintpl->assign( "p_onclick_p", $config['P_ONCLICK_PLUGIN']);
		$this->raintpl->assign( "j_after_onclick", $config['J_AFTER_ONCLICK']);

		$this->raintpl->assign( "j_onfocus", $config['J_ONFOCUS']);
		$this->raintpl->assign( "p_onfocus_f", $config['P_ONFOCUS_FUNCTION']);
		$this->raintpl->assign( "p_onfocus_p", $config['P_ONFOCUS_PLUGIN']);
		$this->raintpl->assign( "j_after_onfocus", $config['J_AFTER_ONFOCUS']);
		
		$this->raintpl->assign( "j_onblur", $config['J_ONBLUR']);
		$this->raintpl->assign( "p_onblur_f", $config['P_ONBLUR_FUNCTION']);
		$this->raintpl->assign( "p_onblur_p", $config['P_ONBLUR_PLUGIN']);
		$this->raintpl->assign( "j_after_onblur", $config['J_AFTER_ONBLUR']);
		
		
		if($config['DISABLE']) $this->raintpl->assign( "disabled", 'disabled');
		else $this->raintpl->assign( "disabled", 'enabled');
		//return control
		return $this->raintpl->draw('ctr_button', true );
	}
}
?>
