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

$services_view = NULL; // Initialize page object first

class cservices_view extends cservices {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'services';

	// Page object name
	var $PageObjName = 'services_view';

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
		$KeyUrl = "";
		if (@$_GET["servicesid"] <> "") {
			$this->RecKey["servicesid"] = $_GET["servicesid"];
			$KeyUrl .= "&amp;servicesid=" . urlencode($this->RecKey["servicesid"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'services', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Create Token
		$this->CreateToken();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["servicesid"] <> "") {
				$this->servicesid->setQueryStringValue($_GET["servicesid"]);
				$this->RecKey["servicesid"] = $this->servicesid->QueryStringValue;
			} elseif (@$_POST["servicesid"] <> "") {
				$this->servicesid->setFormValue($_POST["servicesid"]);
				$this->RecKey["servicesid"] = $this->servicesid->FormValue;
			} else {
				$sReturnUrl = "serviceslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "serviceslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "serviceslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "serviceslist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($services_view)) $services_view = new cservices_view();

// Page init
$services_view->Page_Init();

// Page main
$services_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$services_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fservicesview = new ew_Form("fservicesview", "view");

// Form_CustomValidate event
fservicesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicesview.ValidateRequired = true;
<?php } else { ?>
fservicesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fservicesview.Lists["x_peopleserved_gender"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesview.Lists["x_peopleserved_gender"].Options = <?php echo json_encode($services->peopleserved_gender->Options()) ?>;
fservicesview.Lists["x_peopleserved_age"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesview.Lists["x_programtype"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesview.Lists["x_programfocus"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesview.Lists["x_zone"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $services_view->ExportOptions->Render("body") ?>
<?php
	foreach ($services_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $services_view->ShowPageHeader(); ?>
<?php
$services_view->ShowMessage();
?>
<form name="fservicesview" id="fservicesview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($services_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $services_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="services">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($services->servicesid->Visible) { // servicesid ?>
	<tr id="r_servicesid">
		<td><span id="elh_services_servicesid"><?php echo $services->servicesid->FldCaption() ?></span></td>
		<td data-name="servicesid"<?php echo $services->servicesid->CellAttributes() ?>>
<span id="el_services_servicesid">
<span<?php echo $services->servicesid->ViewAttributes() ?>>
<?php echo $services->servicesid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->servicename->Visible) { // servicename ?>
	<tr id="r_servicename">
		<td><span id="elh_services_servicename"><?php echo $services->servicename->FldCaption() ?></span></td>
		<td data-name="servicename"<?php echo $services->servicename->CellAttributes() ?>>
<span id="el_services_servicename">
<span<?php echo $services->servicename->ViewAttributes() ?>>
<?php echo $services->servicename->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->facility->Visible) { // facility ?>
	<tr id="r_facility">
		<td><span id="elh_services_facility"><?php echo $services->facility->FldCaption() ?></span></td>
		<td data-name="facility"<?php echo $services->facility->CellAttributes() ?>>
<span id="el_services_facility">
<span<?php echo $services->facility->ViewAttributes() ?>>
<?php echo $services->facility->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->community->Visible) { // community ?>
	<tr id="r_community">
		<td><span id="elh_services_community"><?php echo $services->community->FldCaption() ?></span></td>
		<td data-name="community"<?php echo $services->community->CellAttributes() ?>>
<span id="el_services_community">
<span<?php echo $services->community->ViewAttributes() ?>>
<?php echo $services->community->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->address_street->Visible) { // address_street ?>
	<tr id="r_address_street">
		<td><span id="elh_services_address_street"><?php echo $services->address_street->FldCaption() ?></span></td>
		<td data-name="address_street"<?php echo $services->address_street->CellAttributes() ?>>
<span id="el_services_address_street">
<span<?php echo $services->address_street->ViewAttributes() ?>>
<?php echo $services->address_street->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->address_city->Visible) { // address_city ?>
	<tr id="r_address_city">
		<td><span id="elh_services_address_city"><?php echo $services->address_city->FldCaption() ?></span></td>
		<td data-name="address_city"<?php echo $services->address_city->CellAttributes() ?>>
<span id="el_services_address_city">
<span<?php echo $services->address_city->ViewAttributes() ?>>
<?php echo $services->address_city->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->address_postcode->Visible) { // address_postcode ?>
	<tr id="r_address_postcode">
		<td><span id="elh_services_address_postcode"><?php echo $services->address_postcode->FldCaption() ?></span></td>
		<td data-name="address_postcode"<?php echo $services->address_postcode->CellAttributes() ?>>
<span id="el_services_address_postcode">
<span<?php echo $services->address_postcode->ViewAttributes() ?>>
<?php echo $services->address_postcode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->phone->Visible) { // phone ?>
	<tr id="r_phone">
		<td><span id="elh_services_phone"><?php echo $services->phone->FldCaption() ?></span></td>
		<td data-name="phone"<?php echo $services->phone->CellAttributes() ?>>
<span id="el_services_phone">
<span<?php echo $services->phone->ViewAttributes() ?>>
<?php echo $services->phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_services_website"><?php echo $services->website->FldCaption() ?></span></td>
		<td data-name="website"<?php echo $services->website->CellAttributes() ?>>
<span id="el_services_website">
<span<?php echo $services->website->ViewAttributes() ?>>
<?php echo $services->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->peopleserved_gender->Visible) { // peopleserved_gender ?>
	<tr id="r_peopleserved_gender">
		<td><span id="elh_services_peopleserved_gender"><?php echo $services->peopleserved_gender->FldCaption() ?></span></td>
		<td data-name="peopleserved_gender"<?php echo $services->peopleserved_gender->CellAttributes() ?>>
<span id="el_services_peopleserved_gender">
<span<?php echo $services->peopleserved_gender->ViewAttributes() ?>>
<?php echo $services->peopleserved_gender->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->peopleserved_age->Visible) { // peopleserved_age ?>
	<tr id="r_peopleserved_age">
		<td><span id="elh_services_peopleserved_age"><?php echo $services->peopleserved_age->FldCaption() ?></span></td>
		<td data-name="peopleserved_age"<?php echo $services->peopleserved_age->CellAttributes() ?>>
<span id="el_services_peopleserved_age">
<span<?php echo $services->peopleserved_age->ViewAttributes() ?>>
<?php echo $services->peopleserved_age->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->programtype->Visible) { // programtype ?>
	<tr id="r_programtype">
		<td><span id="elh_services_programtype"><?php echo $services->programtype->FldCaption() ?></span></td>
		<td data-name="programtype"<?php echo $services->programtype->CellAttributes() ?>>
<span id="el_services_programtype">
<span<?php echo $services->programtype->ViewAttributes() ?>>
<?php echo $services->programtype->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->programfocus->Visible) { // programfocus ?>
	<tr id="r_programfocus">
		<td><span id="elh_services_programfocus"><?php echo $services->programfocus->FldCaption() ?></span></td>
		<td data-name="programfocus"<?php echo $services->programfocus->CellAttributes() ?>>
<span id="el_services_programfocus">
<span<?php echo $services->programfocus->ViewAttributes() ?>>
<?php echo $services->programfocus->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($services->zone->Visible) { // zone ?>
	<tr id="r_zone">
		<td><span id="elh_services_zone"><?php echo $services->zone->FldCaption() ?></span></td>
		<td data-name="zone"<?php echo $services->zone->CellAttributes() ?>>
<span id="el_services_zone">
<span<?php echo $services->zone->ViewAttributes() ?>>
<?php echo $services->zone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fservicesview.Init();
</script>
<?php
$services_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$services_view->Page_Terminate();
?>
