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

$services_edit = NULL; // Initialize page object first

class cservices_edit extends cservices {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'services';

	// Page object name
	var $PageObjName = 'services_edit';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'services', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
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

		// Create form object
		$objForm = new cFormObj();
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["servicesid"] <> "") {
			$this->servicesid->setQueryStringValue($_GET["servicesid"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->servicesid->CurrentValue == "")
			$this->Page_Terminate("serviceslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("serviceslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->servicesid->FldIsDetailKey)
			$this->servicesid->setFormValue($objForm->GetValue("x_servicesid"));
		if (!$this->servicename->FldIsDetailKey) {
			$this->servicename->setFormValue($objForm->GetValue("x_servicename"));
		}
		if (!$this->facility->FldIsDetailKey) {
			$this->facility->setFormValue($objForm->GetValue("x_facility"));
		}
		if (!$this->community->FldIsDetailKey) {
			$this->community->setFormValue($objForm->GetValue("x_community"));
		}
		if (!$this->address_street->FldIsDetailKey) {
			$this->address_street->setFormValue($objForm->GetValue("x_address_street"));
		}
		if (!$this->address_city->FldIsDetailKey) {
			$this->address_city->setFormValue($objForm->GetValue("x_address_city"));
		}
		if (!$this->address_postcode->FldIsDetailKey) {
			$this->address_postcode->setFormValue($objForm->GetValue("x_address_postcode"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->peopleserved_gender->FldIsDetailKey) {
			$this->peopleserved_gender->setFormValue($objForm->GetValue("x_peopleserved_gender"));
		}
		if (!$this->peopleserved_age->FldIsDetailKey) {
			$this->peopleserved_age->setFormValue($objForm->GetValue("x_peopleserved_age"));
		}
		if (!$this->programtype->FldIsDetailKey) {
			$this->programtype->setFormValue($objForm->GetValue("x_programtype"));
		}
		if (!$this->programfocus->FldIsDetailKey) {
			$this->programfocus->setFormValue($objForm->GetValue("x_programfocus"));
		}
		if (!$this->zone->FldIsDetailKey) {
			$this->zone->setFormValue($objForm->GetValue("x_zone"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->servicesid->CurrentValue = $this->servicesid->FormValue;
		$this->servicename->CurrentValue = $this->servicename->FormValue;
		$this->facility->CurrentValue = $this->facility->FormValue;
		$this->community->CurrentValue = $this->community->FormValue;
		$this->address_street->CurrentValue = $this->address_street->FormValue;
		$this->address_city->CurrentValue = $this->address_city->FormValue;
		$this->address_postcode->CurrentValue = $this->address_postcode->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->peopleserved_gender->CurrentValue = $this->peopleserved_gender->FormValue;
		$this->peopleserved_age->CurrentValue = $this->peopleserved_age->FormValue;
		$this->programtype->CurrentValue = $this->programtype->FormValue;
		$this->programfocus->CurrentValue = $this->programfocus->FormValue;
		$this->zone->CurrentValue = $this->zone->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// servicesid
			$this->servicesid->EditAttrs["class"] = "form-control";
			$this->servicesid->EditCustomAttributes = "";
			$this->servicesid->EditValue = $this->servicesid->CurrentValue;
			$this->servicesid->ViewCustomAttributes = "";

			// servicename
			$this->servicename->EditAttrs["class"] = "form-control";
			$this->servicename->EditCustomAttributes = "";
			$this->servicename->EditValue = ew_HtmlEncode($this->servicename->CurrentValue);
			$this->servicename->PlaceHolder = ew_RemoveHtml($this->servicename->FldCaption());

			// facility
			$this->facility->EditAttrs["class"] = "form-control";
			$this->facility->EditCustomAttributes = "";
			$this->facility->EditValue = ew_HtmlEncode($this->facility->CurrentValue);
			$this->facility->PlaceHolder = ew_RemoveHtml($this->facility->FldCaption());

			// community
			$this->community->EditAttrs["class"] = "form-control";
			$this->community->EditCustomAttributes = "";
			$this->community->EditValue = ew_HtmlEncode($this->community->CurrentValue);
			$this->community->PlaceHolder = ew_RemoveHtml($this->community->FldCaption());

			// address_street
			$this->address_street->EditAttrs["class"] = "form-control";
			$this->address_street->EditCustomAttributes = "";
			$this->address_street->EditValue = ew_HtmlEncode($this->address_street->CurrentValue);
			$this->address_street->PlaceHolder = ew_RemoveHtml($this->address_street->FldCaption());

			// address_city
			$this->address_city->EditAttrs["class"] = "form-control";
			$this->address_city->EditCustomAttributes = "";
			$this->address_city->EditValue = ew_HtmlEncode($this->address_city->CurrentValue);
			$this->address_city->PlaceHolder = ew_RemoveHtml($this->address_city->FldCaption());

			// address_postcode
			$this->address_postcode->EditAttrs["class"] = "form-control";
			$this->address_postcode->EditCustomAttributes = "";
			$this->address_postcode->EditValue = ew_HtmlEncode($this->address_postcode->CurrentValue);
			$this->address_postcode->PlaceHolder = ew_RemoveHtml($this->address_postcode->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// peopleserved_gender
			$this->peopleserved_gender->EditAttrs["class"] = "form-control";
			$this->peopleserved_gender->EditCustomAttributes = "";
			$this->peopleserved_gender->EditValue = $this->peopleserved_gender->Options(TRUE);

			// peopleserved_age
			$this->peopleserved_age->EditAttrs["class"] = "form-control";
			$this->peopleserved_age->EditCustomAttributes = "";
			if (trim(strval($this->peopleserved_age->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`name`" . ew_SearchString("=", $this->peopleserved_age->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `lst_age`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->peopleserved_age, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->peopleserved_age->EditValue = $arwrk;

			// programtype
			$this->programtype->EditAttrs["class"] = "form-control";
			$this->programtype->EditCustomAttributes = "";
			if (trim(strval($this->programtype->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`name`" . ew_SearchString("=", $this->programtype->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `lst_prgtype`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->programtype, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->programtype->EditValue = $arwrk;

			// programfocus
			$this->programfocus->EditAttrs["class"] = "form-control";
			$this->programfocus->EditCustomAttributes = "";
			if (trim(strval($this->programfocus->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`name`" . ew_SearchString("=", $this->programfocus->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `lst_focus`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->programfocus, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->programfocus->EditValue = $arwrk;

			// zone
			$this->zone->EditAttrs["class"] = "form-control";
			$this->zone->EditCustomAttributes = "";
			if (trim(strval($this->zone->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`name`" . ew_SearchString("=", $this->zone->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `lst_zone`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->zone, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->zone->EditValue = $arwrk;

			// Edit refer script
			// servicesid

			$this->servicesid->HrefValue = "";

			// servicename
			$this->servicename->HrefValue = "";

			// facility
			$this->facility->HrefValue = "";

			// community
			$this->community->HrefValue = "";

			// address_street
			$this->address_street->HrefValue = "";

			// address_city
			$this->address_city->HrefValue = "";

			// address_postcode
			$this->address_postcode->HrefValue = "";

			// phone
			$this->phone->HrefValue = "";

			// website
			$this->website->HrefValue = "";

			// peopleserved_gender
			$this->peopleserved_gender->HrefValue = "";

			// peopleserved_age
			$this->peopleserved_age->HrefValue = "";

			// programtype
			$this->programtype->HrefValue = "";

			// programfocus
			$this->programfocus->HrefValue = "";

			// zone
			$this->zone->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->servicename->FldIsDetailKey && !is_null($this->servicename->FormValue) && $this->servicename->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->servicename->FldCaption(), $this->servicename->ReqErrMsg));
		}
		if (!$this->facility->FldIsDetailKey && !is_null($this->facility->FormValue) && $this->facility->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->facility->FldCaption(), $this->facility->ReqErrMsg));
		}
		if (!$this->community->FldIsDetailKey && !is_null($this->community->FormValue) && $this->community->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->community->FldCaption(), $this->community->ReqErrMsg));
		}
		if (!$this->address_street->FldIsDetailKey && !is_null($this->address_street->FormValue) && $this->address_street->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address_street->FldCaption(), $this->address_street->ReqErrMsg));
		}
		if (!$this->address_city->FldIsDetailKey && !is_null($this->address_city->FormValue) && $this->address_city->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address_city->FldCaption(), $this->address_city->ReqErrMsg));
		}
		if (!$this->address_postcode->FldIsDetailKey && !is_null($this->address_postcode->FormValue) && $this->address_postcode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->address_postcode->FldCaption(), $this->address_postcode->ReqErrMsg));
		}
		if (!$this->phone->FldIsDetailKey && !is_null($this->phone->FormValue) && $this->phone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->phone->FldCaption(), $this->phone->ReqErrMsg));
		}
		if (!$this->website->FldIsDetailKey && !is_null($this->website->FormValue) && $this->website->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->website->FldCaption(), $this->website->ReqErrMsg));
		}
		if (!$this->peopleserved_gender->FldIsDetailKey && !is_null($this->peopleserved_gender->FormValue) && $this->peopleserved_gender->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->peopleserved_gender->FldCaption(), $this->peopleserved_gender->ReqErrMsg));
		}
		if (!$this->peopleserved_age->FldIsDetailKey && !is_null($this->peopleserved_age->FormValue) && $this->peopleserved_age->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->peopleserved_age->FldCaption(), $this->peopleserved_age->ReqErrMsg));
		}
		if (!$this->programtype->FldIsDetailKey && !is_null($this->programtype->FormValue) && $this->programtype->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->programtype->FldCaption(), $this->programtype->ReqErrMsg));
		}
		if (!$this->programfocus->FldIsDetailKey && !is_null($this->programfocus->FormValue) && $this->programfocus->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->programfocus->FldCaption(), $this->programfocus->ReqErrMsg));
		}
		if (!$this->zone->FldIsDetailKey && !is_null($this->zone->FormValue) && $this->zone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone->FldCaption(), $this->zone->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$conn = &$this->Connection();
		if ($this->servicename->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`servicename` = '" . ew_AdjustSql($this->servicename->CurrentValue, $this->DBID) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->servicename->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->servicename->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// servicename
			$this->servicename->SetDbValueDef($rsnew, $this->servicename->CurrentValue, "", $this->servicename->ReadOnly);

			// facility
			$this->facility->SetDbValueDef($rsnew, $this->facility->CurrentValue, "", $this->facility->ReadOnly);

			// community
			$this->community->SetDbValueDef($rsnew, $this->community->CurrentValue, "", $this->community->ReadOnly);

			// address_street
			$this->address_street->SetDbValueDef($rsnew, $this->address_street->CurrentValue, "", $this->address_street->ReadOnly);

			// address_city
			$this->address_city->SetDbValueDef($rsnew, $this->address_city->CurrentValue, "", $this->address_city->ReadOnly);

			// address_postcode
			$this->address_postcode->SetDbValueDef($rsnew, $this->address_postcode->CurrentValue, "", $this->address_postcode->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, "", $this->phone->ReadOnly);

			// website
			$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, "", $this->website->ReadOnly);

			// peopleserved_gender
			$this->peopleserved_gender->SetDbValueDef($rsnew, $this->peopleserved_gender->CurrentValue, "", $this->peopleserved_gender->ReadOnly);

			// peopleserved_age
			$this->peopleserved_age->SetDbValueDef($rsnew, $this->peopleserved_age->CurrentValue, "", $this->peopleserved_age->ReadOnly);

			// programtype
			$this->programtype->SetDbValueDef($rsnew, $this->programtype->CurrentValue, "", $this->programtype->ReadOnly);

			// programfocus
			$this->programfocus->SetDbValueDef($rsnew, $this->programfocus->CurrentValue, "", $this->programfocus->ReadOnly);

			// zone
			$this->zone->SetDbValueDef($rsnew, $this->zone->CurrentValue, "", $this->zone->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "serviceslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($services_edit)) $services_edit = new cservices_edit();

// Page init
$services_edit->Page_Init();

// Page main
$services_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$services_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fservicesedit = new ew_Form("fservicesedit", "edit");

// Validate form
fservicesedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_servicename");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->servicename->FldCaption(), $services->servicename->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_facility");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->facility->FldCaption(), $services->facility->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_community");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->community->FldCaption(), $services->community->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address_street");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->address_street->FldCaption(), $services->address_street->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address_city");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->address_city->FldCaption(), $services->address_city->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_address_postcode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->address_postcode->FldCaption(), $services->address_postcode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_phone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->phone->FldCaption(), $services->phone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_website");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->website->FldCaption(), $services->website->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_peopleserved_gender");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->peopleserved_gender->FldCaption(), $services->peopleserved_gender->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_peopleserved_age");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->peopleserved_age->FldCaption(), $services->peopleserved_age->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_programtype");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->programtype->FldCaption(), $services->programtype->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_programfocus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->programfocus->FldCaption(), $services->programfocus->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $services->zone->FldCaption(), $services->zone->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fservicesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fservicesedit.ValidateRequired = true;
<?php } else { ?>
fservicesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fservicesedit.Lists["x_peopleserved_gender"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesedit.Lists["x_peopleserved_gender"].Options = <?php echo json_encode($services->peopleserved_gender->Options()) ?>;
fservicesedit.Lists["x_peopleserved_age"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesedit.Lists["x_programtype"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesedit.Lists["x_programfocus"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fservicesedit.Lists["x_zone"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $services_edit->ShowPageHeader(); ?>
<?php
$services_edit->ShowMessage();
?>
<form name="fservicesedit" id="fservicesedit" class="<?php echo $services_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($services_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $services_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="services">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($services->servicesid->Visible) { // servicesid ?>
	<div id="r_servicesid" class="form-group">
		<label id="elh_services_servicesid" class="col-sm-2 control-label ewLabel"><?php echo $services->servicesid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $services->servicesid->CellAttributes() ?>>
<span id="el_services_servicesid">
<span<?php echo $services->servicesid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $services->servicesid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="services" data-field="x_servicesid" name="x_servicesid" id="x_servicesid" value="<?php echo ew_HtmlEncode($services->servicesid->CurrentValue) ?>">
<?php echo $services->servicesid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->servicename->Visible) { // servicename ?>
	<div id="r_servicename" class="form-group">
		<label id="elh_services_servicename" for="x_servicename" class="col-sm-2 control-label ewLabel"><?php echo $services->servicename->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->servicename->CellAttributes() ?>>
<span id="el_services_servicename">
<input type="text" data-table="services" data-field="x_servicename" name="x_servicename" id="x_servicename" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->servicename->getPlaceHolder()) ?>" value="<?php echo $services->servicename->EditValue ?>"<?php echo $services->servicename->EditAttributes() ?>>
</span>
<?php echo $services->servicename->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->facility->Visible) { // facility ?>
	<div id="r_facility" class="form-group">
		<label id="elh_services_facility" for="x_facility" class="col-sm-2 control-label ewLabel"><?php echo $services->facility->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->facility->CellAttributes() ?>>
<span id="el_services_facility">
<input type="text" data-table="services" data-field="x_facility" name="x_facility" id="x_facility" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->facility->getPlaceHolder()) ?>" value="<?php echo $services->facility->EditValue ?>"<?php echo $services->facility->EditAttributes() ?>>
</span>
<?php echo $services->facility->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->community->Visible) { // community ?>
	<div id="r_community" class="form-group">
		<label id="elh_services_community" for="x_community" class="col-sm-2 control-label ewLabel"><?php echo $services->community->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->community->CellAttributes() ?>>
<span id="el_services_community">
<input type="text" data-table="services" data-field="x_community" name="x_community" id="x_community" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->community->getPlaceHolder()) ?>" value="<?php echo $services->community->EditValue ?>"<?php echo $services->community->EditAttributes() ?>>
</span>
<?php echo $services->community->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->address_street->Visible) { // address_street ?>
	<div id="r_address_street" class="form-group">
		<label id="elh_services_address_street" for="x_address_street" class="col-sm-2 control-label ewLabel"><?php echo $services->address_street->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->address_street->CellAttributes() ?>>
<span id="el_services_address_street">
<input type="text" data-table="services" data-field="x_address_street" name="x_address_street" id="x_address_street" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->address_street->getPlaceHolder()) ?>" value="<?php echo $services->address_street->EditValue ?>"<?php echo $services->address_street->EditAttributes() ?>>
</span>
<?php echo $services->address_street->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->address_city->Visible) { // address_city ?>
	<div id="r_address_city" class="form-group">
		<label id="elh_services_address_city" for="x_address_city" class="col-sm-2 control-label ewLabel"><?php echo $services->address_city->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->address_city->CellAttributes() ?>>
<span id="el_services_address_city">
<input type="text" data-table="services" data-field="x_address_city" name="x_address_city" id="x_address_city" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->address_city->getPlaceHolder()) ?>" value="<?php echo $services->address_city->EditValue ?>"<?php echo $services->address_city->EditAttributes() ?>>
</span>
<?php echo $services->address_city->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->address_postcode->Visible) { // address_postcode ?>
	<div id="r_address_postcode" class="form-group">
		<label id="elh_services_address_postcode" for="x_address_postcode" class="col-sm-2 control-label ewLabel"><?php echo $services->address_postcode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->address_postcode->CellAttributes() ?>>
<span id="el_services_address_postcode">
<input type="text" data-table="services" data-field="x_address_postcode" name="x_address_postcode" id="x_address_postcode" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->address_postcode->getPlaceHolder()) ?>" value="<?php echo $services->address_postcode->EditValue ?>"<?php echo $services->address_postcode->EditAttributes() ?>>
</span>
<?php echo $services->address_postcode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_services_phone" for="x_phone" class="col-sm-2 control-label ewLabel"><?php echo $services->phone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->phone->CellAttributes() ?>>
<span id="el_services_phone">
<input type="text" data-table="services" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->phone->getPlaceHolder()) ?>" value="<?php echo $services->phone->EditValue ?>"<?php echo $services->phone->EditAttributes() ?>>
</span>
<?php echo $services->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_services_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $services->website->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->website->CellAttributes() ?>>
<span id="el_services_website">
<input type="text" data-table="services" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($services->website->getPlaceHolder()) ?>" value="<?php echo $services->website->EditValue ?>"<?php echo $services->website->EditAttributes() ?>>
</span>
<?php echo $services->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->peopleserved_gender->Visible) { // peopleserved_gender ?>
	<div id="r_peopleserved_gender" class="form-group">
		<label id="elh_services_peopleserved_gender" for="x_peopleserved_gender" class="col-sm-2 control-label ewLabel"><?php echo $services->peopleserved_gender->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->peopleserved_gender->CellAttributes() ?>>
<span id="el_services_peopleserved_gender">
<select data-table="services" data-field="x_peopleserved_gender" data-value-separator="<?php echo ew_HtmlEncode(is_array($services->peopleserved_gender->DisplayValueSeparator) ? json_encode($services->peopleserved_gender->DisplayValueSeparator) : $services->peopleserved_gender->DisplayValueSeparator) ?>" id="x_peopleserved_gender" name="x_peopleserved_gender"<?php echo $services->peopleserved_gender->EditAttributes() ?>>
<?php
if (is_array($services->peopleserved_gender->EditValue)) {
	$arwrk = $services->peopleserved_gender->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($services->peopleserved_gender->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $services->peopleserved_gender->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
?>
</select>
</span>
<?php echo $services->peopleserved_gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->peopleserved_age->Visible) { // peopleserved_age ?>
	<div id="r_peopleserved_age" class="form-group">
		<label id="elh_services_peopleserved_age" for="x_peopleserved_age" class="col-sm-2 control-label ewLabel"><?php echo $services->peopleserved_age->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->peopleserved_age->CellAttributes() ?>>
<span id="el_services_peopleserved_age">
<select data-table="services" data-field="x_peopleserved_age" data-value-separator="<?php echo ew_HtmlEncode(is_array($services->peopleserved_age->DisplayValueSeparator) ? json_encode($services->peopleserved_age->DisplayValueSeparator) : $services->peopleserved_age->DisplayValueSeparator) ?>" id="x_peopleserved_age" name="x_peopleserved_age"<?php echo $services->peopleserved_age->EditAttributes() ?>>
<?php
if (is_array($services->peopleserved_age->EditValue)) {
	$arwrk = $services->peopleserved_age->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($services->peopleserved_age->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $services->peopleserved_age->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $services->peopleserved_age->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_peopleserved_age',url:'lst_ageaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_peopleserved_age"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $services->peopleserved_age->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_age`";
$sWhereWrk = "";
$services->peopleserved_age->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$services->peopleserved_age->LookupFilters += array("f0" => "`name` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$services->Lookup_Selecting($services->peopleserved_age, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $services->peopleserved_age->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_peopleserved_age" id="s_x_peopleserved_age" value="<?php echo $services->peopleserved_age->LookupFilterQuery() ?>">
</span>
<?php echo $services->peopleserved_age->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->programtype->Visible) { // programtype ?>
	<div id="r_programtype" class="form-group">
		<label id="elh_services_programtype" for="x_programtype" class="col-sm-2 control-label ewLabel"><?php echo $services->programtype->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->programtype->CellAttributes() ?>>
<span id="el_services_programtype">
<select data-table="services" data-field="x_programtype" data-value-separator="<?php echo ew_HtmlEncode(is_array($services->programtype->DisplayValueSeparator) ? json_encode($services->programtype->DisplayValueSeparator) : $services->programtype->DisplayValueSeparator) ?>" id="x_programtype" name="x_programtype"<?php echo $services->programtype->EditAttributes() ?>>
<?php
if (is_array($services->programtype->EditValue)) {
	$arwrk = $services->programtype->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($services->programtype->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $services->programtype->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $services->programtype->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_programtype',url:'lst_prgtypeaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_programtype"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $services->programtype->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_prgtype`";
$sWhereWrk = "";
$services->programtype->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$services->programtype->LookupFilters += array("f0" => "`name` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$services->Lookup_Selecting($services->programtype, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $services->programtype->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_programtype" id="s_x_programtype" value="<?php echo $services->programtype->LookupFilterQuery() ?>">
</span>
<?php echo $services->programtype->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->programfocus->Visible) { // programfocus ?>
	<div id="r_programfocus" class="form-group">
		<label id="elh_services_programfocus" for="x_programfocus" class="col-sm-2 control-label ewLabel"><?php echo $services->programfocus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->programfocus->CellAttributes() ?>>
<span id="el_services_programfocus">
<select data-table="services" data-field="x_programfocus" data-value-separator="<?php echo ew_HtmlEncode(is_array($services->programfocus->DisplayValueSeparator) ? json_encode($services->programfocus->DisplayValueSeparator) : $services->programfocus->DisplayValueSeparator) ?>" id="x_programfocus" name="x_programfocus"<?php echo $services->programfocus->EditAttributes() ?>>
<?php
if (is_array($services->programfocus->EditValue)) {
	$arwrk = $services->programfocus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($services->programfocus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $services->programfocus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $services->programfocus->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_programfocus',url:'lst_focusaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_programfocus"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $services->programfocus->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_focus`";
$sWhereWrk = "";
$services->programfocus->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$services->programfocus->LookupFilters += array("f0" => "`name` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$services->Lookup_Selecting($services->programfocus, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $services->programfocus->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_programfocus" id="s_x_programfocus" value="<?php echo $services->programfocus->LookupFilterQuery() ?>">
</span>
<?php echo $services->programfocus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($services->zone->Visible) { // zone ?>
	<div id="r_zone" class="form-group">
		<label id="elh_services_zone" for="x_zone" class="col-sm-2 control-label ewLabel"><?php echo $services->zone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $services->zone->CellAttributes() ?>>
<span id="el_services_zone">
<select data-table="services" data-field="x_zone" data-value-separator="<?php echo ew_HtmlEncode(is_array($services->zone->DisplayValueSeparator) ? json_encode($services->zone->DisplayValueSeparator) : $services->zone->DisplayValueSeparator) ?>" id="x_zone" name="x_zone"<?php echo $services->zone->EditAttributes() ?>>
<?php
if (is_array($services->zone->EditValue)) {
	$arwrk = $services->zone->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($services->zone->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $services->zone->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_zone`";
$sWhereWrk = "";
$services->zone->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$services->zone->LookupFilters += array("f0" => "`name` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$services->Lookup_Selecting($services->zone, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $services->zone->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_zone" id="s_x_zone" value="<?php echo $services->zone->LookupFilterQuery() ?>">
</span>
<?php echo $services->zone->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $services_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fservicesedit.Init();
</script>
<?php
$services_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$services_edit->Page_Terminate();
?>
