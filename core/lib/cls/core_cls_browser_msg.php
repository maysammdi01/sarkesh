<?php
namespace core\cls\browser;
use \core\control as control;
use \core\cls\core as core;
//class for controll messages in system
class msg{
	
	/*
	 * return not complete message in modal mode
	 * @param array $e,standard event array
	 * @return array,standard event array
	 */
	public static function modalNotComplete($e){
		$e['RV']['MODAL'] = page::showBlock(_('Message'),_('Please fill all necessary places.'),'MODAL','type-warning');
		return $e;
	}
	
	/*
	 * return not complete message in modal mode
	 * @param array $e,standard event array
	 * @param string next page for jump
	 * @return array,standard event array
	 */
	public static function modalSuccessfull($e,$page='N'){
		$e['RV']['MODAL'] = page::showBlock(_('Successful'),_('Your request successfully completed.'),'MODAL','type-success');
		if($page == 'R') $e['RV']['JUMP_AFTER_MODAL'] = 'R';
		elseif($page != 'N') $e['RV']['JUMP_AFTER_MODAL'] = core\general::createUrl($page);
		return $e;
	}
	
	/*
	 * return no permission message in modal mode
	 * @param array $e,standard event array
	 * @return array,standard event array
	 */
	public static function modalNoPermission($e){
		//show access denied message
		$e['RV']['MODAL'] = page::showBlock(_('Access Denied!'),_('You have no permission to do this operation!'),'MODAL','type-danger');
		$e['RV']['JUMP_AFTER_MODAL'] = 'R';
		return $e;
	}
	
	/*
	 * show page not found page in action mode
	 * @return array ['tittle','notfound message']
	 */
	public static function pageNotFound(){
		return [_('404!'),_('Your requested page not found!')];
	}
	
	/*
	 * Show access denied page in service mode
	 * @return string, html content
	 */
	public static function serviceAccessDenied(){
		$block = page::showBlock(_('503!'),_('You have no permission to access this page.'),'BLOCK','success');
		$form = new control\form('coreAcessDeniedMSG');
		$label = new control\label($block);
		$jump = new control\button('btnJumpHome');
		$jump->label = _('Home');
		$jump->href = SiteDomain;
		$form->addArray([$label,$jump]);
		return page::simplePage(_('503!'),$form->draw(),5,true);
	}
	
	/*
	 * Show access denied page in page mode
	 * @return array, html content
	 */
	public static function pageAccessDenied(){
		return [_('Access denied!'),self::serviceAccessDenied()];
	}
	
	/*
	 * show page with error ecured message
	 * @return array ['tittle','error message']
	 */
	public static function pageError(){
		return [_('Error'),_('Some error was eccurred!')];
	}
	
	/*
	 * add modal with error ecured message
	 * @return array ['tittle','error message']
	 */
	public static function modalEventError($e){
		//show access denied message
		$e['RV']['MODAL'] = page::showBlock(_('Error!'),_('Some error was eccurred! page will be refreshed.'),'MODAL','type-danger');
		$e['RV']['JUMP_AFTER_MODAL'] = 'R';
		return $e;
	}
	
	/*
	 * show message in modal mode
	 * @param array $e,standard event array
	 * @param string $title, tittle of modal message
	 * @param string $content, content of modal message
	 * @param string $type,type of modal:default,danger,warning,info,success
	 * @param string $url, url for jump to thats page after modal showed: R = refresh, default:noting
	 * @return array ['tittle','error message']
	 */
	public static function modal($e,$title,$content,$type = 'default',$url = null){
		//show access denied message
		$e['RV']['MODAL'] = page::showBlock($title,$content,'MODAL','type-' . $type);
		if(!is_null($url))
			$e['RV']['JUMP_AFTER_MODAL'] = $url;
		return $e;
	}
	
	
	
	
}
?>
