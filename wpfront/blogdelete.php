<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "bloginfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$blog_delete = NULL; // Initialize page object first

class cblog_delete extends cblog {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'blog';

	// Page object name
	var $PageObjName = 'blog_delete';

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

		// Table object (blog)
		if (!isset($GLOBALS["blog"]) || get_class($GLOBALS["blog"]) == "cblog") {
			$GLOBALS["blog"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["blog"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'blog', TRUE);

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
		$this->blogid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $blog;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($blog);
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
			$this->Page_Terminate("bloglist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in blog class, bloginfo.php

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
		$this->blogid->setDbValue($rs->fields('blogid'));
		$this->blogname->setDbValue($rs->fields('blogname'));
		$this->blogdesc->setDbValue($rs->fields('blogdesc'));
		$this->blogimage->Upload->DbValue = $rs->fields('blogimage');
		$this->blogimage->CurrentValue = $this->blogimage->Upload->DbValue;
		$this->bloglink->setDbValue($rs->fields('bloglink'));
		$this->createddate->setDbValue($rs->fields('createddate'));
		$this->categoryid->setDbValue($rs->fields('categoryid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->blogid->DbValue = $row['blogid'];
		$this->blogname->DbValue = $row['blogname'];
		$this->blogdesc->DbValue = $row['blogdesc'];
		$this->blogimage->Upload->DbValue = $row['blogimage'];
		$this->bloglink->DbValue = $row['bloglink'];
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
		// blogid
		// blogname
		// blogdesc
		// blogimage
		// bloglink
		// createddate
		// categoryid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// blogid
			$this->blogid->ViewValue = $this->blogid->CurrentValue;
			$this->blogid->ViewCustomAttributes = "";

			// blogname
			$this->blogname->ViewValue = $this->blogname->CurrentValue;
			$this->blogname->ViewCustomAttributes = "";

			// blogdesc
			$this->blogdesc->ViewValue = $this->blogdesc->CurrentValue;
			$this->blogdesc->ViewCustomAttributes = "";

			// blogimage
			if (!ew_Empty($this->blogimage->Upload->DbValue)) {
				$this->blogimage->ImageWidth = 100;
				$this->blogimage->ImageHeight = 0;
				$this->blogimage->ImageAlt = $this->blogimage->FldAlt();
				$this->blogimage->ViewValue = ew_UploadPathEx(FALSE, $this->blogimage->UploadPath) . $this->blogimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->blogimage->ViewValue = ew_UploadPathEx(TRUE, $this->blogimage->UploadPath) . $this->blogimage->Upload->DbValue;
				}
			} else {
				$this->blogimage->ViewValue = "";
			}
			$this->blogimage->ViewCustomAttributes = "";

			// bloglink
			$this->bloglink->ViewValue = $this->bloglink->CurrentValue;
			$this->bloglink->ViewCustomAttributes = "";

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

			// blogid
			$this->blogid->LinkCustomAttributes = "";
			$this->blogid->HrefValue = "";
			$this->blogid->TooltipValue = "";

			// blogname
			$this->blogname->LinkCustomAttributes = "";
			if (!ew_Empty($this->bloglink->CurrentValue)) {
				$this->blogname->HrefValue = $this->bloglink->CurrentValue; // Add prefix/suffix
				$this->blogname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->blogname->HrefValue = ew_ConvertFullUrl($this->blogname->HrefValue);
			} else {
				$this->blogname->HrefValue = "";
			}
			$this->blogname->TooltipValue = "";

			// blogdesc
			$this->blogdesc->LinkCustomAttributes = "";
			$this->blogdesc->HrefValue = "";
			$this->blogdesc->TooltipValue = "";

			// blogimage
			$this->blogimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->blogimage->Upload->DbValue)) {
				$this->blogimage->HrefValue = ew_UploadPathEx(FALSE, $this->blogimage->UploadPath) . $this->blogimage->Upload->DbValue; // Add prefix/suffix
				$this->blogimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->blogimage->HrefValue = ew_ConvertFullUrl($this->blogimage->HrefValue);
			} else {
				$this->blogimage->HrefValue = "";
			}
			$this->blogimage->HrefValue2 = $this->blogimage->UploadPath . $this->blogimage->Upload->DbValue;
			$this->blogimage->TooltipValue = "";
			if ($this->blogimage->UseColorbox) {
				$this->blogimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->blogimage->LinkAttrs["data-rel"] = "blog_x_blogimage";
				$this->blogimage->LinkAttrs["class"] = "ewLightbox";
			}

			// bloglink
			$this->bloglink->LinkCustomAttributes = "";
			$this->bloglink->HrefValue = "";
			$this->bloglink->TooltipValue = "";

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
				$sThisKey .= $row['blogid'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->blogimage->OldUploadPath) . $row['blogimage']);
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
		$Breadcrumb->Add("list", $this->TableVar, "bloglist.php", "", $this->TableVar, TRUE);
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
if (!isset($blog_delete)) $blog_delete = new cblog_delete();

// Page init
$blog_delete->Page_Init();

// Page main
$blog_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$blog_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var blog_delete = new ew_Page("blog_delete");
blog_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = blog_delete.PageID; // For backward compatibility

// Form object
var fblogdelete = new ew_Form("fblogdelete");

// Form_CustomValidate event
fblogdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fblogdelete.ValidateRequired = true;
<?php } else { ?>
fblogdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fblogdelete.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($blog_delete->Recordset = $blog_delete->LoadRecordset())
	$blog_deleteTotalRecs = $blog_delete->Recordset->RecordCount(); // Get record count
if ($blog_deleteTotalRecs <= 0) { // No record found, exit
	if ($blog_delete->Recordset)
		$blog_delete->Recordset->Close();
	$blog_delete->Page_Terminate("bloglist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $blog_delete->ShowPageHeader(); ?>
<?php
$blog_delete->ShowMessage();
?>
<form name="fblogdelete" id="fblogdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($blog_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $blog_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($blog_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $blog->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($blog->blogid->Visible) { // blogid ?>
		<th><span id="elh_blog_blogid" class="blog_blogid"><?php echo $blog->blogid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($blog->blogname->Visible) { // blogname ?>
		<th><span id="elh_blog_blogname" class="blog_blogname"><?php echo $blog->blogname->FldCaption() ?></span></th>
<?php } ?>
<?php if ($blog->blogdesc->Visible) { // blogdesc ?>
		<th><span id="elh_blog_blogdesc" class="blog_blogdesc"><?php echo $blog->blogdesc->FldCaption() ?></span></th>
<?php } ?>
<?php if ($blog->blogimage->Visible) { // blogimage ?>
		<th><span id="elh_blog_blogimage" class="blog_blogimage"><?php echo $blog->blogimage->FldCaption() ?></span></th>
<?php } ?>
<?php if ($blog->bloglink->Visible) { // bloglink ?>
		<th><span id="elh_blog_bloglink" class="blog_bloglink"><?php echo $blog->bloglink->FldCaption() ?></span></th>
<?php } ?>
<?php if ($blog->createddate->Visible) { // createddate ?>
		<th><span id="elh_blog_createddate" class="blog_createddate"><?php echo $blog->createddate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($blog->categoryid->Visible) { // categoryid ?>
		<th><span id="elh_blog_categoryid" class="blog_categoryid"><?php echo $blog->categoryid->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$blog_delete->RecCnt = 0;
$i = 0;
while (!$blog_delete->Recordset->EOF) {
	$blog_delete->RecCnt++;
	$blog_delete->RowCnt++;

	// Set row properties
	$blog->ResetAttrs();
	$blog->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$blog_delete->LoadRowValues($blog_delete->Recordset);

	// Render row
	$blog_delete->RenderRow();
?>
	<tr<?php echo $blog->RowAttributes() ?>>
<?php if ($blog->blogid->Visible) { // blogid ?>
		<td<?php echo $blog->blogid->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_blogid" class="blog_blogid">
<span<?php echo $blog->blogid->ViewAttributes() ?>>
<?php echo $blog->blogid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($blog->blogname->Visible) { // blogname ?>
		<td<?php echo $blog->blogname->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_blogname" class="blog_blogname">
<span<?php echo $blog->blogname->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($blog->blogname->ListViewValue())) && $blog->blogname->LinkAttributes() <> "") { ?>
<a<?php echo $blog->blogname->LinkAttributes() ?>><?php echo $blog->blogname->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $blog->blogname->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($blog->blogdesc->Visible) { // blogdesc ?>
		<td<?php echo $blog->blogdesc->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_blogdesc" class="blog_blogdesc">
<span<?php echo $blog->blogdesc->ViewAttributes() ?>>
<?php echo $blog->blogdesc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($blog->blogimage->Visible) { // blogimage ?>
		<td<?php echo $blog->blogimage->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_blogimage" class="blog_blogimage">
<span>
<?php echo ew_GetFileViewTag($blog->blogimage, $blog->blogimage->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($blog->bloglink->Visible) { // bloglink ?>
		<td<?php echo $blog->bloglink->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_bloglink" class="blog_bloglink">
<span<?php echo $blog->bloglink->ViewAttributes() ?>>
<?php echo $blog->bloglink->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($blog->createddate->Visible) { // createddate ?>
		<td<?php echo $blog->createddate->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_createddate" class="blog_createddate">
<span<?php echo $blog->createddate->ViewAttributes() ?>>
<?php echo $blog->createddate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($blog->categoryid->Visible) { // categoryid ?>
		<td<?php echo $blog->categoryid->CellAttributes() ?>>
<span id="el<?php echo $blog_delete->RowCnt ?>_blog_categoryid" class="blog_categoryid">
<span<?php echo $blog->categoryid->ViewAttributes() ?>>
<?php echo $blog->categoryid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$blog_delete->Recordset->MoveNext();
}
$blog_delete->Recordset->Close();
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
fblogdelete.Init();
</script>
<?php
$blog_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$blog_delete->Page_Terminate();
?>
