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

$events_delete = NULL; // Initialize page object first

class cevents_delete extends cevents {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'events';

	// Page object name
	var $PageObjName = 'events_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("eventslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in events class, eventsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
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
				$sThisKey .= $row['eventsid'];
				$this->LoadDbValues($row);
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
		$Breadcrumb->Add("list", $this->TableVar, "eventslist.php", "", $this->TableVar, TRUE);
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
if (!isset($events_delete)) $events_delete = new cevents_delete();

// Page init
$events_delete->Page_Init();

// Page main
$events_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$events_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = feventsdelete = new ew_Form("feventsdelete", "delete");

// Form_CustomValidate event
feventsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
feventsdelete.ValidateRequired = true;
<?php } else { ?>
feventsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
feventsdelete.Lists["x_eventcategory"] = {"LinkField":"x_name","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($events_delete->Recordset = $events_delete->LoadRecordset())
	$events_deleteTotalRecs = $events_delete->Recordset->RecordCount(); // Get record count
if ($events_deleteTotalRecs <= 0) { // No record found, exit
	if ($events_delete->Recordset)
		$events_delete->Recordset->Close();
	$events_delete->Page_Terminate("eventslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $events_delete->ShowPageHeader(); ?>
<?php
$events_delete->ShowMessage();
?>
<form name="feventsdelete" id="feventsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($events_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $events_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="events">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($events_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $events->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($events->eventsid->Visible) { // eventsid ?>
		<th><span id="elh_events_eventsid" class="events_eventsid"><?php echo $events->eventsid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventtitle->Visible) { // eventtitle ?>
		<th><span id="elh_events_eventtitle" class="events_eventtitle"><?php echo $events->eventtitle->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventhost->Visible) { // eventhost ?>
		<th><span id="elh_events_eventhost" class="events_eventhost"><?php echo $events->eventhost->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventaddress->Visible) { // eventaddress ?>
		<th><span id="elh_events_eventaddress" class="events_eventaddress"><?php echo $events->eventaddress->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventcity->Visible) { // eventcity ?>
		<th><span id="elh_events_eventcity" class="events_eventcity"><?php echo $events->eventcity->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventdate->Visible) { // eventdate ?>
		<th><span id="elh_events_eventdate" class="events_eventdate"><?php echo $events->eventdate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventtime->Visible) { // eventtime ?>
		<th><span id="elh_events_eventtime" class="events_eventtime"><?php echo $events->eventtime->FldCaption() ?></span></th>
<?php } ?>
<?php if ($events->eventcategory->Visible) { // eventcategory ?>
		<th><span id="elh_events_eventcategory" class="events_eventcategory"><?php echo $events->eventcategory->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$events_delete->RecCnt = 0;
$i = 0;
while (!$events_delete->Recordset->EOF) {
	$events_delete->RecCnt++;
	$events_delete->RowCnt++;

	// Set row properties
	$events->ResetAttrs();
	$events->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$events_delete->LoadRowValues($events_delete->Recordset);

	// Render row
	$events_delete->RenderRow();
?>
	<tr<?php echo $events->RowAttributes() ?>>
<?php if ($events->eventsid->Visible) { // eventsid ?>
		<td<?php echo $events->eventsid->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventsid" class="events_eventsid">
<span<?php echo $events->eventsid->ViewAttributes() ?>>
<?php echo $events->eventsid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventtitle->Visible) { // eventtitle ?>
		<td<?php echo $events->eventtitle->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventtitle" class="events_eventtitle">
<span<?php echo $events->eventtitle->ViewAttributes() ?>>
<?php echo $events->eventtitle->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventhost->Visible) { // eventhost ?>
		<td<?php echo $events->eventhost->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventhost" class="events_eventhost">
<span<?php echo $events->eventhost->ViewAttributes() ?>>
<?php echo $events->eventhost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventaddress->Visible) { // eventaddress ?>
		<td<?php echo $events->eventaddress->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventaddress" class="events_eventaddress">
<span<?php echo $events->eventaddress->ViewAttributes() ?>>
<?php echo $events->eventaddress->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventcity->Visible) { // eventcity ?>
		<td<?php echo $events->eventcity->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventcity" class="events_eventcity">
<span<?php echo $events->eventcity->ViewAttributes() ?>>
<?php echo $events->eventcity->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventdate->Visible) { // eventdate ?>
		<td<?php echo $events->eventdate->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventdate" class="events_eventdate">
<span<?php echo $events->eventdate->ViewAttributes() ?>>
<?php echo $events->eventdate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventtime->Visible) { // eventtime ?>
		<td<?php echo $events->eventtime->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventtime" class="events_eventtime">
<span<?php echo $events->eventtime->ViewAttributes() ?>>
<?php echo $events->eventtime->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($events->eventcategory->Visible) { // eventcategory ?>
		<td<?php echo $events->eventcategory->CellAttributes() ?>>
<span id="el<?php echo $events_delete->RowCnt ?>_events_eventcategory" class="events_eventcategory">
<span<?php echo $events->eventcategory->ViewAttributes() ?>>
<?php echo $events->eventcategory->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$events_delete->Recordset->MoveNext();
}
$events_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $events_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
feventsdelete.Init();
</script>
<?php
$events_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$events_delete->Page_Terminate();
?>
