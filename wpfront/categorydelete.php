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

$category_delete = NULL; // Initialize page object first

class ccategory_delete extends ccategory {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'category';

	// Page object name
	var $PageObjName = 'category_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("categorylist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in category class, categoryinfo.php

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
				$sThisKey .= $row['categoryid'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->categoryimage->OldUploadPath) . $row['categoryimage']);
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
		$Breadcrumb->Add("list", $this->TableVar, "categorylist.php", "", $this->TableVar, TRUE);
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
if (!isset($category_delete)) $category_delete = new ccategory_delete();

// Page init
$category_delete->Page_Init();

// Page main
$category_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$category_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var category_delete = new ew_Page("category_delete");
category_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = category_delete.PageID; // For backward compatibility

// Form object
var fcategorydelete = new ew_Form("fcategorydelete");

// Form_CustomValidate event
fcategorydelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcategorydelete.ValidateRequired = true;
<?php } else { ?>
fcategorydelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($category_delete->Recordset = $category_delete->LoadRecordset())
	$category_deleteTotalRecs = $category_delete->Recordset->RecordCount(); // Get record count
if ($category_deleteTotalRecs <= 0) { // No record found, exit
	if ($category_delete->Recordset)
		$category_delete->Recordset->Close();
	$category_delete->Page_Terminate("categorylist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $category_delete->ShowPageHeader(); ?>
<?php
$category_delete->ShowMessage();
?>
<form name="fcategorydelete" id="fcategorydelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($category_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $category_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="category">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($category_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $category->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($category->categoryid->Visible) { // categoryid ?>
		<th><span id="elh_category_categoryid" class="category_categoryid"><?php echo $category->categoryid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($category->categoryname->Visible) { // categoryname ?>
		<th><span id="elh_category_categoryname" class="category_categoryname"><?php echo $category->categoryname->FldCaption() ?></span></th>
<?php } ?>
<?php if ($category->categorydesc->Visible) { // categorydesc ?>
		<th><span id="elh_category_categorydesc" class="category_categorydesc"><?php echo $category->categorydesc->FldCaption() ?></span></th>
<?php } ?>
<?php if ($category->categoryimage->Visible) { // categoryimage ?>
		<th><span id="elh_category_categoryimage" class="category_categoryimage"><?php echo $category->categoryimage->FldCaption() ?></span></th>
<?php } ?>
<?php if ($category->createddate->Visible) { // createddate ?>
		<th><span id="elh_category_createddate" class="category_createddate"><?php echo $category->createddate->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$category_delete->RecCnt = 0;
$i = 0;
while (!$category_delete->Recordset->EOF) {
	$category_delete->RecCnt++;
	$category_delete->RowCnt++;

	// Set row properties
	$category->ResetAttrs();
	$category->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$category_delete->LoadRowValues($category_delete->Recordset);

	// Render row
	$category_delete->RenderRow();
?>
	<tr<?php echo $category->RowAttributes() ?>>
<?php if ($category->categoryid->Visible) { // categoryid ?>
		<td<?php echo $category->categoryid->CellAttributes() ?>>
<span id="el<?php echo $category_delete->RowCnt ?>_category_categoryid" class="category_categoryid">
<span<?php echo $category->categoryid->ViewAttributes() ?>>
<?php echo $category->categoryid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($category->categoryname->Visible) { // categoryname ?>
		<td<?php echo $category->categoryname->CellAttributes() ?>>
<span id="el<?php echo $category_delete->RowCnt ?>_category_categoryname" class="category_categoryname">
<span<?php echo $category->categoryname->ViewAttributes() ?>>
<?php echo $category->categoryname->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($category->categorydesc->Visible) { // categorydesc ?>
		<td<?php echo $category->categorydesc->CellAttributes() ?>>
<span id="el<?php echo $category_delete->RowCnt ?>_category_categorydesc" class="category_categorydesc">
<span<?php echo $category->categorydesc->ViewAttributes() ?>>
<?php echo $category->categorydesc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($category->categoryimage->Visible) { // categoryimage ?>
		<td<?php echo $category->categoryimage->CellAttributes() ?>>
<span id="el<?php echo $category_delete->RowCnt ?>_category_categoryimage" class="category_categoryimage">
<span>
<?php echo ew_GetFileViewTag($category->categoryimage, $category->categoryimage->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($category->createddate->Visible) { // createddate ?>
		<td<?php echo $category->createddate->CellAttributes() ?>>
<span id="el<?php echo $category_delete->RowCnt ?>_category_createddate" class="category_createddate">
<span<?php echo $category->createddate->ViewAttributes() ?>>
<?php echo $category->createddate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$category_delete->Recordset->MoveNext();
}
$category_delete->Recordset->Close();
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
fcategorydelete.Init();
</script>
<?php
$category_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$category_delete->Page_Terminate();
?>
