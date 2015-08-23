<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "appinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$app_delete = NULL; // Initialize page object first

class capp_delete extends capp {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'app';

	// Page object name
	var $PageObjName = 'app_delete';

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

		// Table object (app)
		if (!isset($GLOBALS["app"]) || get_class($GLOBALS["app"]) == "capp") {
			$GLOBALS["app"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["app"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'app', TRUE);

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
		$this->appid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $app;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($app);
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
			$this->Page_Terminate("applist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in app class, appinfo.php

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
		$this->appid->setDbValue($rs->fields('appid'));
		$this->appname->setDbValue($rs->fields('appname'));
		$this->appdesc->setDbValue($rs->fields('appdesc'));
		$this->appimage->Upload->DbValue = $rs->fields('appimage');
		$this->appimage->CurrentValue = $this->appimage->Upload->DbValue;
		$this->applink->setDbValue($rs->fields('applink'));
		$this->appplatform->setDbValue($rs->fields('appplatform'));
		$this->createddate->setDbValue($rs->fields('createddate'));
		$this->categoryid->setDbValue($rs->fields('categoryid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->appid->DbValue = $row['appid'];
		$this->appname->DbValue = $row['appname'];
		$this->appdesc->DbValue = $row['appdesc'];
		$this->appimage->Upload->DbValue = $row['appimage'];
		$this->applink->DbValue = $row['applink'];
		$this->appplatform->DbValue = $row['appplatform'];
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
		// appid
		// appname
		// appdesc
		// appimage
		// applink
		// appplatform
		// createddate
		// categoryid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// appid
			$this->appid->ViewValue = $this->appid->CurrentValue;
			$this->appid->ViewCustomAttributes = "";

			// appname
			$this->appname->ViewValue = $this->appname->CurrentValue;
			$this->appname->ViewCustomAttributes = "";

			// appdesc
			$this->appdesc->ViewValue = $this->appdesc->CurrentValue;
			$this->appdesc->ViewCustomAttributes = "";

			// appimage
			if (!ew_Empty($this->appimage->Upload->DbValue)) {
				$this->appimage->ImageWidth = 100;
				$this->appimage->ImageHeight = 0;
				$this->appimage->ImageAlt = $this->appimage->FldAlt();
				$this->appimage->ViewValue = ew_UploadPathEx(FALSE, $this->appimage->UploadPath) . $this->appimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->appimage->ViewValue = ew_UploadPathEx(TRUE, $this->appimage->UploadPath) . $this->appimage->Upload->DbValue;
				}
			} else {
				$this->appimage->ViewValue = "";
			}
			$this->appimage->ViewCustomAttributes = "";

			// applink
			$this->applink->ViewValue = $this->applink->CurrentValue;
			$this->applink->ViewCustomAttributes = "";

			// appplatform
			if (strval($this->appplatform->CurrentValue) <> "") {
				$sFilterWrk = "`plaformname`" . ew_SearchString("=", $this->appplatform->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `plaformname`, `plaformname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `list_platform`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->appplatform, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->appplatform->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->appplatform->ViewValue = $this->appplatform->CurrentValue;
				}
			} else {
				$this->appplatform->ViewValue = NULL;
			}
			$this->appplatform->ViewCustomAttributes = "";

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

			// appid
			$this->appid->LinkCustomAttributes = "";
			$this->appid->HrefValue = "";
			$this->appid->TooltipValue = "";

			// appname
			$this->appname->LinkCustomAttributes = "";
			if (!ew_Empty($this->applink->CurrentValue)) {
				$this->appname->HrefValue = $this->applink->CurrentValue; // Add prefix/suffix
				$this->appname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->appname->HrefValue = ew_ConvertFullUrl($this->appname->HrefValue);
			} else {
				$this->appname->HrefValue = "";
			}
			$this->appname->TooltipValue = "";

			// appdesc
			$this->appdesc->LinkCustomAttributes = "";
			$this->appdesc->HrefValue = "";
			$this->appdesc->TooltipValue = "";

			// appimage
			$this->appimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->appimage->Upload->DbValue)) {
				$this->appimage->HrefValue = ew_UploadPathEx(FALSE, $this->appimage->UploadPath) . $this->appimage->Upload->DbValue; // Add prefix/suffix
				$this->appimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->appimage->HrefValue = ew_ConvertFullUrl($this->appimage->HrefValue);
			} else {
				$this->appimage->HrefValue = "";
			}
			$this->appimage->HrefValue2 = $this->appimage->UploadPath . $this->appimage->Upload->DbValue;
			$this->appimage->TooltipValue = "";
			if ($this->appimage->UseColorbox) {
				$this->appimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->appimage->LinkAttrs["data-rel"] = "app_x_appimage";
				$this->appimage->LinkAttrs["class"] = "ewLightbox";
			}

			// applink
			$this->applink->LinkCustomAttributes = "";
			$this->applink->HrefValue = "";
			$this->applink->TooltipValue = "";

			// appplatform
			$this->appplatform->LinkCustomAttributes = "";
			$this->appplatform->HrefValue = "";
			$this->appplatform->TooltipValue = "";

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
				$sThisKey .= $row['appid'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->appimage->OldUploadPath) . $row['appimage']);
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
		$Breadcrumb->Add("list", $this->TableVar, "applist.php", "", $this->TableVar, TRUE);
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
if (!isset($app_delete)) $app_delete = new capp_delete();

// Page init
$app_delete->Page_Init();

// Page main
$app_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$app_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var app_delete = new ew_Page("app_delete");
app_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = app_delete.PageID; // For backward compatibility

// Form object
var fappdelete = new ew_Form("fappdelete");

// Form_CustomValidate event
fappdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fappdelete.ValidateRequired = true;
<?php } else { ?>
fappdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fappdelete.Lists["x_appplatform"] = {"LinkField":"x_plaformname","Ajax":null,"AutoFill":false,"DisplayFields":["x_plaformname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fappdelete.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($app_delete->Recordset = $app_delete->LoadRecordset())
	$app_deleteTotalRecs = $app_delete->Recordset->RecordCount(); // Get record count
if ($app_deleteTotalRecs <= 0) { // No record found, exit
	if ($app_delete->Recordset)
		$app_delete->Recordset->Close();
	$app_delete->Page_Terminate("applist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $app_delete->ShowPageHeader(); ?>
<?php
$app_delete->ShowMessage();
?>
<form name="fappdelete" id="fappdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($app_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $app_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="app">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($app_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $app->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($app->appid->Visible) { // appid ?>
		<th><span id="elh_app_appid" class="app_appid"><?php echo $app->appid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->appname->Visible) { // appname ?>
		<th><span id="elh_app_appname" class="app_appname"><?php echo $app->appname->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->appdesc->Visible) { // appdesc ?>
		<th><span id="elh_app_appdesc" class="app_appdesc"><?php echo $app->appdesc->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->appimage->Visible) { // appimage ?>
		<th><span id="elh_app_appimage" class="app_appimage"><?php echo $app->appimage->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->applink->Visible) { // applink ?>
		<th><span id="elh_app_applink" class="app_applink"><?php echo $app->applink->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->appplatform->Visible) { // appplatform ?>
		<th><span id="elh_app_appplatform" class="app_appplatform"><?php echo $app->appplatform->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->createddate->Visible) { // createddate ?>
		<th><span id="elh_app_createddate" class="app_createddate"><?php echo $app->createddate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($app->categoryid->Visible) { // categoryid ?>
		<th><span id="elh_app_categoryid" class="app_categoryid"><?php echo $app->categoryid->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$app_delete->RecCnt = 0;
$i = 0;
while (!$app_delete->Recordset->EOF) {
	$app_delete->RecCnt++;
	$app_delete->RowCnt++;

	// Set row properties
	$app->ResetAttrs();
	$app->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$app_delete->LoadRowValues($app_delete->Recordset);

	// Render row
	$app_delete->RenderRow();
?>
	<tr<?php echo $app->RowAttributes() ?>>
<?php if ($app->appid->Visible) { // appid ?>
		<td<?php echo $app->appid->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_appid" class="app_appid">
<span<?php echo $app->appid->ViewAttributes() ?>>
<?php echo $app->appid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($app->appname->Visible) { // appname ?>
		<td<?php echo $app->appname->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_appname" class="app_appname">
<span<?php echo $app->appname->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($app->appname->ListViewValue())) && $app->appname->LinkAttributes() <> "") { ?>
<a<?php echo $app->appname->LinkAttributes() ?>><?php echo $app->appname->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $app->appname->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($app->appdesc->Visible) { // appdesc ?>
		<td<?php echo $app->appdesc->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_appdesc" class="app_appdesc">
<span<?php echo $app->appdesc->ViewAttributes() ?>>
<?php echo $app->appdesc->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($app->appimage->Visible) { // appimage ?>
		<td<?php echo $app->appimage->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_appimage" class="app_appimage">
<span>
<?php echo ew_GetFileViewTag($app->appimage, $app->appimage->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($app->applink->Visible) { // applink ?>
		<td<?php echo $app->applink->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_applink" class="app_applink">
<span<?php echo $app->applink->ViewAttributes() ?>>
<?php echo $app->applink->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($app->appplatform->Visible) { // appplatform ?>
		<td<?php echo $app->appplatform->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_appplatform" class="app_appplatform">
<span<?php echo $app->appplatform->ViewAttributes() ?>>
<?php echo $app->appplatform->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($app->createddate->Visible) { // createddate ?>
		<td<?php echo $app->createddate->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_createddate" class="app_createddate">
<span<?php echo $app->createddate->ViewAttributes() ?>>
<?php echo $app->createddate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($app->categoryid->Visible) { // categoryid ?>
		<td<?php echo $app->categoryid->CellAttributes() ?>>
<span id="el<?php echo $app_delete->RowCnt ?>_app_categoryid" class="app_categoryid">
<span<?php echo $app->categoryid->ViewAttributes() ?>>
<?php echo $app->categoryid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$app_delete->Recordset->MoveNext();
}
$app_delete->Recordset->Close();
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
fappdelete.Init();
</script>
<?php
$app_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$app_delete->Page_Terminate();
?>
