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

$event_delete = NULL; // Initialize page object first

class cevent_delete extends cevent {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'event';

	// Page object name
	var $PageObjName = 'event_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("eventlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in event class, eventinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['eventid'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->eventimage->OldUploadPath) . $row['eventimage']);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "eventlist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($event_delete)) $event_delete = new cevent_delete();

// Page init
$event_delete->Page_Init();

// Page main
$event_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$event_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var event_delete = new ew_Page("event_delete");
event_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = event_delete.PageID; // For backward compatibility

// Form object
var feventdelete = new ew_Form("feventdelete");

// Form_CustomValidate event
feventdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
feventdelete.ValidateRequired = true;
<?php } else { ?>
feventdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
feventdelete.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($event_delete->Recordset = $event_delete->LoadRecordset())
	$event_deleteTotalRecs = $event_delete->Recordset->RecordCount(); // Get record count
if ($event_deleteTotalRecs <= 0) { // No record found, exit
	if ($event_delete->Recordset)
		$event_delete->Recordset->Close();
	$event_delete->Page_Terminate("eventlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $event_delete->ShowPageHeader(); ?>
<?php
$event_delete->ShowMessage();
?>
<form name="feventdelete" id="feventdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($event_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $event_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="event">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($event_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $event->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($event->eventid->Visible) { // eventid ?>
		<th><span id="elh_event_eventid" class="event_eventid"><?php echo $event->eventid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->_eventname->Visible) { // eventname ?>
		<th><span id="elh_event__eventname" class="event__eventname"><?php echo $event->_eventname->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->eventdesc->Visible) { // eventdesc ?>
		<th><span id="elh_event_eventdesc" class="event_eventdesc"><?php echo $event->eventdesc->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->eventimage->Visible) { // eventimage ?>
		<th><span id="elh_event_eventimage" class="event_eventimage"><?php echo $event->eventimage->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->eventlink->Visible) { // eventlink ?>
		<th><span id="elh_event_eventlink" class="event_eventlink"><?php echo $event->eventlink->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->eventdate->Visible) { // eventdate ?>
		<th><span id="elh_event_eventdate" class="event_eventdate"><?php echo $event->eventdate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->createddate->Visible) { // createddate ?>
		<th><span id="elh_event_createddate" class="event_createddate"><?php echo $event->createddate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($event->categoryid->Visible) { // categoryid ?>
		<th><span id="elh_event_categoryid" class="event_categoryid"><?php echo $event->categoryid->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$event_delete->RecCnt = 0;
$i = 0;
while (!$event_delete->Recordset->EOF) {
	$event_delete->RecCnt++;
	$event_delete->RowCnt++;

	// Set row properties
	$event->ResetAttrs();
	$event->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$event_delete->LoadRowValues($event_delete->Recordset);

	// Render row
	$event_delete->RenderRow();
?>
	<tr<?php echo $event->RowAttributes() ?>>
<?php if ($event->eventid->Visible) { // eventid ?>
		<td<?php echo $event->eventid->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_eventid" class="event_eventid">
<span<?php echo $event->eventid->ViewAttributes() ?>>
<?php echo $event->eventid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($event->_eventname->Visible) { // eventname ?>
		<td<?php echo $event->_eventname->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event__eventname" class="event__eventname">
<span<?php echo $event->_eventname->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($event->_eventname->ListViewValue())) && $event->_eventname->LinkAttributes() <> "") { ?>
<a<?php echo $event->_eventname->LinkAttributes() ?>><?php echo $event->_eventname->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $event->_eventname->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($event->eventdesc->Visible) { // eventdesc ?>
		<td<?php echo $event->eventdesc->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_eventdesc" class="event_eventdesc">
<span<?php echo $event->eventdesc->ViewAttributes() ?>>
<?php echo $event->eventdesc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($event->eventimage->Visible) { // eventimage ?>
		<td<?php echo $event->eventimage->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_eventimage" class="event_eventimage">
<span>
<?php echo ew_GetFileViewTag($event->eventimage, $event->eventimage->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($event->eventlink->Visible) { // eventlink ?>
		<td<?php echo $event->eventlink->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_eventlink" class="event_eventlink">
<span<?php echo $event->eventlink->ViewAttributes() ?>>
<?php echo $event->eventlink->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($event->eventdate->Visible) { // eventdate ?>
		<td<?php echo $event->eventdate->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_eventdate" class="event_eventdate">
<span<?php echo $event->eventdate->ViewAttributes() ?>>
<?php echo $event->eventdate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($event->createddate->Visible) { // createddate ?>
		<td<?php echo $event->createddate->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_createddate" class="event_createddate">
<span<?php echo $event->createddate->ViewAttributes() ?>>
<?php echo $event->createddate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($event->categoryid->Visible) { // categoryid ?>
		<td<?php echo $event->categoryid->CellAttributes() ?>>
<span id="el<?php echo $event_delete->RowCnt ?>_event_categoryid" class="event_categoryid">
<span<?php echo $event->categoryid->ViewAttributes() ?>>
<?php echo $event->categoryid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$event_delete->Recordset->MoveNext();
}
$event_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
feventdelete.Init();
</script>
<?php
$event_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$event_delete->Page_Terminate();
?>
