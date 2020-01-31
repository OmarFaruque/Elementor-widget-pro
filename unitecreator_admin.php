<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');


	class UniteCreatorAdmin extends UniteBaseAdminClassUC{
		
		const DEFAULT_VIEW = "addons";
		
		private static $isScriptsIncluded_settingsBase = false;
		
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct(){
						
			parent::__construct();
		}
		
		
		/**
		 * 
		 * init all actions
		 */
		protected function init(){
						
			//some init content
		}

		/**
		 * add must scripts for any view
		 */
		public static function addMustScripts($specialSettings = ""){
			
			UniteProviderFunctionsUC::addScriptsFramework($specialSettings);
			
			//add color picker scripts
			$colorPickerType = EWPHelper::getGeneralSetting("color_picker_type");
			switch($colorPickerType){
				case "spectrum":
					EWPHelper::addScript("spectrum","unite-spectrum","js/spectrum");
					EWPHelper::addStyle("spectrum","unite-spectrum","js/spectrum");
				break;
				case "farbtastic":
					EWPHelper::addScript("farbtastic","unite-farbtastic","js/farbtastic");
					EWPHelper::addStyle("farbtastic","unite-farbtastic","js/farbtastic");
				break;
				default:
					UniteFunctionsUC::throwError("Wrong color picker typ: ".$colorPickerType);
				break;
			}
						
			
			EWPHelper::addScript("jquery.tipsy","tipsy-js");
			
			//font awsome - from admin always load the 5
			$urlFontAwesomeCSS = EWPHelper::getUrlFontAwesome();
			EWPHelper::addStyleAbsoluteUrl($urlFontAwesomeCSS, "font-awesome");
			
			EWPHelper::addScript("settings", "unitecreator_settings");
			EWPHelper::addScript("admin","unitecreator_admin");
			EWPHelper::addStyle("admin","unitecreator_admin_css");
						
			EWPHelper::addScriptAbsoluteUrl(GlobalsUC::$url_provider."assets/provider_admin.js", "provider_admin_js");
		}
		
		
		/**
		 * 
		 * a must function. adds scripts on the page
		 * add all page scripts and styles here.
		 * pelase don't remove this function
		 * common scripts even if the plugin not load, use this function only if no choise.
		 */
		public static function onAddScripts(){
			
			self::addMustScripts();
			
			EWPHelper::addScript("unitecreator_assets", "unitecreator_assets");
			EWPHelper::addStyle("unitecreator_styles","unitecreator_css","css");
			
			$viewForIncludes = self::$view;
			
			//take from view aliased if exists
			if(isset(GlobalsUC::$arrViewAliases[$viewForIncludes]))
				$viewForIncludes = GlobalsUC::$arrViewAliases[$viewForIncludes];
			
			
			//include dropzone
			switch ($viewForIncludes){
				case GlobalsUC::VIEW_EDIT_ADDON:
				case GlobalsUC::VIEW_ASSETS:
					
					EWPHelper::addScript("jquery.dialogextend.min", "jquery-ui-dialogextend","js/dialog_extend", true);
					
					//dropzone
					EWPHelper::addScript("dropzone", "dropzone_js","js/dropzone");
					EWPHelper::addStyle("dropzone", "dropzone_css","js/dropzone");
					
					//select 2
					EWPHelper::addScript("select2.full.min", "select2_js","js/select2");
					EWPHelper::addStyle("select2", "select2_css","js/select2");
					
					
					//include codemirror
					EWPHelper::addScript("codemirror.min", "codemirror_js","js/codemirror");
					EWPHelper::addScript("css", "codemirror_cssjs","js/codemirror/mode/css");
					EWPHelper::addScript("javascript", "codemirror_javascript","js/codemirror/mode/javascript");
					EWPHelper::addScript("xml", "codemirror_xml","js/codemirror/mode/xml");
					EWPHelper::addScript("htmlmixed", "codemirror_html","js/codemirror/mode/htmlmixed");
					
					EWPHelper::addStyle("codemirror", "codemirror_css","js/codemirror");
					
					EWPHelper::addScript("unitecreator_includes", "unitecreator_includes");
					EWPHelper::addScript("unitecreator_params_dialog", "unitecreator_params_dialog");
					EWPHelper::addScript("unitecreator_params_editor", "unitecreator_params_editor");
					EWPHelper::addScript("unitecreator_params_panel", "unitecreator_params_panel");
					EWPHelper::addScript("unitecreator_variables", "unitecreator_variables");					
					EWPHelper::addScript("unitecreator_admin", "unitecreator_view_admin");
				break;
				case GlobalsUC::VIEW_TEST_ADDON:
					
					self::onAddScriptsBrowser();
					UniteCreatorManager::putScriptsIncludes(UniteCreatorManager::TYPE_ITEMS_INLINE);
					
					EWPHelper::addScript("unitecreator_addon_config", "unitecreator_addon_config");
					EWPHelper::addStyle("unitecreator_admin_front","unitecreator_admin_front_css");
					EWPHelper::addScript("unitecreator_testaddon_admin");
					EWPHelper::addStyle("unitecreator_browser","unitecreator_browser_css");
					
				break;
				case GlobalsUC::VIEW_ADDON_DEFAULTS:
					
					self::onAddScriptsBrowser();
					
					UniteCreatorManager::putScriptsIncludes(UniteCreatorManager::TYPE_ITEMS_INLINE);
					
					EWPHelper::addScript("unitecreator_addon_config", "unitecreator_addon_config");
					EWPHelper::addStyle("unitecreator_admin_front","unitecreator_admin_front_css");
					EWPHelper::addScript("unitecreator_addondefaults_admin");
					EWPHelper::addStyle("unitecreator_browser","unitecreator_browser_css");
					
				break;
				case GlobalsUC::VIEW_SETTINGS:
				case GlobalsUC::VIEW_LAYOUTS_SETTINGS:
					
					EWPHelper::addScript("unitecreator_admin_generalsettings", "unitecreator_admin_generalsettings");
					
				break;
				case GlobalsUC::VIEW_TEMPLATES_LIST:
				case GlobalsUC::VIEW_LAYOUTS_LIST:
					
					self::onAddScriptsBrowser();
					
					UniteCreatorManager::putScriptsIncludes(UniteCreatorManager::TYPE_PAGES);
					
					EWPHelper::addScript("unitecreator_admin_layouts", "unitecreator_admin_layouts");
					
				break;
				case GlobalsUC::VIEW_LAYOUT_IFRAME:
					self::onAddScriptsGridEditor();
				break;
				case GlobalsUC::VIEW_LAYOUT:
					
					self::onAddScriptsGridEditor(true);
					
				break;
				default:
				case GlobalsUC::VIEW_ADDONS_LIST:
					UniteCreatorManager::putScriptsIncludes(UniteCreatorManager::TYPE_ADDONS);
				break;
				case "sort_pages":
				case "sort_sections":
					UniteCreatorManager::putScriptsIncludes(UniteCreatorManager::TYPE_PAGES);
				break;
				
			}

			//provider admin css always comes to end
			EWPHelper::addStyleAbsoluteUrl(GlobalsUC::$url_provider."assets/provider_admin.css", "provider_admin_css");
			
			UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_ADD_ADMIN_SCRIPTS);
			
		}
		
		
		/**
		 * add settings base options
		 */
		public static function addScripts_settingsBase($specialSettings = ""){
			
			//include those scripts only once
			if(self::$isScriptsIncluded_settingsBase == true)
				return(false);
			
			self::addMustScripts($specialSettings);
			
			EWPHelper::addStyle("unitecreator_admin_front","unitecreator_admin_front_css");
			
			UniteCreatorManager::putScriptsIncludes(UniteCreatorManager::TYPE_ITEMS_INLINE);
			
			self::$isScriptsIncluded_settingsBase = true;
		}
		
		
		/**
		 * add scripts only for the browser
		 */
		public static function onAddScriptsBrowser(){
			
			self::addScripts_settingsBase();
			
			EWPHelper::addStyle("unitecreator_browser","unitecreator_browser_css");
			EWPHelper::addScript("unitecreator_browser", "unitecreator_browser");			
			EWPHelper::addScript("unitecreator_addon_config", "unitecreator_addon_config");
			
		}
		
		
		/**
		 * set globals by addon type
		 */
		public static function setAdminGlobalsByAddonType($objAddonType = null, $objAddon = null){
			
			if(empty($objAddonType))
				return($objAddonType);
				
			if(is_string($objAddonType))
				UniteFunctionsUC::throwError("The addon type should be object");
			
			if(!empty($objAddon)){
				
				GlobalsUC::$objActiveAddonForAssets = $objAddon;
			}
				
			$pathAssets = EWPHelper::getAssetsPath($objAddonType);
			
			if($pathAssets != GlobalsUC::$pathAssets){
				
				GlobalsUC::$pathAssets = $pathAssets;
				
				GlobalsUC::$url_assets = EWPHelper::getAssetsUrl($objAddonType);
			}
			
		}
		
		
		/**
		 * add grid editor scripts. include the browser scripts in them
		 */
		public static function onAddScriptsGridEditor($isOuter = false){
			
			if($isOuter == true){
				
				EWPHelper::addScript("unitecreator_page_builder", "unitecreator_page_builder");
			}
						
			self::onAddScriptsBrowser();
			
			EWPHelper::putAnimationIncludes(true);
									
			EWPHelper::addScript("unitecreator_grid_builder", "unitecreator_grid_editor");
			EWPHelper::addScript("unitecreator_grid_actions_panel", "unitecreator_grid_actions_panel");
			EWPHelper::addScript("unitecreator_grid_panel", "unitecreator_grid_panel");
			EWPHelper::addScript("unitecreator_grid_objects", "unitecreator_grid_objects");
			
			//grid builder (inside iframe)
			if($isOuter == false){
				EWPHelper::putSmoothScrollIncludes();				
			}
			
		}
		
		
		/**
		 * validate required php extensions
		 */
		private function validatePHPExtensions(){
			
			//check curl
			if(function_exists("curl_init") == false)
				EWPHelper::addAdminNotice("Your PHP is missing \"CURL\" Extension. Blox needs this extension. Please enable it in php.ini");
							
		}
		
		
		/**
		 * 
		 * admin main page function.
		 */
		public function adminPages(){
			
			$this->validatePHPExtensions();
			// echo 'media select: ' . GlobalsUC::VIEW_MEDIA_SELECT . '<br/>';
			// echo 'view: ' . self::$view . '<br/>';
			if(self::$view != GlobalsUC::VIEW_MEDIA_SELECT)
				self::setMasterView("master_view");
			
			self::requireView(self::$view);
			
		}
		
		
		
		/**
		 * 
		 * onAjax action handler
		 */
		public static function onAjaxAction(){
			
			$objActions = new UniteCreatorActions();
			$objActions->onAjaxAction();
			
		}
		
	}
	
	
?>