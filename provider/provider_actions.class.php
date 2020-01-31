<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

/**
 * actions
 */
class UniteCreatorActions extends UniteCreatorActionsWork{
	
	
	/**
	 * on update layout response, function for override
	 */
	protected function onUpdateLayoutResponse($response){
		
				
		$isUpdate = $response["is_update"];
		
		//create
		if($isUpdate == false){
			
			$layoutID = $response["layout_id"];
			
			$urlRedirect = EWPHelper::getViewUrl_Layout($layoutID);
			
			EWPHelper::ajaxResponseSuccessRedirect(esc_html__("Layout Created, redirecting...", "unlimited_elementor_elements"), $urlRedirect);
			
		}else{
			//update
			
			$message = esc_html__("Updated", "unlimited_elementor_elements");
			
			EWPHelper::ajaxResponseSuccess($message);
		}
		
	}
	
	
}