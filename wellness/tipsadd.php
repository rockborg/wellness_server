<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "tipsinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$tips_add = NULL; // Initialize page object first

class ctips_add extends ctips {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'tips';

	// Page object name
	var $PageObjName = 'tips_add';

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

		// Table object (tips)
		if (!isset($GLOBALS["tips"]) || get_class($GLOBALS["tips"]) == "ctips") {
			$GLOBALS["tips"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tips"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tips', TRUE);

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
		global $EW_EXPORT, $tips;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tips);
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
			if (@$_GET["tipsid"] != "") {
				$this->tipsid->setQueryStringValue($_GET["tipsid"]);
				$this->setKey("tipsid", $this->tipsid->CurrentValue); // Set up key
			} else {
				$this->setKey("tipsid", ""); // Clear key
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
					$this->Page_Terminate("tipslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tipsview.php")
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
		$this->tipimage->Upload->Index = $objForm->Index;
		$this->tipimage->Upload->UploadFile();
		$this->tipimage->CurrentValue = $this->tipimage->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->tiptitle->CurrentValue = NULL;
		$this->tiptitle->OldValue = $this->tiptitle->CurrentValue;
		$this->tipimage->Upload->DbValue = NULL;
		$this->tipimage->OldValue = $this->tipimage->Upload->DbValue;
		$this->tipimage->CurrentValue = NULL; // Clear file related field
		$this->tipdesc->CurrentValue = NULL;
		$this->tipdesc->OldValue = $this->tipdesc->CurrentValue;
		$this->tiplink->CurrentValue = NULL;
		$this->tiplink->OldValue = $this->tiplink->CurrentValue;
		$this->dateadded->CurrentValue = NULL;
		$this->dateadded->OldValue = $this->dateadded->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->tiptitle->FldIsDetailKey) {
			$this->tiptitle->setFormValue($objForm->GetValue("x_tiptitle"));
		}
		if (!$this->tipdesc->FldIsDetailKey) {
			$this->tipdesc->setFormValue($objForm->GetValue("x_tipdesc"));
		}
		if (!$this->tiplink->FldIsDetailKey) {
			$this->tiplink->setFormValue($objForm->GetValue("x_tiplink"));
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
		$this->tiptitle->CurrentValue = $this->tiptitle->FormValue;
		$this->tipdesc->CurrentValue = $this->tipdesc->FormValue;
		$this->tiplink->CurrentValue = $this->tiplink->FormValue;
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
		$this->tipsid->setDbValue($rs->fields('tipsid'));
		$this->tiptitle->setDbValue($rs->fields('tiptitle'));
		$this->tipimage->Upload->DbValue = $rs->fields('tipimage');
		$this->tipimage->CurrentValue = $this->tipimage->Upload->DbValue;
		$this->tipdesc->setDbValue($rs->fields('tipdesc'));
		$this->tiplink->setDbValue($rs->fields('tiplink'));
		$this->dateadded->setDbValue($rs->fields('dateadded'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->tipsid->DbValue = $row['tipsid'];
		$this->tiptitle->DbValue = $row['tiptitle'];
		$this->tipimage->Upload->DbValue = $row['tipimage'];
		$this->tipdesc->DbValue = $row['tipdesc'];
		$this->tiplink->DbValue = $row['tiplink'];
		$this->dateadded->DbValue = $row['dateadded'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("tipsid")) <> "")
			$this->tipsid->CurrentValue = $this->getKey("tipsid"); // tipsid
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
		// tipsid
		// tiptitle
		// tipimage
		// tipdesc
		// tiplink
		// dateadded

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// tipsid
		$this->tipsid->ViewValue = $this->tipsid->CurrentValue;
		$this->tipsid->ViewCustomAttributes = "";

		// tiptitle
		$this->tiptitle->ViewValue = $this->tiptitle->CurrentValue;
		$this->tiptitle->ViewCustomAttributes = "";

		// tipimage
		if (!ew_Empty($this->tipimage->Upload->DbValue)) {
			$this->tipimage->ImageWidth = 400;
			$this->tipimage->ImageHeight = 0;
			$this->tipimage->ImageAlt = $this->tipimage->FldAlt();
			$this->tipimage->ViewValue = $this->tipimage->Upload->DbValue;
		} else {
			$this->tipimage->ViewValue = "";
		}
		$this->tipimage->ViewCustomAttributes = "";

		// tipdesc
		$this->tipdesc->ViewValue = $this->tipdesc->CurrentValue;
		$this->tipdesc->ViewCustomAttributes = "";

		// tiplink
		$this->tiplink->ViewValue = $this->tiplink->CurrentValue;
		$this->tiplink->ViewCustomAttributes = "";

		// dateadded
		$this->dateadded->ViewValue = $this->dateadded->CurrentValue;
		$this->dateadded->ViewValue = ew_FormatDateTime($this->dateadded->ViewValue, 5);
		$this->dateadded->ViewCustomAttributes = "";

			// tiptitle
			$this->tiptitle->LinkCustomAttributes = "";
			$this->tiptitle->HrefValue = "";
			$this->tiptitle->TooltipValue = "";

			// tipimage
			$this->tipimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->tipimage->Upload->DbValue)) {
				$this->tipimage->HrefValue = ew_GetFileUploadUrl($this->tipimage, $this->tipimage->Upload->DbValue); // Add prefix/suffix
				$this->tipimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->tipimage->HrefValue = ew_ConvertFullUrl($this->tipimage->HrefValue);
			} else {
				$this->tipimage->HrefValue = "";
			}
			$this->tipimage->HrefValue2 = $this->tipimage->UploadPath . $this->tipimage->Upload->DbValue;
			$this->tipimage->TooltipValue = "";
			if ($this->tipimage->UseColorbox) {
				$this->tipimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->tipimage->LinkAttrs["data-rel"] = "tips_x_tipimage";

				//$this->tipimage->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->tipimage->LinkAttrs["data-placement"] = "bottom";
				//$this->tipimage->LinkAttrs["data-container"] = "body";

				$this->tipimage->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// tipdesc
			$this->tipdesc->LinkCustomAttributes = "";
			$this->tipdesc->HrefValue = "";
			$this->tipdesc->TooltipValue = "";

			// tiplink
			$this->tiplink->LinkCustomAttributes = "";
			$this->tiplink->HrefValue = "";
			$this->tiplink->TooltipValue = "";

			// dateadded
			$this->dateadded->LinkCustomAttributes = "";
			$this->dateadded->HrefValue = "";
			$this->dateadded->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// tiptitle
			$this->tiptitle->EditAttrs["class"] = "form-control";
			$this->tiptitle->EditCustomAttributes = "";
			$this->tiptitle->EditValue = ew_HtmlEncode($this->tiptitle->CurrentValue);
			$this->tiptitle->PlaceHolder = ew_RemoveHtml($this->tiptitle->FldCaption());

			// tipimage
			$this->tipimage->EditAttrs["class"] = "form-control";
			$this->tipimage->EditCustomAttributes = "";
			if (!ew_Empty($this->tipimage->Upload->DbValue)) {
				$this->tipimage->ImageWidth = 400;
				$this->tipimage->ImageHeight = 0;
				$this->tipimage->ImageAlt = $this->tipimage->FldAlt();
				$this->tipimage->EditValue = $this->tipimage->Upload->DbValue;
			} else {
				$this->tipimage->EditValue = "";
			}
			if (!ew_Empty($this->tipimage->CurrentValue))
				$this->tipimage->Upload->FileName = $this->tipimage->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->tipimage);

			// tipdesc
			$this->tipdesc->EditAttrs["class"] = "form-control";
			$this->tipdesc->EditCustomAttributes = "";
			$this->tipdesc->EditValue = ew_HtmlEncode($this->tipdesc->CurrentValue);
			$this->tipdesc->PlaceHolder = ew_RemoveHtml($this->tipdesc->FldCaption());

			// tiplink
			$this->tiplink->EditAttrs["class"] = "form-control";
			$this->tiplink->EditCustomAttributes = "";
			$this->tiplink->EditValue = ew_HtmlEncode($this->tiplink->CurrentValue);
			$this->tiplink->PlaceHolder = ew_RemoveHtml($this->tiplink->FldCaption());

			// dateadded
			// Edit refer script
			// tiptitle

			$this->tiptitle->HrefValue = "";

			// tipimage
			if (!ew_Empty($this->tipimage->Upload->DbValue)) {
				$this->tipimage->HrefValue = ew_GetFileUploadUrl($this->tipimage, $this->tipimage->Upload->DbValue); // Add prefix/suffix
				$this->tipimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->tipimage->HrefValue = ew_ConvertFullUrl($this->tipimage->HrefValue);
			} else {
				$this->tipimage->HrefValue = "";
			}
			$this->tipimage->HrefValue2 = $this->tipimage->UploadPath . $this->tipimage->Upload->DbValue;

			// tipdesc
			$this->tipdesc->HrefValue = "";

			// tiplink
			$this->tiplink->HrefValue = "";

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
		if (!$this->tiptitle->FldIsDetailKey && !is_null($this->tiptitle->FormValue) && $this->tiptitle->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tiptitle->FldCaption(), $this->tiptitle->ReqErrMsg));
		}
		if ($this->tipimage->Upload->FileName == "" && !$this->tipimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipimage->FldCaption(), $this->tipimage->ReqErrMsg));
		}
		if (!$this->tipdesc->FldIsDetailKey && !is_null($this->tipdesc->FormValue) && $this->tipdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipdesc->FldCaption(), $this->tipdesc->ReqErrMsg));
		}
		if (!$this->tiplink->FldIsDetailKey && !is_null($this->tiplink->FormValue) && $this->tiplink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tiplink->FldCaption(), $this->tiplink->ReqErrMsg));
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

		// tiptitle
		$this->tiptitle->SetDbValueDef($rsnew, $this->tiptitle->CurrentValue, "", FALSE);

		// tipimage
		if (!$this->tipimage->Upload->KeepFile) {
			$this->tipimage->Upload->DbValue = ""; // No need to delete old file
			if ($this->tipimage->Upload->FileName == "") {
				$rsnew['tipimage'] = NULL;
			} else {
				$rsnew['tipimage'] = $this->tipimage->Upload->FileName;
			}
			$this->tipimage->ImageWidth = 400; // Resize width
			$this->tipimage->ImageHeight = 0; // Resize height
		}

		// tipdesc
		$this->tipdesc->SetDbValueDef($rsnew, $this->tipdesc->CurrentValue, "", FALSE);

		// tiplink
		$this->tiplink->SetDbValueDef($rsnew, $this->tiplink->CurrentValue, "", FALSE);

		// dateadded
		$this->dateadded->SetDbValueDef($rsnew, ew_CurrentDateTime(), ew_CurrentDate());
		$rsnew['dateadded'] = &$this->dateadded->DbValue;
		if (!$this->tipimage->Upload->KeepFile) {
			if (!ew_Empty($this->tipimage->Upload->Value)) {
				if ($this->tipimage->Upload->FileName == $this->tipimage->Upload->DbValue) { // Overwrite if same file name
					$this->tipimage->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['tipimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->tipimage->UploadPath), $rsnew['tipimage']); // Get new file name
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
				$this->tipsid->setDbValue($conn->Insert_ID());
				$rsnew['tipsid'] = $this->tipsid->DbValue;
				if (!$this->tipimage->Upload->KeepFile) {
					if (!ew_Empty($this->tipimage->Upload->Value)) {
						$this->tipimage->Upload->Resize($this->tipimage->ImageWidth, $this->tipimage->ImageHeight);
						$this->tipimage->Upload->SaveToFile($this->tipimage->UploadPath, $rsnew['tipimage'], TRUE);
					}
					if ($this->tipimage->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->tipimage->OldUploadPath) . $this->tipimage->Upload->DbValue);
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

		// tipimage
		ew_CleanUploadTempPath($this->tipimage, $this->tipimage->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "tipslist.php", "", $this->TableVar, TRUE);
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
if (!isset($tips_add)) $tips_add = new ctips_add();

// Page init
$tips_add->Page_Init();

// Page main
$tips_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tips_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftipsadd = new ew_Form("ftipsadd", "add");

// Validate form
ftipsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tiptitle");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tips->tiptitle->FldCaption(), $tips->tiptitle->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_tipimage");
			elm = this.GetElements("fn_x" + infix + "_tipimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $tips->tipimage->FldCaption(), $tips->tipimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tips->tipdesc->FldCaption(), $tips->tipdesc->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tiplink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tips->tiplink->FldCaption(), $tips->tiplink->ReqErrMsg)) ?>");

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
ftipsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftipsadd.ValidateRequired = true;
<?php } else { ?>
ftipsadd.ValidateRequired = false; 
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
<?php $tips_add->ShowPageHeader(); ?>
<?php
$tips_add->ShowMessage();
?>
<form name="ftipsadd" id="ftipsadd" class="<?php echo $tips_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tips_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tips_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tips">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($tips->tiptitle->Visible) { // tiptitle ?>
	<div id="r_tiptitle" class="form-group">
		<label id="elh_tips_tiptitle" for="x_tiptitle" class="col-sm-2 control-label ewLabel"><?php echo $tips->tiptitle->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tips->tiptitle->CellAttributes() ?>>
<span id="el_tips_tiptitle">
<input type="text" data-table="tips" data-field="x_tiptitle" name="x_tiptitle" id="x_tiptitle" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tips->tiptitle->getPlaceHolder()) ?>" value="<?php echo $tips->tiptitle->EditValue ?>"<?php echo $tips->tiptitle->EditAttributes() ?>>
</span>
<?php echo $tips->tiptitle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tips->tipimage->Visible) { // tipimage ?>
	<div id="r_tipimage" class="form-group">
		<label id="elh_tips_tipimage" class="col-sm-2 control-label ewLabel"><?php echo $tips->tipimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tips->tipimage->CellAttributes() ?>>
<span id="el_tips_tipimage">
<div id="fd_x_tipimage">
<span title="<?php echo $tips->tipimage->FldTitle() ? $tips->tipimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($tips->tipimage->ReadOnly || $tips->tipimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="tips" data-field="x_tipimage" name="x_tipimage" id="x_tipimage"<?php echo $tips->tipimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_tipimage" id= "fn_x_tipimage" value="<?php echo $tips->tipimage->Upload->FileName ?>">
<input type="hidden" name="fa_x_tipimage" id= "fa_x_tipimage" value="0">
<input type="hidden" name="fs_x_tipimage" id= "fs_x_tipimage" value="255">
<input type="hidden" name="fx_x_tipimage" id= "fx_x_tipimage" value="<?php echo $tips->tipimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_tipimage" id= "fm_x_tipimage" value="<?php echo $tips->tipimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_tipimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $tips->tipimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tips->tipdesc->Visible) { // tipdesc ?>
	<div id="r_tipdesc" class="form-group">
		<label id="elh_tips_tipdesc" for="x_tipdesc" class="col-sm-2 control-label ewLabel"><?php echo $tips->tipdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tips->tipdesc->CellAttributes() ?>>
<span id="el_tips_tipdesc">
<textarea data-table="tips" data-field="x_tipdesc" name="x_tipdesc" id="x_tipdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tips->tipdesc->getPlaceHolder()) ?>"<?php echo $tips->tipdesc->EditAttributes() ?>><?php echo $tips->tipdesc->EditValue ?></textarea>
</span>
<?php echo $tips->tipdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tips->tiplink->Visible) { // tiplink ?>
	<div id="r_tiplink" class="form-group">
		<label id="elh_tips_tiplink" for="x_tiplink" class="col-sm-2 control-label ewLabel"><?php echo $tips->tiplink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tips->tiplink->CellAttributes() ?>>
<span id="el_tips_tiplink">
<input type="text" data-table="tips" data-field="x_tiplink" name="x_tiplink" id="x_tiplink" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tips->tiplink->getPlaceHolder()) ?>" value="<?php echo $tips->tiplink->EditValue ?>"<?php echo $tips->tiplink->EditAttributes() ?>>
</span>
<?php echo $tips->tiplink->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tips_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
ftipsadd.Init();
</script>
<?php
$tips_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tips_add->Page_Terminate();
?>
