<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');


require EWPHelper::getPathViewObject("layouts_view.class");
require EWPHelper::getPathViewProvider("provider_layouts_view.class");

if(!isset($layoutType))
	$layoutType = UniteFunctionsUC::getGetVar("layout_type", "",UniteFunctionsUC::SANITIZE_KEY);
	

$objLayouts = new UniteCreatorLayoutsViewProvider();
$objLayouts->setLayoutType($layoutType);
$objLayouts->display();
