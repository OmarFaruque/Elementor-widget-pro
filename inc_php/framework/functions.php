<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');

	//---------------------------------------------------------------------------------------------------------------------	
	
	if(!function_exists("dmp")){
		function dmp($str){
			echo "<div align='left' style='direction:ltr;'>";
			echo "<pre>";
			print_r($str);
			echo "</pre>";
			echo "</div>";
		}
	}
	
	
	
	
?>