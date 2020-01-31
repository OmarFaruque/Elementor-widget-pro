<?php

//no direct accees
defined ('ELEMENTOR_WIDGET_PRO_INC') or die ('restricted aceess');

require EWPHelper::getPathViewObject("addons_view.class");


class UniteCreatorCustomPostTypesView extends UniteCreatorAddonsView{
	
	protected $showButtons = true;
	protected $showHeader = true;
	protected $pluginTitle = null;
	
	
	/**
	 * get header text
	 * @return unknown
	 */
	protected function getHeaderText(){
				
		$headerTitle = esc_html__("Manage Custom Post Types", "unlimited_elementor_elements");
		
		return($headerTitle);
	}
	
	
	/**
	 * addons view provider
	 */
	public function __construct(){
		
		$this->addonType = Globalselementwidgetpro::ADDONSTYPE_CUSTOM_POSTTYPES;
		$this->product = Globalselementwidgetpro::PLUGIN_NAME;
		$this->pluginTitle = Globalselementwidgetpro::PLUGIN_TITLE;
		
		
		parent::__construct();
	}
	
	
}


new UniteCreatorCustomPostTypesView();
