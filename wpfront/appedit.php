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

$app_edit = NULL; // Initialize page object first

class capp_edit extends capp {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'app';

	// Page object name
	var $PageObjName = 'app_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["appid"] <> "") {
			$this->appid->setQueryStringValue($_GET["appid"]);
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
		if ($this->appid->CurrentValue == "")
			$this->Page_Terminate("applist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("applist.php"); // No matching record, return to list
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
		$this->appimage->Upload->Index = $objForm->Index;
		$this->appimage->Upload->UploadFile();
		$this->appimage->CurrentValue = $this->appimage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->appid->FldIsDetailKey)
			$this->appid->setFormValue($objForm->GetValue("x_appid"));
		if (!$this->appname->FldIsDetailKey) {
			$this->appname->setFormValue($objForm->GetValue("x_appname"));
		}
		if (!$this->appdesc->FldIsDetailKey) {
			$this->appdesc->setFormValue($objForm->GetValue("x_appdesc"));
		}
		if (!$this->applink->FldIsDetailKey) {
			$this->applink->setFormValue($objForm->GetValue("x_applink"));
		}
		if (!$this->appplatform->FldIsDetailKey) {
			$this->appplatform->setFormValue($objForm->GetValue("x_appplatform"));
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
		$this->appid->CurrentValue = $this->appid->FormValue;
		$this->appname->CurrentValue = $this->appname->FormValue;
		$this->appdesc->CurrentValue = $this->appdesc->FormValue;
		$this->applink->CurrentValue = $this->applink->FormValue;
		$this->appplatform->CurrentValue = $this->appplatform->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// appid
			$this->appid->EditAttrs["class"] = "form-control";
			$this->appid->EditCustomAttributes = "";
			$this->appid->EditValue = $this->appid->CurrentValue;
			$this->appid->ViewCustomAttributes = "";

			// appname
			$this->appname->EditAttrs["class"] = "form-control";
			$this->appname->EditCustomAttributes = "";
			$this->appname->EditValue = ew_HtmlEncode($this->appname->CurrentValue);
			$this->appname->PlaceHolder = ew_RemoveHtml($this->appname->FldCaption());

			// appdesc
			$this->appdesc->EditAttrs["class"] = "form-control";
			$this->appdesc->EditCustomAttributes = "";
			$this->appdesc->EditValue = ew_HtmlEncode($this->appdesc->CurrentValue);
			$this->appdesc->PlaceHolder = ew_RemoveHtml($this->appdesc->FldCaption());

			// appimage
			$this->appimage->EditAttrs["class"] = "form-control";
			$this->appimage->EditCustomAttributes = "";
			if (!ew_Empty($this->appimage->Upload->DbValue)) {
				$this->appimage->ImageWidth = 100;
				$this->appimage->ImageHeight = 0;
				$this->appimage->ImageAlt = $this->appimage->FldAlt();
				$this->appimage->EditValue = ew_UploadPathEx(FALSE, $this->appimage->UploadPath) . $this->appimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->appimage->EditValue = ew_UploadPathEx(TRUE, $this->appimage->UploadPath) . $this->appimage->Upload->DbValue;
				}
			} else {
				$this->appimage->EditValue = "";
			}
			if (!ew_Empty($this->appimage->CurrentValue))
				$this->appimage->Upload->FileName = $this->appimage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->appimage);

			// applink
			$this->applink->EditAttrs["class"] = "form-control";
			$this->applink->EditCustomAttributes = "";
			$this->applink->EditValue = ew_HtmlEncode($this->applink->CurrentValue);
			$this->applink->PlaceHolder = ew_RemoveHtml($this->applink->FldCaption());

			// appplatform
			$this->appplatform->EditAttrs["class"] = "form-control";
			$this->appplatform->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `plaformname`, `plaformname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `list_platform`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->appplatform, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->appplatform->EditValue = $arwrk;

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
			// appid

			$this->appid->HrefValue = "";

			// appname
			if (!ew_Empty($this->applink->CurrentValue)) {
				$this->appname->HrefValue = $this->applink->CurrentValue; // Add prefix/suffix
				$this->appname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->appname->HrefValue = ew_ConvertFullUrl($this->appname->HrefValue);
			} else {
				$this->appname->HrefValue = "";
			}

			// appdesc
			$this->appdesc->HrefValue = "";

			// appimage
			if (!ew_Empty($this->appimage->Upload->DbValue)) {
				$this->appimage->HrefValue = ew_UploadPathEx(FALSE, $this->appimage->UploadPath) . $this->appimage->Upload->DbValue; // Add prefix/suffix
				$this->appimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->appimage->HrefValue = ew_ConvertFullUrl($this->appimage->HrefValue);
			} else {
				$this->appimage->HrefValue = "";
			}
			$this->appimage->HrefValue2 = $this->appimage->UploadPath . $this->appimage->Upload->DbValue;

			// applink
			$this->applink->HrefValue = "";

			// appplatform
			$this->appplatform->HrefValue = "";

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
		if (!$this->appname->FldIsDetailKey && !is_null($this->appname->FormValue) && $this->appname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->appname->FldCaption(), $this->appname->ReqErrMsg));
		}
		if (!$this->appdesc->FldIsDetailKey && !is_null($this->appdesc->FormValue) && $this->appdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->appdesc->FldCaption(), $this->appdesc->ReqErrMsg));
		}
		if ($this->appimage->Upload->FileName == "" && !$this->appimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->appimage->FldCaption(), $this->appimage->ReqErrMsg));
		}
		if (!$this->applink->FldIsDetailKey && !is_null($this->applink->FormValue) && $this->applink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->applink->FldCaption(), $this->applink->ReqErrMsg));
		}
		if (!$this->appplatform->FldIsDetailKey && !is_null($this->appplatform->FormValue) && $this->appplatform->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->appplatform->FldCaption(), $this->appplatform->ReqErrMsg));
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

			// appname
			$this->appname->SetDbValueDef($rsnew, $this->appname->CurrentValue, "", $this->appname->ReadOnly);

			// appdesc
			$this->appdesc->SetDbValueDef($rsnew, $this->appdesc->CurrentValue, "", $this->appdesc->ReadOnly);

			// appimage
			if (!($this->appimage->ReadOnly) && !$this->appimage->Upload->KeepFile) {
				$this->appimage->Upload->DbValue = $rsold['appimage']; // Get original value
				if ($this->appimage->Upload->FileName == "") {
					$rsnew['appimage'] = NULL;
				} else {
					$rsnew['appimage'] = $this->appimage->Upload->FileName;
				}
			}

			// applink
			$this->applink->SetDbValueDef($rsnew, $this->applink->CurrentValue, "", $this->applink->ReadOnly);

			// appplatform
			$this->appplatform->SetDbValueDef($rsnew, $this->appplatform->CurrentValue, "", $this->appplatform->ReadOnly);

			// createddate
			$this->createddate->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['createddate'] = &$this->createddate->DbValue;

			// categoryid
			$this->categoryid->SetDbValueDef($rsnew, $this->categoryid->CurrentValue, 0, $this->categoryid->ReadOnly);
			if (!$this->appimage->Upload->KeepFile) {
				if (!ew_Empty($this->appimage->Upload->Value)) {
					if ($this->appimage->Upload->FileName == $this->appimage->Upload->DbValue) { // Overwrite if same file name
						$this->appimage->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['appimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->appimage->UploadPath), $rsnew['appimage']); // Get new file name
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
					if (!$this->appimage->Upload->KeepFile) {
						if (!ew_Empty($this->appimage->Upload->Value)) {
							$this->appimage->Upload->SaveToFile($this->appimage->UploadPath, $rsnew['appimage'], TRUE);
						}
						if ($this->appimage->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->appimage->OldUploadPath) . $this->appimage->Upload->DbValue);
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

		// appimage
		ew_CleanUploadTempPath($this->appimage, $this->appimage->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "applist.php", "", $this->TableVar, TRUE);
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
if (!isset($app_edit)) $app_edit = new capp_edit();

// Page init
$app_edit->Page_Init();

// Page main
$app_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$app_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var app_edit = new ew_Page("app_edit");
app_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = app_edit.PageID; // For backward compatibility

// Form object
var fappedit = new ew_Form("fappedit");

// Validate form
fappedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_appname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $app->appname->FldCaption(), $app->appname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_appdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $app->appdesc->FldCaption(), $app->appdesc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_appimage");
			elm = this.GetElements("fn_x" + infix + "_appimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $app->appimage->FldCaption(), $app->appimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_applink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $app->applink->FldCaption(), $app->applink->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_appplatform");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $app->appplatform->FldCaption(), $app->appplatform->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categoryid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $app->categoryid->FldCaption(), $app->categoryid->ReqErrMsg)) ?>");

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
fappedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fappedit.ValidateRequired = true;
<?php } else { ?>
fappedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fappedit.Lists["x_appplatform"] = {"LinkField":"x_plaformname","Ajax":null,"AutoFill":false,"DisplayFields":["x_plaformname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fappedit.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $app_edit->ShowPageHeader(); ?>
<?php
$app_edit->ShowMessage();
?>
<form name="fappedit" id="fappedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($app_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $app_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="app">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($app->appid->Visible) { // appid ?>
	<div id="r_appid" class="form-group">
		<label id="elh_app_appid" class="col-sm-2 control-label ewLabel"><?php echo $app->appid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $app->appid->CellAttributes() ?>>
<span id="el_app_appid">
<span<?php echo $app->appid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $app->appid->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_appid" name="x_appid" id="x_appid" value="<?php echo ew_HtmlEncode($app->appid->CurrentValue) ?>">
<?php echo $app->appid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($app->appname->Visible) { // appname ?>
	<div id="r_appname" class="form-group">
		<label id="elh_app_appname" for="x_appname" class="col-sm-2 control-label ewLabel"><?php echo $app->appname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $app->appname->CellAttributes() ?>>
<span id="el_app_appname">
<input type="text" data-field="x_appname" name="x_appname" id="x_appname" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($app->appname->PlaceHolder) ?>" value="<?php echo $app->appname->EditValue ?>"<?php echo $app->appname->EditAttributes() ?>>
</span>
<?php echo $app->appname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($app->appdesc->Visible) { // appdesc ?>
	<div id="r_appdesc" class="form-group">
		<label id="elh_app_appdesc" for="x_appdesc" class="col-sm-2 control-label ewLabel"><?php echo $app->appdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $app->appdesc->CellAttributes() ?>>
<span id="el_app_appdesc">
<textarea data-field="x_appdesc" name="x_appdesc" id="x_appdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($app->appdesc->PlaceHolder) ?>"<?php echo $app->appdesc->EditAttributes() ?>><?php echo $app->appdesc->EditValue ?></textarea>
</span>
<?php echo $app->appdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($app->appimage->Visible) { // appimage ?>
	<div id="r_appimage" class="form-group">
		<label id="elh_app_appimage" class="col-sm-2 control-label ewLabel"><?php echo $app->appimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $app->appimage->CellAttributes() ?>>
<span id="el_app_appimage">
<div id="fd_x_appimage">
<span title="<?php echo $app->appimage->FldTitle() ? $app->appimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($app->appimage->ReadOnly || $app->appimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_appimage" name="x_appimage" id="x_appimage">
</span>
<input type="hidden" name="fn_x_appimage" id= "fn_x_appimage" value="<?php echo $app->appimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_appimage"] == "0") { ?>
<input type="hidden" name="fa_x_appimage" id= "fa_x_appimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_appimage" id= "fa_x_appimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_appimage" id= "fs_x_appimage" value="255">
<input type="hidden" name="fx_x_appimage" id= "fx_x_appimage" value="<?php echo $app->appimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_appimage" id= "fm_x_appimage" value="<?php echo $app->appimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_appimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $app->appimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($app->applink->Visible) { // applink ?>
	<div id="r_applink" class="form-group">
		<label id="elh_app_applink" for="x_applink" class="col-sm-2 control-label ewLabel"><?php echo $app->applink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $app->applink->CellAttributes() ?>>
<span id="el_app_applink">
<input type="text" data-field="x_applink" name="x_applink" id="x_applink" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($app->applink->PlaceHolder) ?>" value="<?php echo $app->applink->EditValue ?>"<?php echo $app->applink->EditAttributes() ?>>
</span>
<?php echo $app->applink->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($app->appplatform->Visible) { // appplatform ?>
	<div id="r_appplatform" class="form-group">
		<label id="elh_app_appplatform" for="x_appplatform" class="col-sm-2 control-label ewLabel"><?php echo $app->appplatform->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $app->appplatform->CellAttributes() ?>>
<span id="el_app_appplatform">
<select data-field="x_appplatform" id="x_appplatform" name="x_appplatform"<?php echo $app->appplatform->EditAttributes() ?>>
<?php
if (is_array($app->appplatform->EditValue)) {
	$arwrk = $app->appplatform->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($app->appplatform->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fappedit.Lists["x_appplatform"].Options = <?php echo (is_array($app->appplatform->EditValue)) ? ew_ArrayToJson($app->appplatform->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $app->appplatform->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($app->categoryid->Visible) { // categoryid ?>
	<div id="r_categoryid" class="form-group">
		<label id="elh_app_categoryid" for="x_categoryid" class="col-sm-2 control-label ewLabel"><?php echo $app->categoryid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $app->categoryid->CellAttributes() ?>>
<span id="el_app_categoryid">
<select data-field="x_categoryid" id="x_categoryid" name="x_categoryid"<?php echo $app->categoryid->EditAttributes() ?>>
<?php
if (is_array($app->categoryid->EditValue)) {
	$arwrk = $app->categoryid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($app->categoryid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fappedit.Lists["x_categoryid"].Options = <?php echo (is_array($app->categoryid->EditValue)) ? ew_ArrayToJson($app->categoryid->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $app->categoryid->CustomMsg ?></div></div>
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
fappedit.Init();
</script>
<?php
$app_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$app_edit->Page_Terminate();
?>
