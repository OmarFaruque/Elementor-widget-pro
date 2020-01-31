<?php

//no direct accees
defined ('ELEMENTOR_WIDGET_PRO_INC') or die ('restricted aceess');

require EWPHelper::getPathViewObject("addons_view.class");


class UniteCreatorAddonsElementorView extends UniteCreatorAddonsView{
	
	protected $showButtons = true;
	protected $showHeader = true;
	protected $pluginTitle = null;
	
	
	/**
	 * get header text
	 * @return unknown
	 */
	protected function getHeaderText(){
				
		$headerTitle = esc_html__("Manage Templates for Elementor", "unlimited_elementor_elements");
		
		return($headerTitle);
	}
	
	
	/**
	 * addons view provider
	 */
	public function __construct(){
		
		$this->addonType = Globalselementwidgetpro::ADDONSTYPE_ELEMENTOR_TEMPLATE;
		$this->product = Globalselementwidgetpro::PLUGIN_NAME;
		$this->pluginTitle = Globalselementwidgetpro::PLUGIN_TITLE;
		
		
		parent::__construct();
	}
	
	
}


new UniteCreatorAddonsElementorView();
