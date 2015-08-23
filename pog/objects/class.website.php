<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `website` (
	`websiteid` int(11) NOT NULL auto_increment,
	`webname` VARCHAR(255) NOT NULL,
	`webdesc` TEXT NOT NULL,
	`webimage` VARCHAR(255) NOT NULL,
	`weblink` VARCHAR(255) NOT NULL,
	`createddate` TIMESTAMP NOT NULL,
	`categoryid` int(11) NOT NULL, INDEX(`categoryid`), PRIMARY KEY  (`websiteid`)) ENGINE=MyISAM;
*/

/**
* <b>website</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=website&attributeList=array+%28%0A++0+%3D%3E+%27webname%27%2C%0A++1+%3D%3E+%27webdesc%27%2C%0A++2+%3D%3E+%27webimage%27%2C%0A++3+%3D%3E+%27weblink%27%2C%0A++4+%3D%3E+%27createddate%27%2C%0A++5+%3D%3E+%27category%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27TIMESTAMP%27%2C%0A++5+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class website extends POG_Base
{
	public $websiteId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $webname;
	
	/**
	 * @var TEXT
	 */
	public $webdesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $webimage;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $weblink;
	
	/**
	 * @var TIMESTAMP
	 */
	public $createddate;
	
	/**
	 * @var INT(11)
	 */
	public $categoryId;
	
	public $pog_attribute_type = array(
		"websiteId" => array('db_attributes' => array("NUMERIC", "INT")),
		"webname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"webdesc" => array('db_attributes' => array("TEXT", "TEXT")),
		"webimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"weblink" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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
	
	function website($webname='', $webdesc='', $webimage='', $weblink='', $createddate='')
	{
		$this->webname = $webname;
		$this->webdesc = $webdesc;
		$this->webimage = $webimage;
		$this->weblink = $weblink;
		$this->createddate = $createddate;
	}
	
	
	/**
	* Gets object from database
	* @param integer $websiteId 
	* @return object $website
	*/
	function Get($websiteId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `website` where `websiteid`='".intval($websiteId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->websiteId = $row['websiteid'];
			$this->webname = $this->Unescape($row['webname']);
			$this->webdesc = $this->Unescape($row['webdesc']);
			$this->webimage = $this->Unescape($row['webimage']);
			$this->weblink = $this->Unescape($row['weblink']);
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
	* @return array $websiteList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `website` ";
		$websiteList = Array();
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
			$sortBy = "websiteid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$website = new $thisObjectName();
			$website->websiteId = $row['websiteid'];
			$website->webname = $this->Unescape($row['webname']);
			$website->webdesc = $this->Unescape($row['webdesc']);
			$website->webimage = $this->Unescape($row['webimage']);
			$website->weblink = $this->Unescape($row['weblink']);
			$website->createddate = $row['createddate'];
			$website->categoryId = $row['categoryid'];
			$websiteList[] = $website;
		}
		return $websiteList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $websiteId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->websiteId!=''){
			$this->pog_query = "select `websiteid` from `website` where `websiteid`='".$this->websiteId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `website` set 
			`webname`='".$this->Escape($this->webname)."', 
			`webdesc`='".$this->Escape($this->webdesc)."', 
			`webimage`='".$this->Escape($this->webimage)."', 
			`weblink`='".$this->Escape($this->weblink)."', 
			`createddate`='".$this->createddate."', 
			`categoryid`='".$this->categoryId."' where `websiteid`='".$this->websiteId."'";
		}
		else
		{
			$this->pog_query = "insert into `website` (`webname`, `webdesc`, `webimage`, `weblink`, `createddate`, `categoryid` ) values (
			'".$this->Escape($this->webname)."', 
			'".$this->Escape($this->webdesc)."', 
			'".$this->Escape($this->webimage)."', 
			'".$this->Escape($this->weblink)."', 
			'".$this->createddate."', 
			'".$this->categoryId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->websiteId == "")
		{
			$this->websiteId = $insertId;
		}
		return $this->websiteId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $websiteId
	*/
	function SaveNew()
	{
		$this->websiteId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `website` where `websiteid`='".$this->websiteId."'";
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
			$pog_query = "delete from `website` where ";
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