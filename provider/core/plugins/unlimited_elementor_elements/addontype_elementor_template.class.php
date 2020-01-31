<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

class UniteCreatorAddonType_Elementor_Template extends UniteCreatorAddonType_Layout{
	
	
	/**
	 * init the addon type
	 */
	protected function init(){
		
		parent::init();
		
		$this->typeName = Globalselementwidgetpro::ADDONSTYPE_ELEMENTOR_TEMPLATE;
				
		$this->isBasicType = false;
		$this->layoutTypeForCategory = $this->typeName;
		$this->displayType = self::DISPLAYTYPE_MANAGER;
		
		$this->allowDuplicateTitle = false;
		$this->defaultBlankTemplate = false;
		
		$this->allowWebCatalog = false;
		$this->allowManagerWebCatalog = false;
		$this->showDescriptionField = false;
		
		$this->allowNoCategory = false;
		$this->defaultCatTitle = __("Main", "unlimited_elementor_elements");
		
		$this->postType = Globalselementwidgetpro::POSTTYPE_UNLIMITED_ELEMENS_LIBRARY;
		$this->isBloxPage = false;
		
		//$this->catalogKey = "addons";
		//$this->arrCatalogExcludeCats = array("basic");
		
		$this->textPlural = __("Templates", "unlimited_elementor_elements");
		$this->textSingle = __("Template", "unlimited_elementor_elements");
		$this->textShowType = __("Elementor Template", "unlimited_elementor_elements");
		
		$this->browser_textBuy = esc_html__("Activate Plugin", "unlimited_elementor_elements");
		$this->browser_textHoverPro = __("This template is available<br>when the plugin is activated.", "unlimited_elementor_elements");
		
		$urlLicense = EWPHelper::getViewUrl(Globalselementwidgetpro::VIEW_LICENSE_ELEMENTOR);		
		$this->browser_urlBuyPro = $urlLicense;
		
		$responseAssets = UniteProviderFunctionsUC::setAssetsPath("ac_assets", true);
		
		$this->pathAssets = $responseAssets["path_assets"];
		$this->urlAssets = $responseAssets["url_assets"];
		
		$this->addonView_urlBack = EWPHelper::getViewUrl(Globalselementwidgetpro::VIEW_TEMPLATES_ELEMENTOR);
		
		
	}
	
	
}
