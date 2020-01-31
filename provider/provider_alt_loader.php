<?php
/**
 * Check if addon creator pluign exist.
 * Check if addon creator pluign active.
 */
function addonpluginisexist(){
	
	$addonPlugin = "addon-library/addonlibrary.php";
	$addonPlugin2 = "unlimited-addons-for-wpbakery-page-builder/unlimited_addons.php";
	
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	
	$aladdPlugins = get_plugins();
	
	if(isset($aladdPlugins[$addonPlugin]) == true){
		$isActive = is_plugin_active($addonPlugin);
		return($isActive);
	}elseif(isset($aladdPlugins[$addonPlugin2]) == true){
		return(true);
	}
		
	$isActive = is_plugin_active($addonPlugin);
	
	return(false);
}


if(addonpluginisexist()){
	
	require_once dirname(__FILE__)."/views/compatability_message.php";

}else{

	
	try{
		
		require_once $currentFolder.'/includes.php';
		require_once  GlobalsUC::$pathProvider."core/provider_main_file.php";
		
	}catch(Exception $e){
		
		$code = $e->getCode();
		if($code == 100){
			
			$filePathViewStandAlone = dirname(__FILE__)."/views/stand_alone_broken_error.php";
			if(file_exists($filePathViewStandAlone) == false)
				echo UniteProviderFunctionsUC::escCombinedHtml($e->getMessage());
			
			require $filePathViewStandAlone;
			
		}else
			echo UniteProviderFunctionsUC::escCombinedHtml($e->getMessage());
	
		
	}
	
}

