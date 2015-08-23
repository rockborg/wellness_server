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

$tips_delete = NULL; // Initialize page object first

class ctips_delete extends ctips {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'tips';

	// Page object name
	var $PageObjName = 'tips_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->tipsid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("tipslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tips class, tipsinfo.php

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

		// tiplink
		$this->tiplink->ViewValue = $this->tiplink->CurrentValue;
		$this->tiplink->ViewCustomAttributes = "";

		// dateadded
		$this->dateadded->ViewValue = $this->dateadded->CurrentValue;
		$this->dateadded->ViewValue = ew_FormatDateTime($this->dateadded->ViewValue, 5);
		$this->dateadded->ViewCustomAttributes = "";

			// tipsid
			$this->tipsid->LinkCustomAttributes = "";
			$this->tipsid->HrefValue = "";
			$this->tipsid->TooltipValue = "";

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

			// tiplink
			$this->tiplink->LinkCustomAttributes = "";
			$this->tiplink->HrefValue = "";
			$this->tiplink->TooltipValue = "";

			// dateadded
			$this->dateadded->LinkCustomAttributes = "";
			$this->dateadded->HrefValue = "";
			$this->dateadded->TooltipValue = "";
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
				$sThisKey .= $row['tipsid'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->tipimage->OldUploadPath) . $row['tipimage']);
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
		$Breadcrumb->Add("list", $this->TableVar, "tipslist.php", "", $this->TableVar, TRUE);
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
if (!isset($tips_delete)) $tips_delete = new ctips_delete();

// Page init
$tips_delete->Page_Init();

// Page main
$tips_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tips_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftipsdelete = new ew_Form("ftipsdelete", "delete");

// Form_CustomValidate event
ftipsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftipsdelete.ValidateRequired = true;
<?php } else { ?>
ftipsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tips_delete->Recordset = $tips_delete->LoadRecordset())
	$tips_deleteTotalRecs = $tips_delete->Recordset->RecordCount(); // Get record count
if ($tips_deleteTotalRecs <= 0) { // No record found, exit
	if ($tips_delete->Recordset)
		$tips_delete->Recordset->Close();
	$tips_delete->Page_Terminate("tipslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $tips_delete->ShowPageHeader(); ?>
<?php
$tips_delete->ShowMessage();
?>
<form name="ftipsdelete" id="ftipsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tips_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tips_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tips">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tips_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tips->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tips->tipsid->Visible) { // tipsid ?>
		<th><span id="elh_tips_tipsid" class="tips_tipsid"><?php echo $tips->tipsid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tips->tiptitle->Visible) { // tiptitle ?>
		<th><span id="elh_tips_tiptitle" class="tips_tiptitle"><?php echo $tips->tiptitle->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tips->tipimage->Visible) { // tipimage ?>
		<th><span id="elh_tips_tipimage" class="tips_tipimage"><?php echo $tips->tipimage->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tips->tiplink->Visible) { // tiplink ?>
		<th><span id="elh_tips_tiplink" class="tips_tiplink"><?php echo $tips->tiplink->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tips->dateadded->Visible) { // dateadded ?>
		<th><span id="elh_tips_dateadded" class="tips_dateadded"><?php echo $tips->dateadded->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tips_delete->RecCnt = 0;
$i = 0;
while (!$tips_delete->Recordset->EOF) {
	$tips_delete->RecCnt++;
	$tips_delete->RowCnt++;

	// Set row properties
	$tips->ResetAttrs();
	$tips->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tips_delete->LoadRowValues($tips_delete->Recordset);

	// Render row
	$tips_delete->RenderRow();
?>
	<tr<?php echo $tips->RowAttributes() ?>>
<?php if ($tips->tipsid->Visible) { // tipsid ?>
		<td<?php echo $tips->tipsid->CellAttributes() ?>>
<span id="el<?php echo $tips_delete->RowCnt ?>_tips_tipsid" class="tips_tipsid">
<span<?php echo $tips->tipsid->ViewAttributes() ?>>
<?php echo $tips->tipsid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tips->tiptitle->Visible) { // tiptitle ?>
		<td<?php echo $tips->tiptitle->CellAttributes() ?>>
<span id="el<?php echo $tips_delete->RowCnt ?>_tips_tiptitle" class="tips_tiptitle">
<span<?php echo $tips->tiptitle->ViewAttributes() ?>>
<?php echo $tips->tiptitle->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tips->tipimage->Visible) { // tipimage ?>
		<td<?php echo $tips->tipimage->CellAttributes() ?>>
<span id="el<?php echo $tips_delete->RowCnt ?>_tips_tipimage" class="tips_tipimage">
<span>
<?php echo ew_GetFileViewTag($tips->tipimage, $tips->tipimage->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($tips->tiplink->Visible) { // tiplink ?>
		<td<?php echo $tips->tiplink->CellAttributes() ?>>
<span id="el<?php echo $tips_delete->RowCnt ?>_tips_tiplink" class="tips_tiplink">
<span<?php echo $tips->tiplink->ViewAttributes() ?>>
<?php echo $tips->tiplink->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tips->dateadded->Visible) { // dateadded ?>
		<td<?php echo $tips->dateadded->CellAttributes() ?>>
<span id="el<?php echo $tips_delete->RowCnt ?>_tips_dateadded" class="tips_dateadded">
<span<?php echo $tips->dateadded->ViewAttributes() ?>>
<?php echo $tips->dateadded->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tips_delete->Recordset->MoveNext();
}
$tips_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tips_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftipsdelete.Init();
</script>
<?php
$tips_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tips_delete->Page_Terminate();
?>
