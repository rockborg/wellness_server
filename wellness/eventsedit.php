<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "eventsinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$events_edit = NULL; // Initialize page object first

class cevents_edit extends cevents {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'events';

	// Page object name
	var $PageObjName = 'events_edit';

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

		// Table object (events)
		if (!isset($GLOBALS["events"]) || get_class($GLOBALS["events"]) == "cevents") {
			$GLOBALS["events"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["events"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'events', TRUE);

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
		$this->eventsid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $events;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($events);
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
		if (@$_GET["eventsid"] <> "") {
			$this->eventsid->setQueryStringValue($_GET["eventsid"]);
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
		if ($this->eventsid->CurrentValue == "")
			$this->Page_Terminate("eventslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("eventslist.php"); // No matching record, return to list
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
		if (!$this->eventsid->FldIsDetailKey)
			$this->eventsid->setFormValue($objForm->GetValue("x_eventsid"));
		if (!$this->eventtitle->FldIsDetailKey) {
			$this->eventtitle->setFormValue($objForm->GetValue("x_eventtitle"));
		}
		if (!$this->eventhost->FldIsDetailKey) {
			$this->eventhost->setFormValue($objForm->GetValue("x_eventhost"));
		}
		if (!$this->eventaddress->FldIsDetailKey) {
			$this->eventaddress->setFormValue($objForm->GetValue("x_eventaddress"));
		}
		if (!$this->eventcity->FldIsDetailKey) {
			$this->eventcity->setFormValue($objForm->GetValue("x_eventcity"));
		}
		if (!$this->eventdate->FldIsDetailKey) {
			$this->eventdate->setFormValue($objForm->GetValue("x_eventdate"));
			$this->eventdate->CurrentValue = ew_UnFormatDateTime($this->eventdate->CurrentValue, 5);
		}
		if (!$this->eventtime->FldIsDetailKey) {
			$this->eventtime->setFormValue($objForm->GetValue("x_eventtime"));
		}
		if (!$this->eventcategory->FldIsDetailKey) {
			$this->eventcategory->setFormValue($objForm->GetValue("x_eventcategory"));
		}
		if (!$this->eventdesc->FldIsDetailKey) {
			$this->eventdesc->setFormValue($objForm->GetValue("x_eventdesc"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->eventsid->CurrentValue = $this->eventsid->FormValue;
		$this->eventtitle->CurrentValue = $this->eventtitle->FormValue;
		$this->eventhost->CurrentValue = $this->eventhost->FormValue;
		$this->eventaddress->CurrentValue = $this->eventaddress->FormValue;
		$this->eventcity->CurrentValue = $this->eventcity->FormValue;
		$this->eventdate->CurrentValue = $this->eventdate->FormValue;
		$this->eventdate->CurrentValue = ew_UnFormatDateTime($this->eventdate->CurrentValue, 5);
		$this->eventtime->CurrentValue = $this->eventtime->FormValue;
		$this->eventcategory->CurrentValue = $this->eventcategory->FormValue;
		$this->eventdesc->CurrentValue = $this->eventdesc->FormValue;
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
		$this->eventsid->setDbValue($rs->fields('eventsid'));
		$this->eventtitle->setDbValue($rs->fields('eventtitle'));
		$this->eventhost->setDbValue($rs->fields('eventhost'));
		$this->eventaddress->setDbValue($rs->fields('eventaddress'));
		$this->eventcity->setDbValue($rs->fields('eventcity'));
		$this->eventdate->setDbValue($rs->fields('eventdate'));
		$this->eventtime->setDbValue($rs->fields('eventtime'));
		$this->eventcategory->setDbValue($rs->fields('eventcategory'));
		$this->eventdesc->setDbValue($rs->fields('eventdesc'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->eventsid->DbValue = $row['eventsid'];
		$this->eventtitle->DbValue = $row['eventtitle'];
		$this->eventhost->DbValue = $row['eventhost'];
		$this->eventaddress->DbValue = $row['eventaddress'];
		$this->eventcity->DbValue = $row['eventcity'];
		$this->eventdate->DbValue = $row['eventdate'];
		$this->eventtime->DbValue = $row['eventtime'];
		$this->eventcategory->DbValue = $row['eventcategory'];
		$this->eventdesc->DbValue = $row['eventdesc'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// eventsid
		// eventtitle
		// eventhost
		// eventaddress
		// eventcity
		// eventdate
		// eventtime
		// eventcategory
		// eventdesc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// eventsid
		$this->eventsid->ViewValue = $this->eventsid->CurrentValue;
		$this->eventsid->ViewCustomAttributes = "";

		// eventtitle
		$this->eventtitle->ViewValue = $this->eventtitle->CurrentValue;
		$this->eventtitle->ViewCustomAttributes = "";

		// eventhost
		$this->eventhost->ViewValue = $this->eventhost->CurrentValue;
		$this->eventhost->ViewCustomAttributes = "";

		// eventaddress
		$this->eventaddress->ViewValue = $this->eventaddress->CurrentValue;
		$this->eventaddress->ViewCustomAttributes = "";

		// eventcity
		$this->eventcity->ViewValue = $this->eventcity->CurrentValue;
		$this->eventcity->ViewCustomAttributes = "";

		// eventdate
		$this->eventdate->ViewValue = $this->eventdate->CurrentValue;
		$this->eventdate->ViewValue = ew_FormatDateTime($this->eventdate->ViewValue, 5);
		$this->eventdate->ViewCustomAttributes = "";

		// eventtime
		$this->eventtime->ViewValue = $this->eventtime->CurrentValue;
		$this->eventtime->ViewCustomAttributes = "";

		// eventcategory
		if (strval($this->eventcategory->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->eventcategory->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->eventcategory, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->eventcategory->ViewValue = $this->eventcategory->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->eventcategory->ViewValue = $this->eventcategory->CurrentValue;
			}
		} else {
			$this->eventcategory->ViewValue = NULL;
		}
		$this->eventcategory->ViewCustomAttributes = "";

		// eventdesc
		$this->eventdesc->ViewValue = $this->eventdesc->CurrentValue;
		$this->eventdesc->ViewCustomAttributes = "";

			// eventsid
			$this->eventsid->LinkCustomAttributes = "";
			$this->eventsid->HrefValue = "";
			$this->eventsid->TooltipValue = "";

			// eventtitle
			$this->eventtitle->LinkCustomAttributes = "";
			$this->eventtitle->HrefValue = "";
			$this->eventtitle->TooltipValue = "";

			// eventhost
			$this->eventhost->LinkCustomAttributes = "";
			$this->eventhost->HrefValue = "";
			$this->eventhost->TooltipValue = "";

			// eventaddress
			$this->eventaddress->LinkCustomAttributes = "";
			$this->eventaddress->HrefValue = "";
			$this->eventaddress->TooltipValue = "";

			// eventcity
			$this->eventcity->LinkCustomAttributes = "";
			$this->eventcity->HrefValue = "";
			$this->eventcity->TooltipValue = "";

			// eventdate
			$this->eventdate->LinkCustomAttributes = "";
			$this->eventdate->HrefValue = "";
			$this->eventdate->TooltipValue = "";

			// eventtime
			$this->eventtime->LinkCustomAttributes = "";
			$this->eventtime->HrefValue = "";
			$this->eventtime->TooltipValue = "";

			// eventcategory
			$this->eventcategory->LinkCustomAttributes = "";
			$this->eventcategory->HrefValue = "";
			$this->eventcategory->TooltipValue = "";

			// eventdesc
			$this->eventdesc->LinkCustomAttributes = "";
			$this->eventdesc->HrefValue = "";
			$this->eventdesc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// eventsid
			$this->eventsid->EditAttrs["class"] = "form-control";
			$this->eventsid->EditCustomAttributes = "";
			$this->eventsid->EditValue = $this->eventsid->CurrentValue;
			$this->eventsid->ViewCustomAttributes = "";

			// eventtitle
			$this->eventtitle->EditAttrs["class"] = "form-control";
			$this->eventtitle->EditCustomAttributes = "";
			$this->eventtitle->EditValue = ew_HtmlEncode($this->eventtitle->CurrentValue);
			$this->eventtitle->PlaceHolder = ew_RemoveHtml($this->eventtitle->FldCaption());

			// eventhost
			$this->eventhost->EditAttrs["class"] = "form-control";
			$this->eventhost->EditCustomAttributes = "";
			$this->eventhost->EditValue = ew_HtmlEncode($this->eventhost->CurrentValue);
			$this->eventhost->PlaceHolder = ew_RemoveHtml($this->eventhost->FldCaption());

			// eventaddress
			$this->eventaddress->EditAttrs["class"] = "form-control";
			$this->eventaddress->EditCustomAttributes = "";
			$this->eventaddress->EditValue = ew_HtmlEncode($this->eventaddress->CurrentValue);
			$this->eventaddress->PlaceHolder = ew_RemoveHtml($this->eventaddress->FldCaption());

			// eventcity
			$this->eventcity->EditAttrs["class"] = "form-control";
			$this->eventcity->EditCustomAttributes = "";
			$this->eventcity->EditValue = ew_HtmlEncode($this->eventcity->CurrentValue);
			$this->eventcity->PlaceHolder = ew_RemoveHtml($this->eventcity->FldCaption());

			// eventdate
			$this->eventdate->EditAttrs["class"] = "form-control";
			$this->eventdate->EditCustomAttributes = "";
			$this->eventdate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->eventdate->CurrentValue, 5));
			$this->eventdate->PlaceHolder = ew_RemoveHtml($this->eventdate->FldCaption());

			// eventtime
			$this->eventtime->EditAttrs["class"] = "form-control";
			$this->eventtime->EditCustomAttributes = "";
			$this->eventtime->EditValue = ew_HtmlEncode($this->eventtime->CurrentValue);
			$this->eventtime->PlaceHolder = ew_RemoveHtml($this->eventtime->FldCaption());

			// eventcategory
			$this->eventcategory->EditAttrs["class"] = "form-control";
			$this->eventcategory->EditCustomAttributes = "";
			if (trim(strval($this->eventcategory->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`name`" . ew_SearchString("=", $this->eventcategory->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `lst_category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->eventcategory, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->eventcategory->EditValue = $arwrk;

			// eventdesc
			$this->eventdesc->EditAttrs["class"] = "form-control";
			$this->eventdesc->EditCustomAttributes = "";
			$this->eventdesc->EditValue = ew_HtmlEncode($this->eventdesc->CurrentValue);
			$this->eventdesc->PlaceHolder = ew_RemoveHtml($this->eventdesc->FldCaption());

			// Edit refer script
			// eventsid

			$this->eventsid->HrefValue = "";

			// eventtitle
			$this->eventtitle->HrefValue = "";

			// eventhost
			$this->eventhost->HrefValue = "";

			// eventaddress
			$this->eventaddress->HrefValue = "";

			// eventcity
			$this->eventcity->HrefValue = "";

			// eventdate
			$this->eventdate->HrefValue = "";

			// eventtime
			$this->eventtime->HrefValue = "";

			// eventcategory
			$this->eventcategory->HrefValue = "";

			// eventdesc
			$this->eventdesc->HrefValue = "";
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
		if (!$this->eventtitle->FldIsDetailKey && !is_null($this->eventtitle->FormValue) && $this->eventtitle->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventtitle->FldCaption(), $this->eventtitle->ReqErrMsg));
		}
		if (!$this->eventhost->FldIsDetailKey && !is_null($this->eventhost->FormValue) && $this->eventhost->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventhost->FldCaption(), $this->eventhost->ReqErrMsg));
		}
		if (!$this->eventaddress->FldIsDetailKey && !is_null($this->eventaddress->FormValue) && $this->eventaddress->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventaddress->FldCaption(), $this->eventaddress->ReqErrMsg));
		}
		if (!$this->eventcity->FldIsDetailKey && !is_null($this->eventcity->FormValue) && $this->eventcity->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventcity->FldCaption(), $this->eventcity->ReqErrMsg));
		}
		if (!$this->eventdate->FldIsDetailKey && !is_null($this->eventdate->FormValue) && $this->eventdate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventdate->FldCaption(), $this->eventdate->ReqErrMsg));
		}
		if (!ew_CheckDate($this->eventdate->FormValue)) {
			ew_AddMessage($gsFormError, $this->eventdate->FldErrMsg());
		}
		if (!$this->eventtime->FldIsDetailKey && !is_null($this->eventtime->FormValue) && $this->eventtime->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventtime->FldCaption(), $this->eventtime->ReqErrMsg));
		}
		if (!ew_CheckTime($this->eventtime->FormValue)) {
			ew_AddMessage($gsFormError, $this->eventtime->FldErrMsg());
		}
		if (!$this->eventcategory->FldIsDetailKey && !is_null($this->eventcategory->FormValue) && $this->eventcategory->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventcategory->FldCaption(), $this->eventcategory->ReqErrMsg));
		}
		if (!$this->eventdesc->FldIsDetailKey && !is_null($this->eventdesc->FormValue) && $this->eventdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->eventdesc->FldCaption(), $this->eventdesc->ReqErrMsg));
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

			// eventtitle
			$this->eventtitle->SetDbValueDef($rsnew, $this->eventtitle->CurrentValue, "", $this->eventtitle->ReadOnly);

			// eventhost
			$this->eventhost->SetDbValueDef($rsnew, $this->eventhost->CurrentValue, "", $this->eventhost->ReadOnly);

			// eventaddress
			$this->eventaddress->SetDbValueDef($rsnew, $this->eventaddress->CurrentValue, "", $this->eventaddress->ReadOnly);

			// eventcity
			$this->eventcity->SetDbValueDef($rsnew, $this->eventcity->CurrentValue, "", $this->eventcity->ReadOnly);

			// eventdate
			$this->eventdate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->eventdate->CurrentValue, 5), ew_CurrentDate(), $this->eventdate->ReadOnly);

			// eventtime
			$this->eventtime->SetDbValueDef($rsnew, $this->eventtime->CurrentValue, ew_CurrentTime(), $this->eventtime->ReadOnly);

			// eventcategory
			$this->eventcategory->SetDbValueDef($rsnew, $this->eventcategory->CurrentValue, "", $this->eventcategory->ReadOnly);

			// eventdesc
			$this->eventdesc->SetDbValueDef($rsnew, $this->eventdesc->CurrentValue, "", $this->eventdesc->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "eventslist.php", "", $this->TableVar, TRUE);
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
if (!isset($events_edit)) $events_edit = new cevents_edit();

// Page init
$events_edit->Page_Init();

// Page main
$events_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$events_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = feventsedit = new ew_Form("feventsedit", "edit");

// Validate form
feventsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_eventtitle");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventtitle->FldCaption(), $events->eventtitle->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventhost");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventhost->FldCaption(), $events->eventhost->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventaddress");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventaddress->FldCaption(), $events->eventaddress->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventcity");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventcity->FldCaption(), $events->eventcity->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventdate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventdate->FldCaption(), $events->eventdate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventdate");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($events->eventdate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_eventtime");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventtime->FldCaption(), $events->eventtime->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventtime");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($events->eventtime->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_eventcategory");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventcategory->FldCaption(), $events->eventcategory->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eventdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $events->eventdesc->FldCaption(), $events->eventdesc->ReqErrMsg)) ?>");

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
feventsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
feventsedit.ValidateRequired = true;
<?php } else { ?>
feventsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
feventsedit.Lists["x_eventcategory"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $events_edit->ShowPageHeader(); ?>
<?php
$events_edit->ShowMessage();
?>
<form name="feventsedit" id="feventsedit" class="<?php echo $events_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($events_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $events_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="events">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($events->eventsid->Visible) { // eventsid ?>
	<div id="r_eventsid" class="form-group">
		<label id="elh_events_eventsid" class="col-sm-2 control-label ewLabel"><?php echo $events->eventsid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventsid->CellAttributes() ?>>
<span id="el_events_eventsid">
<span<?php echo $events->eventsid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $events->eventsid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="events" data-field="x_eventsid" name="x_eventsid" id="x_eventsid" value="<?php echo ew_HtmlEncode($events->eventsid->CurrentValue) ?>">
<?php echo $events->eventsid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventtitle->Visible) { // eventtitle ?>
	<div id="r_eventtitle" class="form-group">
		<label id="elh_events_eventtitle" for="x_eventtitle" class="col-sm-2 control-label ewLabel"><?php echo $events->eventtitle->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventtitle->CellAttributes() ?>>
<span id="el_events_eventtitle">
<input type="text" data-table="events" data-field="x_eventtitle" name="x_eventtitle" id="x_eventtitle" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($events->eventtitle->getPlaceHolder()) ?>" value="<?php echo $events->eventtitle->EditValue ?>"<?php echo $events->eventtitle->EditAttributes() ?>>
</span>
<?php echo $events->eventtitle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventhost->Visible) { // eventhost ?>
	<div id="r_eventhost" class="form-group">
		<label id="elh_events_eventhost" for="x_eventhost" class="col-sm-2 control-label ewLabel"><?php echo $events->eventhost->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventhost->CellAttributes() ?>>
<span id="el_events_eventhost">
<input type="text" data-table="events" data-field="x_eventhost" name="x_eventhost" id="x_eventhost" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($events->eventhost->getPlaceHolder()) ?>" value="<?php echo $events->eventhost->EditValue ?>"<?php echo $events->eventhost->EditAttributes() ?>>
</span>
<?php echo $events->eventhost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventaddress->Visible) { // eventaddress ?>
	<div id="r_eventaddress" class="form-group">
		<label id="elh_events_eventaddress" for="x_eventaddress" class="col-sm-2 control-label ewLabel"><?php echo $events->eventaddress->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventaddress->CellAttributes() ?>>
<span id="el_events_eventaddress">
<input type="text" data-table="events" data-field="x_eventaddress" name="x_eventaddress" id="x_eventaddress" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($events->eventaddress->getPlaceHolder()) ?>" value="<?php echo $events->eventaddress->EditValue ?>"<?php echo $events->eventaddress->EditAttributes() ?>>
</span>
<?php echo $events->eventaddress->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventcity->Visible) { // eventcity ?>
	<div id="r_eventcity" class="form-group">
		<label id="elh_events_eventcity" for="x_eventcity" class="col-sm-2 control-label ewLabel"><?php echo $events->eventcity->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventcity->CellAttributes() ?>>
<span id="el_events_eventcity">
<input type="text" data-table="events" data-field="x_eventcity" name="x_eventcity" id="x_eventcity" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($events->eventcity->getPlaceHolder()) ?>" value="<?php echo $events->eventcity->EditValue ?>"<?php echo $events->eventcity->EditAttributes() ?>>
</span>
<?php echo $events->eventcity->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventdate->Visible) { // eventdate ?>
	<div id="r_eventdate" class="form-group">
		<label id="elh_events_eventdate" for="x_eventdate" class="col-sm-2 control-label ewLabel"><?php echo $events->eventdate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventdate->CellAttributes() ?>>
<span id="el_events_eventdate">
<input type="text" data-table="events" data-field="x_eventdate" data-format="5" name="x_eventdate" id="x_eventdate" placeholder="<?php echo ew_HtmlEncode($events->eventdate->getPlaceHolder()) ?>" value="<?php echo $events->eventdate->EditValue ?>"<?php echo $events->eventdate->EditAttributes() ?>>
</span>
<?php echo $events->eventdate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventtime->Visible) { // eventtime ?>
	<div id="r_eventtime" class="form-group">
		<label id="elh_events_eventtime" for="x_eventtime" class="col-sm-2 control-label ewLabel"><?php echo $events->eventtime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventtime->CellAttributes() ?>>
<span id="el_events_eventtime">
<input type="text" data-table="events" data-field="x_eventtime" name="x_eventtime" id="x_eventtime" size="30" placeholder="<?php echo ew_HtmlEncode($events->eventtime->getPlaceHolder()) ?>" value="<?php echo $events->eventtime->EditValue ?>"<?php echo $events->eventtime->EditAttributes() ?>>
</span>
<?php echo $events->eventtime->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventcategory->Visible) { // eventcategory ?>
	<div id="r_eventcategory" class="form-group">
		<label id="elh_events_eventcategory" for="x_eventcategory" class="col-sm-2 control-label ewLabel"><?php echo $events->eventcategory->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventcategory->CellAttributes() ?>>
<span id="el_events_eventcategory">
<select data-table="events" data-field="x_eventcategory" data-value-separator="<?php echo ew_HtmlEncode(is_array($events->eventcategory->DisplayValueSeparator) ? json_encode($events->eventcategory->DisplayValueSeparator) : $events->eventcategory->DisplayValueSeparator) ?>" id="x_eventcategory" name="x_eventcategory"<?php echo $events->eventcategory->EditAttributes() ?>>
<?php
if (is_array($events->eventcategory->EditValue)) {
	$arwrk = $events->eventcategory->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($events->eventcategory->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $events->eventcategory->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
?>
</select>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $events->eventcategory->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_eventcategory',url:'lst_categoryaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_eventcategory"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $events->eventcategory->FldCaption() ?></span></button>
<?php
$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_category`";
$sWhereWrk = "";
$events->eventcategory->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$events->eventcategory->LookupFilters += array("f0" => "`name` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$events->Lookup_Selecting($events->eventcategory, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $events->eventcategory->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_eventcategory" id="s_x_eventcategory" value="<?php echo $events->eventcategory->LookupFilterQuery() ?>">
</span>
<?php echo $events->eventcategory->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($events->eventdesc->Visible) { // eventdesc ?>
	<div id="r_eventdesc" class="form-group">
		<label id="elh_events_eventdesc" for="x_eventdesc" class="col-sm-2 control-label ewLabel"><?php echo $events->eventdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $events->eventdesc->CellAttributes() ?>>
<span id="el_events_eventdesc">
<textarea data-table="events" data-field="x_eventdesc" name="x_eventdesc" id="x_eventdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($events->eventdesc->getPlaceHolder()) ?>"<?php echo $events->eventdesc->EditAttributes() ?>><?php echo $events->eventdesc->EditValue ?></textarea>
</span>
<?php echo $events->eventdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $events_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
feventsedit.Init();
</script>
<?php
$events_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$events_edit->Page_Terminate();
?>
