<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

class UniteCreatorAddonType_Shape extends UniteCreatorAddonType{
	
	
	
	/**
	 * init the addon type
	 */
	protected function init(){
		
		$this->typeName = GlobalsUC::ADDON_TYPE_SHAPES;
		$this->textSingle = __("Shape", "unlimited_elementor_elements");
		$this->textPlural = __("Shapes", "unlimited_elementor_elements");
		$this->isSVG = true;
		$this->textShowType = $this->textSingle;
		$this->titlePrefix = $this->textSingle." - ";
		$this->isBasicType = false;
		$this->allowWebCatalog = true;
		$this->allowManagerWebCatalog = true;
		$this->catalogKey = $this->typeName;
		
	}
	
	
}
