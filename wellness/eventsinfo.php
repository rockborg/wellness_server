<?php

// Global variable for table object
$events = NULL;

//
// Table class for events
//
class cevents extends cTable {
	var $eventsid;
	var $eventtitle;
	var $eventhost;
	var $eventaddress;
	var $eventcity;
	var $eventdate;
	var $eventtime;
	var $eventcategory;
	var $eventdesc;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'events';
		$this->TableName = 'events';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`events`";
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

		// eventsid
		$this->eventsid = new cField('events', 'events', 'x_eventsid', 'eventsid', '`eventsid`', '`eventsid`', 3, -1, FALSE, '`eventsid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->eventsid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['eventsid'] = &$this->eventsid;

		// eventtitle
		$this->eventtitle = new cField('events', 'events', 'x_eventtitle', 'eventtitle', '`eventtitle`', '`eventtitle`', 200, -1, FALSE, '`eventtitle`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['eventtitle'] = &$this->eventtitle;

		// eventhost
		$this->eventhost = new cField('events', 'events', 'x_eventhost', 'eventhost', '`eventhost`', '`eventhost`', 200, -1, FALSE, '`eventhost`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['eventhost'] = &$this->eventhost;

		// eventaddress
		$this->eventaddress = new cField('events', 'events', 'x_eventaddress', 'eventaddress', '`eventaddress`', '`eventaddress`', 200, -1, FALSE, '`eventaddress`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['eventaddress'] = &$this->eventaddress;

		// eventcity
		$this->eventcity = new cField('events', 'events', 'x_eventcity', 'eventcity', '`eventcity`', '`eventcity`', 200, -1, FALSE, '`eventcity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['eventcity'] = &$this->eventcity;

		// eventdate
		$this->eventdate = new cField('events', 'events', 'x_eventdate', 'eventdate', '`eventdate`', 'DATE_FORMAT(`eventdate`, \'%Y/%m/%d\')', 133, 5, FALSE, '`eventdate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->eventdate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['eventdate'] = &$this->eventdate;

		// eventtime
		$this->eventtime = new cField('events', 'events', 'x_eventtime', 'eventtime', '`eventtime`', 'DATE_FORMAT(`eventtime`, \'%Y/%m/%d\')', 134, -1, FALSE, '`eventtime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->eventtime->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['eventtime'] = &$this->eventtime;

		// eventcategory
		$this->eventcategory = new cField('events', 'events', 'x_eventcategory', 'eventcategory', '`eventcategory`', '`eventcategory`', 200, -1, FALSE, '`eventcategory`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['eventcategory'] = &$this->eventcategory;

		// eventdesc
		$this->eventdesc = new cField('events', 'events', 'x_eventdesc', 'eventdesc', '`eventdesc`', '`eventdesc`', 201, -1, FALSE, '`eventdesc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['eventdesc'] = &$this->eventdesc;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`events`";
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
			if (array_key_exists('eventsid', $rs))
				ew_AddFilter($where, ew_QuotedName('eventsid', $this->DBID) . '=' . ew_QuotedValue($rs['eventsid'], $this->eventsid->FldDataType, $this->DBID));
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
		return "`eventsid` = @eventsid@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->eventsid->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@eventsid@", ew_AdjustSql($this->eventsid->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "eventslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "eventslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("eventsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("eventsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "eventsadd.php?" . $this->UrlParm($parm);
		else
			return "eventsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("eventsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("eventsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("eventsdelete.php", $this->UrlParm());
	}

	function KeyToJson() {
		$json = "";
		$json .= "eventsid:" . ew_VarToJson($this->eventsid->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->eventsid->CurrentValue)) {
			$sUrl .= "eventsid=" . urlencode($this->eventsid->CurrentValue);
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
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["eventsid"]) : ew_StripSlashes(@$_GET["eventsid"]); // eventsid

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
			$this->eventsid->CurrentValue = $key;
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
		$this->eventsid->setDbValue($rs->fields('eventsid'));
		$this->eventtitle->setDbValue($rs->fields('eventtitle'));
		$this->eventhost->setDbValue($rs->fields('eventhost'));
		$this->eventaddress->setDbValue($rs->fields('eventaddress'));
		$this->eventcity->setDbValue($rs->fields('eventcity'));
		$this->eventdate->setDbValue($rs->fields('eventdate'));
		$this->eventtime->setDbValue($rs->fields('eventtime'));
		$this->eventcategory->setDbValue($rs->fields('eventcategory'));
		$this->eventdesc->setDbValue($rs->fields('eventdesc'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// eventsid
		// eventtitle
		// eventhost
		// eventaddress
		// eventcity
		// eventdate
		// eventtime
		// eventcategory
		// eventdesc
		// eventsid

		$this->eventsid->ViewValue = $this->eventsid->CurrentValue;
		$this->eventsid->ViewCustomAttributes = "";

		// eventtitle
		$this->eventtitle->ViewValue = $this->eventtitle->CurrentValue;
		$this->eventtitle->ViewCustomAttributes = "";

		// eventhost
		$this->eventhost->ViewValue = $this->eventhost->CurrentValue;
		$this->eventhost->ViewCustomAttributes = "";

		// eventaddress
		$this->eventaddress->ViewValue = $this->eventaddress->CurrentValue;
		$this->eventaddress->ViewCustomAttributes = "";

		// eventcity
		$this->eventcity->ViewValue = $this->eventcity->CurrentValue;
		$this->eventcity->ViewCustomAttributes = "";

		// eventdate
		$this->eventdate->ViewValue = $this->eventdate->CurrentValue;
		$this->eventdate->ViewValue = ew_FormatDateTime($this->eventdate->ViewValue, 5);
		$this->eventdate->ViewCustomAttributes = "";

		// eventtime
		$this->eventtime->ViewValue = $this->eventtime->CurrentValue;
		$this->eventtime->ViewCustomAttributes = "";

		// eventcategory
		if (strval($this->eventcategory->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->eventcategory->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->eventcategory, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->eventcategory->ViewValue = $this->eventcategory->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->eventcategory->ViewValue = $this->eventcategory->CurrentValue;
			}
		} else {
			$this->eventcategory->ViewValue = NULL;
		}
		$this->eventcategory->ViewCustomAttributes = "";

		// eventdesc
		$this->eventdesc->ViewValue = $this->eventdesc->CurrentValue;
		$this->eventdesc->ViewCustomAttributes = "";

		// eventsid
		$this->eventsid->LinkCustomAttributes = "";
		$this->eventsid->HrefValue = "";
		$this->eventsid->TooltipValue = "";

		// eventtitle
		$this->eventtitle->LinkCustomAttributes = "";
		$this->eventtitle->HrefValue = "";
		$this->eventtitle->TooltipValue = "";

		// eventhost
		$this->eventhost->LinkCustomAttributes = "";
		$this->eventhost->HrefValue = "";
		$this->eventhost->TooltipValue = "";

		// eventaddress
		$this->eventaddress->LinkCustomAttributes = "";
		$this->eventaddress->HrefValue = "";
		$this->eventaddress->TooltipValue = "";

		// eventcity
		$this->eventcity->LinkCustomAttributes = "";
		$this->eventcity->HrefValue = "";
		$this->eventcity->TooltipValue = "";

		// eventdate
		$this->eventdate->LinkCustomAttributes = "";
		$this->eventdate->HrefValue = "";
		$this->eventdate->TooltipValue = "";

		// eventtime
		$this->eventtime->LinkCustomAttributes = "";
		$this->eventtime->HrefValue = "";
		$this->eventtime->TooltipValue = "";

		// eventcategory
		$this->eventcategory->LinkCustomAttributes = "";
		$this->eventcategory->HrefValue = "";
		$this->eventcategory->TooltipValue = "";

		// eventdesc
		$this->eventdesc->LinkCustomAttributes = "";
		$this->eventdesc->HrefValue = "";
		$this->eventdesc->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// eventsid
		$this->eventsid->EditAttrs["class"] = "form-control";
		$this->eventsid->EditCustomAttributes = "";
		$this->eventsid->EditValue = $this->eventsid->CurrentValue;
		$this->eventsid->ViewCustomAttributes = "";

		// eventtitle
		$this->eventtitle->EditAttrs["class"] = "form-control";
		$this->eventtitle->EditCustomAttributes = "";
		$this->eventtitle->EditValue = $this->eventtitle->CurrentValue;
		$this->eventtitle->PlaceHolder = ew_RemoveHtml($this->eventtitle->FldCaption());

		// eventhost
		$this->eventhost->EditAttrs["class"] = "form-control";
		$this->eventhost->EditCustomAttributes = "";
		$this->eventhost->EditValue = $this->eventhost->CurrentValue;
		$this->eventhost->PlaceHolder = ew_RemoveHtml($this->eventhost->FldCaption());

		// eventaddress
		$this->eventaddress->EditAttrs["class"] = "form-control";
		$this->eventaddress->EditCustomAttributes = "";
		$this->eventaddress->EditValue = $this->eventaddress->CurrentValue;
		$this->eventaddress->PlaceHolder = ew_RemoveHtml($this->eventaddress->FldCaption());

		// eventcity
		$this->eventcity->EditAttrs["class"] = "form-control";
		$this->eventcity->EditCustomAttributes = "";
		$this->eventcity->EditValue = $this->eventcity->CurrentValue;
		$this->eventcity->PlaceHolder = ew_RemoveHtml($this->eventcity->FldCaption());

		// eventdate
		$this->eventdate->EditAttrs["class"] = "form-control";
		$this->eventdate->EditCustomAttributes = "";
		$this->eventdate->EditValue = ew_FormatDateTime($this->eventdate->CurrentValue, 5);
		$this->eventdate->PlaceHolder = ew_RemoveHtml($this->eventdate->FldCaption());

		// eventtime
		$this->eventtime->EditAttrs["class"] = "form-control";
		$this->eventtime->EditCustomAttributes = "";
		$this->eventtime->EditValue = $this->eventtime->CurrentValue;
		$this->eventtime->PlaceHolder = ew_RemoveHtml($this->eventtime->FldCaption());

		// eventcategory
		$this->eventcategory->EditAttrs["class"] = "form-control";
		$this->eventcategory->EditCustomAttributes = "";

		// eventdesc
		$this->eventdesc->EditAttrs["class"] = "form-control";
		$this->eventdesc->EditCustomAttributes = "";
		$this->eventdesc->EditValue = $this->eventdesc->CurrentValue;
		$this->eventdesc->PlaceHolder = ew_RemoveHtml($this->eventdesc->FldCaption());

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
					if ($this->eventsid->Exportable) $Doc->ExportCaption($this->eventsid);
					if ($this->eventtitle->Exportable) $Doc->ExportCaption($this->eventtitle);
					if ($this->eventhost->Exportable) $Doc->ExportCaption($this->eventhost);
					if ($this->eventaddress->Exportable) $Doc->ExportCaption($this->eventaddress);
					if ($this->eventcity->Exportable) $Doc->ExportCaption($this->eventcity);
					if ($this->eventdate->Exportable) $Doc->ExportCaption($this->eventdate);
					if ($this->eventtime->Exportable) $Doc->ExportCaption($this->eventtime);
					if ($this->eventcategory->Exportable) $Doc->ExportCaption($this->eventcategory);
					if ($this->eventdesc->Exportable) $Doc->ExportCaption($this->eventdesc);
				} else {
					if ($this->eventsid->Exportable) $Doc->ExportCaption($this->eventsid);
					if ($this->eventtitle->Exportable) $Doc->ExportCaption($this->eventtitle);
					if ($this->eventhost->Exportable) $Doc->ExportCaption($this->eventhost);
					if ($this->eventaddress->Exportable) $Doc->ExportCaption($this->eventaddress);
					if ($this->eventcity->Exportable) $Doc->ExportCaption($this->eventcity);
					if ($this->eventdate->Exportable) $Doc->ExportCaption($this->eventdate);
					if ($this->eventtime->Exportable) $Doc->ExportCaption($this->eventtime);
					if ($this->eventcategory->Exportable) $Doc->ExportCaption($this->eventcategory);
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
						if ($this->eventsid->Exportable) $Doc->ExportField($this->eventsid);
						if ($this->eventtitle->Exportable) $Doc->ExportField($this->eventtitle);
						if ($this->eventhost->Exportable) $Doc->ExportField($this->eventhost);
						if ($this->eventaddress->Exportable) $Doc->ExportField($this->eventaddress);
						if ($this->eventcity->Exportable) $Doc->ExportField($this->eventcity);
						if ($this->eventdate->Exportable) $Doc->ExportField($this->eventdate);
						if ($this->eventtime->Exportable) $Doc->ExportField($this->eventtime);
						if ($this->eventcategory->Exportable) $Doc->ExportField($this->eventcategory);
						if ($this->eventdesc->Exportable) $Doc->ExportField($this->eventdesc);
					} else {
						if ($this->eventsid->Exportable) $Doc->ExportField($this->eventsid);
						if ($this->eventtitle->Exportable) $Doc->ExportField($this->eventtitle);
						if ($this->eventhost->Exportable) $Doc->ExportField($this->eventhost);
						if ($this->eventaddress->Exportable) $Doc->ExportField($this->eventaddress);
						if ($this->eventcity->Exportable) $Doc->ExportField($this->eventcity);
						if ($this->eventdate->Exportable) $Doc->ExportField($this->eventdate);
						if ($this->eventtime->Exportable) $Doc->ExportField($this->eventtime);
						if ($this->eventcategory->Exportable) $Doc->ExportField($this->eventcategory);
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
