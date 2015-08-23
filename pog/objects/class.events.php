<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `events` (
	`eventsid` int(11) NOT NULL auto_increment,
	`eventtitle` VARCHAR(255) NOT NULL,
	`eventhost` VARCHAR(255) NOT NULL,
	`eventaddress` VARCHAR(255) NOT NULL,
	`eventcity` VARCHAR(255) NOT NULL,
	`eventdate` DATE NOT NULL,
	`eventtime` TIME NOT NULL,
	`eventcategory` VARCHAR(255) NOT NULL,
	`eventdesc` TEXT NOT NULL, PRIMARY KEY  (`eventsid`)) ENGINE=MyISAM;
*/

/**
* <b>events</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=events&attributeList=array+%28%0A++0+%3D%3E+%27eventtitle%27%2C%0A++1+%3D%3E+%27eventhost%27%2C%0A++2+%3D%3E+%27eventaddress%27%2C%0A++3+%3D%3E+%27eventcity%27%2C%0A++4+%3D%3E+%27eventdate%27%2C%0A++5+%3D%3E+%27eventtime%27%2C%0A++6+%3D%3E+%27eventcategory%27%2C%0A++7+%3D%3E+%27eventdesc%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27DATE%27%2C%0A++5+%3D%3E+%27TIME%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++7+%3D%3E+%27TEXT%27%2C%0A%29
*/
include_once('class.pog_base.php');
class events extends POG_Base
{
	public $eventsId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $eventtitle;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventhost;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventaddress;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventcity;
	
	/**
	 * @var DATE
	 */
	public $eventdate;
	
	/**
	 * @var TIME
	 */
	public $eventtime;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventcategory;
	
	/**
	 * @var TEXT
	 */
	public $eventdesc;
	
	public $pog_attribute_type = array(
		"eventsId" => array('db_attributes' => array("NUMERIC", "INT")),
		"eventtitle" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventhost" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventaddress" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventcity" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventdate" => array('db_attributes' => array("NUMERIC", "DATE")),
		"eventtime" => array('db_attributes' => array("NUMERIC", "TIME")),
		"eventcategory" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventdesc" => array('db_attributes' => array("TEXT", "TEXT")),
		);
	public $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	public function __get($attribute)
	{
		if (isset($this->{"_".$attribute}))
		{
			return $this->{"_".$attribute};
		}
		else
		{
			return false;
		}
	}
	
	function events($eventtitle='', $eventhost='', $eventaddress='', $eventcity='', $eventdate='', $eventtime='', $eventcategory='', $eventdesc='')
	{
		$this->eventtitle = $eventtitle;
		$this->eventhost = $eventhost;
		$this->eventaddress = $eventaddress;
		$this->eventcity = $eventcity;
		$this->eventdate = $eventdate;
		$this->eventtime = $eventtime;
		$this->eventcategory = $eventcategory;
		$this->eventdesc = $eventdesc;
	}
	
	
	/**
	* Gets object from database
	* @param integer $eventsId 
	* @return object $events
	*/
	function Get($eventsId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `events` where `eventsid`='".intval($eventsId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->eventsId = $row['eventsid'];
			$this->eventtitle = $this->Unescape($row['eventtitle']);
			$this->eventhost = $this->Unescape($row['eventhost']);
			$this->eventaddress = $this->Unescape($row['eventaddress']);
			$this->eventcity = $this->Unescape($row['eventcity']);
			$this->eventdate = $row['eventdate'];
			$this->eventtime = $row['eventtime'];
			$this->eventcategory = $this->Unescape($row['eventcategory']);
			$this->eventdesc = $this->Unescape($row['eventdesc']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $eventsList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `events` ";
		$eventsList = Array();
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE($sortBy) ";
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "$sortBy ";
			}
		}
		else
		{
			$sortBy = "eventsid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$events = new $thisObjectName();
			$events->eventsId = $row['eventsid'];
			$events->eventtitle = $this->Unescape($row['eventtitle']);
			$events->eventhost = $this->Unescape($row['eventhost']);
			$events->eventaddress = $this->Unescape($row['eventaddress']);
			$events->eventcity = $this->Unescape($row['eventcity']);
			$events->eventdate = $row['eventdate'];
			$events->eventtime = $row['eventtime'];
			$events->eventcategory = $this->Unescape($row['eventcategory']);
			$events->eventdesc = $this->Unescape($row['eventdesc']);
			$eventsList[] = $events;
		}
		return $eventsList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $eventsId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->eventsId!=''){
			$this->pog_query = "select `eventsid` from `events` where `eventsid`='".$this->eventsId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `events` set 
			`eventtitle`='".$this->Escape($this->eventtitle)."', 
			`eventhost`='".$this->Escape($this->eventhost)."', 
			`eventaddress`='".$this->Escape($this->eventaddress)."', 
			`eventcity`='".$this->Escape($this->eventcity)."', 
			`eventdate`='".$this->eventdate."', 
			`eventtime`='".$this->eventtime."', 
			`eventcategory`='".$this->Escape($this->eventcategory)."', 
			`eventdesc`='".$this->Escape($this->eventdesc)."' where `eventsid`='".$this->eventsId."'";
		}
		else
		{
			$this->pog_query = "insert into `events` (`eventtitle`, `eventhost`, `eventaddress`, `eventcity`, `eventdate`, `eventtime`, `eventcategory`, `eventdesc` ) values (
			'".$this->Escape($this->eventtitle)."', 
			'".$this->Escape($this->eventhost)."', 
			'".$this->Escape($this->eventaddress)."', 
			'".$this->Escape($this->eventcity)."', 
			'".$this->eventdate."', 
			'".$this->eventtime."', 
			'".$this->Escape($this->eventcategory)."', 
			'".$this->Escape($this->eventdesc)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->eventsId == "")
		{
			$this->eventsId = $insertId;
		}
		return $this->eventsId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $eventsId
	*/
	function SaveNew()
	{
		$this->eventsId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `events` where `eventsid`='".$this->eventsId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$connection = Database::Connect();
			$pog_query = "delete from `events` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return Database::NonQuery($pog_query, $connection);
		}
	}
}
?>