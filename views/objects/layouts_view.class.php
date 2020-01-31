<?php
/**
 * @package Unlimited Elements
 * @author UniteCMS.net
 * @copyright (C) 2017 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('ELEMENTOR_WIDGET_PRO_INC') or die('Restricted access');


class UniteCreatorLayoutsView{
	
	protected $isTemplate = false;
	protected $layoutType, $layoutTypeTitle, $layoutTypeTitlePlural;
	protected $objLayoutType;
	
	protected $showButtonsPanel = true, $showHeaderTitle = true;
	protected $showColCategory = true, $showColShortcode = true;
	protected $isDisplayTable = true;
	protected $objTable, $urlViewCreateObject, $urlManageAddons;
	protected $arrLayouts, $pageBuilder, $objLayouts, $objManager;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		$this->objTable = new UniteTableUC();
		$this->pageBuilder = new UniteCreatorPageBuilder();
		$this->objLayouts = new UniteCreatorLayouts();
		
	}
	
	private function z_INIT(){}
	
	
	/**
	 * set templates text
	 */
	protected function getTemplatesTextArray(){
		
		$pluralLower = strtolower($this->layoutTypeTitlePlural);
		$titleLower = strtolower($this->layoutTypeTitle);
		
		
		$arrText = array(
			"import_layout"=>esc_html__("Import ","unlimited_elementor_elements").$this->layoutTypeTitle,
			"import_layouts"=>esc_html__("Import ","unlimited_elementor_elements").$this->layoutTypeTitlePlural,
			"uploading_layouts_file"=>esc_html__("Uploading ","unlimited_elementor_elements"). $this->layoutTypeTitlePlural. esc_html__("  file...","unlimited_elementor_elements"),
			"layouts_added_successfully"=> $this->layoutTypeTitle.esc_html__(" Added Successfully","unlimited_elementor_elements"),
			"my_layouts"=>esc_html__("My ","unlimited_elementor_elements").$this->layoutTypeTitlePlural,
			"search_layout"=> esc_html__("Search","unlimited_elementor_elements")." ". $this->layoutTypeTitlePlural,
			"layout_title"=>$this->layoutTypeTitle." ". esc_html__("Title","unlimited_elementor_elements"),
			"no_layouts_found"=>esc_html__("No","unlimited_elementor_elements")." ".$this->layoutTypeTitlePlural. " ". esc_html__("Found","unlimited_elementor_elements"),
			"are_you_sure_to_delete_this_layout"=>esc_html__("Are you sure to delete this ?","unlimited_elementor_elements").$titleLower,
			"edit_layout"=>esc_html__("Edit","unlimited_elementor_elements")." ".$this->layoutTypeTitle,
			"manage_layout_categories"=>esc_html__("Manage ","unlimited_elementor_elements"). $this->layoutTypeTitlePlural. esc_html__(" Categories","unlimited_elementor_elements"),
			"select_layouts_export_file"=>esc_html__("Select ","unlimited_elementor_elements"). $pluralLower.  esc_html__(" export file","unlimited_elementor_elements"),
			"new_layout"=>esc_html__("New","unlimited_elementor_elements")." ". $this->layoutTypeTitle,
		);
		
		return($arrText);
	}
	
	
	/**
	 * set templat etype
	 */
	public function setLayoutType($layoutType){
		
		$this->layoutType = $layoutType;
		
		$this->objLayoutType = UniteCreatorAddonType::getAddonTypeObject($layoutType, true);
		
		//set title
		$this->layoutTypeTitle = $this->objLayoutType->textSingle;
		$this->layoutTypeTitlePlural = $this->objLayoutType->textPlural;
		
		//set text
		$arrText = $this->getTemplatesTextArray();
		
		EWPHelper::setLocalText($arrText);
		
		//set other settings
		$this->isTemplate = $this->objLayoutType->isTemplate;
		$this->showColCategory = $this->objLayoutType->enblCategories;
		$this->showColShortcode = $this->objLayoutType->enableShortcodes;
		
		//set display type manager / table
		$displayType = UniteFunctionsUC::getGetVar("displaytype", "",UniteFunctionsUC::SANITIZE_TEXT_FIELD);
		if(empty($displayType))
			$displayType = $this->objLayoutType->displayType;
		
			
		if($displayType == UniteCreatorAddonType_Layout::DISPLAYTYPE_MANAGER)
			$this->isDisplayTable = false;
		
	}
	
	
	/**
	 * validate inited
	 */
	protected function validateInited(){
		
		if(empty($this->objLayoutType))
			UniteFunctionsUC::throwError("The layout type not inited, please use : setLayoutType function");
		
		if($this->objLayoutType->isLayout == false)
			UniteFunctionsUC::throwError("The layout type should be layout type, now: ".$this->objLayoutType->textShowType);
		
			
	}
	
	/**
	 * init display vars table related
	 */
	protected function initDisplayVars_table(){
		
		$this->objTable->setDefaultOrderby("title");
		
		$pagingOptions = $this->objTable->getPagingOptions();
		
		if(!empty($this->layoutType)){
			$pagingOptions["layout_type"] = $this->layoutType;
		}
		
		$response = $this->objLayouts->getArrLayoutsPaging($pagingOptions);
		
		$this->arrLayouts = $response["layouts"];
		$pagingData = $response["paging"];
		
		$urlLayouts = EWPHelper::getViewUrl_LayoutsList();
		
		$this->objTable->setPagingData($urlLayouts, $pagingData);
		
	}
	
	
	/**
	 * 
	 * init manager display vars
	 */
	protected function initDisplayVars_manager(){
		
		$this->objManager = new UniteCreatorManagerLayouts();
		$this->objManager->init($this->layoutType);
		
	}
	
	
	/**
	 * init display vars
	 */
	protected function initDisplayVars(){
		
		//init layout type		
		$this->urlViewCreateObject = EWPHelper::getViewUrl_Layout();
		$this->urlManageAddons = EWPHelper::getViewUrl_Addons();
			
		
		if($this->showHeaderTitle == true){
			$headerTitle = EWPHelper::getText("my_layouts");
			require EWPHelper::getPathTemplate("header");
		}else
			require EWPHelper::getPathTemplate("header_missing");
		
		//table object
		if($this->isDisplayTable == true)
			$this->initDisplayVars_table();
		else
			$this->initDisplayVars_manager();
	}
	
	
	private function z_PUT_HTML(){}
	
	
	/**
	 * put page catalog browser
	 */
	public function putDialogPageCatalog(){
		
		$webAPI = new UniteCreatorWebAPI();
		$isPageCatalogExists = $webAPI->isPagesCatalogExists();
		if($isPageCatalogExists == false)
			return(false);
		
		$objBrowser = new UniteCreatorBrowser();		
		$objBrowser->initAddonType(GlobalsUC::ADDON_TYPE_REGULAR_LAYOUT);
		$objBrowser->putBrowser();
		
	}
	
	
	/**
	 * put manage categories dialog
	 */
	public function putDialogCategories(){
		
		$prefix = "uc_dialog_add_category";
		
		?>
			<div id="uc_dialog_add_category"  title="<?php EWPHelper::putText("manage_layout_categories")?>" style="display:none; height: 300px;" class="unite-inputs">
				
				<div class="unite-dialog-top">
				
					<input type="text" class="uc-catdialog-button-clearfilter" style="margin-bottom: 1px;">
					<a class='uc-catdialog-button-filter unite-button-secondary' href="javascript:void(0)"><?php esc_html_e("Filter", "unlimited_elementor_elements")?></a>
					<a class='uc-catdialog-button-filter-clear unite-button-secondary' href="javascript:void(0)"><?php esc_html_e("Clear Filter", "unlimited_elementor_elements")?></a>
					
					<span class="uc-catlist-sort-wrapper">
					
						<?php esc_html_e("Sort: ","unlimited_elementor_elements")?>
						<a href="javascript:void(0)" class="uc-link-change-cat-sort" data-type="a-z">a-z</a>
						, 
						<a href="javascript:void(0)" class="uc-link-change-cat-sort" data-type="z-a">z-a</a>
					</span>
					
				</div>
				
				<div id="list_layouts_cats" class="uc-categories-list"></div>
				
				<hr/>
				
					<?php esc_html_e("Add New Category", "unlimited_elementor_elements")?>: 
					<input id="uc_dialog_add_category_catname" type="text" class="unite-input-regular" value="">
					
					<a id="uc_dialog_add_category_button_add" href="javascript:void(0)" class="unite-button-secondary" data-action="add_category"><?php esc_html_e("Create Category", "unlimited_elementor_elements")?></a>
					
				<div>
				
					<?php 
					$buttonTitle = esc_html__("Set Category to Page", "unlimited_elementor_elements");
					$loaderTitle = esc_html__("Updating Category...", "unlimited_elementor_elements");
					$successTitle = esc_html__("Category Updated", "unlimited_elementor_elements");
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
				
				
			</div>
			
			<div id="uc_layout_categories_message" title="<?php esc_html_e("Categories Message", "unlimited_elementor_elements")?>">
			</div>
			
		</div>
		
		<?php 
	}
	
	
	/**
	 * put import addons dialog
	 */
	public function putDialogImportLayout(){
	
		$dialogTitle = EWPHelper::getText("import_layout");
		
		?>
		
			<div id="uc_dialog_import_layouts" class="unite-inputs" title="<?php echo esc_attr($dialogTitle)?>" style="display:none;">
				
				<div class="unite-dialog-top"></div>
				
				<div class="unite-inputs-label">
					<?php EWPHelper::putText("select_layouts_export_file")?>:
				</div>
				
				<form id="dialog_import_layouts_form" name="form_import_layouts">
					<input id="dialog_import_layouts_file" type="file" name="import_layout">
							
				</form>	
				
				<div class="unite-inputs-sap-double"></div>
				
				<div class="unite-inputs-label" >
					<label for="dialog_import_layouts_file_overwrite">
						<?php esc_html_e("Overwrite Addons", "unlimited_elementor_elements")?>:
					</label>
					<input type="checkbox" id="dialog_import_layouts_file_overwrite">
				</div>
				
				
				<div class="unite-clear"></div>
				
				<?php 
					$prefix = "uc_dialog_import_layouts";
					
					$buttonTitle = EWPHelper::getText("import_layouts");
					$loaderTitle = EWPHelper::getText("uploading_layouts_file");
					$successTitle = EWPHelper::getText("layouts_added_successfully");
					
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
					
			</div>		
		
	<?php
	}
	
	
	
	
	/**
	 * put buttons panel html
	 */
	protected function putHtmlButtonsPanel(){
				
		?>
		<div class="uc-buttons-panel unite-clearfix">
			<a href="<?php echo esc_attr($this->urlViewCreateObject)?>" class="unite-button-primary unite-float-left"><?php EWPHelper::putText("new_layout");?></a>
			
			<a id="uc_button_import_layout" href="javascript:void(0)" class="unite-button-secondary unite-float-left mleft_20"><?php EWPHelper::putText("import_layouts");?></a>
			
			<a href="javascript:void(0)" id="uc_layouts_global_settings" class="unite-float-right mright_20 unite-button-secondary"><?php EWPHelper::putText("layouts_global_settings");?></a>
			<a href="<?php echo esc_attr($this->urlManageAddons)?>" class="unite-float-right mright_20 unite-button-secondary"><?php esc_html_e("My Addons", "unlimited_elementor_elements")?></a>
			
		</div>
		<?php 
	}
	
	/**
	 * display notice
	 */
	protected function putHtmlTemplatesNotice(){
		
		if($this->isTemplate == false)
			return(false);
			
		?>
			<div class="uc-layouts-notice"> Notice - The templates will work for only if the blox template selected</div>
		<?php 
	}
	
	
	/**
	 * put layout type tabs
	 */
	public function putLayoutTypeTabs(){
		
		
		dmp("get all template types");
		exit();
		
		?>
		<div class="uc-layout-type-tabs-wrapper">
			
			<?php foreach($arrLayoutTypes as $type => $arrType):

				$tabTitle = UniteFunctionsUC::getVal($arrType, "plural");
				
				$urlView = EWPHelper::getViewUrl_TemplatesList(null, $type);
				
				$addClass = "";
				if($type == $this->layoutType){
					$addClass = " uc-tab-selected";
					$urlView = "javascript:void(0)";
				}
				
			?>
			<a href="<?php echo esc_attr($urlView)?>" class="uc-tab-layouttype<?php echo esc_attr($addClass)?>"><?php echo esc_html($tabTitle)?></a>
			
			<?php endforeach?>
						
		</div>
		<?php 
		
	}
	
	/**
	 * display manager
	 */
	public function displayManager(){
		
		$this->objManager->outputHtml();
				
	}
	
	
	/**
	 * display table view
	 */
	public function display(){
		
		$this->validateInited();
		$this->initDisplayVars();
		
		if($this->isDisplayTable)
			$this->displayTable();
		else
			$this->displayManager();
			
	}
	
	
	/**
	 * display layouts view
	 */
	public function displayTable(){
				
		$sizeActions = UniteProviderFunctionsUC::applyFilters(UniteCreatorFilters::FILTER_LAYOUTS_ACTIONS_COL_WIDTH, 380);
		
		$numLayouts = count($this->arrLayouts);

		
?>
	<?php if($this->showButtonsPanel == true)
			$this->putHtmlButtonsPanel();
	?>

	<div class="unite-content-wrapper">
								
		<?php
		
		$this->objTable->putActionsFormStart();
		
		if($this->isTemplate == true)
			$this->putLayoutTypeTabs();
		
		?>
		<div class="unite-table-filters">
		
		
		<?php 
		$this->objTable->putSearchForm(EWPHelper::getText("search_layout"), "Clear");
		
			if($this->isTemplate == false):
			
				$this->objTable->putFilterCategory();
		
			endif;
		
		?>
		
		</div>
		
		<?php if(empty($this->arrLayouts)): ?>
		<div class="uc-no-layouts-wrapper">
			<?php EWPHelper::putText("no_layouts_found");?>
		</div>			
		<?php else:?>
	
			<!-- sort chars: &#8743 , &#8744; -->
			
			<table id="uc_table_layouts" class='unite_table_items' data-text-delete="<?php EWPHelper::putText("are_you_sure_to_delete_this_layout")?>">
				<thead>
					<tr>
						<th width=''>
							<?php $this->objTable->putTableOrderHeader("title", EWPHelper::getText("layout_title")) ?>
						</th>
						
						<?php if($this->showColShortcode == true):?>
						<th width='200'><?php esc_html_e("Shortcode","unlimited_elementor_elements"); ?></th>
						<?php endif?>
						
						<?php if($this->showColCategory == true):?>
						<th width='200'><?php $this->objTable->putTableOrderHeader("catid", esc_html__("Category","unlimited_elementor_elements")) ?>
						<?php endif?>
						
						<th width='<?php echo esc_attr($sizeActions)?>'><?php esc_html_e("Actions","unlimited_elementor_elements"); ?></th>
						<th width='60'><?php esc_html_e("Preview","unlimited_elementor_elements"); ?></th>						
					</tr>
				</thead>
				<tbody>

					<?php foreach($this->arrLayouts as $key=>$layout):
						
						$id = $layout->getID();
																
						$title = $layout->getTitle();

						$shortcode = $layout->getShortcode();
						$shortcode = UniteFunctionsUC::sanitizeAttr($shortcode);
												
						$editLink = EWPHelper::getViewUrl_Layout($id);
												
						$previewLink = EWPHelper::getViewUrl_LayoutPreview($id, true);
						
						$showTitle = HelperHtmlUC::getHtmlLink($editLink, $title);
						
						$rowClass = ($key%2==0)?"unite-row1":"unite-row2";
						
						$arrCategory = $layout->getCategory();
						
						$catID = UniteFunctionsUC::getVal($arrCategory, "id");
						$catTitle = UniteFunctionsUC::getVal($arrCategory, "name");
						
					?>
						<tr class="<?php echo esc_attr($rowClass)?>">
							<td><?php echo esc_html($showTitle)?></td>
							
							<?php if($this->showColShortcode):?>
							
							<td>
								<input type="text" readonly onfocus="this.select()" class="unite-input-medium unite-cursor-text" value="<?php echo esc_attr($shortcode)?>" />
							</td>
							
							<?php endif?>
							
							<?php if($this->showColCategory):?>
							
							<td><a href="javascript:void(0)" class="uc-layouts-list-category" data-layoutid="<?php echo esc_attr($id)?>" data-catid="<?php echo esc_attr($catID)?>" data-action="manage_category"><?php echo esc_html($catTitle)?></a></td>
							
							<?php endif?>
							
							<td>
								<a href='<?php echo esc_attr($editLink)?>' class="unite-button-primary float_left mleft_15"><?php EWPHelper::putText("edit_layout"); ?></a>
								
								<a href='javascript:void(0)' data-layoutid="<?php echo esc_attr($id)?>" data-id="<?php echo esc_attr($id)?>" class="button_delete unite-button-secondary float_left mleft_15"><?php esc_html_e("Delete","unlimited_elementor_elements"); ?></a>
								<span class="loader_text uc-loader-delete" style="display:none"><?php esc_html_e("Deleting", "unlimited_elementor_elements")?></span>
								<a href='javascript:void(0)' data-layoutid="<?php echo esc_attr($id)?>" data-id="<?php echo esc_attr($id)?>" class="button_duplicate unite-button-secondary float_left mleft_15"><?php esc_html_e("Duplicate","unlimited_elementor_elements"); ?></a>
								<span class="loader_text uc-loader-duplicate" style="display:none"><?php esc_html_e("Duplicating", "unlimited_elementor_elements")?></span>
								<a href='javascript:void(0)' data-layoutid="<?php echo esc_attr($id)?>" data-id="<?php echo esc_attr($id)?>" class="button_export unite-button-secondary float_left mleft_15"><?php esc_html_e("Export","unlimited_elementor_elements"); ?></a>
								<?php UniteProviderFunctionsUC::doAction(UniteCreatorFilters::ACTION_LAYOUTS_LIST_ACTIONS, $id); ?>
							</td>
							<td>
								<a href='<?php echo esc_attr($previewLink)?>' target="_blank" class="unite-button-secondary float_left"><?php esc_html_e("Preview","unlimited_elementor_elements"); ?></a>					
							</td>
						</tr>							
					<?php endforeach;?>
					
				</tbody>		 
			</table>
			
			<?php 
			
				$this->objTable->putPaginationHtml();				
				$this->objTable->putInpageSelect();
				
			?>
			
		<?php endif?>
		
		<?php
		 
			$this->objTable->putActionsFormEnd();
			
			$this->pageBuilder->putLayoutsGlobalSettingsDialog();
			$this->putDialogImportLayout();
			
			$this->putDialogCategories();
			
			//put pages catalog if exists
			$this->putDialogPageCatalog();
		?>
		
		
	</div>
	
<script type="text/javascript">

	jQuery(document).ready(function(){

		var objAdmin = new UniteCreatorAdmin_LayoutsList();
		objAdmin.initObjectsListView();
		
	});

</script>

	<?php 	
		
	}
	
}

