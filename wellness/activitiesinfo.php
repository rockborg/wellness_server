<?php

// Global variable for table object
$activities = NULL;

//
// Table class for activities
//
class cactivities extends cTable {
	var $activitiesid;
	var $acttitle;
	var $actimage;
	var $actdesc;
	var $actlink;
	var $dateadded;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'activities';
		$this->TableName = 'activities';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`activities`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
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

		// activitiesid
		$this->activitiesid = new cField('activities', 'activities', 'x_activitiesid', 'activitiesid', '`activitiesid`', '`activitiesid`', 3, -1, FALSE, '`activitiesid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->activitiesid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activitiesid'] = &$this->activitiesid;

		// acttitle
		$this->acttitle = new cField('activities', 'activities', 'x_acttitle', 'acttitle', '`acttitle`', '`acttitle`', 200, -1, FALSE, '`acttitle`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['acttitle'] = &$this->acttitle;

		// actimage
		$this->actimage = new cField('activities', 'activities', 'x_actimage', 'actimage', '`actimage`', '`actimage`', 200, -1, TRUE, '`actimage`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->fields['actimage'] = &$this->actimage;

		// actdesc
		$this->actdesc = new cField('activities', 'activities', 'x_actdesc', 'actdesc', '`actdesc`', '`actdesc`', 201, -1, FALSE, '`actdesc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['actdesc'] = &$this->actdesc;

		// actlink
		$this->actlink = new cField('activities', 'activities', 'x_actlink', 'actlink', '`actlink`', '`actlink`', 200, -1, FALSE, '`actlink`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['actlink'] = &$this->actlink;

		// dateadded
		$this->dateadded = new cField('activities', 'activities', 'x_dateadded', 'dateadded', '`dateadded`', 'DATE_FORMAT(`dateadded`, \'%Y/%m/%d\')', 135, 5, FALSE, '`dateadded`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->dateadded->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['dateadded'] = &$this->dateadded;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`activities`";
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
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
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
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('activitiesid', $rs))
				ew_AddFilter($where, ew_QuotedName('activitiesid', $this->DBID) . '=' . ew_QuotedValue($rs['activitiesid'], $this->activitiesid->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`activitiesid` = @activitiesid@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->activitiesid->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@activitiesid@", ew_AdjustSql($this->activitiesid->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "activitieslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "activitieslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("activitiesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("activitiesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "activitiesadd.php?" . $this->UrlParm($parm);
		else
			return "activitiesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("activitiesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("activitiesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("activitiesdelete.php", $this->UrlParm());
	}

	function KeyToJson() {
		$json = "";
		$json .= "activitiesid:" . ew_VarToJson($this->activitiesid->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->activitiesid->CurrentValue)) {
			$sUrl .= "activitiesid=" . urlencode($this->activitiesid->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
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
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["activitiesid"]) : ew_StripSlashes(@$_GET["activitiesid"]); // activitiesid

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
			$this->activitiesid->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->activitiesid->setDbValue($rs->fields('activitiesid'));
		$this->acttitle->setDbValue($rs->fields('acttitle'));
		$this->actimage->Upload->DbValue = $rs->fields('actimage');
		$this->actdesc->setDbValue($rs->fields('actdesc'));
		$this->actlink->setDbValue($rs->fields('actlink'));
		$this->dateadded->setDbValue($rs->fields('dateadded'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// activitiesid
		// acttitle
		// actimage
		// actdesc
		// actlink
		// dateadded
		// activitiesid

		$this->activitiesid->ViewValue = $this->activitiesid->CurrentValue;
		$this->activitiesid->ViewCustomAttributes = "";

		// acttitle
		$this->acttitle->ViewValue = $this->acttitle->CurrentValue;
		$this->acttitle->ViewCustomAttributes = "";

		// actimage
		if (!ew_Empty($this->actimage->Upload->DbValue)) {
			$this->actimage->ImageWidth = 400;
			$this->actimage->ImageHeight = 0;
			$this->actimage->ImageAlt = $this->actimage->FldAlt();
			$this->actimage->ViewValue = $this->actimage->Upload->DbValue;
		} else {
			$this->actimage->ViewValue = "";
		}
		$this->actimage->ViewCustomAttributes = "";

		// actdesc
		$this->actdesc->ViewValue = $this->actdesc->CurrentValue;
		$this->actdesc->ViewCustomAttributes = "";

		// actlink
		$this->actlink->ViewValue = $this->actlink->CurrentValue;
		$this->actlink->ViewCustomAttributes = "";

		// dateadded
		$this->dateadded->ViewValue = $this->dateadded->CurrentValue;
		$this->dateadded->ViewValue = ew_FormatDateTime($this->dateadded->ViewValue, 5);
		$this->dateadded->ViewCustomAttributes = "";

		// activitiesid
		$this->activitiesid->LinkCustomAttributes = "";
		$this->activitiesid->HrefValue = "";
		$this->activitiesid->TooltipValue = "";

		// acttitle
		$this->acttitle->LinkCustomAttributes = "";
		$this->acttitle->HrefValue = "";
		$this->acttitle->TooltipValue = "";

		// actimage
		$this->actimage->LinkCustomAttributes = "";
		if (!ew_Empty($this->actimage->Upload->DbValue)) {
			$this->actimage->HrefValue = ew_GetFileUploadUrl($this->actimage, $this->actimage->Upload->DbValue); // Add prefix/suffix
			$this->actimage->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->actimage->HrefValue = ew_ConvertFullUrl($this->actimage->HrefValue);
		} else {
			$this->actimage->HrefValue = "";
		}
		$this->actimage->HrefValue2 = $this->actimage->UploadPath . $this->actimage->Upload->DbValue;
		$this->actimage->TooltipValue = "";
		if ($this->actimage->UseColorbox) {
			$this->actimage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->actimage->LinkAttrs["data-rel"] = "activities_x_actimage";

			//$this->actimage->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
			//$this->actimage->LinkAttrs["data-placement"] = "bottom";
			//$this->actimage->LinkAttrs["data-container"] = "body";

			$this->actimage->LinkAttrs["class"] = "ewLightbox img-thumbnail";
		}

		// actdesc
		$this->actdesc->LinkCustomAttributes = "";
		$this->actdesc->HrefValue = "";
		$this->actdesc->TooltipValue = "";

		// actlink
		$this->actlink->LinkCustomAttributes = "";
		$this->actlink->HrefValue = "";
		$this->actlink->TooltipValue = "";

		// dateadded
		$this->dateadded->LinkCustomAttributes = "";
		$this->dateadded->HrefValue = "";
		$this->dateadded->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// activitiesid
		$this->activitiesid->EditAttrs["class"] = "form-control";
		$this->activitiesid->EditCustomAttributes = "";
		$this->activitiesid->EditValue = $this->activitiesid->CurrentValue;
		$this->activitiesid->ViewCustomAttributes = "";

		// acttitle
		$this->acttitle->EditAttrs["class"] = "form-control";
		$this->acttitle->EditCustomAttributes = "";
		$this->acttitle->EditValue = $this->acttitle->CurrentValue;
		$this->acttitle->PlaceHolder = ew_RemoveHtml($this->acttitle->FldCaption());

		// actimage
		$this->actimage->EditAttrs["class"] = "form-control";
		$this->actimage->EditCustomAttributes = "";
		if (!ew_Empty($this->actimage->Upload->DbValue)) {
			$this->actimage->ImageWidth = 400;
			$this->actimage->ImageHeight = 0;
			$this->actimage->ImageAlt = $this->actimage->FldAlt();
			$this->actimage->EditValue = $this->actimage->Upload->DbValue;
		} else {
			$this->actimage->EditValue = "";
		}
		if (!ew_Empty($this->actimage->CurrentValue))
			$this->actimage->Upload->FileName = $this->actimage->CurrentValue;

		// actdesc
		$this->actdesc->EditAttrs["class"] = "form-control";
		$this->actdesc->EditCustomAttributes = "";
		$this->actdesc->EditValue = $this->actdesc->CurrentValue;
		$this->actdesc->PlaceHolder = ew_RemoveHtml($this->actdesc->FldCaption());

		// actlink
		$this->actlink->EditAttrs["class"] = "form-control";
		$this->actlink->EditCustomAttributes = "";
		$this->actlink->EditValue = $this->actlink->CurrentValue;
		$this->actlink->PlaceHolder = ew_RemoveHtml($this->actlink->FldCaption());

		// dateadded
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
					if ($this->activitiesid->Exportable) $Doc->ExportCaption($this->activitiesid);
					if ($this->acttitle->Exportable) $Doc->ExportCaption($this->acttitle);
					if ($this->actimage->Exportable) $Doc->ExportCaption($this->actimage);
					if ($this->actdesc->Exportable) $Doc->ExportCaption($this->actdesc);
					if ($this->actlink->Exportable) $Doc->ExportCaption($this->actlink);
					if ($this->dateadded->Exportable) $Doc->ExportCaption($this->dateadded);
				} else {
					if ($this->activitiesid->Exportable) $Doc->ExportCaption($this->activitiesid);
					if ($this->acttitle->Exportable) $Doc->ExportCaption($this->acttitle);
					if ($this->actimage->Exportable) $Doc->ExportCaption($this->actimage);
					if ($this->actlink->Exportable) $Doc->ExportCaption($this->actlink);
					if ($this->dateadded->Exportable) $Doc->ExportCaption($this->dateadded);
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
						if ($this->activitiesid->Exportable) $Doc->ExportField($this->activitiesid);
						if ($this->acttitle->Exportable) $Doc->ExportField($this->acttitle);
						if ($this->actimage->Exportable) $Doc->ExportField($this->actimage);
						if ($this->actdesc->Exportable) $Doc->ExportField($this->actdesc);
						if ($this->actlink->Exportable) $Doc->ExportField($this->actlink);
						if ($this->dateadded->Exportable) $Doc->ExportField($this->dateadded);
					} else {
						if ($this->activitiesid->Exportable) $Doc->ExportField($this->activitiesid);
						if ($this->acttitle->Exportable) $Doc->ExportField($this->acttitle);
						if ($this->actimage->Exportable) $Doc->ExportField($this->actimage);
						if ($this->actlink->Exportable) $Doc->ExportField($this->actlink);
						if ($this->dateadded->Exportable) $Doc->ExportField($this->dateadded);
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

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
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
