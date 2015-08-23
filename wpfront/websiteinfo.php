<?php

// Global variable for table object
$website = NULL;

//
// Table class for website
//
class cwebsite extends cTable {
	var $websiteid;
	var $webname;
	var $webdesc;
	var $webimage;
	var $weblink;
	var $createddate;
	var $categoryid;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'website';
		$this->TableName = 'website';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// websiteid
		$this->websiteid = new cField('website', 'website', 'x_websiteid', 'websiteid', '`websiteid`', '`websiteid`', 3, -1, FALSE, '`websiteid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->websiteid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['websiteid'] = &$this->websiteid;

		// webname
		$this->webname = new cField('website', 'website', 'x_webname', 'webname', '`webname`', '`webname`', 200, -1, FALSE, '`webname`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['webname'] = &$this->webname;

		// webdesc
		$this->webdesc = new cField('website', 'website', 'x_webdesc', 'webdesc', '`webdesc`', '`webdesc`', 200, -1, FALSE, '`webdesc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['webdesc'] = &$this->webdesc;

		// webimage
		$this->webimage = new cField('website', 'website', 'x_webimage', 'webimage', '`webimage`', '`webimage`', 200, -1, TRUE, '`webimage`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->webimage->UploadAllowedFileExt = "jpg,png,jpeg";
		$this->fields['webimage'] = &$this->webimage;

		// weblink
		$this->weblink = new cField('website', 'website', 'x_weblink', 'weblink', '`weblink`', '`weblink`', 200, -1, FALSE, '`weblink`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['weblink'] = &$this->weblink;

		// createddate
		$this->createddate = new cField('website', 'website', 'x_createddate', 'createddate', '`createddate`', 'DATE_FORMAT(`createddate`, \'%d/%m/%Y %H:%i:%s\')', 135, 11, FALSE, '`createddate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->createddate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['createddate'] = &$this->createddate;

		// categoryid
		$this->categoryid = new cField('website', 'website', 'x_categoryid', 'categoryid', '`categoryid`', '`categoryid`', 3, -1, FALSE, '`categoryid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->categoryid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['categoryid'] = &$this->categoryid;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`website`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`website`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('websiteid', $rs))
				ew_AddFilter($where, ew_QuotedName('websiteid') . '=' . ew_QuotedValue($rs['websiteid'], $this->websiteid->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`websiteid` = @websiteid@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->websiteid->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@websiteid@", ew_AdjustSql($this->websiteid->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "websitelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "websitelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("websiteview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("websiteview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "websiteadd.php?" . $this->UrlParm($parm);
		else
			return "websiteadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("websiteedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("websiteadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("websitedelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->websiteid->CurrentValue)) {
			$sUrl .= "websiteid=" . urlencode($this->websiteid->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["websiteid"]; // websiteid

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->websiteid->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->websiteid->setDbValue($rs->fields('websiteid'));
		$this->webname->setDbValue($rs->fields('webname'));
		$this->webdesc->setDbValue($rs->fields('webdesc'));
		$this->webimage->Upload->DbValue = $rs->fields('webimage');
		$this->weblink->setDbValue($rs->fields('weblink'));
		$this->createddate->setDbValue($rs->fields('createddate'));
		$this->categoryid->setDbValue($rs->fields('categoryid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// websiteid
		// webname
		// webdesc
		// webimage
		// weblink
		// createddate
		// categoryid
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

		// websiteid
		$this->websiteid->LinkCustomAttributes = "";
		$this->websiteid->HrefValue = "";
		$this->websiteid->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// websiteid
		$this->websiteid->EditAttrs["class"] = "form-control";
		$this->websiteid->EditCustomAttributes = "";
		$this->websiteid->EditValue = $this->websiteid->CurrentValue;
		$this->websiteid->ViewCustomAttributes = "";

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

		// weblink
		$this->weblink->EditAttrs["class"] = "form-control";
		$this->weblink->EditCustomAttributes = "";
		$this->weblink->EditValue = ew_HtmlEncode($this->weblink->CurrentValue);
		$this->weblink->PlaceHolder = ew_RemoveHtml($this->weblink->FldCaption());

		// createddate
		// categoryid

		$this->categoryid->EditAttrs["class"] = "form-control";
		$this->categoryid->EditCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->websiteid->Exportable) $Doc->ExportCaption($this->websiteid);
					if ($this->webname->Exportable) $Doc->ExportCaption($this->webname);
					if ($this->webdesc->Exportable) $Doc->ExportCaption($this->webdesc);
					if ($this->webimage->Exportable) $Doc->ExportCaption($this->webimage);
					if ($this->weblink->Exportable) $Doc->ExportCaption($this->weblink);
					if ($this->createddate->Exportable) $Doc->ExportCaption($this->createddate);
					if ($this->categoryid->Exportable) $Doc->ExportCaption($this->categoryid);
				} else {
					if ($this->websiteid->Exportable) $Doc->ExportCaption($this->websiteid);
					if ($this->webname->Exportable) $Doc->ExportCaption($this->webname);
					if ($this->webdesc->Exportable) $Doc->ExportCaption($this->webdesc);
					if ($this->webimage->Exportable) $Doc->ExportCaption($this->webimage);
					if ($this->weblink->Exportable) $Doc->ExportCaption($this->weblink);
					if ($this->createddate->Exportable) $Doc->ExportCaption($this->createddate);
					if ($this->categoryid->Exportable) $Doc->ExportCaption($this->categoryid);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->websiteid->Exportable) $Doc->ExportField($this->websiteid);
						if ($this->webname->Exportable) $Doc->ExportField($this->webname);
						if ($this->webdesc->Exportable) $Doc->ExportField($this->webdesc);
						if ($this->webimage->Exportable) $Doc->ExportField($this->webimage);
						if ($this->weblink->Exportable) $Doc->ExportField($this->weblink);
						if ($this->createddate->Exportable) $Doc->ExportField($this->createddate);
						if ($this->categoryid->Exportable) $Doc->ExportField($this->categoryid);
					} else {
						if ($this->websiteid->Exportable) $Doc->ExportField($this->websiteid);
						if ($this->webname->Exportable) $Doc->ExportField($this->webname);
						if ($this->webdesc->Exportable) $Doc->ExportField($this->webdesc);
						if ($this->webimage->Exportable) $Doc->ExportField($this->webimage);
						if ($this->weblink->Exportable) $Doc->ExportField($this->weblink);
						if ($this->createddate->Exportable) $Doc->ExportField($this->createddate);
						if ($this->categoryid->Exportable) $Doc->ExportField($this->categoryid);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
