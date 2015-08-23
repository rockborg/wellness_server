<?php

// Global variable for table object
$services = NULL;

//
// Table class for services
//
class cservices extends cTable {
	var $servicesid;
	var $servicename;
	var $facility;
	var $community;
	var $address_street;
	var $address_city;
	var $address_postcode;
	var $phone;
	var $website;
	var $peopleserved_gender;
	var $peopleserved_age;
	var $programtype;
	var $programfocus;
	var $zone;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'services';
		$this->TableName = 'services';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`services`";
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

		// servicesid
		$this->servicesid = new cField('services', 'services', 'x_servicesid', 'servicesid', '`servicesid`', '`servicesid`', 3, -1, FALSE, '`servicesid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->servicesid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['servicesid'] = &$this->servicesid;

		// servicename
		$this->servicename = new cField('services', 'services', 'x_servicename', 'servicename', '`servicename`', '`servicename`', 200, -1, FALSE, '`servicename`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['servicename'] = &$this->servicename;

		// facility
		$this->facility = new cField('services', 'services', 'x_facility', 'facility', '`facility`', '`facility`', 200, -1, FALSE, '`facility`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['facility'] = &$this->facility;

		// community
		$this->community = new cField('services', 'services', 'x_community', 'community', '`community`', '`community`', 200, -1, FALSE, '`community`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['community'] = &$this->community;

		// address_street
		$this->address_street = new cField('services', 'services', 'x_address_street', 'address_street', '`address_street`', '`address_street`', 200, -1, FALSE, '`address_street`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['address_street'] = &$this->address_street;

		// address_city
		$this->address_city = new cField('services', 'services', 'x_address_city', 'address_city', '`address_city`', '`address_city`', 200, -1, FALSE, '`address_city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['address_city'] = &$this->address_city;

		// address_postcode
		$this->address_postcode = new cField('services', 'services', 'x_address_postcode', 'address_postcode', '`address_postcode`', '`address_postcode`', 200, -1, FALSE, '`address_postcode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['address_postcode'] = &$this->address_postcode;

		// phone
		$this->phone = new cField('services', 'services', 'x_phone', 'phone', '`phone`', '`phone`', 200, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['phone'] = &$this->phone;

		// website
		$this->website = new cField('services', 'services', 'x_website', 'website', '`website`', '`website`', 200, -1, FALSE, '`website`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['website'] = &$this->website;

		// peopleserved_gender
		$this->peopleserved_gender = new cField('services', 'services', 'x_peopleserved_gender', 'peopleserved_gender', '`peopleserved_gender`', '`peopleserved_gender`', 200, -1, FALSE, '`peopleserved_gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->peopleserved_gender->OptionCount = 2;
		$this->fields['peopleserved_gender'] = &$this->peopleserved_gender;

		// peopleserved_age
		$this->peopleserved_age = new cField('services', 'services', 'x_peopleserved_age', 'peopleserved_age', '`peopleserved_age`', '`peopleserved_age`', 200, -1, FALSE, '`peopleserved_age`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['peopleserved_age'] = &$this->peopleserved_age;

		// programtype
		$this->programtype = new cField('services', 'services', 'x_programtype', 'programtype', '`programtype`', '`programtype`', 200, -1, FALSE, '`programtype`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['programtype'] = &$this->programtype;

		// programfocus
		$this->programfocus = new cField('services', 'services', 'x_programfocus', 'programfocus', '`programfocus`', '`programfocus`', 200, -1, FALSE, '`programfocus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['programfocus'] = &$this->programfocus;

		// zone
		$this->zone = new cField('services', 'services', 'x_zone', 'zone', '`zone`', '`zone`', 200, -1, FALSE, '`zone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->fields['zone'] = &$this->zone;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`services`";
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
			if (array_key_exists('servicesid', $rs))
				ew_AddFilter($where, ew_QuotedName('servicesid', $this->DBID) . '=' . ew_QuotedValue($rs['servicesid'], $this->servicesid->FldDataType, $this->DBID));
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
		return "`servicesid` = @servicesid@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->servicesid->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@servicesid@", ew_AdjustSql($this->servicesid->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "serviceslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "serviceslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("servicesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("servicesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "servicesadd.php?" . $this->UrlParm($parm);
		else
			return "servicesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("servicesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("servicesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("servicesdelete.php", $this->UrlParm());
	}

	function KeyToJson() {
		$json = "";
		$json .= "servicesid:" . ew_VarToJson($this->servicesid->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->servicesid->CurrentValue)) {
			$sUrl .= "servicesid=" . urlencode($this->servicesid->CurrentValue);
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
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["servicesid"]) : ew_StripSlashes(@$_GET["servicesid"]); // servicesid

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
			$this->servicesid->CurrentValue = $key;
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
		$this->servicesid->setDbValue($rs->fields('servicesid'));
		$this->servicename->setDbValue($rs->fields('servicename'));
		$this->facility->setDbValue($rs->fields('facility'));
		$this->community->setDbValue($rs->fields('community'));
		$this->address_street->setDbValue($rs->fields('address_street'));
		$this->address_city->setDbValue($rs->fields('address_city'));
		$this->address_postcode->setDbValue($rs->fields('address_postcode'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->website->setDbValue($rs->fields('website'));
		$this->peopleserved_gender->setDbValue($rs->fields('peopleserved_gender'));
		$this->peopleserved_age->setDbValue($rs->fields('peopleserved_age'));
		$this->programtype->setDbValue($rs->fields('programtype'));
		$this->programfocus->setDbValue($rs->fields('programfocus'));
		$this->zone->setDbValue($rs->fields('zone'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// servicesid
		// servicename
		// facility
		// community
		// address_street
		// address_city
		// address_postcode
		// phone
		// website
		// peopleserved_gender
		// peopleserved_age
		// programtype
		// programfocus
		// zone
		// servicesid

		$this->servicesid->ViewValue = $this->servicesid->CurrentValue;
		$this->servicesid->ViewCustomAttributes = "";

		// servicename
		$this->servicename->ViewValue = $this->servicename->CurrentValue;
		$this->servicename->ViewCustomAttributes = "";

		// facility
		$this->facility->ViewValue = $this->facility->CurrentValue;
		$this->facility->ViewCustomAttributes = "";

		// community
		$this->community->ViewValue = $this->community->CurrentValue;
		$this->community->ViewCustomAttributes = "";

		// address_street
		$this->address_street->ViewValue = $this->address_street->CurrentValue;
		$this->address_street->ViewCustomAttributes = "";

		// address_city
		$this->address_city->ViewValue = $this->address_city->CurrentValue;
		$this->address_city->ViewCustomAttributes = "";

		// address_postcode
		$this->address_postcode->ViewValue = $this->address_postcode->CurrentValue;
		$this->address_postcode->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// peopleserved_gender
		if (strval($this->peopleserved_gender->CurrentValue) <> "") {
			$this->peopleserved_gender->ViewValue = $this->peopleserved_gender->OptionCaption($this->peopleserved_gender->CurrentValue);
		} else {
			$this->peopleserved_gender->ViewValue = NULL;
		}
		$this->peopleserved_gender->ViewCustomAttributes = "";

		// peopleserved_age
		if (strval($this->peopleserved_age->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->peopleserved_age->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_age`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->peopleserved_age, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->peopleserved_age->ViewValue = $this->peopleserved_age->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->peopleserved_age->ViewValue = $this->peopleserved_age->CurrentValue;
			}
		} else {
			$this->peopleserved_age->ViewValue = NULL;
		}
		$this->peopleserved_age->ViewCustomAttributes = "";

		// programtype
		if (strval($this->programtype->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->programtype->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_prgtype`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->programtype, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->programtype->ViewValue = $this->programtype->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->programtype->ViewValue = $this->programtype->CurrentValue;
			}
		} else {
			$this->programtype->ViewValue = NULL;
		}
		$this->programtype->ViewCustomAttributes = "";

		// programfocus
		if (strval($this->programfocus->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->programfocus->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_focus`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->programfocus, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->programfocus->ViewValue = $this->programfocus->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->programfocus->ViewValue = $this->programfocus->CurrentValue;
			}
		} else {
			$this->programfocus->ViewValue = NULL;
		}
		$this->programfocus->ViewCustomAttributes = "";

		// zone
		if (strval($this->zone->CurrentValue) <> "") {
			$sFilterWrk = "`name`" . ew_SearchString("=", $this->zone->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `name`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `lst_zone`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->zone, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->zone->ViewValue = $this->zone->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->zone->ViewValue = $this->zone->CurrentValue;
			}
		} else {
			$this->zone->ViewValue = NULL;
		}
		$this->zone->ViewCustomAttributes = "";

		// servicesid
		$this->servicesid->LinkCustomAttributes = "";
		$this->servicesid->HrefValue = "";
		$this->servicesid->TooltipValue = "";

		// servicename
		$this->servicename->LinkCustomAttributes = "";
		$this->servicename->HrefValue = "";
		$this->servicename->TooltipValue = "";

		// facility
		$this->facility->LinkCustomAttributes = "";
		$this->facility->HrefValue = "";
		$this->facility->TooltipValue = "";

		// community
		$this->community->LinkCustomAttributes = "";
		$this->community->HrefValue = "";
		$this->community->TooltipValue = "";

		// address_street
		$this->address_street->LinkCustomAttributes = "";
		$this->address_street->HrefValue = "";
		$this->address_street->TooltipValue = "";

		// address_city
		$this->address_city->LinkCustomAttributes = "";
		$this->address_city->HrefValue = "";
		$this->address_city->TooltipValue = "";

		// address_postcode
		$this->address_postcode->LinkCustomAttributes = "";
		$this->address_postcode->HrefValue = "";
		$this->address_postcode->TooltipValue = "";

		// phone
		$this->phone->LinkCustomAttributes = "";
		$this->phone->HrefValue = "";
		$this->phone->TooltipValue = "";

		// website
		$this->website->LinkCustomAttributes = "";
		$this->website->HrefValue = "";
		$this->website->TooltipValue = "";

		// peopleserved_gender
		$this->peopleserved_gender->LinkCustomAttributes = "";
		$this->peopleserved_gender->HrefValue = "";
		$this->peopleserved_gender->TooltipValue = "";

		// peopleserved_age
		$this->peopleserved_age->LinkCustomAttributes = "";
		$this->peopleserved_age->HrefValue = "";
		$this->peopleserved_age->TooltipValue = "";

		// programtype
		$this->programtype->LinkCustomAttributes = "";
		$this->programtype->HrefValue = "";
		$this->programtype->TooltipValue = "";

		// programfocus
		$this->programfocus->LinkCustomAttributes = "";
		$this->programfocus->HrefValue = "";
		$this->programfocus->TooltipValue = "";

		// zone
		$this->zone->LinkCustomAttributes = "";
		$this->zone->HrefValue = "";
		$this->zone->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// servicesid
		$this->servicesid->EditAttrs["class"] = "form-control";
		$this->servicesid->EditCustomAttributes = "";
		$this->servicesid->EditValue = $this->servicesid->CurrentValue;
		$this->servicesid->ViewCustomAttributes = "";

		// servicename
		$this->servicename->EditAttrs["class"] = "form-control";
		$this->servicename->EditCustomAttributes = "";
		$this->servicename->EditValue = $this->servicename->CurrentValue;
		$this->servicename->PlaceHolder = ew_RemoveHtml($this->servicename->FldCaption());

		// facility
		$this->facility->EditAttrs["class"] = "form-control";
		$this->facility->EditCustomAttributes = "";
		$this->facility->EditValue = $this->facility->CurrentValue;
		$this->facility->PlaceHolder = ew_RemoveHtml($this->facility->FldCaption());

		// community
		$this->community->EditAttrs["class"] = "form-control";
		$this->community->EditCustomAttributes = "";
		$this->community->EditValue = $this->community->CurrentValue;
		$this->community->PlaceHolder = ew_RemoveHtml($this->community->FldCaption());

		// address_street
		$this->address_street->EditAttrs["class"] = "form-control";
		$this->address_street->EditCustomAttributes = "";
		$this->address_street->EditValue = $this->address_street->CurrentValue;
		$this->address_street->PlaceHolder = ew_RemoveHtml($this->address_street->FldCaption());

		// address_city
		$this->address_city->EditAttrs["class"] = "form-control";
		$this->address_city->EditCustomAttributes = "";
		$this->address_city->EditValue = $this->address_city->CurrentValue;
		$this->address_city->PlaceHolder = ew_RemoveHtml($this->address_city->FldCaption());

		// address_postcode
		$this->address_postcode->EditAttrs["class"] = "form-control";
		$this->address_postcode->EditCustomAttributes = "";
		$this->address_postcode->EditValue = $this->address_postcode->CurrentValue;
		$this->address_postcode->PlaceHolder = ew_RemoveHtml($this->address_postcode->FldCaption());

		// phone
		$this->phone->EditAttrs["class"] = "form-control";
		$this->phone->EditCustomAttributes = "";
		$this->phone->EditValue = $this->phone->CurrentValue;
		$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

		// website
		$this->website->EditAttrs["class"] = "form-control";
		$this->website->EditCustomAttributes = "";
		$this->website->EditValue = $this->website->CurrentValue;
		$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

		// peopleserved_gender
		$this->peopleserved_gender->EditAttrs["class"] = "form-control";
		$this->peopleserved_gender->EditCustomAttributes = "";
		$this->peopleserved_gender->EditValue = $this->peopleserved_gender->Options(TRUE);

		// peopleserved_age
		$this->peopleserved_age->EditAttrs["class"] = "form-control";
		$this->peopleserved_age->EditCustomAttributes = "";

		// programtype
		$this->programtype->EditAttrs["class"] = "form-control";
		$this->programtype->EditCustomAttributes = "";

		// programfocus
		$this->programfocus->EditAttrs["class"] = "form-control";
		$this->programfocus->EditCustomAttributes = "";

		// zone
		$this->zone->EditAttrs["class"] = "form-control";
		$this->zone->EditCustomAttributes = "";

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
					if ($this->servicesid->Exportable) $Doc->ExportCaption($this->servicesid);
					if ($this->servicename->Exportable) $Doc->ExportCaption($this->servicename);
					if ($this->facility->Exportable) $Doc->ExportCaption($this->facility);
					if ($this->community->Exportable) $Doc->ExportCaption($this->community);
					if ($this->address_street->Exportable) $Doc->ExportCaption($this->address_street);
					if ($this->address_city->Exportable) $Doc->ExportCaption($this->address_city);
					if ($this->address_postcode->Exportable) $Doc->ExportCaption($this->address_postcode);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->peopleserved_gender->Exportable) $Doc->ExportCaption($this->peopleserved_gender);
					if ($this->peopleserved_age->Exportable) $Doc->ExportCaption($this->peopleserved_age);
					if ($this->programtype->Exportable) $Doc->ExportCaption($this->programtype);
					if ($this->programfocus->Exportable) $Doc->ExportCaption($this->programfocus);
					if ($this->zone->Exportable) $Doc->ExportCaption($this->zone);
				} else {
					if ($this->servicesid->Exportable) $Doc->ExportCaption($this->servicesid);
					if ($this->servicename->Exportable) $Doc->ExportCaption($this->servicename);
					if ($this->facility->Exportable) $Doc->ExportCaption($this->facility);
					if ($this->community->Exportable) $Doc->ExportCaption($this->community);
					if ($this->address_street->Exportable) $Doc->ExportCaption($this->address_street);
					if ($this->address_city->Exportable) $Doc->ExportCaption($this->address_city);
					if ($this->address_postcode->Exportable) $Doc->ExportCaption($this->address_postcode);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->peopleserved_gender->Exportable) $Doc->ExportCaption($this->peopleserved_gender);
					if ($this->peopleserved_age->Exportable) $Doc->ExportCaption($this->peopleserved_age);
					if ($this->programtype->Exportable) $Doc->ExportCaption($this->programtype);
					if ($this->programfocus->Exportable) $Doc->ExportCaption($this->programfocus);
					if ($this->zone->Exportable) $Doc->ExportCaption($this->zone);
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
						if ($this->servicesid->Exportable) $Doc->ExportField($this->servicesid);
						if ($this->servicename->Exportable) $Doc->ExportField($this->servicename);
						if ($this->facility->Exportable) $Doc->ExportField($this->facility);
						if ($this->community->Exportable) $Doc->ExportField($this->community);
						if ($this->address_street->Exportable) $Doc->ExportField($this->address_street);
						if ($this->address_city->Exportable) $Doc->ExportField($this->address_city);
						if ($this->address_postcode->Exportable) $Doc->ExportField($this->address_postcode);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->peopleserved_gender->Exportable) $Doc->ExportField($this->peopleserved_gender);
						if ($this->peopleserved_age->Exportable) $Doc->ExportField($this->peopleserved_age);
						if ($this->programtype->Exportable) $Doc->ExportField($this->programtype);
						if ($this->programfocus->Exportable) $Doc->ExportField($this->programfocus);
						if ($this->zone->Exportable) $Doc->ExportField($this->zone);
					} else {
						if ($this->servicesid->Exportable) $Doc->ExportField($this->servicesid);
						if ($this->servicename->Exportable) $Doc->ExportField($this->servicename);
						if ($this->facility->Exportable) $Doc->ExportField($this->facility);
						if ($this->community->Exportable) $Doc->ExportField($this->community);
						if ($this->address_street->Exportable) $Doc->ExportField($this->address_street);
						if ($this->address_city->Exportable) $Doc->ExportField($this->address_city);
						if ($this->address_postcode->Exportable) $Doc->ExportField($this->address_postcode);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->peopleserved_gender->Exportable) $Doc->ExportField($this->peopleserved_gender);
						if ($this->peopleserved_age->Exportable) $Doc->ExportField($this->peopleserved_age);
						if ($this->programtype->Exportable) $Doc->ExportField($this->programtype);
						if ($this->programfocus->Exportable) $Doc->ExportField($this->programfocus);
						if ($this->zone->Exportable) $Doc->ExportField($this->zone);
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
