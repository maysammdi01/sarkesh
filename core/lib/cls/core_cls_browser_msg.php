<?php
namespace core\cls\browser;

//class for controll messages in system
class msg{
	
	/*
	 * return not complete message in modal mode
	 * @param array $e,standard event array
	 * @return array,standard event array
	 */
	public static function modalNotComplete($e){
		$e['RV']['MODAL'] = page::showBlock(_('Message'),_('Please fill all necessary places'),'MODAL','type-warning');
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
	
	
}
?>
