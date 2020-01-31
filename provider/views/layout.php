<?php

defined('ELEMENTOR_WIDGET_PRO_INC') or die;

class AddonLibraryViewLayoutProvider extends AddonLibraryViewLayout{
	
	
	/**
	 * add toolbar
	 */
	function __construct(){
		parent::__construct();
		
		$this->shortcodeWrappers = "wp";
		$this->shortcode = "blox_layout";
				
		$this->display();
	}
	
	
}