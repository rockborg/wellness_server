<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "settingsinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$settings_edit = NULL; // Initialize page object first

class csettings_edit extends csettings {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{7060AD9E-0B65-4EDC-A749-00C6623FA119}";

	// Table name
	var $TableName = 'settings';

	// Page object name
	var $PageObjName = 'settings_edit';

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

		// Table object (settings)
		if (!isset($GLOBALS["settings"]) || get_class($GLOBALS["settings"]) == "csettings") {
			$GLOBALS["settings"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["settings"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'settings', TRUE);

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
		$this->settingsid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $settings;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($settings);
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
		if (@$_GET["settingsid"] <> "") {
			$this->settingsid->setQueryStringValue($_GET["settingsid"]);
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
		if ($this->settingsid->CurrentValue == "")
			$this->Page_Terminate("settingslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("settingslist.php"); // No matching record, return to list
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
		$this->aboutimage->Upload->Index = $objForm->Index;
		$this->aboutimage->Upload->UploadFile();
		$this->aboutimage->CurrentValue = $this->aboutimage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->settingsid->FldIsDetailKey)
			$this->settingsid->setFormValue($objForm->GetValue("x_settingsid"));
		if (!$this->abouttext->FldIsDetailKey) {
			$this->abouttext->setFormValue($objForm->GetValue("x_abouttext"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->settingsid->CurrentValue = $this->settingsid->FormValue;
		$this->abouttext->CurrentValue = $this->abouttext->FormValue;
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
		$this->settingsid->setDbValue($rs->fields('settingsid'));
		$this->aboutimage->Upload->DbValue = $rs->fields('aboutimage');
		$this->aboutimage->CurrentValue = $this->aboutimage->Upload->DbValue;
		$this->abouttext->setDbValue($rs->fields('abouttext'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->settingsid->DbValue = $row['settingsid'];
		$this->aboutimage->Upload->DbValue = $row['aboutimage'];
		$this->abouttext->DbValue = $row['abouttext'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// settingsid
		// aboutimage
		// abouttext

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// settingsid
		$this->settingsid->ViewValue = $this->settingsid->CurrentValue;
		$this->settingsid->ViewCustomAttributes = "";

		// aboutimage
		if (!ew_Empty($this->aboutimage->Upload->DbValue)) {
			$this->aboutimage->ImageWidth = 400;
			$this->aboutimage->ImageHeight = 0;
			$this->aboutimage->ImageAlt = $this->aboutimage->FldAlt();
			$this->aboutimage->ViewValue = $this->aboutimage->Upload->DbValue;
		} else {
			$this->aboutimage->ViewValue = "";
		}
		$this->aboutimage->ViewCustomAttributes = "";

		// abouttext
		$this->abouttext->ViewValue = $this->abouttext->CurrentValue;
		$this->abouttext->ViewCustomAttributes = "";

			// settingsid
			$this->settingsid->LinkCustomAttributes = "";
			$this->settingsid->HrefValue = "";
			$this->settingsid->TooltipValue = "";

			// aboutimage
			$this->aboutimage->LinkCustomAttributes = "";
			if (!ew_Empty($this->aboutimage->Upload->DbValue)) {
				$this->aboutimage->HrefValue = ew_GetFileUploadUrl($this->aboutimage, $this->aboutimage->Upload->DbValue); // Add prefix/suffix
				$this->aboutimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->aboutimage->HrefValue = ew_ConvertFullUrl($this->aboutimage->HrefValue);
			} else {
				$this->aboutimage->HrefValue = "";
			}
			$this->aboutimage->HrefValue2 = $this->aboutimage->UploadPath . $this->aboutimage->Upload->DbValue;
			$this->aboutimage->TooltipValue = "";
			if ($this->aboutimage->UseColorbox) {
				$this->aboutimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->aboutimage->LinkAttrs["data-rel"] = "settings_x_aboutimage";

				//$this->aboutimage->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->aboutimage->LinkAttrs["data-placement"] = "bottom";
				//$this->aboutimage->LinkAttrs["data-container"] = "body";

				$this->aboutimage->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// abouttext
			$this->abouttext->LinkCustomAttributes = "";
			$this->abouttext->HrefValue = "";
			$this->abouttext->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// settingsid
			$this->settingsid->EditAttrs["class"] = "form-control";
			$this->settingsid->EditCustomAttributes = "";
			$this->settingsid->EditValue = $this->settingsid->CurrentValue;
			$this->settingsid->ViewCustomAttributes = "";

			// aboutimage
			$this->aboutimage->EditAttrs["class"] = "form-control";
			$this->aboutimage->EditCustomAttributes = "";
			if (!ew_Empty($this->aboutimage->Upload->DbValue)) {
				$this->aboutimage->ImageWidth = 400;
				$this->aboutimage->ImageHeight = 0;
				$this->aboutimage->ImageAlt = $this->aboutimage->FldAlt();
				$this->aboutimage->EditValue = $this->aboutimage->Upload->DbValue;
			} else {
				$this->aboutimage->EditValue = "";
			}
			if (!ew_Empty($this->aboutimage->CurrentValue))
				$this->aboutimage->Upload->FileName = $this->aboutimage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->aboutimage);

			// abouttext
			$this->abouttext->EditAttrs["class"] = "form-control";
			$this->abouttext->EditCustomAttributes = "";
			$this->abouttext->EditValue = ew_HtmlEncode($this->abouttext->CurrentValue);
			$this->abouttext->PlaceHolder = ew_RemoveHtml($this->abouttext->FldCaption());

			// Edit refer script
			// settingsid

			$this->settingsid->HrefValue = "";

			// aboutimage
			if (!ew_Empty($this->aboutimage->Upload->DbValue)) {
				$this->aboutimage->HrefValue = ew_GetFileUploadUrl($this->aboutimage, $this->aboutimage->Upload->DbValue); // Add prefix/suffix
				$this->aboutimage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->aboutimage->HrefValue = ew_ConvertFullUrl($this->aboutimage->HrefValue);
			} else {
				$this->aboutimage->HrefValue = "";
			}
			$this->aboutimage->HrefValue2 = $this->aboutimage->UploadPath . $this->aboutimage->Upload->DbValue;

			// abouttext
			$this->abouttext->HrefValue = "";
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
		if ($this->aboutimage->Upload->FileName == "" && !$this->aboutimage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->aboutimage->FldCaption(), $this->aboutimage->ReqErrMsg));
		}
		if (!$this->abouttext->FldIsDetailKey && !is_null($this->abouttext->FormValue) && $this->abouttext->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->abouttext->FldCaption(), $this->abouttext->ReqErrMsg));
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

			// aboutimage
			if (!($this->aboutimage->ReadOnly) && !$this->aboutimage->Upload->KeepFile) {
				$this->aboutimage->Upload->DbValue = $rsold['aboutimage']; // Get original value
				if ($this->aboutimage->Upload->FileName == "") {
					$rsnew['aboutimage'] = NULL;
				} else {
					$rsnew['aboutimage'] = $this->aboutimage->Upload->FileName;
				}
				$this->aboutimage->ImageWidth = 400; // Resize width
				$this->aboutimage->ImageHeight = 0; // Resize height
			}

			// abouttext
			$this->abouttext->SetDbValueDef($rsnew, $this->abouttext->CurrentValue, "", $this->abouttext->ReadOnly);
			if (!$this->aboutimage->Upload->KeepFile) {
				if (!ew_Empty($this->aboutimage->Upload->Value)) {
					if ($this->aboutimage->Upload->FileName == $this->aboutimage->Upload->DbValue) { // Overwrite if same file name
						$this->aboutimage->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['aboutimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->aboutimage->UploadPath), $rsnew['aboutimage']); // Get new file name
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
					if (!$this->aboutimage->Upload->KeepFile) {
						if (!ew_Empty($this->aboutimage->Upload->Value)) {
							$this->aboutimage->Upload->Resize($this->aboutimage->ImageWidth, $this->aboutimage->ImageHeight);
							$this->aboutimage->Upload->SaveToFile($this->aboutimage->UploadPath, $rsnew['aboutimage'], TRUE);
						}
						if ($this->aboutimage->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->aboutimage->OldUploadPath) . $this->aboutimage->Upload->DbValue);
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

		// aboutimage
		ew_CleanUploadTempPath($this->aboutimage, $this->aboutimage->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "settingslist.php", "", $this->TableVar, TRUE);
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
if (!isset($settings_edit)) $settings_edit = new csettings_edit();

// Page init
$settings_edit->Page_Init();

// Page main
$settings_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$settings_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsettingsedit = new ew_Form("fsettingsedit", "edit");

// Validate form
fsettingsedit.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_aboutimage");
			elm = this.GetElements("fn_x" + infix + "_aboutimage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $settings->aboutimage->FldCaption(), $settings->aboutimage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_abouttext");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $settings->abouttext->FldCaption(), $settings->abouttext->ReqErrMsg)) ?>");

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
fsettingsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsettingsedit.ValidateRequired = true;
<?php } else { ?>
fsettingsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $settings_edit->ShowPageHeader(); ?>
<?php
$settings_edit->ShowMessage();
?>
<form name="fsettingsedit" id="fsettingsedit" class="<?php echo $settings_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($settings_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $settings_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="settings">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($settings->settingsid->Visible) { // settingsid ?>
	<div id="r_settingsid" class="form-group">
		<label id="elh_settings_settingsid" class="col-sm-2 control-label ewLabel"><?php echo $settings->settingsid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $settings->settingsid->CellAttributes() ?>>
<span id="el_settings_settingsid">
<span<?php echo $settings->settingsid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $settings->settingsid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="settings" data-field="x_settingsid" name="x_settingsid" id="x_settingsid" value="<?php echo ew_HtmlEncode($settings->settingsid->CurrentValue) ?>">
<?php echo $settings->settingsid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($settings->aboutimage->Visible) { // aboutimage ?>
	<div id="r_aboutimage" class="form-group">
		<label id="elh_settings_aboutimage" class="col-sm-2 control-label ewLabel"><?php echo $settings->aboutimage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $settings->aboutimage->CellAttributes() ?>>
<span id="el_settings_aboutimage">
<div id="fd_x_aboutimage">
<span title="<?php echo $settings->aboutimage->FldTitle() ? $settings->aboutimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($settings->aboutimage->ReadOnly || $settings->aboutimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="settings" data-field="x_aboutimage" name="x_aboutimage" id="x_aboutimage"<?php echo $settings->aboutimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_aboutimage" id= "fn_x_aboutimage" value="<?php echo $settings->aboutimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_aboutimage"] == "0") { ?>
<input type="hidden" name="fa_x_aboutimage" id= "fa_x_aboutimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_aboutimage" id= "fa_x_aboutimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_aboutimage" id= "fs_x_aboutimage" value="255">
<input type="hidden" name="fx_x_aboutimage" id= "fx_x_aboutimage" value="<?php echo $settings->aboutimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_aboutimage" id= "fm_x_aboutimage" value="<?php echo $settings->aboutimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_aboutimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $settings->aboutimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($settings->abouttext->Visible) { // abouttext ?>
	<div id="r_abouttext" class="form-group">
		<label id="elh_settings_abouttext" for="x_abouttext" class="col-sm-2 control-label ewLabel"><?php echo $settings->abouttext->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $settings->abouttext->CellAttributes() ?>>
<span id="el_settings_abouttext">
<textarea data-table="settings" data-field="x_abouttext" name="x_abouttext" id="x_abouttext" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($settings->abouttext->getPlaceHolder()) ?>"<?php echo $settings->abouttext->EditAttributes() ?>><?php echo $settings->abouttext->EditValue ?></textarea>
</span>
<?php echo $settings->abouttext->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $settings_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsettingsedit.Init();
</script>
<?php
$settings_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$settings_edit->Page_Terminate();
?>
