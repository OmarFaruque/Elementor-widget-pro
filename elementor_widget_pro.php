<?php
/*
Plugin Name: Elementor Widget Pro
Plugin URI: http://elementor-widget.com
Description: Elementor Widget Pro - Unlimited Widgets for Elementor Page Builder, with html/css/js widget creator and editor.
Author: Ruben Romero
Version: 1.0.1
Author URI: http://authorurl.com/
*/


if(!defined("ELEMENTOR_WIDGET_PRO"))
	define("ELEMENTOR_WIDGET_PRO", true);
	
$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);
$pathProvider = $currentFolder."/provider/";

try{
	
	if(class_exists("GlobalsUC"))
		define("UC_BOTH_VERSIONS_ACTIVE", true);
	else{
		
		$pathAltLoader = $pathProvider."provider_alt_loader.php";
		if(file_exists($pathAltLoader)){
			require $pathAltLoader;
		}else{
		require_once $currentFolder.'/includes.php';
		
		require_once  GlobalsUC::$pathProvider."core/provider_main_file.php";
		}
		
	}
	
}catch(Exception $e){
	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	
	echo "<br>";
	echo esc_html($message);
	echo "<pre>";
	print_r($trace);
}


