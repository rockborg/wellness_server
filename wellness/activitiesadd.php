<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "activitiesinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$activities_add = NULL; // Initialize page object first

class cactivities_add extends cactivities {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'activities';

	// Page object name
	var $PageObjName = 'activities_add';

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

		// Table object (activities)
		if (!isset($GLOBALS["activities"]) || get_class($GLOBALS["activities"]) == "cactivities") {
			$GLOBALS["activities"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["activities"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'activities', TRUE);

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
		global $EW_EXPORT, $activities;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($activities);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["activitiesid"] != "") {
				$this->activitiesid->setQueryStringValue($_GET["activitiesid"]);
				$this->setKey("activitiesid", $this->activitiesid->CurrentValue); // Set up key
			} else {
				$this->setKey("activitiesid", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("activitieslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "activitiesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->actimage->Upload->Index = $objForm->Index;
		$this->actimage->Upload->UploadFile();
		$this->actimage->CurrentValue = $this->actimage->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->acttitle->CurrentValue = NULL;
		$this->acttitle->OldValue = $this->acttitle->CurrentValue;
		$this->actimage->Upload->DbValue = NULL;
		$this->actimage->OldValue = $this->actimage->Upload->DbValue;
		$this->actimage->CurrentValue = NULL; // Clear file related field
		$this->actdesc->CurrentValue = NULL;
		$this->actdesc->OldValue = $this->actdesc->CurrentValue;
		$this->actlink->CurrentValue = NULL;
		$this->actlink->OldValue = $this->actlink->CurrentValue;
		$this->dateadded->CurrentValue = NULL;
		$this->dateadded->OldValue = $this->dateadded->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->acttitle->FldIsDetailKey) {
			$this->acttitle->setFormValue($objForm->GetValue("x_acttitle"));
		}
		if (!$this->actdesc->FldIsDetailKey) {
			$this->actdesc->setFormValue($objForm->GetValue("x_actdesc"));
		}
		if (!$this->actlink->FldIsDetailKey) {
			$this->actlink->setFormValue($objForm->GetValue("x_actlink"));
		}
		if (!$this->dateadded->FldIsDetailKey) {
			$this->dateadded->setFormValue($objForm->GetValue("x_dateadded"));
			$this->dateadded->CurrentValue = ew_UnFormatDateTime($this->dateadded->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->acttitle->CurrentValue = $this->acttitle->FormValue;
		$this->actdesc->CurrentValue = $this->actdesc->FormValue;
		$this->actlink->CurrentValue = $this->actlink->FormValue;
		$this->dateadded->CurrentValue = $this->dateadded->FormValue;
		$this->dateadded->CurrentValue = ew_UnFormatDateTime($this->dateadded->CurrentValue, 5);
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
		$this->activitiesid->setDbValue($rs->fields('activitiesid'));
		$this->acttitle->setDbValue($rs->fields('acttitle'));
		$this->actimage->Upload->DbValue = $rs->fields('actimage');
		$this->actimage->CurrentValue = $this->actimage->Upload->DbValue;
		$this->actdesc->setDbValue($rs->fields('actdesc'));
		$this->actlink->setDbValue($rs->fields('actlink'));
		$this->dateadded->setDbValue($rs->fields('dateadded'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->activitiesid->DbValue = $row['activitiesid'];
		$this->acttitle->DbValue = $row['acttitle'];
		$this->actimage->Upload->DbValue = $row['actimage'];
		$this->actdesc->DbValue = $row['actdesc'];
		$this->actlink->DbValue = $row['actlink'];
		$this->dateadded->DbValue = $row['dateadded'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("activitiesid")) <> "")
			$this->activitiesid->CurrentValue = $this->getKey("activitiesid"); // activitiesid
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// activitiesid
		// acttitle
		// actimage
		// actdesc
		// actlink
		// dateadded

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// activitiesid
		$this->activitiesid->ViewValue = $this->activitiesid->CurrentValue;
		$this->activitiesid->ViewCustomAttributes = "";

		// acttitle
		$this->acttitle->ViewValue = $this->acttitle->CurrentValue;
		$this->acttitle->ViewCustomAttributes = "";

		// actimage
		if (!ew_Empty($this->actimage->Upload->DbValue)) {
			$this->actimage->ImageWidth = 400;
			$this->actimage->ImageHeight = 0;
			$this->actimage->ImageAlt = $this->actimage->FldAlt();
			$this->actimage->ViewValue = $this->actimage->Upload->DbValue;
		} else {
			$this->actimage->ViewValue = "";
		}
		$this->actimage->ViewCustomAttributes = "";

		// actdesc
		$this->actdesc->ViewValue = $this->actdesc->CurrentValue;
		$this->actdesc->ViewCustomAttributes = "";

		// actlink
		$this->actlink->ViewValue = $this->actlink->CurrentValue;
		$this->actlink->ViewCustomAttributes = "";

		// dateadded
		$this->dateadded->ViewValue = $this->dateadded->CurrentValue;
		$this->dateadded->ViewValue = ew_FormatDateTime($this->dateadded->ViewValue, 5);
		$this->dateadded->ViewCustomAttributes = "";

			// acttitle
			$this->acttitle->LinkCustomAttributes = "";
			$this->acttitle->HrefValue = "";
			$this->acttitle->TooltipValue = "";

			// actimage
			$this->actimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->actimage->Upload->DbValue)) {
				$this->actimage->HrefValue = ew_GetFileUploadUrl($this->actimage, $this->actimage->Upload->DbValue); // Add prefix/suffix
				$this->actimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->actimage->HrefValue = ew_ConvertFullUrl($this->actimage->HrefValue);
			} else {
				$this->actimage->HrefValue = "";
			}
			$this->actimage->HrefValue2 = $this->actimage->UploadPath . $this->actimage->Upload->DbValue;
			$this->actimage->TooltipValue = "";
			if ($this->actimage->UseColorbox) {
				$this->actimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->actimage->LinkAttrs["data-rel"] = "activities_x_actimage";

				//$this->actimage->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->actimage->LinkAttrs["data-placement"] = "bottom";
				//$this->actimage->LinkAttrs["data-container"] = "body";

				$this->actimage->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// actdesc
			$this->actdesc->LinkCustomAttributes = "";
			$this->actdesc->HrefValue = "";
			$this->actdesc->TooltipValue = "";

			// actlink
			$this->actlink->LinkCustomAttributes = "";
			$this->actlink->HrefValue = "";
			$this->actlink->TooltipValue = "";

			// dateadded
			$this->dateadded->LinkCustomAttributes = "";
			$this->dateadded->HrefValue = "";
			$this->dateadded->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// acttitle
			$this->acttitle->EditAttrs["class"] = "form-control";
			$this->acttitle->EditCustomAttributes = "";
			$this->acttitle->EditValue = ew_HtmlEncode($this->acttitle->CurrentValue);
			$this->acttitle->PlaceHolder = ew_RemoveHtml($this->acttitle->FldCaption());

			// actimage
			$this->actimage->EditAttrs["class"] = "form-control";
			$this->actimage->EditCustomAttributes = "";
			if (!ew_Empty($this->actimage->Upload->DbValue)) {
				$this->actimage->ImageWidth = 400;
				$this->actimage->ImageHeight = 0;
				$this->actimage->ImageAlt = $this->actimage->FldAlt();
				$this->actimage->EditValue = $this->actimage->Upload->DbValue;
			} else {
				$this->actimage->EditValue = "";
			}
			if (!ew_Empty($this->actimage->CurrentValue))
				$this->actimage->Upload->FileName = $this->actimage->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->actimage);

			// actdesc
			$this->actdesc->EditAttrs["class"] = "form-control";
			$this->actdesc->EditCustomAttributes = "";
			$this->actdesc->EditValue = ew_HtmlEncode($this->actdesc->CurrentValue);
			$this->actdesc->PlaceHolder = ew_RemoveHtml($this->actdesc->FldCaption());

			// actlink
			$this->actlink->EditAttrs["class"] = "form-control";
			$this->actlink->EditCustomAttributes = "";
			$this->actlink->EditValue = ew_HtmlEncode($this->actlink->CurrentValue);
			$this->actlink->PlaceHolder = ew_RemoveHtml($this->actlink->FldCaption());

			// dateadded
			// Edit refer script
			// acttitle

			$this->acttitle->HrefValue = "";

			// actimage
			if (!ew_Empty($this->actimage->Upload->DbValue)) {
				$this->actimage->HrefValue = ew_GetFileUploadUrl($this->actimage, $this->actimage->Upload->DbValue); // Add prefix/suffix
				$this->actimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->actimage->HrefValue = ew_ConvertFullUrl($this->actimage->HrefValue);
			} else {
				$this->actimage->HrefValue = "";
			}
			$this->actimage->HrefValue2 = $this->actimage->UploadPath . $this->actimage->Upload->DbValue;

			// actdesc
			$this->actdesc->HrefValue = "";

			// actlink
			$this->actlink->HrefValue = "";

			// dateadded
			$this->dateadded->HrefValue = "";
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
		if (!$this->acttitle->FldIsDetailKey && !is_null($this->acttitle->FormValue) && $this->acttitle->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->acttitle->FldCaption(), $this->acttitle->ReqErrMsg));
		}
		if ($this->actimage->Upload->FileName == "" && !$this->actimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->actimage->FldCaption(), $this->actimage->ReqErrMsg));
		}
		if (!$this->actdesc->FldIsDetailKey && !is_null($this->actdesc->FormValue) && $this->actdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->actdesc->FldCaption(), $this->actdesc->ReqErrMsg));
		}
		if (!$this->actlink->FldIsDetailKey && !is_null($this->actlink->FormValue) && $this->actlink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->actlink->FldCaption(), $this->actlink->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// acttitle
		$this->acttitle->SetDbValueDef($rsnew, $this->acttitle->CurrentValue, "", FALSE);

		// actimage
		if (!$this->actimage->Upload->KeepFile) {
			$this->actimage->Upload->DbValue = ""; // No need to delete old file
			if ($this->actimage->Upload->FileName == "") {
				$rsnew['actimage'] = NULL;
			} else {
				$rsnew['actimage'] = $this->actimage->Upload->FileName;
			}
			$this->actimage->ImageWidth = 400; // Resize width
			$this->actimage->ImageHeight = 0; // Resize height
		}

		// actdesc
		$this->actdesc->SetDbValueDef($rsnew, $this->actdesc->CurrentValue, "", FALSE);

		// actlink
		$this->actlink->SetDbValueDef($rsnew, $this->actlink->CurrentValue, "", FALSE);

		// dateadded
		$this->dateadded->SetDbValueDef($rsnew, ew_CurrentDateTime(), ew_CurrentDate());
		$rsnew['dateadded'] = &$this->dateadded->DbValue;
		if (!$this->actimage->Upload->KeepFile) {
			if (!ew_Empty($this->actimage->Upload->Value)) {
				if ($this->actimage->Upload->FileName == $this->actimage->Upload->DbValue) { // Overwrite if same file name
					$this->actimage->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['actimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->actimage->UploadPath), $rsnew['actimage']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->activitiesid->setDbValue($conn->Insert_ID());
				$rsnew['activitiesid'] = $this->activitiesid->DbValue;
				if (!$this->actimage->Upload->KeepFile) {
					if (!ew_Empty($this->actimage->Upload->Value)) {
						$this->actimage->Upload->Resize($this->actimage->ImageWidth, $this->actimage->ImageHeight);
						$this->actimage->Upload->SaveToFile($this->actimage->UploadPath, $rsnew['actimage'], TRUE);
					}
					if ($this->actimage->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->actimage->OldUploadPath) . $this->actimage->Upload->DbValue);
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// actimage
		ew_CleanUploadTempPath($this->actimage, $this->actimage->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "activitieslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($activities_add)) $activities_add = new cactivities_add();

// Page init
$activities_add->Page_Init();

// Page main
$activities_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$activities_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = factivitiesadd = new ew_Form("factivitiesadd", "add");

// Validate form
factivitiesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_acttitle");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $activities->acttitle->FldCaption(), $activities->acttitle->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_actimage");
			elm = this.GetElements("fn_x" + infix + "_actimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $activities->actimage->FldCaption(), $activities->actimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_actdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $activities->actdesc->FldCaption(), $activities->actdesc->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_actlink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $activities->actlink->FldCaption(), $activities->actlink->ReqErrMsg)) ?>");

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
factivitiesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factivitiesadd.ValidateRequired = true;
<?php } else { ?>
factivitiesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $activities_add->ShowPageHeader(); ?>
<?php
$activities_add->ShowMessage();
?>
<form name="factivitiesadd" id="factivitiesadd" class="<?php echo $activities_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($activities_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $activities_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="activities">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($activities->acttitle->Visible) { // acttitle ?>
	<div id="r_acttitle" class="form-group">
		<label id="elh_activities_acttitle" for="x_acttitle" class="col-sm-2 control-label ewLabel"><?php echo $activities->acttitle->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $activities->acttitle->CellAttributes() ?>>
<span id="el_activities_acttitle">
<input type="text" data-table="activities" data-field="x_acttitle" name="x_acttitle" id="x_acttitle" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($activities->acttitle->getPlaceHolder()) ?>" value="<?php echo $activities->acttitle->EditValue ?>"<?php echo $activities->acttitle->EditAttributes() ?>>
</span>
<?php echo $activities->acttitle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($activities->actimage->Visible) { // actimage ?>
	<div id="r_actimage" class="form-group">
		<label id="elh_activities_actimage" class="col-sm-2 control-label ewLabel"><?php echo $activities->actimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $activities->actimage->CellAttributes() ?>>
<span id="el_activities_actimage">
<div id="fd_x_actimage">
<span title="<?php echo $activities->actimage->FldTitle() ? $activities->actimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($activities->actimage->ReadOnly || $activities->actimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="activities" data-field="x_actimage" name="x_actimage" id="x_actimage"<?php echo $activities->actimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_actimage" id= "fn_x_actimage" value="<?php echo $activities->actimage->Upload->FileName ?>">
<input type="hidden" name="fa_x_actimage" id= "fa_x_actimage" value="0">
<input type="hidden" name="fs_x_actimage" id= "fs_x_actimage" value="255">
<input type="hidden" name="fx_x_actimage" id= "fx_x_actimage" value="<?php echo $activities->actimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_actimage" id= "fm_x_actimage" value="<?php echo $activities->actimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_actimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $activities->actimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($activities->actdesc->Visible) { // actdesc ?>
	<div id="r_actdesc" class="form-group">
		<label id="elh_activities_actdesc" for="x_actdesc" class="col-sm-2 control-label ewLabel"><?php echo $activities->actdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $activities->actdesc->CellAttributes() ?>>
<span id="el_activities_actdesc">
<textarea data-table="activities" data-field="x_actdesc" name="x_actdesc" id="x_actdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->actdesc->getPlaceHolder()) ?>"<?php echo $activities->actdesc->EditAttributes() ?>><?php echo $activities->actdesc->EditValue ?></textarea>
</span>
<?php echo $activities->actdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($activities->actlink->Visible) { // actlink ?>
	<div id="r_actlink" class="form-group">
		<label id="elh_activities_actlink" for="x_actlink" class="col-sm-2 control-label ewLabel"><?php echo $activities->actlink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $activities->actlink->CellAttributes() ?>>
<span id="el_activities_actlink">
<input type="text" data-table="activities" data-field="x_actlink" name="x_actlink" id="x_actlink" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($activities->actlink->getPlaceHolder()) ?>" value="<?php echo $activities->actlink->EditValue ?>"<?php echo $activities->actlink->EditAttributes() ?>>
</span>
<?php echo $activities->actlink->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $activities_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
factivitiesadd.Init();
</script>
<?php
$activities_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$activities_add->Page_Terminate();
?>
