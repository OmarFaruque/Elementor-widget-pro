<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

class UniteCreatorBrowser extends UniteCreatorBrowserWork{
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		parent::__construct();

		$urlLicense = EWPHelper::getViewUrl(GlobalsUC::VIEW_LICENSE);
		
		$this->textBuy = esc_html__("Activate Blox", "unlimited_elementor_elements");
		$this->textHoverProAddon = __("This addon is available<br>when blox is activated.", "unlimited_elementor_elements");
		$this->urlBuy = $urlLicense;
		
	}
	
}