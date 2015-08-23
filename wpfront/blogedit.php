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

$blog_edit = NULL; // Initialize page object first

class cblog_edit extends cblog {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{394CAB35-9A75-4A4A-AC50-D31C1662AE27}";

	// Table name
	var $TableName = 'blog';

	// Page object name
	var $PageObjName = 'blog_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["blogid"] <> "") {
			$this->blogid->setQueryStringValue($_GET["blogid"]);
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
		if ($this->blogid->CurrentValue == "")
			$this->Page_Terminate("bloglist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("bloglist.php"); // No matching record, return to list
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
		$this->blogimage->Upload->Index = $objForm->Index;
		$this->blogimage->Upload->UploadFile();
		$this->blogimage->CurrentValue = $this->blogimage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->blogid->FldIsDetailKey)
			$this->blogid->setFormValue($objForm->GetValue("x_blogid"));
		if (!$this->blogname->FldIsDetailKey) {
			$this->blogname->setFormValue($objForm->GetValue("x_blogname"));
		}
		if (!$this->blogdesc->FldIsDetailKey) {
			$this->blogdesc->setFormValue($objForm->GetValue("x_blogdesc"));
		}
		if (!$this->bloglink->FldIsDetailKey) {
			$this->bloglink->setFormValue($objForm->GetValue("x_bloglink"));
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
		$this->blogid->CurrentValue = $this->blogid->FormValue;
		$this->blogname->CurrentValue = $this->blogname->FormValue;
		$this->blogdesc->CurrentValue = $this->blogdesc->FormValue;
		$this->bloglink->CurrentValue = $this->bloglink->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// blogid
			$this->blogid->EditAttrs["class"] = "form-control";
			$this->blogid->EditCustomAttributes = "";
			$this->blogid->EditValue = $this->blogid->CurrentValue;
			$this->blogid->ViewCustomAttributes = "";

			// blogname
			$this->blogname->EditAttrs["class"] = "form-control";
			$this->blogname->EditCustomAttributes = "";
			$this->blogname->EditValue = ew_HtmlEncode($this->blogname->CurrentValue);
			$this->blogname->PlaceHolder = ew_RemoveHtml($this->blogname->FldCaption());

			// blogdesc
			$this->blogdesc->EditAttrs["class"] = "form-control";
			$this->blogdesc->EditCustomAttributes = "";
			$this->blogdesc->EditValue = ew_HtmlEncode($this->blogdesc->CurrentValue);
			$this->blogdesc->PlaceHolder = ew_RemoveHtml($this->blogdesc->FldCaption());

			// blogimage
			$this->blogimage->EditAttrs["class"] = "form-control";
			$this->blogimage->EditCustomAttributes = "";
			if (!ew_Empty($this->blogimage->Upload->DbValue)) {
				$this->blogimage->ImageWidth = 100;
				$this->blogimage->ImageHeight = 0;
				$this->blogimage->ImageAlt = $this->blogimage->FldAlt();
				$this->blogimage->EditValue = ew_UploadPathEx(FALSE, $this->blogimage->UploadPath) . $this->blogimage->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->blogimage->EditValue = ew_UploadPathEx(TRUE, $this->blogimage->UploadPath) . $this->blogimage->Upload->DbValue;
				}
			} else {
				$this->blogimage->EditValue = "";
			}
			if (!ew_Empty($this->blogimage->CurrentValue))
				$this->blogimage->Upload->FileName = $this->blogimage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->blogimage);

			// bloglink
			$this->bloglink->EditAttrs["class"] = "form-control";
			$this->bloglink->EditCustomAttributes = "";
			$this->bloglink->EditValue = ew_HtmlEncode($this->bloglink->CurrentValue);
			$this->bloglink->PlaceHolder = ew_RemoveHtml($this->bloglink->FldCaption());

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
			// blogid

			$this->blogid->HrefValue = "";

			// blogname
			if (!ew_Empty($this->bloglink->CurrentValue)) {
				$this->blogname->HrefValue = $this->bloglink->CurrentValue; // Add prefix/suffix
				$this->blogname->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->blogname->HrefValue = ew_ConvertFullUrl($this->blogname->HrefValue);
			} else {
				$this->blogname->HrefValue = "";
			}

			// blogdesc
			$this->blogdesc->HrefValue = "";

			// blogimage
			if (!ew_Empty($this->blogimage->Upload->DbValue)) {
				$this->blogimage->HrefValue = ew_UploadPathEx(FALSE, $this->blogimage->UploadPath) . $this->blogimage->Upload->DbValue; // Add prefix/suffix
				$this->blogimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->blogimage->HrefValue = ew_ConvertFullUrl($this->blogimage->HrefValue);
			} else {
				$this->blogimage->HrefValue = "";
			}
			$this->blogimage->HrefValue2 = $this->blogimage->UploadPath . $this->blogimage->Upload->DbValue;

			// bloglink
			$this->bloglink->HrefValue = "";

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
		if (!$this->blogname->FldIsDetailKey && !is_null($this->blogname->FormValue) && $this->blogname->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->blogname->FldCaption(), $this->blogname->ReqErrMsg));
		}
		if (!$this->blogdesc->FldIsDetailKey && !is_null($this->blogdesc->FormValue) && $this->blogdesc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->blogdesc->FldCaption(), $this->blogdesc->ReqErrMsg));
		}
		if ($this->blogimage->Upload->FileName == "" && !$this->blogimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->blogimage->FldCaption(), $this->blogimage->ReqErrMsg));
		}
		if (!$this->bloglink->FldIsDetailKey && !is_null($this->bloglink->FormValue) && $this->bloglink->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bloglink->FldCaption(), $this->bloglink->ReqErrMsg));
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

			// blogname
			$this->blogname->SetDbValueDef($rsnew, $this->blogname->CurrentValue, "", $this->blogname->ReadOnly);

			// blogdesc
			$this->blogdesc->SetDbValueDef($rsnew, $this->blogdesc->CurrentValue, "", $this->blogdesc->ReadOnly);

			// blogimage
			if (!($this->blogimage->ReadOnly) && !$this->blogimage->Upload->KeepFile) {
				$this->blogimage->Upload->DbValue = $rsold['blogimage']; // Get original value
				if ($this->blogimage->Upload->FileName == "") {
					$rsnew['blogimage'] = NULL;
				} else {
					$rsnew['blogimage'] = $this->blogimage->Upload->FileName;
				}
			}

			// bloglink
			$this->bloglink->SetDbValueDef($rsnew, $this->bloglink->CurrentValue, "", $this->bloglink->ReadOnly);

			// createddate
			$this->createddate->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['createddate'] = &$this->createddate->DbValue;

			// categoryid
			$this->categoryid->SetDbValueDef($rsnew, $this->categoryid->CurrentValue, 0, $this->categoryid->ReadOnly);
			if (!$this->blogimage->Upload->KeepFile) {
				if (!ew_Empty($this->blogimage->Upload->Value)) {
					if ($this->blogimage->Upload->FileName == $this->blogimage->Upload->DbValue) { // Overwrite if same file name
						$this->blogimage->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['blogimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->blogimage->UploadPath), $rsnew['blogimage']); // Get new file name
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
					if (!$this->blogimage->Upload->KeepFile) {
						if (!ew_Empty($this->blogimage->Upload->Value)) {
							$this->blogimage->Upload->SaveToFile($this->blogimage->UploadPath, $rsnew['blogimage'], TRUE);
						}
						if ($this->blogimage->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->blogimage->OldUploadPath) . $this->blogimage->Upload->DbValue);
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

		// blogimage
		ew_CleanUploadTempPath($this->blogimage, $this->blogimage->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "bloglist.php", "", $this->TableVar, TRUE);
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
if (!isset($blog_edit)) $blog_edit = new cblog_edit();

// Page init
$blog_edit->Page_Init();

// Page main
$blog_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$blog_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var blog_edit = new ew_Page("blog_edit");
blog_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = blog_edit.PageID; // For backward compatibility

// Form object
var fblogedit = new ew_Form("fblogedit");

// Validate form
fblogedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_blogname");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->blogname->FldCaption(), $blog->blogname->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_blogdesc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->blogdesc->FldCaption(), $blog->blogdesc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_blogimage");
			elm = this.GetElements("fn_x" + infix + "_blogimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->blogimage->FldCaption(), $blog->blogimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bloglink");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->bloglink->FldCaption(), $blog->bloglink->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_categoryid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $blog->categoryid->FldCaption(), $blog->categoryid->ReqErrMsg)) ?>");

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
fblogedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fblogedit.ValidateRequired = true;
<?php } else { ?>
fblogedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fblogedit.Lists["x_categoryid"] = {"LinkField":"x_categoryid","Ajax":null,"AutoFill":false,"DisplayFields":["x_categoryname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $blog_edit->ShowPageHeader(); ?>
<?php
$blog_edit->ShowMessage();
?>
<form name="fblogedit" id="fblogedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($blog_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $blog_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($blog->blogid->Visible) { // blogid ?>
	<div id="r_blogid" class="form-group">
		<label id="elh_blog_blogid" class="col-sm-2 control-label ewLabel"><?php echo $blog->blogid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $blog->blogid->CellAttributes() ?>>
<span id="el_blog_blogid">
<span<?php echo $blog->blogid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $blog->blogid->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_blogid" name="x_blogid" id="x_blogid" value="<?php echo ew_HtmlEncode($blog->blogid->CurrentValue) ?>">
<?php echo $blog->blogid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->blogname->Visible) { // blogname ?>
	<div id="r_blogname" class="form-group">
		<label id="elh_blog_blogname" for="x_blogname" class="col-sm-2 control-label ewLabel"><?php echo $blog->blogname->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $blog->blogname->CellAttributes() ?>>
<span id="el_blog_blogname">
<input type="text" data-field="x_blogname" name="x_blogname" id="x_blogname" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($blog->blogname->PlaceHolder) ?>" value="<?php echo $blog->blogname->EditValue ?>"<?php echo $blog->blogname->EditAttributes() ?>>
</span>
<?php echo $blog->blogname->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->blogdesc->Visible) { // blogdesc ?>
	<div id="r_blogdesc" class="form-group">
		<label id="elh_blog_blogdesc" for="x_blogdesc" class="col-sm-2 control-label ewLabel"><?php echo $blog->blogdesc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $blog->blogdesc->CellAttributes() ?>>
<span id="el_blog_blogdesc">
<textarea data-field="x_blogdesc" name="x_blogdesc" id="x_blogdesc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($blog->blogdesc->PlaceHolder) ?>"<?php echo $blog->blogdesc->EditAttributes() ?>><?php echo $blog->blogdesc->EditValue ?></textarea>
</span>
<?php echo $blog->blogdesc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->blogimage->Visible) { // blogimage ?>
	<div id="r_blogimage" class="form-group">
		<label id="elh_blog_blogimage" class="col-sm-2 control-label ewLabel"><?php echo $blog->blogimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $blog->blogimage->CellAttributes() ?>>
<span id="el_blog_blogimage">
<div id="fd_x_blogimage">
<span title="<?php echo $blog->blogimage->FldTitle() ? $blog->blogimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($blog->blogimage->ReadOnly || $blog->blogimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_blogimage" name="x_blogimage" id="x_blogimage">
</span>
<input type="hidden" name="fn_x_blogimage" id= "fn_x_blogimage" value="<?php echo $blog->blogimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_blogimage"] == "0") { ?>
<input type="hidden" name="fa_x_blogimage" id= "fa_x_blogimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_blogimage" id= "fa_x_blogimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_blogimage" id= "fs_x_blogimage" value="255">
<input type="hidden" name="fx_x_blogimage" id= "fx_x_blogimage" value="<?php echo $blog->blogimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_blogimage" id= "fm_x_blogimage" value="<?php echo $blog->blogimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_blogimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $blog->blogimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->bloglink->Visible) { // bloglink ?>
	<div id="r_bloglink" class="form-group">
		<label id="elh_blog_bloglink" for="x_bloglink" class="col-sm-2 control-label ewLabel"><?php echo $blog->bloglink->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $blog->bloglink->CellAttributes() ?>>
<span id="el_blog_bloglink">
<input type="text" data-field="x_bloglink" name="x_bloglink" id="x_bloglink" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($blog->bloglink->PlaceHolder) ?>" value="<?php echo $blog->bloglink->EditValue ?>"<?php echo $blog->bloglink->EditAttributes() ?>>
</span>
<?php echo $blog->bloglink->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($blog->categoryid->Visible) { // categoryid ?>
	<div id="r_categoryid" class="form-group">
		<label id="elh_blog_categoryid" for="x_categoryid" class="col-sm-2 control-label ewLabel"><?php echo $blog->categoryid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $blog->categoryid->CellAttributes() ?>>
<span id="el_blog_categoryid">
<select data-field="x_categoryid" id="x_categoryid" name="x_categoryid"<?php echo $blog->categoryid->EditAttributes() ?>>
<?php
if (is_array($blog->categoryid->EditValue)) {
	$arwrk = $blog->categoryid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($blog->categoryid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fblogedit.Lists["x_categoryid"].Options = <?php echo (is_array($blog->categoryid->EditValue)) ? ew_ArrayToJson($blog->categoryid->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $blog->categoryid->CustomMsg ?></div></div>
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
fblogedit.Init();
</script>
<?php
$blog_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$blog_edit->Page_Terminate();
?>
