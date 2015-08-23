<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "servicesinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$services_list = NULL; // Initialize page object first

class cservices_list extends cservices {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'services';

	// Page object name
	var $PageObjName = 'services_list';

	// Grid form hidden field names
	var $FormName = 'fserviceslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (services)
		if (!isset($GLOBALS["services"]) || get_class($GLOBALS["services"]) == "cservices") {
			$GLOBALS["services"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["services"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "servicesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "servicesdelete.php";
		$this->MultiUpdateUrl = "servicesupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'services', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fserviceslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->servicesid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $services;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($services);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->servicesid->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->servicesid->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->servicesid->AdvancedSearch->ToJSON(), ","); // Field servicesid
		$sFilterList = ew_Concat($sFilterList, $this->servicename->AdvancedSearch->ToJSON(), ","); // Field servicename
		$sFilterList = ew_Concat($sFilterList, $this->facility->AdvancedSearch->ToJSON(), ","); // Field facility
		$sFilterList = ew_Concat($sFilterList, $this->community->AdvancedSearch->ToJSON(), ","); // Field community
		$sFilterList = ew_Concat($sFilterList, $this->address_street->AdvancedSearch->ToJSON(), ","); // Field address_street
		$sFilterList = ew_Concat($sFilterList, $this->address_city->AdvancedSearch->ToJSON(), ","); // Field address_city
		$sFilterList = ew_Concat($sFilterList, $this->address_postcode->AdvancedSearch->ToJSON(), ","); // Field address_postcode
		$sFilterList = ew_Concat($sFilterList, $this->phone->AdvancedSearch->ToJSON(), ","); // Field phone
		$sFilterList = ew_Concat($sFilterList, $this->website->AdvancedSearch->ToJSON(), ","); // Field website
		$sFilterList = ew_Concat($sFilterList, $this->peopleserved_gender->AdvancedSearch->ToJSON(), ","); // Field peopleserved_gender
		$sFilterList = ew_Concat($sFilterList, $this->peopleserved_age->AdvancedSearch->ToJSON(), ","); // Field peopleserved_age
		$sFilterList = ew_Concat($sFilterList, $this->programtype->AdvancedSearch->ToJSON(), ","); // Field programtype
		$sFilterList = ew_Concat($sFilterList, $this->programfocus->AdvancedSearch->ToJSON(), ","); // Field programfocus
		$sFilterList = ew_Concat($sFilterList, $this->zone->AdvancedSearch->ToJSON(), ","); // Field zone
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"psearch\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"psearchtype\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field servicesid
		$this->servicesid->AdvancedSearch->SearchValue = @$filter["x_servicesid"];
		$this->servicesid->AdvancedSearch->SearchOperator = @$filter["z_servicesid"];
		$this->servicesid->AdvancedSearch->SearchCondition = @$filter["v_servicesid"];
		$this->servicesid->AdvancedSearch->SearchValue2 = @$filter["y_servicesid"];
		$this->servicesid->AdvancedSearch->SearchOperator2 = @$filter["w_servicesid"];
		$this->servicesid->AdvancedSearch->Save();

		// Field servicename
		$this->servicename->AdvancedSearch->SearchValue = @$filter["x_servicename"];
		$this->servicename->AdvancedSearch->SearchOperator = @$filter["z_servicename"];
		$this->servicename->AdvancedSearch->SearchCondition = @$filter["v_servicename"];
		$this->servicename->AdvancedSearch->SearchValue2 = @$filter["y_servicename"];
		$this->servicename->AdvancedSearch->SearchOperator2 = @$filter["w_servicename"];
		$this->servicename->AdvancedSearch->Save();

		// Field facility
		$this->facility->AdvancedSearch->SearchValue = @$filter["x_facility"];
		$this->facility->AdvancedSearch->SearchOperator = @$filter["z_facility"];
		$this->facility->AdvancedSearch->SearchCondition = @$filter["v_facility"];
		$this->facility->AdvancedSearch->SearchValue2 = @$filter["y_facility"];
		$this->facility->AdvancedSearch->SearchOperator2 = @$filter["w_facility"];
		$this->facility->AdvancedSearch->Save();

		// Field community
		$this->community->AdvancedSearch->SearchValue = @$filter["x_community"];
		$this->community->AdvancedSearch->SearchOperator = @$filter["z_community"];
		$this->community->AdvancedSearch->SearchCondition = @$filter["v_community"];
		$this->community->AdvancedSearch->SearchValue2 = @$filter["y_community"];
		$this->community->AdvancedSearch->SearchOperator2 = @$filter["w_community"];
		$this->community->AdvancedSearch->Save();

		// Field address_street
		$this->address_street->AdvancedSearch->SearchValue = @$filter["x_address_street"];
		$this->address_street->AdvancedSearch->SearchOperator = @$filter["z_address_street"];
		$this->address_street->AdvancedSearch->SearchCondition = @$filter["v_address_street"];
		$this->address_street->AdvancedSearch->SearchValue2 = @$filter["y_address_street"];
		$this->address_street->AdvancedSearch->SearchOperator2 = @$filter["w_address_street"];
		$this->address_street->AdvancedSearch->Save();

		// Field address_city
		$this->address_city->AdvancedSearch->SearchValue = @$filter["x_address_city"];
		$this->address_city->AdvancedSearch->SearchOperator = @$filter["z_address_city"];
		$this->address_city->AdvancedSearch->SearchCondition = @$filter["v_address_city"];
		$this->address_city->AdvancedSearch->SearchValue2 = @$filter["y_address_city"];
		$this->address_city->AdvancedSearch->SearchOperator2 = @$filter["w_address_city"];
		$this->address_city->AdvancedSearch->Save();

		// Field address_postcode
		$this->address_postcode->AdvancedSearch->SearchValue = @$filter["x_address_postcode"];
		$this->address_postcode->AdvancedSearch->SearchOperator = @$filter["z_address_postcode"];
		$this->address_postcode->AdvancedSearch->SearchCondition = @$filter["v_address_postcode"];
		$this->address_postcode->AdvancedSearch->SearchValue2 = @$filter["y_address_postcode"];
		$this->address_postcode->AdvancedSearch->SearchOperator2 = @$filter["w_address_postcode"];
		$this->address_postcode->AdvancedSearch->Save();

		// Field phone
		$this->phone->AdvancedSearch->SearchValue = @$filter["x_phone"];
		$this->phone->AdvancedSearch->SearchOperator = @$filter["z_phone"];
		$this->phone->AdvancedSearch->SearchCondition = @$filter["v_phone"];
		$this->phone->AdvancedSearch->SearchValue2 = @$filter["y_phone"];
		$this->phone->AdvancedSearch->SearchOperator2 = @$filter["w_phone"];
		$this->phone->AdvancedSearch->Save();

		// Field website
		$this->website->AdvancedSearch->SearchValue = @$filter["x_website"];
		$this->website->AdvancedSearch->SearchOperator = @$filter["z_website"];
		$this->website->AdvancedSearch->SearchCondition = @$filter["v_website"];
		$this->website->AdvancedSearch->SearchValue2 = @$filter["y_website"];
		$this->website->AdvancedSearch->SearchOperator2 = @$filter["w_website"];
		$this->website->AdvancedSearch->Save();

		// Field peopleserved_gender
		$this->peopleserved_gender->AdvancedSearch->SearchValue = @$filter["x_peopleserved_gender"];
		$this->peopleserved_gender->AdvancedSearch->SearchOperator = @$filter["z_peopleserved_gender"];
		$this->peopleserved_gender->AdvancedSearch->SearchCondition = @$filter["v_peopleserved_gender"];
		$this->peopleserved_gender->AdvancedSearch->SearchValue2 = @$filter["y_peopleserved_gender"];
		$this->peopleserved_gender->AdvancedSearch->SearchOperator2 = @$filter["w_peopleserved_gender"];
		$this->peopleserved_gender->AdvancedSearch->Save();

		// Field peopleserved_age
		$this->peopleserved_age->AdvancedSearch->SearchValue = @$filter["x_peopleserved_age"];
		$this->peopleserved_age->AdvancedSearch->SearchOperator = @$filter["z_peopleserved_age"];
		$this->peopleserved_age->AdvancedSearch->SearchCondition = @$filter["v_peopleserved_age"];
		$this->peopleserved_age->AdvancedSearch->SearchValue2 = @$filter["y_peopleserved_age"];
		$this->peopleserved_age->AdvancedSearch->SearchOperator2 = @$filter["w_peopleserved_age"];
		$this->peopleserved_age->AdvancedSearch->Save();

		// Field programtype
		$this->programtype->AdvancedSearch->SearchValue = @$filter["x_programtype"];
		$this->programtype->AdvancedSearch->SearchOperator = @$filter["z_programtype"];
		$this->programtype->AdvancedSearch->SearchCondition = @$filter["v_programtype"];
		$this->programtype->AdvancedSearch->SearchValue2 = @$filter["y_programtype"];
		$this->programtype->AdvancedSearch->SearchOperator2 = @$filter["w_programtype"];
		$this->programtype->AdvancedSearch->Save();

		// Field programfocus
		$this->programfocus->AdvancedSearch->SearchValue = @$filter["x_programfocus"];
		$this->programfocus->AdvancedSearch->SearchOperator = @$filter["z_programfocus"];
		$this->programfocus->AdvancedSearch->SearchCondition = @$filter["v_programfocus"];
		$this->programfocus->AdvancedSearch->SearchValue2 = @$filter["y_programfocus"];
		$this->programfocus->AdvancedSearch->SearchOperator2 = @$filter["w_programfocus"];
		$this->programfocus->AdvancedSearch->Save();

		// Field zone
		$this->zone->AdvancedSearch->SearchValue = @$filter["x_zone"];
		$this->zone->AdvancedSearch->SearchOperator = @$filter["z_zone"];
		$this->zone->AdvancedSearch->SearchCondition = @$filter["v_zone"];
		$this->zone->AdvancedSearch->SearchValue2 = @$filter["y_zone"];
		$this->zone->AdvancedSearch->SearchOperator2 = @$filter["w_zone"];
		$this->zone->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter["psearch"]);
		$this->BasicSearch->setType(@$filter["psearchtype"]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->servicename, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->facility, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->community, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address_street, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address_city, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address_postcode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->website, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->peopleserved_gender, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->peopleserved_age, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->programtype, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->programfocus, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->zone, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->servicesid); // servicesid
			$this->UpdateSort($this->servicename); // servicename
			$this->UpdateSort($this->facility); // facility
			$this->UpdateSort($this->community); // community
			$this->UpdateSort($this->address_street); // address_street
			$this->UpdateSort($this->address_city); // address_city
			$this->UpdateSort($this->address_postcode); // address_postcode
			$this->UpdateSort($this->phone); // phone
			$this->UpdateSort($this->website); // website
			$this->UpdateSort($this->peopleserved_gender); // peopleserved_gender
			$this->UpdateSort($this->peopleserved_age); // peopleserved_age
			$this->UpdateSort($this->programtype); // programtype
			$this->UpdateSort($this->programfocus); // programfocus
			$this->UpdateSort($this->zone); // zone
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->servicesid->setSort("");
				$this->servicename->setSort("");
				$this->facility->setSort("");
				$this->community->setSort("");
				$this->address_street->setSort("");
				$this->address_city->setSort("");
				$this->address_postcode->setSort("");
				$this->phone->setSort("");
				$this->website->setSort("");
				$this->peopleserved_gender->setSort("");
				$this->peopleserved_age->setSort("");
				$this->programtype->setSort("");
				$this->programfocus->setSort("");
				$this->zone->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->servicesid->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fserviceslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fserviceslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fserviceslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fserviceslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->servicesid->setDbValue($rs->fields('servicesid'));
		$this->servicename->setDbValue($rs->fields('servicename'));
		$this->facility->setDbValue($rs->fields('facility'));
		$this->community->setDbValue($rs->fields('community'));
		$this->address_street->setDbValue($rs->fields('address_street'));
		$this->address_city->setDbValue($rs->fields('address_city'));
		$this->address_postcode->setDbValue($rs->fields('address_postcode'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->website->setDbValue($rs->fields('website'));
		$this->peopleserved_gender->setDbValue($rs->fields('peopleserved_gender'));
		$this->peopleserved_age->setDbValue($rs->fields('peopleserved_age'));
		$this->programtype->setDbValue($rs->fields('programtype'));
		$this->programfocus->setDbValue($rs->fields('programfocus'));
		$this->zone->setDbValue($rs->fields('zone'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->servicesid->DbValue = $row['servicesid'];
		$this->servicename->DbValue = $row['servicename'];
		$this->facility->DbValue = $row['facility'];
		$this->community->DbValue = $row['community'];
		$this->address_street->DbValue = $row['address_street'];
		$this->address_city->DbValue = $row['address_city'];
		$this->address_postcode->DbValue = $row['address_postcode'];
		$this->phone->DbValue = $row['phone'];
		$this->website->DbValue = $row['website'];
		$this->peopleserved_gender->DbValue = $row['peopleserved_gender'];
		$this->peopleserved_age->DbValue = $row['peopleserved_age'];
		$this->programtype->DbValue = $row['programtype'];
		$this->programfocus->DbValue = $row['programfocus'];
		$this->zone->DbValue = $row['zone'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("servicesid")) <> "")
			$this->servicesid->CurrentValue = $this->getKey("servicesid"); // servicesid
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// servicesid
		// servicename
		// facility
		// community
		// address_street
		// address_city
		// address_postcode
		// phone
		// website
		// peopleserved_gender
		// peopleserved_age
		// programtype
		// programfocus
		// zone

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// servicesid
		$this->servicesid->ViewValue = $this->servicesid->CurrentValue;
		$this->servicesid->ViewCustomAttributes = "";

		// servicename
		$this->servicename->ViewValue = $this->servicename->CurrentValue;
		$this->servicename->ViewCustomAttributes = "";

		// facility
		$this->facility->ViewValue = $this->facility->CurrentValue;
		$this->facility->ViewCustomAttributes = "";

		// community
		$this->community->ViewValue = $this->community->CurrentValue;
		$this->community->ViewCustomAttributes = "";

		// address_street
		$this->address_street->ViewValue = $this->address_street->CurrentValue;
		$this->address_street->ViewCustomAttributes = "";

		// address_city
		$this->address_city->ViewValue = $this->address_city->CurrentValue;
		$this->address_city->ViewCustomAttributes = "";

		// address_postcode
		$this->address_postcode->ViewValue = $this->address_postcode->CurrentValue;
		$this->address_postcode->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// peopleserved_gender
		if (strval($this->peopleserved_gender->CurrentValue) <> "") {
			$this->peopleserved_gender->ViewValue = $this->peopleserved_gender->OptionCaption($this->peopleserved_gender->CurrentValue);
		} else {
			$this->peopleserved_gender->ViewValue = NULL;
		}
		$this->peopleserved_gender->ViewCustomAttributes = "";

		// peopleserved_age
		if (strval($this->peopleserved_age->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->peopleserved_age->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_age`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->peopleserved_age, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->peopleserved_age->ViewValue = $this->peopleserved_age->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->peopleserved_age->ViewValue = $this->peopleserved_age->CurrentValue;
			}
		} else {
			$this->peopleserved_age->ViewValue = NULL;
		}
		$this->peopleserved_age->ViewCustomAttributes = "";

		// programtype
		if (strval($this->programtype->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->programtype->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_prgtype`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->programtype, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->programtype->ViewValue = $this->programtype->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->programtype->ViewValue = $this->programtype->CurrentValue;
			}
		} else {
			$this->programtype->ViewValue = NULL;
		}
		$this->programtype->ViewCustomAttributes = "";

		// programfocus
		if (strval($this->programfocus->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->programfocus->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_focus`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->programfocus, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->programfocus->ViewValue = $this->programfocus->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->programfocus->ViewValue = $this->programfocus->CurrentValue;
			}
		} else {
			$this->programfocus->ViewValue = NULL;
		}
		$this->programfocus->ViewCustomAttributes = "";

		// zone
		if (strval($this->zone->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->zone->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_zone`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->zone, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->zone->ViewValue = $this->zone->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->zone->ViewValue = $this->zone->CurrentValue;
			}
		} else {
			$this->zone->ViewValue = NULL;
		}
		$this->zone->ViewCustomAttributes = "";

			// servicesid
			$this->servicesid->LinkCustomAttributes = "";
			$this->servicesid->HrefValue = "";
			$this->servicesid->TooltipValue = "";

			// servicename
			$this->servicename->LinkCustomAttributes = "";
			$this->servicename->HrefValue = "";
			$this->servicename->TooltipValue = "";

			// facility
			$this->facility->LinkCustomAttributes = "";
			$this->facility->HrefValue = "";
			$this->facility->TooltipValue = "";

			// community
			$this->community->LinkCustomAttributes = "";
			$this->community->HrefValue = "";
			$this->community->TooltipValue = "";

			// address_street
			$this->address_street->LinkCustomAttributes = "";
			$this->address_street->HrefValue = "";
			$this->address_street->TooltipValue = "";

			// address_city
			$this->address_city->LinkCustomAttributes = "";
			$this->address_city->HrefValue = "";
			$this->address_city->TooltipValue = "";

			// address_postcode
			$this->address_postcode->LinkCustomAttributes = "";
			$this->address_postcode->HrefValue = "";
			$this->address_postcode->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// peopleserved_gender
			$this->peopleserved_gender->LinkCustomAttributes = "";
			$this->peopleserved_gender->HrefValue = "";
			$this->peopleserved_gender->TooltipValue = "";

			// peopleserved_age
			$this->peopleserved_age->LinkCustomAttributes = "";
			$this->peopleserved_age->HrefValue = "";
			$this->peopleserved_age->TooltipValue = "";

			// programtype
			$this->programtype->LinkCustomAttributes = "";
			$this->programtype->HrefValue = "";
			$this->programtype->TooltipValue = "";

			// programfocus
			$this->programfocus->LinkCustomAttributes = "";
			$this->programfocus->HrefValue = "";
			$this->programfocus->TooltipValue = "";

			// zone
			$this->zone->LinkCustomAttributes = "";
			$this->zone->HrefValue = "";
			$this->zone->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_services\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_services',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fserviceslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($services_list)) $services_list = new cservices_list();

// Page init
$services_list->Page_Init();

// Page main
$services_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$services_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($services->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fserviceslist = new ew_Form("fserviceslist", "list");
fserviceslist.FormKeyCountName = '<?php echo $services_list->FormKeyCountName ?>';

// Form_CustomValidate event
fserviceslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fserviceslist.ValidateRequired = true;
<?php } else { ?>
fserviceslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fserviceslist.Lists["x_peopleserved_gender"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fserviceslist.Lists["x_peopleserved_gender"].Options = <?php echo json_encode($services->peopleserved_gender->Options()) ?>;
fserviceslist.Lists["x_peopleserved_age"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fserviceslist.Lists["x_programtype"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fserviceslist.Lists["x_programfocus"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fserviceslist.Lists["x_zone"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fserviceslistsrch = new ew_Form("fserviceslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($services->Export == "") { ?>
<div class="ewToolbar">
<?php if ($services->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($services_list->TotalRecs > 0 && $services_list->ExportOptions->Visible()) { ?>
<?php $services_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($services_list->SearchOptions->Visible()) { ?>
<?php $services_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($services_list->FilterOptions->Visible()) { ?>
<?php $services_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($services->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $services_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($services_list->TotalRecs <= 0)
			$services_list->TotalRecs = $services->SelectRecordCount();
	} else {
		if (!$services_list->Recordset && ($services_list->Recordset = $services_list->LoadRecordset()))
			$services_list->TotalRecs = $services_list->Recordset->RecordCount();
	}
	$services_list->StartRec = 1;
	if ($services_list->DisplayRecs <= 0 || ($services->Export <> "" && $services->ExportAll)) // Display all records
		$services_list->DisplayRecs = $services_list->TotalRecs;
	if (!($services->Export <> "" && $services->ExportAll))
		$services_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$services_list->Recordset = $services_list->LoadRecordset($services_list->StartRec-1, $services_list->DisplayRecs);

	// Set no record found message
	if ($services->CurrentAction == "" && $services_list->TotalRecs == 0) {
		if ($services_list->SearchWhere == "0=101")
			$services_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$services_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$services_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($services->Export == "" && $services->CurrentAction == "") { ?>
<form name="fserviceslistsrch" id="fserviceslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($services_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fserviceslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="services">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($services_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($services_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $services_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($services_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($services_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($services_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($services_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $services_list->ShowPageHeader(); ?>
<?php
$services_list->ShowMessage();
?>
<?php if ($services_list->TotalRecs > 0 || $services->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fserviceslist" id="fserviceslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($services_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $services_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="services">
<div id="gmp_services" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($services_list->TotalRecs > 0) { ?>
<table id="tbl_serviceslist" class="table ewTable">
<?php echo $services->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$services_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$services_list->RenderListOptions();

// Render list options (header, left)
$services_list->ListOptions->Render("header", "left");
?>
<?php if ($services->servicesid->Visible) { // servicesid ?>
	<?php if ($services->SortUrl($services->servicesid) == "") { ?>
		<th data-name="servicesid"><div id="elh_services_servicesid" class="services_servicesid"><div class="ewTableHeaderCaption"><?php echo $services->servicesid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="servicesid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->servicesid) ?>',1);"><div id="elh_services_servicesid" class="services_servicesid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->servicesid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($services->servicesid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->servicesid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->servicename->Visible) { // servicename ?>
	<?php if ($services->SortUrl($services->servicename) == "") { ?>
		<th data-name="servicename"><div id="elh_services_servicename" class="services_servicename"><div class="ewTableHeaderCaption"><?php echo $services->servicename->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="servicename"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->servicename) ?>',1);"><div id="elh_services_servicename" class="services_servicename">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->servicename->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->servicename->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->servicename->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->facility->Visible) { // facility ?>
	<?php if ($services->SortUrl($services->facility) == "") { ?>
		<th data-name="facility"><div id="elh_services_facility" class="services_facility"><div class="ewTableHeaderCaption"><?php echo $services->facility->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="facility"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->facility) ?>',1);"><div id="elh_services_facility" class="services_facility">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->facility->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->facility->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->facility->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->community->Visible) { // community ?>
	<?php if ($services->SortUrl($services->community) == "") { ?>
		<th data-name="community"><div id="elh_services_community" class="services_community"><div class="ewTableHeaderCaption"><?php echo $services->community->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="community"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->community) ?>',1);"><div id="elh_services_community" class="services_community">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->community->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->community->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->community->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->address_street->Visible) { // address_street ?>
	<?php if ($services->SortUrl($services->address_street) == "") { ?>
		<th data-name="address_street"><div id="elh_services_address_street" class="services_address_street"><div class="ewTableHeaderCaption"><?php echo $services->address_street->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address_street"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->address_street) ?>',1);"><div id="elh_services_address_street" class="services_address_street">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->address_street->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->address_street->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->address_street->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->address_city->Visible) { // address_city ?>
	<?php if ($services->SortUrl($services->address_city) == "") { ?>
		<th data-name="address_city"><div id="elh_services_address_city" class="services_address_city"><div class="ewTableHeaderCaption"><?php echo $services->address_city->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address_city"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->address_city) ?>',1);"><div id="elh_services_address_city" class="services_address_city">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->address_city->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->address_city->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->address_city->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->address_postcode->Visible) { // address_postcode ?>
	<?php if ($services->SortUrl($services->address_postcode) == "") { ?>
		<th data-name="address_postcode"><div id="elh_services_address_postcode" class="services_address_postcode"><div class="ewTableHeaderCaption"><?php echo $services->address_postcode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address_postcode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->address_postcode) ?>',1);"><div id="elh_services_address_postcode" class="services_address_postcode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->address_postcode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->address_postcode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->address_postcode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->phone->Visible) { // phone ?>
	<?php if ($services->SortUrl($services->phone) == "") { ?>
		<th data-name="phone"><div id="elh_services_phone" class="services_phone"><div class="ewTableHeaderCaption"><?php echo $services->phone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->phone) ?>',1);"><div id="elh_services_phone" class="services_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->phone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->website->Visible) { // website ?>
	<?php if ($services->SortUrl($services->website) == "") { ?>
		<th data-name="website"><div id="elh_services_website" class="services_website"><div class="ewTableHeaderCaption"><?php echo $services->website->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="website"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->website) ?>',1);"><div id="elh_services_website" class="services_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($services->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->peopleserved_gender->Visible) { // peopleserved_gender ?>
	<?php if ($services->SortUrl($services->peopleserved_gender) == "") { ?>
		<th data-name="peopleserved_gender"><div id="elh_services_peopleserved_gender" class="services_peopleserved_gender"><div class="ewTableHeaderCaption"><?php echo $services->peopleserved_gender->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="peopleserved_gender"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->peopleserved_gender) ?>',1);"><div id="elh_services_peopleserved_gender" class="services_peopleserved_gender">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->peopleserved_gender->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($services->peopleserved_gender->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->peopleserved_gender->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->peopleserved_age->Visible) { // peopleserved_age ?>
	<?php if ($services->SortUrl($services->peopleserved_age) == "") { ?>
		<th data-name="peopleserved_age"><div id="elh_services_peopleserved_age" class="services_peopleserved_age"><div class="ewTableHeaderCaption"><?php echo $services->peopleserved_age->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="peopleserved_age"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->peopleserved_age) ?>',1);"><div id="elh_services_peopleserved_age" class="services_peopleserved_age">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->peopleserved_age->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($services->peopleserved_age->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->peopleserved_age->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->programtype->Visible) { // programtype ?>
	<?php if ($services->SortUrl($services->programtype) == "") { ?>
		<th data-name="programtype"><div id="elh_services_programtype" class="services_programtype"><div class="ewTableHeaderCaption"><?php echo $services->programtype->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="programtype"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->programtype) ?>',1);"><div id="elh_services_programtype" class="services_programtype">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->programtype->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($services->programtype->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->programtype->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->programfocus->Visible) { // programfocus ?>
	<?php if ($services->SortUrl($services->programfocus) == "") { ?>
		<th data-name="programfocus"><div id="elh_services_programfocus" class="services_programfocus"><div class="ewTableHeaderCaption"><?php echo $services->programfocus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="programfocus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->programfocus) ?>',1);"><div id="elh_services_programfocus" class="services_programfocus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->programfocus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($services->programfocus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->programfocus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($services->zone->Visible) { // zone ?>
	<?php if ($services->SortUrl($services->zone) == "") { ?>
		<th data-name="zone"><div id="elh_services_zone" class="services_zone"><div class="ewTableHeaderCaption"><?php echo $services->zone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $services->SortUrl($services->zone) ?>',1);"><div id="elh_services_zone" class="services_zone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $services->zone->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($services->zone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($services->zone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$services_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($services->ExportAll && $services->Export <> "") {
	$services_list->StopRec = $services_list->TotalRecs;
} else {

	// Set the last record to display
	if ($services_list->TotalRecs > $services_list->StartRec + $services_list->DisplayRecs - 1)
		$services_list->StopRec = $services_list->StartRec + $services_list->DisplayRecs - 1;
	else
		$services_list->StopRec = $services_list->TotalRecs;
}
$services_list->RecCnt = $services_list->StartRec - 1;
if ($services_list->Recordset && !$services_list->Recordset->EOF) {
	$services_list->Recordset->MoveFirst();
	$bSelectLimit = $services_list->UseSelectLimit;
	if (!$bSelectLimit && $services_list->StartRec > 1)
		$services_list->Recordset->Move($services_list->StartRec - 1);
} elseif (!$services->AllowAddDeleteRow && $services_list->StopRec == 0) {
	$services_list->StopRec = $services->GridAddRowCount;
}

// Initialize aggregate
$services->RowType = EW_ROWTYPE_AGGREGATEINIT;
$services->ResetAttrs();
$services_list->RenderRow();
while ($services_list->RecCnt < $services_list->StopRec) {
	$services_list->RecCnt++;
	if (intval($services_list->RecCnt) >= intval($services_list->StartRec)) {
		$services_list->RowCnt++;

		// Set up key count
		$services_list->KeyCount = $services_list->RowIndex;

		// Init row class and style
		$services->ResetAttrs();
		$services->CssClass = "";
		if ($services->CurrentAction == "gridadd") {
		} else {
			$services_list->LoadRowValues($services_list->Recordset); // Load row values
		}
		$services->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$services->RowAttrs = array_merge($services->RowAttrs, array('data-rowindex'=>$services_list->RowCnt, 'id'=>'r' . $services_list->RowCnt . '_services', 'data-rowtype'=>$services->RowType));

		// Render row
		$services_list->RenderRow();

		// Render list options
		$services_list->RenderListOptions();
?>
	<tr<?php echo $services->RowAttributes() ?>>
<?php

// Render list options (body, left)
$services_list->ListOptions->Render("body", "left", $services_list->RowCnt);
?>
	<?php if ($services->servicesid->Visible) { // servicesid ?>
		<td data-name="servicesid"<?php echo $services->servicesid->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_servicesid" class="services_servicesid">
<span<?php echo $services->servicesid->ViewAttributes() ?>>
<?php echo $services->servicesid->ListViewValue() ?></span>
</span>
<a id="<?php echo $services_list->PageObjName . "_row_" . $services_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($services->servicename->Visible) { // servicename ?>
		<td data-name="servicename"<?php echo $services->servicename->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_servicename" class="services_servicename">
<span<?php echo $services->servicename->ViewAttributes() ?>>
<?php echo $services->servicename->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->facility->Visible) { // facility ?>
		<td data-name="facility"<?php echo $services->facility->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_facility" class="services_facility">
<span<?php echo $services->facility->ViewAttributes() ?>>
<?php echo $services->facility->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->community->Visible) { // community ?>
		<td data-name="community"<?php echo $services->community->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_community" class="services_community">
<span<?php echo $services->community->ViewAttributes() ?>>
<?php echo $services->community->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->address_street->Visible) { // address_street ?>
		<td data-name="address_street"<?php echo $services->address_street->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_address_street" class="services_address_street">
<span<?php echo $services->address_street->ViewAttributes() ?>>
<?php echo $services->address_street->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->address_city->Visible) { // address_city ?>
		<td data-name="address_city"<?php echo $services->address_city->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_address_city" class="services_address_city">
<span<?php echo $services->address_city->ViewAttributes() ?>>
<?php echo $services->address_city->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->address_postcode->Visible) { // address_postcode ?>
		<td data-name="address_postcode"<?php echo $services->address_postcode->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_address_postcode" class="services_address_postcode">
<span<?php echo $services->address_postcode->ViewAttributes() ?>>
<?php echo $services->address_postcode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->phone->Visible) { // phone ?>
		<td data-name="phone"<?php echo $services->phone->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_phone" class="services_phone">
<span<?php echo $services->phone->ViewAttributes() ?>>
<?php echo $services->phone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->website->Visible) { // website ?>
		<td data-name="website"<?php echo $services->website->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_website" class="services_website">
<span<?php echo $services->website->ViewAttributes() ?>>
<?php echo $services->website->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->peopleserved_gender->Visible) { // peopleserved_gender ?>
		<td data-name="peopleserved_gender"<?php echo $services->peopleserved_gender->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_peopleserved_gender" class="services_peopleserved_gender">
<span<?php echo $services->peopleserved_gender->ViewAttributes() ?>>
<?php echo $services->peopleserved_gender->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->peopleserved_age->Visible) { // peopleserved_age ?>
		<td data-name="peopleserved_age"<?php echo $services->peopleserved_age->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_peopleserved_age" class="services_peopleserved_age">
<span<?php echo $services->peopleserved_age->ViewAttributes() ?>>
<?php echo $services->peopleserved_age->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->programtype->Visible) { // programtype ?>
		<td data-name="programtype"<?php echo $services->programtype->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_programtype" class="services_programtype">
<span<?php echo $services->programtype->ViewAttributes() ?>>
<?php echo $services->programtype->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->programfocus->Visible) { // programfocus ?>
		<td data-name="programfocus"<?php echo $services->programfocus->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_programfocus" class="services_programfocus">
<span<?php echo $services->programfocus->ViewAttributes() ?>>
<?php echo $services->programfocus->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($services->zone->Visible) { // zone ?>
		<td data-name="zone"<?php echo $services->zone->CellAttributes() ?>>
<span id="el<?php echo $services_list->RowCnt ?>_services_zone" class="services_zone">
<span<?php echo $services->zone->ViewAttributes() ?>>
<?php echo $services->zone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$services_list->ListOptions->Render("body", "right", $services_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($services->CurrentAction <> "gridadd")
		$services_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($services->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($services_list->Recordset)
	$services_list->Recordset->Close();
?>
<?php if ($services->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($services->CurrentAction <> "gridadd" && $services->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($services_list->Pager)) $services_list->Pager = new cPrevNextPager($services_list->StartRec, $services_list->DisplayRecs, $services_list->TotalRecs) ?>
<?php if ($services_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($services_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $services_list->PageUrl() ?>start=<?php echo $services_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($services_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $services_list->PageUrl() ?>start=<?php echo $services_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $services_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($services_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $services_list->PageUrl() ?>start=<?php echo $services_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($services_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $services_list->PageUrl() ?>start=<?php echo $services_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $services_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $services_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $services_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $services_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($services_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($services_list->TotalRecs == 0 && $services->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($services_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($services->Export == "") { ?>
<script type="text/javascript">
fserviceslistsrch.Init();
fserviceslistsrch.FilterList = <?php echo $services_list->GetFilterList() ?>;
fserviceslist.Init();
</script>
<?php } ?>
<?php
$services_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($services->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$services_list->Page_Terminate();
?>
