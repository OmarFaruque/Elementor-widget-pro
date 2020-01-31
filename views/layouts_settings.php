<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

require	EWPHelper::getPathViewObject("settings_view.class");

class UniteCreatorViewLayoutsSettings extends UniteCreatorSettingsView{
	
	
	/**
	 * constructor
	 */
	public function __construct(){

		$this->headerTitle = EWPHelper::getText("layouts_global_settings");
		$this->saveAction = "update_global_layout_settings";
		$this->textButton = EWPHelper::getText("save_layout_settings");
		
		//set settings object
		$this->objSettings = UniteCreatorLayout::getGlobalSettingsObject();
		
		$this->display();
	}
	
}


new UniteCreatorViewLayoutsSettings();
