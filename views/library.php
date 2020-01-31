<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');


class UniteCreatorLibraryView{
	
	protected $showButtons = true;
	protected $showHeader = true;
	
	protected $arrPages = array();
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->init();
		$this->putHtml();
	}
	
	/**
	 * init the pages
	 */
	protected function init(){
		
		$urlAddons = EWPHelper::getViewUrl_Addons();
		$urlDividers = EWPHelper::getViewUrl_Addons(GlobalsUC::ADDON_TYPE_SHAPE_DEVIDER);
		$urlShapes = EWPHelper::getViewUrl_Addons(GlobalsUC::ADDON_TYPE_SHAPES);
		$urlBGAddons = EWPHelper::getViewUrl_Addons(GlobalsUC::ADDON_TYPE_BGADDON);
		
		
		$urlSections = EWPHelper::getViewUrl_LayoutsList(array(), GlobalsUC::ADDON_TYPE_LAYOUT_SECTION);
		
		$textAddons = esc_html__("My Addons", "unlimited_elementor_elements");
		$textDividers = esc_html__("Dividers", "unlimited_elementor_elements");
		$textShapes = esc_html__("Shapes", "unlimited_elementor_elements");
		$textSection = esc_html__("Sections", "unlimited_elementor_elements");
		$textPageTemplates = esc_html__("Page Templates", "unlimited_elementor_elements");
		$textBackgroundAddons = esc_html__("Background Addons", "unlimited_elementor_elements");
		
		$defaultIcon = "puzzle-piece";
		
		$this->addPage($urlAddons, $textAddons, $defaultIcon);
		$this->addPage($urlBGAddons, $textBackgroundAddons, $defaultIcon);
		$this->addPage($urlDividers, $textDividers, "map");
		$this->addPage($urlShapes, $textShapes, "map");
		$this->addPage($urlSections, $textSection, $defaultIcon);
		
		
	}
	
	
	/**
	 * get header text
	 * @return unknown
	 */
	protected function getHeaderText(){
		$headerTitle = esc_html__("My Library", "unlimited_elementor_elements");
		return($headerTitle);
	}
	
	/**
	 * add page
	 */
	protected function addPage($url, $title, $icon){
		
		$this->arrPages[] = array(
			"url"=>$url,
			"title"=>$title,
			"icon"=>$icon);
		
	}
	
	/**
	 * show buttons panel
	 */
	protected function putHtmlButtonsPanel(){
		
		$urlLayouts = EWPHelper::getViewUrl_LayoutsList();
		$urlAddons = EWPHelper::getViewUrl_Addons();
		
		?>
		<div class="uc-buttons-panel unite-clearfix">
			<a href="<?php echo esc_attr($urlLayouts)?>" class="unite-float-right mleft_20 unite-button-secondary"><?php EWPHelper::putText("my_layouts")?></a>
			<a href="<?php echo esc_attr($urlAddons)?>" class="unite-float-right mleft_20 unite-button-secondary"><?php esc_html_e("My Addons", "unlimited_elementor_elements")?></a>
			
		</div>
		
		<?php 
	}
	
	
	/**
	 * put pages html
	 */
	protected function putHtmlPages(){
		
		if($this->showHeader == true){
			
			$headerTitle = $this->getHeaderText();
			
			require EWPHelper::getPathTemplate("header");
		}else
			require EWPHelper::getPathTemplate("header_missing");
		
		if($this->showButtons == true)
			$this->putHtmlButtonsPanel();
		
		?>
		
		<div class="content_wrapper unite-content-wrapper">
			
		
		<ul class='uc-list-pages-thumbs'>
		<?php 
		foreach($this->arrPages as $page){
			
			$url = $page["url"];
			$icon = $page["icon"];
			
			if(empty($icon))
				$icon = "angellist";
			
			$title = $page["title"];
				
			?>
			<li>				
				<a href="<?php echo esc_attr($url)?>">
					<i class="fa fa-<?php echo esc_attr($icon)?>"></i>
					<?php echo esc_html($title)?>
				</a>
			</li>
			<?php 
		}
		?>
		</ul>
		
		</div>
		
		<?php 
		
	}
	
	
	/**
	 * constructor
	 */
	protected function putHtml(){
		
		$this->putHtmlPages();
		
	}

}

$pathProviderAddons = GlobalsUC::$pathProvider."views/library.php";

if(file_exists($pathProviderAddons) == true){
	require_once $pathProviderAddons;
	new UniteCreatorLibraryViewProvider();
}
else{
	new UniteCreatorLibraryView();
}

