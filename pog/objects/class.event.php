<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `event` (
	`eventid` int(11) NOT NULL auto_increment,
	`eventname` VARCHAR(255) NOT NULL,
	`eventdesc` VARCHAR(255) NOT NULL,
	`eventimage` VARCHAR(255) NOT NULL,
	`eventlink` VARCHAR(255) NOT NULL,
	`eventdate` TIMESTAMP NOT NULL,
	`createddate` TIMESTAMP NOT NULL,
	`categoryid` int(11) NOT NULL, INDEX(`categoryid`), PRIMARY KEY  (`eventid`)) ENGINE=MyISAM;
*/

/**
* <b>event</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=event&attributeList=array+%28%0A++0+%3D%3E+%27eventname%27%2C%0A++1+%3D%3E+%27eventdesc%27%2C%0A++2+%3D%3E+%27eventimage%27%2C%0A++3+%3D%3E+%27eventlink%27%2C%0A++4+%3D%3E+%27eventdate%27%2C%0A++5+%3D%3E+%27createddate%27%2C%0A++6+%3D%3E+%27category%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27TIMESTAMP%27%2C%0A++5+%3D%3E+%27TIMESTAMP%27%2C%0A++6+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class event extends POG_Base
{
	public $eventId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $eventname;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventdesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventimage;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $eventlink;
	
	/**
	 * @var TIMESTAMP
	 */
	public $eventdate;
	
	/**
	 * @var TIMESTAMP
	 */
	public $createddate;
	
	/**
	 * @var INT(11)
	 */
	public $categoryId;
	
	public $pog_attribute_type = array(
		"eventId" => array('db_attributes' => array("NUMERIC", "INT")),
		"eventname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventdesc" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventlink" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"eventdate" => array('db_attributes' => array("NUMERIC", "TIMESTAMP")),
		"createddate" => array('db_attributes' => array("NUMERIC", "TIMESTAMP")),
		"category" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
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
	
	function event($eventname='', $eventdesc='', $eventimage='', $eventlink='', $eventdate='', $createddate='')
	{
		$this->eventname = $eventname;
		$this->eventdesc = $eventdesc;
		$this->eventimage = $eventimage;
		$this->eventlink = $eventlink;
		$this->eventdate = $eventdate;
		$this->createddate = $createddate;
	}
	
	
	/**
	* Gets object from database
	* @param integer $eventId 
	* @return object $event
	*/
	function Get($eventId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `event` where `eventid`='".intval($eventId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->eventId = $row['eventid'];
			$this->eventname = $this->Unescape($row['eventname']);
			$this->eventdesc = $this->Unescape($row['eventdesc']);
			$this->eventimage = $this->Unescape($row['eventimage']);
			$this->eventlink = $this->Unescape($row['eventlink']);
			$this->eventdate = $row['eventdate'];
			$this->createddate = $row['createddate'];
			$this->categoryId = $row['categoryid'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $eventList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `event` ";
		$eventList = Array();
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
			$sortBy = "eventid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$event = new $thisObjectName();
			$event->eventId = $row['eventid'];
			$event->eventname = $this->Unescape($row['eventname']);
			$event->eventdesc = $this->Unescape($row['eventdesc']);
			$event->eventimage = $this->Unescape($row['eventimage']);
			$event->eventlink = $this->Unescape($row['eventlink']);
			$event->eventdate = $row['eventdate'];
			$event->createddate = $row['createddate'];
			$event->categoryId = $row['categoryid'];
			$eventList[] = $event;
		}
		return $eventList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $eventId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->eventId!=''){
			$this->pog_query = "select `eventid` from `event` where `eventid`='".$this->eventId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `event` set 
			`eventname`='".$this->Escape($this->eventname)."', 
			`eventdesc`='".$this->Escape($this->eventdesc)."', 
			`eventimage`='".$this->Escape($this->eventimage)."', 
			`eventlink`='".$this->Escape($this->eventlink)."', 
			`eventdate`='".$this->eventdate."', 
			`createddate`='".$this->createddate."', 
			`categoryid`='".$this->categoryId."' where `eventid`='".$this->eventId."'";
		}
		else
		{
			$this->pog_query = "insert into `event` (`eventname`, `eventdesc`, `eventimage`, `eventlink`, `eventdate`, `createddate`, `categoryid` ) values (
			'".$this->Escape($this->eventname)."', 
			'".$this->Escape($this->eventdesc)."', 
			'".$this->Escape($this->eventimage)."', 
			'".$this->Escape($this->eventlink)."', 
			'".$this->eventdate."', 
			'".$this->createddate."', 
			'".$this->categoryId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->eventId == "")
		{
			$this->eventId = $insertId;
		}
		return $this->eventId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $eventId
	*/
	function SaveNew()
	{
		$this->eventId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `event` where `eventid`='".$this->eventId."'";
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
			$pog_query = "delete from `event` where ";
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
	
	
	/**
	* Associates the category object to this one
	* @return boolean
	*/
	function GetCategory()
	{
		$category = new category();
		return $category->Get($this->categoryId);
	}
	
	
	/**
	* Associates the category object to this one
	* @return 
	*/
	function SetCategory(&$category)
	{
		$this->categoryId = $category->categoryId;
	}
}
?>