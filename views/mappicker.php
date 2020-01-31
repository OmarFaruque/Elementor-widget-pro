<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

	
	$filepathPickerObject = GlobalsUC::$pathViewsObjects."mappicker_view.class.php";
	require $filepathPickerObject;
	
	$objView = new UniteCreatorMappickerView();
	
	
	$objView->putHtml();

