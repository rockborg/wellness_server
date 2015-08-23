<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "categoryinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$category_edit = NULL; // Initialize page object first

class ccategory_edit extends ccategory {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'category';

	// Page object name
	var $PageObjName = 'category_edit';

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
			return $fn($_POST[EW_TOKEN_NAME]);
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

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (category)
		if (!isset($GLOBALS["category"]) || get_class($GLOBALS["category"]) == "ccategory") {
			$GLOBALS["category"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["category"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'category', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->categoryid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $category;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($category);
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
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["categoryid"] <> "") {
			$this->categoryid->setQueryStringValue($_GET["categoryid"]);
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
		if ($this->categoryid->CurrentValue == "")
			$this->Page_Terminate("categorylist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("categorylist.php"); // No matching record, return to list
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
		$this->categoryimage->Upload->Index = $objForm->Index;
		$this->categoryimage->Upload->UploadFile();
		$this->categoryimage->CurrentValue = $this->categoryimage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->categoryid->FldIsDetailKey)
			$this->categoryid->setFormValue($objForm->GetValue("x_categoryid"));
		if (!$this->categoryname->FldIsDetailKey) {
			$this->categoryname->setFormValue($objForm->GetValue("x_categoryname"));
		}
		if (!$this->categorydesc->FldIsDetailKey) {
			$this->categorydesc->setFormValue($objForm->GetValue("x_categorydesc"));
		}
		if (!$this->createddate->FldIsDetailKey) {
			$this->createddate->setFormValue($objForm->GetValue("x_createddate"));
			$this->createddate->CurrentValue = ew_UnFormatDateTime($this->createddate->CurrentValue, 11);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->categoryid->CurrentValue = $this->categoryid->FormValue;
		$this->categoryname->CurrentValue = $this->categoryname->FormValue;
		$this->categorydesc->CurrentValue = $this->categorydesc->FormValue;
		$this->createddate->CurrentValue = $this->createddate->FormValue;
		$this->createddate->CurrentValue = ew_UnFormatDateTime($this->createddate->CurrentValue, 11);
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->categoryid->setDbValue($rs->fields('categoryid'));
		$this->categoryname->setDbValue($rs->fields('categoryname'));
		$this->categorydesc->setDbValue($rs->fields('categorydesc'));
		$this->categoryimage->Upload->DbValue = $rs->fields('categoryimage');
		$this->categoryimage->CurrentValue = $this->categoryimage->Upload->DbValue;
		$this->createddate->setDbValue($rs->fields('createddate'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->categoryid->DbValue = $row['categoryid'];
		$this->categoryname->DbValue = $row['categoryname'];
		$this->categorydesc->DbValue = $row['categorydesc'];
		$this->categoryimage->Upload->DbValue = $row['categoryimage'];
		$this->createddate->DbValue = $row['createddate'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// categoryid
		// categoryname
		// categorydesc
		// categoryimage
		// createddate

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// categoryid
			$this->categoryid->ViewValue = $this->categoryid->CurrentValue;
			$this->categoryid->ViewCustomAttributes = "";

			// categoryname
			$this->categoryname->ViewValue = $this->categoryname->CurrentValue;
			$this->categoryname->ViewCustomAttributes = "";

			// categorydesc
			$this->categorydesc->ViewValue = $this->categorydesc->CurrentValue;
			$this->categorydesc->ViewCustomAttributes = "";

			// categoryimage
			if (!ew_Empty($this->categoryimage->Upload->DbValue)) {
				$this->categoryimage->ImageWidth = 100;
				$this->categoryimage->ImageHeight = 0;
				$this->categoryimage->ImageAlt = $this->categoryimage->FldAlt();
				$this->categoryimage->ViewValue = ew_UploadPathEx(FALSE, $this->categoryimage->UploadPath) . $this->categoryimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->categoryimage->ViewValue = ew_UploadPathEx(TRUE, $this->categoryimage->UploadPath) . $this->categoryimage->Upload->DbValue;
				}
			} else {
				$this->categoryimage->ViewValue = "";
			}
			$this->categoryimage->ViewCustomAttributes = "";

			// createddate
			$this->createddate->ViewValue = $this->createddate->CurrentValue;
			$this->createddate->ViewValue = ew_FormatDateTime($this->createddate->ViewValue, 11);
			$this->createddate->ViewCustomAttributes = "";

			// categoryid
			$this->categoryid->LinkCustomAttributes = "";
			$this->categoryid->HrefValue = "";
			$this->categoryid->TooltipValue = "";

			// categoryname
			$this->categoryname->LinkCustomAttributes = "";
			$this->categoryname->HrefValue = "";
			$this->categoryname->TooltipValue = "";

			// categorydesc
			$this->categorydesc->LinkCustomAttributes = "";
			$this->categorydesc->HrefValue = "";
			$this->categorydesc->TooltipValue = "";

			// categoryimage
			$this->categoryimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->categoryimage->Upload->DbValue)) {
				$this->categoryimage->HrefValue = ew_UploadPathEx(FALSE, $this->categoryimage->UploadPath) . $this->categoryimage->Upload->DbValue; // Add prefix/suffix
				$this->categoryimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->categoryimage->HrefValue = ew_ConvertFullUrl($this->categoryimage->HrefValue);
			} else {
				$this->categoryimage->HrefValue = "";
			}
			$this->categoryimage->HrefValue2 = $this->categoryimage->UploadPath . $this->categoryimage->Upload->DbValue;
			$this->categoryimage->TooltipValue = "";
			if ($this->categoryimage->UseColorbox) {
				$this->categoryimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->categoryimage->LinkAttrs["data-rel"] = "category_x_categoryimage";
				$this->categoryimage->LinkAttrs["class"] = "ewLightbox";
			}

			// createddate
			$this->createddate->LinkCustomAttributes = "";
			$this->createddate->HrefValue = "";
			$this->createddate->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// categoryid
			$this->categoryid->EditAttrs["class"] = "form-control";
			$this->categoryid->EditCustomAttributes = "";
			$this->categoryid->EditValue = $this->categoryid->CurrentValue;
			$this->categoryid->ViewCustomAttributes = "";

			// categoryname
			$this->categoryname->EditAttrs["class"] = "form-control";
			$this->categoryname->EditCustomAttributes = "";
			$this->categoryname->EditValue = ew_HtmlEncode($this->categoryname->CurrentValue);
			$this->categoryname->PlaceHolder = ew_RemoveHtml($this->categoryname->FldCaption());

			// categorydesc
			$this->categorydesc->EditAttrs["class"] = "form-control";
			$this->categorydesc->EditCustomAttributes = "";
			$this->categorydesc->EditValue = ew_HtmlEncode($this->categorydesc->CurrentValue);
			$this->categorydesc->PlaceHolder = ew_RemoveHtml($this->categorydesc->FldCaption());

			// categoryimage
			$this->categoryimage->EditAttrs["class"] = "form-control";
			$this->categoryimage->EditCustomAttributes = "";
			if (!ew_Empty($this->categoryimage->Upload->DbValue)) {
				$this->categoryimage->ImageWidth = 100;
				$this->categoryimage->ImageHeight = 0;
				$this->categoryimage->ImageAlt = $this->categoryimage->FldAlt();
				$this->categoryimage->EditValue = ew_UploadPathEx(FALSE, $this->categoryimage->UploadPath) . $this->categoryimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->categoryimage->EditValue = ew_UploadPathEx(TRUE, $this->categoryimage->UploadPath) . $this->categoryimage->Upload->DbValue;
				}
			} else {
				$this->categoryimage->EditValue = "";
			}
			if (!ew_Empty($this->categoryimage->CurrentValue))
				$this->categoryimage->Upload->FileName = $this->categoryimage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->categoryimage);

			// createddate
			// Edit refer script
			// categoryid

			$this->categoryid->HrefValue = "";

			// categoryname
			$this->categoryname->HrefValue = "";

			// categorydesc
			$this->categorydesc->HrefValue = "";

			// categoryimage
			if (!ew_Empty($this->categoryimage->Upload->DbValue)) {
				$this->categoryimage->HrefValue = ew_UploadPathEx(FALSE, $this->categoryimage->UploadPath) . $this->categoryimage->Upload->DbValue; // Add prefix/suffix
				$this->categoryimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->categoryimage->HrefValue = ew_ConvertFullUrl($this->categoryimage->HrefValue);
			} else {
				$this->categoryimage->HrefValue = "";
			}
			$this->categoryimage->HrefValue2 = $this->categoryimage->UploadPath . $this->categoryimage->Upload->DbValue;

			// createddate
			$this->createddate->HrefValue = "";
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
		if (!$this->categoryname->FldIsDetailKey && !is_null($this->categoryname->FormValue) && $this->categoryname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->categoryname->FldCaption(), $this->categoryname->ReqErrMsg));
		}
		if (!$this->categorydesc->FldIsDetailKey && !is_null($this->categorydesc->FormValue) && $this->categorydesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->categorydesc->FldCaption(), $this->categorydesc->ReqErrMsg));
		}
		if ($this->categoryimage->Upload->FileName == "" && !$this->categoryimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->categoryimage->FldCaption(), $this->categoryimage->ReqErrMsg));
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
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
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

			// categoryname
			$this->categoryname->SetDbValueDef($rsnew, $this->categoryname->CurrentValue, "", $this->categoryname->ReadOnly);

			// categorydesc
			$this->categorydesc->SetDbValueDef($rsnew, $this->categorydesc->CurrentValue, "", $this->categorydesc->ReadOnly);

			// categoryimage
			if (!($this->categoryimage->ReadOnly) && !$this->categoryimage->Upload->KeepFile) {
				$this->categoryimage->Upload->DbValue = $rsold['categoryimage']; // Get original value
				if ($this->categoryimage->Upload->FileName == "") {
					$rsnew['categoryimage'] = NULL;
				} else {
					$rsnew['categoryimage'] = $this->categoryimage->Upload->FileName;
				}
			}

			// createddate
			$this->createddate->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['createddate'] = &$this->createddate->DbValue;
			if (!$this->categoryimage->Upload->KeepFile) {
				if (!ew_Empty($this->categoryimage->Upload->Value)) {
					if ($this->categoryimage->Upload->FileName == $this->categoryimage->Upload->DbValue) { // Overwrite if same file name
						$this->categoryimage->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['categoryimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->categoryimage->UploadPath), $rsnew['categoryimage']); // Get new file name
					}
				}
			}

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
					if (!$this->categoryimage->Upload->KeepFile) {
						if (!ew_Empty($this->categoryimage->Upload->Value)) {
							$this->categoryimage->Upload->SaveToFile($this->categoryimage->UploadPath, $rsnew['categoryimage'], TRUE);
						}
						if ($this->categoryimage->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->categoryimage->OldUploadPath) . $this->categoryimage->Upload->DbValue);
					}
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

		// categoryimage
		ew_CleanUploadTempPath($this->categoryimage, $this->categoryimage->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "categorylist.php", "", $this->TableVar, TRUE);
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
if (!isset($category_edit)) $category_edit = new ccategory_edit();

// Page init
$category_edit->Page_Init();

// Page main
$category_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$category_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var category_edit = new ew_Page("category_edit");
category_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = category_edit.PageID; // For backward compatibility

// Form object
var fcategoryedit = new ew_Form("fcategoryedit");

// Validate form
fcategoryedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
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
			elm = this.GetElements("x" + infix + "_categoryname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $category->categoryname->FldCaption(), $category->categoryname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categorydesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $category->categorydesc->FldCaption(), $category->categorydesc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_categoryimage");
			elm = this.GetElements("fn_x" + infix + "_categoryimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $category->categoryimage->FldCaption(), $category->categoryimage->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

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
fcategoryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcategoryedit.ValidateRequired = true;
<?php } else { ?>
fcategoryedit.ValidateRequired = false; 
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
<?php $category_edit->ShowPageHeader(); ?>
<?php
$category_edit->ShowMessage();
?>
<form name="fcategoryedit" id="fcategoryedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($category_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $category_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="category">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($category->categoryid->Visible) { // categoryid ?>
	<div id="r_categoryid" class="form-group">
		<label id="elh_category_categoryid" class="col-sm-2 control-label ewLabel"><?php echo $category->categoryid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $category->categoryid->CellAttributes() ?>>
<span id="el_category_categoryid">
<span<?php echo $category->categoryid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $category->categoryid->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_categoryid" name="x_categoryid" id="x_categoryid" value="<?php echo ew_HtmlEncode($category->categoryid->CurrentValue) ?>">
<?php echo $category->categoryid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($category->categoryname->Visible) { // categoryname ?>
	<div id="r_categoryname" class="form-group">
		<label id="elh_category_categoryname" for="x_categoryname" class="col-sm-2 control-label ewLabel"><?php echo $category->categoryname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $category->categoryname->CellAttributes() ?>>
<span id="el_category_categoryname">
<input type="text" data-field="x_categoryname" name="x_categoryname" id="x_categoryname" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($category->categoryname->PlaceHolder) ?>" value="<?php echo $category->categoryname->EditValue ?>"<?php echo $category->categoryname->EditAttributes() ?>>
</span>
<?php echo $category->categoryname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($category->categorydesc->Visible) { // categorydesc ?>
	<div id="r_categorydesc" class="form-group">
		<label id="elh_category_categorydesc" for="x_categorydesc" class="col-sm-2 control-label ewLabel"><?php echo $category->categorydesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $category->categorydesc->CellAttributes() ?>>
<span id="el_category_categorydesc">
<textarea data-field="x_categorydesc" name="x_categorydesc" id="x_categorydesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($category->categorydesc->PlaceHolder) ?>"<?php echo $category->categorydesc->EditAttributes() ?>><?php echo $category->categorydesc->EditValue ?></textarea>
</span>
<?php echo $category->categorydesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($category->categoryimage->Visible) { // categoryimage ?>
	<div id="r_categoryimage" class="form-group">
		<label id="elh_category_categoryimage" class="col-sm-2 control-label ewLabel"><?php echo $category->categoryimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $category->categoryimage->CellAttributes() ?>>
<span id="el_category_categoryimage">
<div id="fd_x_categoryimage">
<span title="<?php echo $category->categoryimage->FldTitle() ? $category->categoryimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($category->categoryimage->ReadOnly || $category->categoryimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_categoryimage" name="x_categoryimage" id="x_categoryimage">
</span>
<input type="hidden" name="fn_x_categoryimage" id= "fn_x_categoryimage" value="<?php echo $category->categoryimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_categoryimage"] == "0") { ?>
<input type="hidden" name="fa_x_categoryimage" id= "fa_x_categoryimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_categoryimage" id= "fa_x_categoryimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_categoryimage" id= "fs_x_categoryimage" value="255">
<input type="hidden" name="fx_x_categoryimage" id= "fx_x_categoryimage" value="<?php echo $category->categoryimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_categoryimage" id= "fm_x_categoryimage" value="<?php echo $category->categoryimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_categoryimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $category->categoryimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcategoryedit.Init();
</script>
<?php
$category_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$category_edit->Page_Terminate();
?>
