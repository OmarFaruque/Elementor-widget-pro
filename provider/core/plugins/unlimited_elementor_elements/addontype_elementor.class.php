<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

class UniteCreatorAddonType_Elementor extends UniteCreatorAddonType{
	
	
	/**
	 * init the addon type
	 */
	protected function init(){
		
		parent::init();
		
		$this->typeName = Globalselementwidgetpro::ADDONSTYPE_ELEMENTOR;
		$this->isBasicType = false;
		
		$this->allowWebCatalog = true;
		$this->allowManagerWebCatalog = true;
		$this->catalogKey = "addons";
		$this->arrCatalogExcludeCats = array("basic");
		
		$this->textPlural = __("Widgets", "unlimited_elementor_elements");
		$this->textSingle = __("Widget", "unlimited_elementor_elements");
		$this->textShowType = __("Elementor Widget", "unlimited_elementor_elements");
		
		$this->browser_textBuy = esc_html__("Go Pro", "unlimited_elementor_elements");
		$this->browser_textHoverPro = __("Upgrade to PRO version <br> to use this widget", "unlimited_elementor_elements");
		$this->browser_urlPreview = "https://unlimited-elements.com/widget-preview/?widget=[name]";
		
		$urlLicense = admin_url("admin.php?page=elementwidgetpro-pricing");
		
		$this->browser_urlBuyPro = $urlLicense;
		
		$responseAssets = UniteProviderFunctionsUC::setAssetsPath("ac_assets", true);
		
		$this->pathAssets = $responseAssets["path_assets"];
		$this->urlAssets = $responseAssets["url_assets"];
		
		$this->addonView_urlBack = EWPHelper::getViewUrl(Globalselementwidgetpro::VIEW_ADDONS_ELEMENTOR);
		$this->addonView_showSmallIconOption = false;
				
	}
	
	
}
