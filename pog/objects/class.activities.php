<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `activities` (
	`activitiesid` int(11) NOT NULL auto_increment,
	`acttitle` VARCHAR(255) NOT NULL,
	`actimage` VARCHAR(255) NOT NULL,
	`actdesc` TEXT NOT NULL,
	`actlink` VARCHAR(255) NOT NULL,
	`dateadded` DATETIME NOT NULL, PRIMARY KEY  (`activitiesid`)) ENGINE=MyISAM;
*/

/**
* <b>activities</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=activities&attributeList=array+%28%0A++0+%3D%3E+%27acttitle%27%2C%0A++1+%3D%3E+%27actimage%27%2C%0A++2+%3D%3E+%27actdesc%27%2C%0A++3+%3D%3E+%27actlink%27%2C%0A++4+%3D%3E+%27dateadded%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27DATETIME%27%2C%0A%29
*/
include_once('class.pog_base.php');
class activities extends POG_Base
{
	public $activitiesId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $acttitle;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $actimage;
	
	/**
	 * @var TEXT
	 */
	public $actdesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $actlink;
	
	/**
	 * @var DATETIME
	 */
	public $dateadded;
	
	public $pog_attribute_type = array(
		"activitiesId" => array('db_attributes' => array("NUMERIC", "INT")),
		"acttitle" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"actimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"actdesc" => array('db_attributes' => array("TEXT", "TEXT")),
		"actlink" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"dateadded" => array('db_attributes' => array("TEXT", "DATETIME")),
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
	
	function activities($acttitle='', $actimage='', $actdesc='', $actlink='', $dateadded='')
	{
		$this->acttitle = $acttitle;
		$this->actimage = $actimage;
		$this->actdesc = $actdesc;
		$this->actlink = $actlink;
		$this->dateadded = $dateadded;
	}
	
	
	/**
	* Gets object from database
	* @param integer $activitiesId 
	* @return object $activities
	*/
	function Get($activitiesId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `activities` where `activitiesid`='".intval($activitiesId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->activitiesId = $row['activitiesid'];
			$this->acttitle = $this->Unescape($row['acttitle']);
			$this->actimage = $this->Unescape($row['actimage']);
			$this->actdesc = $this->Unescape($row['actdesc']);
			$this->actlink = $this->Unescape($row['actlink']);
			$this->dateadded = $row['dateadded'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $activitiesList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `activities` ";
		$activitiesList = Array();
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
			$sortBy = "activitiesid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$activities = new $thisObjectName();
			$activities->activitiesId = $row['activitiesid'];
			$activities->acttitle = $this->Unescape($row['acttitle']);
			$activities->actimage = $this->Unescape($row['actimage']);
			$activities->actdesc = $this->Unescape($row['actdesc']);
			$activities->actlink = $this->Unescape($row['actlink']);
			$activities->dateadded = $row['dateadded'];
			$activitiesList[] = $activities;
		}
		return $activitiesList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $activitiesId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->activitiesId!=''){
			$this->pog_query = "select `activitiesid` from `activities` where `activitiesid`='".$this->activitiesId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `activities` set 
			`acttitle`='".$this->Escape($this->acttitle)."', 
			`actimage`='".$this->Escape($this->actimage)."', 
			`actdesc`='".$this->Escape($this->actdesc)."', 
			`actlink`='".$this->Escape($this->actlink)."', 
			`dateadded`='".$this->dateadded."' where `activitiesid`='".$this->activitiesId."'";
		}
		else
		{
			$this->pog_query = "insert into `activities` (`acttitle`, `actimage`, `actdesc`, `actlink`, `dateadded` ) values (
			'".$this->Escape($this->acttitle)."', 
			'".$this->Escape($this->actimage)."', 
			'".$this->Escape($this->actdesc)."', 
			'".$this->Escape($this->actlink)."', 
			'".$this->dateadded."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->activitiesId == "")
		{
			$this->activitiesId = $insertId;
		}
		return $this->activitiesId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $activitiesId
	*/
	function SaveNew()
	{
		$this->activitiesId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `activities` where `activitiesid`='".$this->activitiesId."'";
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
			$pog_query = "delete from `activities` where ";
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