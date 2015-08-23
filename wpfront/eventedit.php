<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "eventinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$event_edit = NULL; // Initialize page object first

class cevent_edit extends cevent {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'event';

	// Page object name
	var $PageObjName = 'event_edit';

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

		// Table object (event)
		if (!isset($GLOBALS["event"]) || get_class($GLOBALS["event"]) == "cevent") {
			$GLOBALS["event"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["event"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'event', TRUE);

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
		$this->eventid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $event;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($event);
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
		if (@$_GET["eventid"] <> "") {
			$this->eventid->setQueryStringValue($_GET["eventid"]);
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
		if ($this->eventid->CurrentValue == "")
			$this->Page_Terminate("eventlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("eventlist.php"); // No matching record, return to list
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
		$this->eventimage->Upload->Index = $objForm->Index;
		$this->eventimage->Upload->UploadFile();
		$this->eventimage->CurrentValue = $this->eventimage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->eventid->FldIsDetailKey)
			$this->eventid->setFormValue($objForm->GetValue("x_eventid"));
		if (!$this->_eventname->FldIsDetailKey) {
			$this->_eventname->setFormValue($objForm->GetValue("x__eventname"));
		}
		if (!$this->eventdesc->FldIsDetailKey) {
			$this->eventdesc->setFormValue($objForm->GetValue("x_eventdesc"));
		}
		if (!$this->eventlink->FldIsDetailKey) {
			$this->eventlink->setFormValue($objForm->GetValue("x_eventlink"));
		}
		if (!$this->eventdate->FldIsDetailKey) {
			$this->eventdate->setFormValue($objForm->GetValue("x_eventdate"));
			$this->eventdate->CurrentValue = ew_UnFormatDateTime($this->eventdate->CurrentValue, 11);
		}
		if (!$this->createddate->FldIsDetailKey) {
			$this->createddate->setFormValue($objForm->GetValue("x_createddate"));
			$this->createddate->CurrentValue = ew_UnFormatDateTime($this->createddate->CurrentValue, 11);
		}
		if (!$this->categoryid->FldIsDetailKey) {
			$this->categoryid->setFormValue($objForm->GetValue("x_categoryid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->eventid->CurrentValue = $this->eventid->FormValue;
		$this->_eventname->CurrentValue = $this->_eventname->FormValue;
		$this->eventdesc->CurrentValue = $this->eventdesc->FormValue;
		$this->eventlink->CurrentValue = $this->eventlink->FormValue;
		$this->eventdate->CurrentValue = $this->eventdate->FormValue;
		$this->eventdate->CurrentValue = ew_UnFormatDateTime($this->eventdate->CurrentValue, 11);
		$this->createddate->CurrentValue = $this->createddate->FormValue;
		$this->createddate->CurrentValue = ew_UnFormatDateTime($this->createddate->CurrentValue, 11);
		$this->categoryid->CurrentValue = $this->categoryid->FormValue;
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
		$this->eventid->setDbValue($rs->fields('eventid'));
		$this->_eventname->setDbValue($rs->fields('eventname'));
		$this->eventdesc->setDbValue($rs->fields('eventdesc'));
		$this->eventimage->Upload->DbValue = $rs->fields('eventimage');
		$this->eventimage->CurrentValue = $this->eventimage->Upload->DbValue;
		$this->eventlink->setDbValue($rs->fields('eventlink'));
		$this->eventdate->setDbValue($rs->fields('eventdate'));
		$this->createddate->setDbValue($rs->fields('createddate'));
		$this->categoryid->setDbValue($rs->fields('categoryid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->eventid->DbValue = $row['eventid'];
		$this->_eventname->DbValue = $row['eventname'];
		$this->eventdesc->DbValue = $row['eventdesc'];
		$this->eventimage->Upload->DbValue = $row['eventimage'];
		$this->eventlink->DbValue = $row['eventlink'];
		$this->eventdate->DbValue = $row['eventdate'];
		$this->createddate->DbValue = $row['createddate'];
		$this->categoryid->DbValue = $row['categoryid'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// eventid
		// eventname
		// eventdesc
		// eventimage
		// eventlink
		// eventdate
		// createddate
		// categoryid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// eventid
			$this->eventid->ViewValue = $this->eventid->CurrentValue;
			$this->eventid->ViewCustomAttributes = "";

			// eventname
			$this->_eventname->ViewValue = $this->_eventname->CurrentValue;
			$this->_eventname->ViewCustomAttributes = "";

			// eventdesc
			$this->eventdesc->ViewValue = $this->eventdesc->CurrentValue;
			$this->eventdesc->ViewCustomAttributes = "";

			// eventimage
			if (!ew_Empty($this->eventimage->Upload->DbValue)) {
				$this->eventimage->ImageWidth = 100;
				$this->eventimage->ImageHeight = 0;
				$this->eventimage->ImageAlt = $this->eventimage->FldAlt();
				$this->eventimage->ViewValue = ew_UploadPathEx(FALSE, $this->eventimage->UploadPath) . $this->eventimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->eventimage->ViewValue = ew_UploadPathEx(TRUE, $this->eventimage->UploadPath) . $this->eventimage->Upload->DbValue;
				}
			} else {
				$this->eventimage->ViewValue = "";
			}
			$this->eventimage->ViewCustomAttributes = "";

			// eventlink
			$this->eventlink->ViewValue = $this->eventlink->CurrentValue;
			$this->eventlink->ViewCustomAttributes = "";

			// eventdate
			$this->eventdate->ViewValue = $this->eventdate->CurrentValue;
			$this->eventdate->ViewValue = ew_FormatDateTime($this->eventdate->ViewValue, 11);
			$this->eventdate->ViewCustomAttributes = "";

			// createddate
			$this->createddate->ViewValue = $this->createddate->CurrentValue;
			$this->createddate->ViewValue = ew_FormatDateTime($this->createddate->ViewValue, 11);
			$this->createddate->ViewCustomAttributes = "";

			// categoryid
			if (strval($this->categoryid->CurrentValue) <> "") {
				$sFilterWrk = "`categoryid`" . ew_SearchString("=", $this->categoryid->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `categoryid`, `categoryname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->categoryid, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->categoryid->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->categoryid->ViewValue = $this->categoryid->CurrentValue;
				}
			} else {
				$this->categoryid->ViewValue = NULL;
			}
			$this->categoryid->ViewCustomAttributes = "";

			// eventid
			$this->eventid->LinkCustomAttributes = "";
			$this->eventid->HrefValue = "";
			$this->eventid->TooltipValue = "";

			// eventname
			$this->_eventname->LinkCustomAttributes = "";
			if (!ew_Empty($this->eventlink->CurrentValue)) {
				$this->_eventname->HrefValue = $this->eventlink->CurrentValue; // Add prefix/suffix
				$this->_eventname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->_eventname->HrefValue = ew_ConvertFullUrl($this->_eventname->HrefValue);
			} else {
				$this->_eventname->HrefValue = "";
			}
			$this->_eventname->TooltipValue = "";

			// eventdesc
			$this->eventdesc->LinkCustomAttributes = "";
			$this->eventdesc->HrefValue = "";
			$this->eventdesc->TooltipValue = "";

			// eventimage
			$this->eventimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->eventimage->Upload->DbValue)) {
				$this->eventimage->HrefValue = ew_UploadPathEx(FALSE, $this->eventimage->UploadPath) . $this->eventimage->Upload->DbValue; // Add prefix/suffix
				$this->eventimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->eventimage->HrefValue = ew_ConvertFullUrl($this->eventimage->HrefValue);
			} else {
				$this->eventimage->HrefValue = "";
			}
			$this->eventimage->HrefValue2 = $this->eventimage->UploadPath . $this->eventimage->Upload->DbValue;
			$this->eventimage->TooltipValue = "";
			if ($this->eventimage->UseColorbox) {
				$this->eventimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->eventimage->LinkAttrs["data-rel"] = "event_x_eventimage";
				$this->eventimage->LinkAttrs["class"] = "ewLightbox";
			}

			// eventlink
			$this->eventlink->LinkCustomAttributes = "";
			$this->eventlink->HrefValue = "";
			$this->eventlink->TooltipValue = "";

			// eventdate
			$this->eventdate->LinkCustomAttributes = "";
			$this->eventdate->HrefValue = "";
			$this->eventdate->TooltipValue = "";

			// createddate
			$this->createddate->LinkCustomAttributes = "";
			$this->createddate->HrefValue = "";
			$this->createddate->TooltipValue = "";

			// categoryid
			$this->categoryid->LinkCustomAttributes = "";
			$this->categoryid->HrefValue = "";
			$this->categoryid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// eventid
			$this->eventid->EditAttrs["class"] = "form-control";
			$this->eventid->EditCustomAttributes = "";
			$this->eventid->EditValue = $this->eventid->CurrentValue;
			$this->eventid->ViewCustomAttributes = "";

			// eventname
			$this->_eventname->EditAttrs["class"] = "form-control";
			$this->_eventname->EditCustomAttributes = "";
			$this->_eventname->EditValue = ew_HtmlEncode($this->_eventname->CurrentValue);
			$this->_eventname->PlaceHolder = ew_RemoveHtml($this->_eventname->FldCaption());

			// eventdesc
			$this->eventdesc->EditAttrs["class"] = "form-control";
			$this->eventdesc->EditCustomAttributes = "";
			$this->eventdesc->EditValue = ew_HtmlEncode($this->eventdesc->CurrentValue);
			$this->eventdesc->PlaceHolder = ew_RemoveHtml($this->eventdesc->FldCaption());

			// eventimage
			$this->eventimage->EditAttrs["class"] = "form-control";
			$this->eventimage->EditCustomAttributes = "";
			if (!ew_Empty($this->eventimage->Upload->DbValue)) {
				$this->eventimage->ImageWidth = 100;
				$this->eventimage->ImageHeight = 0;
				$this->eventimage->ImageAlt = $this->eventimage->FldAlt();
				$this->eventimage->EditValue = ew_UploadPathEx(FALSE, $this->eventimage->UploadPath) . $this->eventimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->eventimage->EditValue = ew_UploadPathEx(TRUE, $this->eventimage->UploadPath) . $this->eventimage->Upload->DbValue;
				}
			} else {
				$this->eventimage->EditValue = "";
			}
			if (!ew_Empty($this->eventimage->CurrentValue))
				$this->eventimage->Upload->FileName = $this->eventimage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->eventimage);

			// eventlink
			$this->eventlink->EditAttrs["class"] = "form-control";
			$this->eventlink->EditCustomAttributes = "";
			$this->eventlink->EditValue = ew_HtmlEncode($this->eventlink->CurrentValue);
			$this->eventlink->PlaceHolder = ew_RemoveHtml($this->eventlink->FldCaption());

			// eventdate
			$this->eventdate->EditAttrs["class"] = "form-control";
			$this->eventdate->EditCustomAttributes = "";
			$this->eventdate->CurrentValue = ew_FormatDateTime($this->eventdate->CurrentValue, 11);

			// createddate
			// categoryid

			$this->categoryid->EditAttrs["class"] = "form-control";
			$this->categoryid->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `categoryid`, `categoryname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `category`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->categoryid, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->categoryid->EditValue = $arwrk;

			// Edit refer script
			// eventid

			$this->eventid->HrefValue = "";

			// eventname
			if (!ew_Empty($this->eventlink->CurrentValue)) {
				$this->_eventname->HrefValue = $this->eventlink->CurrentValue; // Add prefix/suffix
				$this->_eventname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->_eventname->HrefValue = ew_ConvertFullUrl($this->_eventname->HrefValue);
			} else {
				$this->_eventname->HrefValue = "";
			}

			// eventdesc
			$this->eventdesc->HrefValue = "";

			// eventimage
			if (!ew_Empty($this->eventimage->Upload->DbValue)) {
				$this->eventimage->HrefValue = ew_UploadPathEx(FALSE, $this->eventimage->UploadPath) . $this->eventimage->Upload->DbValue; // Add prefix/suffix
				$this->eventimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->eventimage->HrefValue = ew_ConvertFullUrl($this->eventimage->HrefValue);
			} else {
				$this->eventimage->HrefValue = "";
			}
			$this->eventimage->HrefValue2 = $this->eventimage->UploadPath . $this->eventimage->Upload->DbValue;

			// eventlink
			$this->eventlink->HrefValue = "";

			// eventdate
			$this->eventdate->HrefValue = "";

			// createddate
			$this->createddate->HrefValue = "";

			// categoryid
			$this->categoryid->HrefValue = "";
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
		if (!$this->_eventname->FldIsDetailKey && !is_null($this->_eventname->FormValue) && $this->_eventname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_eventname->FldCaption(), $this->_eventname->ReqErrMsg));
		}
		if (!$this->eventdesc->FldIsDetailKey && !is_null($this->eventdesc->FormValue) && $this->eventdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventdesc->FldCaption(), $this->eventdesc->ReqErrMsg));
		}
		if ($this->eventimage->Upload->FileName == "" && !$this->eventimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventimage->FldCaption(), $this->eventimage->ReqErrMsg));
		}
		if (!$this->eventlink->FldIsDetailKey && !is_null($this->eventlink->FormValue) && $this->eventlink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventlink->FldCaption(), $this->eventlink->ReqErrMsg));
		}
		if (!$this->categoryid->FldIsDetailKey && !is_null($this->categoryid->FormValue) && $this->categoryid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->categoryid->FldCaption(), $this->categoryid->ReqErrMsg));
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

			// eventname
			$this->_eventname->SetDbValueDef($rsnew, $this->_eventname->CurrentValue, "", $this->_eventname->ReadOnly);

			// eventdesc
			$this->eventdesc->SetDbValueDef($rsnew, $this->eventdesc->CurrentValue, "", $this->eventdesc->ReadOnly);

			// eventimage
			if (!($this->eventimage->ReadOnly) && !$this->eventimage->Upload->KeepFile) {
				$this->eventimage->Upload->DbValue = $rsold['eventimage']; // Get original value
				if ($this->eventimage->Upload->FileName == "") {
					$rsnew['eventimage'] = NULL;
				} else {
					$rsnew['eventimage'] = $this->eventimage->Upload->FileName;
				}
			}

			// eventlink
			$this->eventlink->SetDbValueDef($rsnew, $this->eventlink->CurrentValue, "", $this->eventlink->ReadOnly);

			// eventdate
			$this->eventdate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->eventdate->CurrentValue, 11), NULL, $this->eventdate->ReadOnly);

			// createddate
			$this->createddate->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['createddate'] = &$this->createddate->DbValue;

			// categoryid
			$this->categoryid->SetDbValueDef($rsnew, $this->categoryid->CurrentValue, 0, $this->categoryid->ReadOnly);
			if (!$this->eventimage->Upload->KeepFile) {
				if (!ew_Empty($this->eventimage->Upload->Value)) {
					if ($this->eventimage->Upload->FileName == $this->eventimage->Upload->DbValue) { // Overwrite if same file name
						$this->eventimage->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['eventimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->eventimage->UploadPath), $rsnew['eventimage']); // Get new file name
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
					if (!$this->eventimage->Upload->KeepFile) {
						if (!ew_Empty($this->eventimage->Upload->Value)) {
							$this->eventimage->Upload->SaveToFile($this->eventimage->UploadPath, $rsnew['eventimage'], TRUE);
						}
						if ($this->eventimage->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->eventimage->OldUploadPath) . $this->eventimage->Upload->DbValue);
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

		// eventimage
		ew_CleanUploadTempPath($this->eventimage, $this->eventimage->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "eventlist.php", "", $this->TableVar, TRUE);
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
if (!isset($event_edit)) $event_edit = new cevent_edit();

// Page init
$event_edit->Page_Init();

// Page main
$event_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$event_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var event_edit = new ew_Page("event_edit");
event_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = event_edit.PageID; // For backward compatibility

// Form object
var feventedit = new ew_Form("feventedit");

// Validate form
feventedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "__eventname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $event->_eventname->FldCaption(), $event->_eventname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $event->eventdesc->FldCaption(), $event->eventdesc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_eventimage");
			elm = this.GetElements("fn_x" + infix + "_eventimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $event->eventimage->FldCaption(), $event->eventimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventlink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $event->eventlink->FldCaption(), $event->eventlink->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categoryid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $event->categoryid->FldCaption(), $event->categoryid->ReqErrMsg)) ?>");

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
feventedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
feventedit.ValidateRequired = true;
<?php } else { ?>
feventedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
feventedit.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $event_edit->ShowPageHeader(); ?>
<?php
$event_edit->ShowMessage();
?>
<form name="feventedit" id="feventedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($event_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $event_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="event">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($event->eventid->Visible) { // eventid ?>
	<div id="r_eventid" class="form-group">
		<label id="elh_event_eventid" class="col-sm-2 control-label ewLabel"><?php echo $event->eventid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $event->eventid->CellAttributes() ?>>
<span id="el_event_eventid">
<span<?php echo $event->eventid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $event->eventid->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_eventid" name="x_eventid" id="x_eventid" value="<?php echo ew_HtmlEncode($event->eventid->CurrentValue) ?>">
<?php echo $event->eventid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($event->_eventname->Visible) { // eventname ?>
	<div id="r__eventname" class="form-group">
		<label id="elh_event__eventname" for="x__eventname" class="col-sm-2 control-label ewLabel"><?php echo $event->_eventname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $event->_eventname->CellAttributes() ?>>
<span id="el_event__eventname">
<input type="text" data-field="x__eventname" name="x__eventname" id="x__eventname" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($event->_eventname->PlaceHolder) ?>" value="<?php echo $event->_eventname->EditValue ?>"<?php echo $event->_eventname->EditAttributes() ?>>
</span>
<?php echo $event->_eventname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($event->eventdesc->Visible) { // eventdesc ?>
	<div id="r_eventdesc" class="form-group">
		<label id="elh_event_eventdesc" for="x_eventdesc" class="col-sm-2 control-label ewLabel"><?php echo $event->eventdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $event->eventdesc->CellAttributes() ?>>
<span id="el_event_eventdesc">
<textarea data-field="x_eventdesc" name="x_eventdesc" id="x_eventdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($event->eventdesc->PlaceHolder) ?>"<?php echo $event->eventdesc->EditAttributes() ?>><?php echo $event->eventdesc->EditValue ?></textarea>
</span>
<?php echo $event->eventdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($event->eventimage->Visible) { // eventimage ?>
	<div id="r_eventimage" class="form-group">
		<label id="elh_event_eventimage" class="col-sm-2 control-label ewLabel"><?php echo $event->eventimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $event->eventimage->CellAttributes() ?>>
<span id="el_event_eventimage">
<div id="fd_x_eventimage">
<span title="<?php echo $event->eventimage->FldTitle() ? $event->eventimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($event->eventimage->ReadOnly || $event->eventimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_eventimage" name="x_eventimage" id="x_eventimage">
</span>
<input type="hidden" name="fn_x_eventimage" id= "fn_x_eventimage" value="<?php echo $event->eventimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_eventimage"] == "0") { ?>
<input type="hidden" name="fa_x_eventimage" id= "fa_x_eventimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_eventimage" id= "fa_x_eventimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_eventimage" id= "fs_x_eventimage" value="255">
<input type="hidden" name="fx_x_eventimage" id= "fx_x_eventimage" value="<?php echo $event->eventimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_eventimage" id= "fm_x_eventimage" value="<?php echo $event->eventimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_eventimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $event->eventimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($event->eventlink->Visible) { // eventlink ?>
	<div id="r_eventlink" class="form-group">
		<label id="elh_event_eventlink" for="x_eventlink" class="col-sm-2 control-label ewLabel"><?php echo $event->eventlink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $event->eventlink->CellAttributes() ?>>
<span id="el_event_eventlink">
<input type="text" data-field="x_eventlink" name="x_eventlink" id="x_eventlink" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($event->eventlink->PlaceHolder) ?>" value="<?php echo $event->eventlink->EditValue ?>"<?php echo $event->eventlink->EditAttributes() ?>>
</span>
<?php echo $event->eventlink->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($event->categoryid->Visible) { // categoryid ?>
	<div id="r_categoryid" class="form-group">
		<label id="elh_event_categoryid" for="x_categoryid" class="col-sm-2 control-label ewLabel"><?php echo $event->categoryid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $event->categoryid->CellAttributes() ?>>
<span id="el_event_categoryid">
<select data-field="x_categoryid" id="x_categoryid" name="x_categoryid"<?php echo $event->categoryid->EditAttributes() ?>>
<?php
if (is_array($event->categoryid->EditValue)) {
	$arwrk = $event->categoryid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($event->categoryid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
feventedit.Lists["x_categoryid"].Options = <?php echo (is_array($event->categoryid->EditValue)) ? ew_ArrayToJson($event->categoryid->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $event->categoryid->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<span id="el_event_eventdate">
<input type="hidden" data-field="x_eventdate" name="x_eventdate" id="x_eventdate" value="<?php echo ew_HtmlEncode($event->eventdate->CurrentValue) ?>">
</span>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
feventedit.Init();
</script>
<?php
$event_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$event_edit->Page_Terminate();
?>
