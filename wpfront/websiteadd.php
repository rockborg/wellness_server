<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "websiteinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$website_add = NULL; // Initialize page object first

class cwebsite_add extends cwebsite {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'website';

	// Page object name
	var $PageObjName = 'website_add';

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

		// Table object (website)
		if (!isset($GLOBALS["website"]) || get_class($GLOBALS["website"]) == "cwebsite") {
			$GLOBALS["website"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["website"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'website', TRUE);

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
		global $EW_EXPORT, $website;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($website);
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
			if (@$_GET["websiteid"] != "") {
				$this->websiteid->setQueryStringValue($_GET["websiteid"]);
				$this->setKey("websiteid", $this->websiteid->CurrentValue); // Set up key
			} else {
				$this->setKey("websiteid", ""); // Clear key
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
					$this->Page_Terminate("websitelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "websiteview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->webimage->Upload->Index = $objForm->Index;
		$this->webimage->Upload->UploadFile();
		$this->webimage->CurrentValue = $this->webimage->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->webname->CurrentValue = NULL;
		$this->webname->OldValue = $this->webname->CurrentValue;
		$this->webdesc->CurrentValue = NULL;
		$this->webdesc->OldValue = $this->webdesc->CurrentValue;
		$this->webimage->Upload->DbValue = NULL;
		$this->webimage->OldValue = $this->webimage->Upload->DbValue;
		$this->webimage->CurrentValue = NULL; // Clear file related field
		$this->weblink->CurrentValue = NULL;
		$this->weblink->OldValue = $this->weblink->CurrentValue;
		$this->createddate->CurrentValue = NULL;
		$this->createddate->OldValue = $this->createddate->CurrentValue;
		$this->categoryid->CurrentValue = NULL;
		$this->categoryid->OldValue = $this->categoryid->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->webname->FldIsDetailKey) {
			$this->webname->setFormValue($objForm->GetValue("x_webname"));
		}
		if (!$this->webdesc->FldIsDetailKey) {
			$this->webdesc->setFormValue($objForm->GetValue("x_webdesc"));
		}
		if (!$this->weblink->FldIsDetailKey) {
			$this->weblink->setFormValue($objForm->GetValue("x_weblink"));
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
		$this->LoadOldRecord();
		$this->webname->CurrentValue = $this->webname->FormValue;
		$this->webdesc->CurrentValue = $this->webdesc->FormValue;
		$this->weblink->CurrentValue = $this->weblink->FormValue;
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
		$this->websiteid->setDbValue($rs->fields('websiteid'));
		$this->webname->setDbValue($rs->fields('webname'));
		$this->webdesc->setDbValue($rs->fields('webdesc'));
		$this->webimage->Upload->DbValue = $rs->fields('webimage');
		$this->webimage->CurrentValue = $this->webimage->Upload->DbValue;
		$this->weblink->setDbValue($rs->fields('weblink'));
		$this->createddate->setDbValue($rs->fields('createddate'));
		$this->categoryid->setDbValue($rs->fields('categoryid'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->websiteid->DbValue = $row['websiteid'];
		$this->webname->DbValue = $row['webname'];
		$this->webdesc->DbValue = $row['webdesc'];
		$this->webimage->Upload->DbValue = $row['webimage'];
		$this->weblink->DbValue = $row['weblink'];
		$this->createddate->DbValue = $row['createddate'];
		$this->categoryid->DbValue = $row['categoryid'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("websiteid")) <> "")
			$this->websiteid->CurrentValue = $this->getKey("websiteid"); // websiteid
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// websiteid
		// webname
		// webdesc
		// webimage
		// weblink
		// createddate
		// categoryid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// websiteid
			$this->websiteid->ViewValue = $this->websiteid->CurrentValue;
			$this->websiteid->ViewCustomAttributes = "";

			// webname
			$this->webname->ViewValue = $this->webname->CurrentValue;
			$this->webname->ViewCustomAttributes = "";

			// webdesc
			$this->webdesc->ViewValue = $this->webdesc->CurrentValue;
			$this->webdesc->ViewCustomAttributes = "";

			// webimage
			if (!ew_Empty($this->webimage->Upload->DbValue)) {
				$this->webimage->ImageWidth = 100;
				$this->webimage->ImageHeight = 0;
				$this->webimage->ImageAlt = $this->webimage->FldAlt();
				$this->webimage->ViewValue = ew_UploadPathEx(FALSE, $this->webimage->UploadPath) . $this->webimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->webimage->ViewValue = ew_UploadPathEx(TRUE, $this->webimage->UploadPath) . $this->webimage->Upload->DbValue;
				}
			} else {
				$this->webimage->ViewValue = "";
			}
			$this->webimage->ViewCustomAttributes = "";

			// weblink
			$this->weblink->ViewValue = $this->weblink->CurrentValue;
			$this->weblink->ViewCustomAttributes = "";

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

			// webname
			$this->webname->LinkCustomAttributes = "";
			if (!ew_Empty($this->weblink->CurrentValue)) {
				$this->webname->HrefValue = $this->weblink->CurrentValue; // Add prefix/suffix
				$this->webname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->webname->HrefValue = ew_ConvertFullUrl($this->webname->HrefValue);
			} else {
				$this->webname->HrefValue = "";
			}
			$this->webname->TooltipValue = "";

			// webdesc
			$this->webdesc->LinkCustomAttributes = "";
			$this->webdesc->HrefValue = "";
			$this->webdesc->TooltipValue = "";

			// webimage
			$this->webimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->webimage->Upload->DbValue)) {
				$this->webimage->HrefValue = ew_UploadPathEx(FALSE, $this->webimage->UploadPath) . $this->webimage->Upload->DbValue; // Add prefix/suffix
				$this->webimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->webimage->HrefValue = ew_ConvertFullUrl($this->webimage->HrefValue);
			} else {
				$this->webimage->HrefValue = "";
			}
			$this->webimage->HrefValue2 = $this->webimage->UploadPath . $this->webimage->Upload->DbValue;
			$this->webimage->TooltipValue = "";
			if ($this->webimage->UseColorbox) {
				$this->webimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->webimage->LinkAttrs["data-rel"] = "website_x_webimage";
				$this->webimage->LinkAttrs["class"] = "ewLightbox";
			}

			// weblink
			$this->weblink->LinkCustomAttributes = "";
			$this->weblink->HrefValue = "";
			$this->weblink->TooltipValue = "";

			// createddate
			$this->createddate->LinkCustomAttributes = "";
			$this->createddate->HrefValue = "";
			$this->createddate->TooltipValue = "";

			// categoryid
			$this->categoryid->LinkCustomAttributes = "";
			$this->categoryid->HrefValue = "";
			$this->categoryid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// webname
			$this->webname->EditAttrs["class"] = "form-control";
			$this->webname->EditCustomAttributes = "";
			$this->webname->EditValue = ew_HtmlEncode($this->webname->CurrentValue);
			$this->webname->PlaceHolder = ew_RemoveHtml($this->webname->FldCaption());

			// webdesc
			$this->webdesc->EditAttrs["class"] = "form-control";
			$this->webdesc->EditCustomAttributes = "";
			$this->webdesc->EditValue = ew_HtmlEncode($this->webdesc->CurrentValue);
			$this->webdesc->PlaceHolder = ew_RemoveHtml($this->webdesc->FldCaption());

			// webimage
			$this->webimage->EditAttrs["class"] = "form-control";
			$this->webimage->EditCustomAttributes = "";
			if (!ew_Empty($this->webimage->Upload->DbValue)) {
				$this->webimage->ImageWidth = 100;
				$this->webimage->ImageHeight = 0;
				$this->webimage->ImageAlt = $this->webimage->FldAlt();
				$this->webimage->EditValue = ew_UploadPathEx(FALSE, $this->webimage->UploadPath) . $this->webimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->webimage->EditValue = ew_UploadPathEx(TRUE, $this->webimage->UploadPath) . $this->webimage->Upload->DbValue;
				}
			} else {
				$this->webimage->EditValue = "";
			}
			if (!ew_Empty($this->webimage->CurrentValue))
				$this->webimage->Upload->FileName = $this->webimage->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->webimage);

			// weblink
			$this->weblink->EditAttrs["class"] = "form-control";
			$this->weblink->EditCustomAttributes = "";
			$this->weblink->EditValue = ew_HtmlEncode($this->weblink->CurrentValue);
			$this->weblink->PlaceHolder = ew_RemoveHtml($this->weblink->FldCaption());

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
			// webname

			if (!ew_Empty($this->weblink->CurrentValue)) {
				$this->webname->HrefValue = $this->weblink->CurrentValue; // Add prefix/suffix
				$this->webname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->webname->HrefValue = ew_ConvertFullUrl($this->webname->HrefValue);
			} else {
				$this->webname->HrefValue = "";
			}

			// webdesc
			$this->webdesc->HrefValue = "";

			// webimage
			if (!ew_Empty($this->webimage->Upload->DbValue)) {
				$this->webimage->HrefValue = ew_UploadPathEx(FALSE, $this->webimage->UploadPath) . $this->webimage->Upload->DbValue; // Add prefix/suffix
				$this->webimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->webimage->HrefValue = ew_ConvertFullUrl($this->webimage->HrefValue);
			} else {
				$this->webimage->HrefValue = "";
			}
			$this->webimage->HrefValue2 = $this->webimage->UploadPath . $this->webimage->Upload->DbValue;

			// weblink
			$this->weblink->HrefValue = "";

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
		if (!$this->webname->FldIsDetailKey && !is_null($this->webname->FormValue) && $this->webname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->webname->FldCaption(), $this->webname->ReqErrMsg));
		}
		if (!$this->webdesc->FldIsDetailKey && !is_null($this->webdesc->FormValue) && $this->webdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->webdesc->FldCaption(), $this->webdesc->ReqErrMsg));
		}
		if ($this->webimage->Upload->FileName == "" && !$this->webimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->webimage->FldCaption(), $this->webimage->ReqErrMsg));
		}
		if (!$this->weblink->FldIsDetailKey && !is_null($this->weblink->FormValue) && $this->weblink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->weblink->FldCaption(), $this->weblink->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// webname
		$this->webname->SetDbValueDef($rsnew, $this->webname->CurrentValue, "", FALSE);

		// webdesc
		$this->webdesc->SetDbValueDef($rsnew, $this->webdesc->CurrentValue, "", FALSE);

		// webimage
		if (!$this->webimage->Upload->KeepFile) {
			$this->webimage->Upload->DbValue = ""; // No need to delete old file
			if ($this->webimage->Upload->FileName == "") {
				$rsnew['webimage'] = NULL;
			} else {
				$rsnew['webimage'] = $this->webimage->Upload->FileName;
			}
		}

		// weblink
		$this->weblink->SetDbValueDef($rsnew, $this->weblink->CurrentValue, "", FALSE);

		// createddate
		$this->createddate->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['createddate'] = &$this->createddate->DbValue;

		// categoryid
		$this->categoryid->SetDbValueDef($rsnew, $this->categoryid->CurrentValue, 0, FALSE);
		if (!$this->webimage->Upload->KeepFile) {
			if (!ew_Empty($this->webimage->Upload->Value)) {
				if ($this->webimage->Upload->FileName == $this->webimage->Upload->DbValue) { // Overwrite if same file name
					$this->webimage->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['webimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->webimage->UploadPath), $rsnew['webimage']); // Get new file name
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
				if (!$this->webimage->Upload->KeepFile) {
					if (!ew_Empty($this->webimage->Upload->Value)) {
						$this->webimage->Upload->SaveToFile($this->webimage->UploadPath, $rsnew['webimage'], TRUE);
					}
					if ($this->webimage->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->webimage->OldUploadPath) . $this->webimage->Upload->DbValue);
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

		// Get insert id if necessary
		if ($AddRow) {
			$this->websiteid->setDbValue($conn->Insert_ID());
			$rsnew['websiteid'] = $this->websiteid->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// webimage
		ew_CleanUploadTempPath($this->webimage, $this->webimage->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "websitelist.php", "", $this->TableVar, TRUE);
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
if (!isset($website_add)) $website_add = new cwebsite_add();

// Page init
$website_add->Page_Init();

// Page main
$website_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$website_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var website_add = new ew_Page("website_add");
website_add.PageID = "add"; // Page ID
var EW_PAGE_ID = website_add.PageID; // For backward compatibility

// Form object
var fwebsiteadd = new ew_Form("fwebsiteadd");

// Validate form
fwebsiteadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_webname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $website->webname->FldCaption(), $website->webname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_webdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $website->webdesc->FldCaption(), $website->webdesc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_webimage");
			elm = this.GetElements("fn_x" + infix + "_webimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $website->webimage->FldCaption(), $website->webimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_weblink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $website->weblink->FldCaption(), $website->weblink->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categoryid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $website->categoryid->FldCaption(), $website->categoryid->ReqErrMsg)) ?>");

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
fwebsiteadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fwebsiteadd.ValidateRequired = true;
<?php } else { ?>
fwebsiteadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fwebsiteadd.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $website_add->ShowPageHeader(); ?>
<?php
$website_add->ShowMessage();
?>
<form name="fwebsiteadd" id="fwebsiteadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($website_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $website_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="website">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($website->webname->Visible) { // webname ?>
	<div id="r_webname" class="form-group">
		<label id="elh_website_webname" for="x_webname" class="col-sm-2 control-label ewLabel"><?php echo $website->webname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $website->webname->CellAttributes() ?>>
<span id="el_website_webname">
<input type="text" data-field="x_webname" name="x_webname" id="x_webname" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($website->webname->PlaceHolder) ?>" value="<?php echo $website->webname->EditValue ?>"<?php echo $website->webname->EditAttributes() ?>>
</span>
<?php echo $website->webname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($website->webdesc->Visible) { // webdesc ?>
	<div id="r_webdesc" class="form-group">
		<label id="elh_website_webdesc" for="x_webdesc" class="col-sm-2 control-label ewLabel"><?php echo $website->webdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $website->webdesc->CellAttributes() ?>>
<span id="el_website_webdesc">
<textarea data-field="x_webdesc" name="x_webdesc" id="x_webdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($website->webdesc->PlaceHolder) ?>"<?php echo $website->webdesc->EditAttributes() ?>><?php echo $website->webdesc->EditValue ?></textarea>
</span>
<?php echo $website->webdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($website->webimage->Visible) { // webimage ?>
	<div id="r_webimage" class="form-group">
		<label id="elh_website_webimage" class="col-sm-2 control-label ewLabel"><?php echo $website->webimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $website->webimage->CellAttributes() ?>>
<span id="el_website_webimage">
<div id="fd_x_webimage">
<span title="<?php echo $website->webimage->FldTitle() ? $website->webimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($website->webimage->ReadOnly || $website->webimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_webimage" name="x_webimage" id="x_webimage">
</span>
<input type="hidden" name="fn_x_webimage" id= "fn_x_webimage" value="<?php echo $website->webimage->Upload->FileName ?>">
<input type="hidden" name="fa_x_webimage" id= "fa_x_webimage" value="0">
<input type="hidden" name="fs_x_webimage" id= "fs_x_webimage" value="255">
<input type="hidden" name="fx_x_webimage" id= "fx_x_webimage" value="<?php echo $website->webimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_webimage" id= "fm_x_webimage" value="<?php echo $website->webimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_webimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $website->webimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($website->weblink->Visible) { // weblink ?>
	<div id="r_weblink" class="form-group">
		<label id="elh_website_weblink" for="x_weblink" class="col-sm-2 control-label ewLabel"><?php echo $website->weblink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $website->weblink->CellAttributes() ?>>
<span id="el_website_weblink">
<input type="text" data-field="x_weblink" name="x_weblink" id="x_weblink" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($website->weblink->PlaceHolder) ?>" value="<?php echo $website->weblink->EditValue ?>"<?php echo $website->weblink->EditAttributes() ?>>
</span>
<?php echo $website->weblink->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($website->categoryid->Visible) { // categoryid ?>
	<div id="r_categoryid" class="form-group">
		<label id="elh_website_categoryid" for="x_categoryid" class="col-sm-2 control-label ewLabel"><?php echo $website->categoryid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $website->categoryid->CellAttributes() ?>>
<span id="el_website_categoryid">
<select data-field="x_categoryid" id="x_categoryid" name="x_categoryid"<?php echo $website->categoryid->EditAttributes() ?>>
<?php
if (is_array($website->categoryid->EditValue)) {
	$arwrk = $website->categoryid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($website->categoryid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fwebsiteadd.Lists["x_categoryid"].Options = <?php echo (is_array($website->categoryid->EditValue)) ? ew_ArrayToJson($website->categoryid->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $website->categoryid->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fwebsiteadd.Init();
</script>
<?php
$website_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$website_add->Page_Terminate();
?>
