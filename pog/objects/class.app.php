<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `app` (
	`appid` int(11) NOT NULL auto_increment,
	`appname` VARCHAR(255) NOT NULL,
	`appdesc` VARCHAR(255) NOT NULL,
	`appimage` VARCHAR(255) NOT NULL,
	`applink` VARCHAR(255) NOT NULL,
	`appplatform` VARCHAR(255) NOT NULL,
	`createddate` TIMESTAMP NOT NULL,
	`categoryid` int(11) NOT NULL, INDEX(`categoryid`), PRIMARY KEY  (`appid`)) ENGINE=MyISAM;
*/

/**
* <b>app</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=app&attributeList=array+%28%0A++0+%3D%3E+%27appname%27%2C%0A++1+%3D%3E+%27appdesc%27%2C%0A++2+%3D%3E+%27appimage%27%2C%0A++3+%3D%3E+%27applink%27%2C%0A++4+%3D%3E+%27appplatform%27%2C%0A++5+%3D%3E+%27createddate%27%2C%0A++6+%3D%3E+%27category%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27TIMESTAMP%27%2C%0A++6+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class app extends POG_Base
{
	public $appId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $appname;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $appdesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $appimage;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $applink;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $appplatform;
	
	/**
	 * @var TIMESTAMP
	 */
	public $createddate;
	
	/**
	 * @var INT(11)
	 */
	public $categoryId;
	
	public $pog_attribute_type = array(
		"appId" => array('db_attributes' => array("NUMERIC", "INT")),
		"appname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"appdesc" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"appimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"applink" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"appplatform" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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
	
	function app($appname='', $appdesc='', $appimage='', $applink='', $appplatform='', $createddate='')
	{
		$this->appname = $appname;
		$this->appdesc = $appdesc;
		$this->appimage = $appimage;
		$this->applink = $applink;
		$this->appplatform = $appplatform;
		$this->createddate = $createddate;
	}
	
	
	/**
	* Gets object from database
	* @param integer $appId 
	* @return object $app
	*/
	function Get($appId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `app` where `appid`='".intval($appId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->appId = $row['appid'];
			$this->appname = $this->Unescape($row['appname']);
			$this->appdesc = $this->Unescape($row['appdesc']);
			$this->appimage = $this->Unescape($row['appimage']);
			$this->applink = $this->Unescape($row['applink']);
			$this->appplatform = $this->Unescape($row['appplatform']);
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
	* @return array $appList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `app` ";
		$appList = Array();
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
			$sortBy = "appid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$app = new $thisObjectName();
			$app->appId = $row['appid'];
			$app->appname = $this->Unescape($row['appname']);
			$app->appdesc = $this->Unescape($row['appdesc']);
			$app->appimage = $this->Unescape($row['appimage']);
			$app->applink = $this->Unescape($row['applink']);
			$app->appplatform = $this->Unescape($row['appplatform']);
			$app->createddate = $row['createddate'];
			$app->categoryId = $row['categoryid'];
			$appList[] = $app;
		}
		return $appList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $appId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->appId!=''){
			$this->pog_query = "select `appid` from `app` where `appid`='".$this->appId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `app` set 
			`appname`='".$this->Escape($this->appname)."', 
			`appdesc`='".$this->Escape($this->appdesc)."', 
			`appimage`='".$this->Escape($this->appimage)."', 
			`applink`='".$this->Escape($this->applink)."', 
			`appplatform`='".$this->Escape($this->appplatform)."', 
			`createddate`='".$this->createddate."', 
			`categoryid`='".$this->categoryId."' where `appid`='".$this->appId."'";
		}
		else
		{
			$this->pog_query = "insert into `app` (`appname`, `appdesc`, `appimage`, `applink`, `appplatform`, `createddate`, `categoryid` ) values (
			'".$this->Escape($this->appname)."', 
			'".$this->Escape($this->appdesc)."', 
			'".$this->Escape($this->appimage)."', 
			'".$this->Escape($this->applink)."', 
			'".$this->Escape($this->appplatform)."', 
			'".$this->createddate."', 
			'".$this->categoryId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->appId == "")
		{
			$this->appId = $insertId;
		}
		return $this->appId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $appId
	*/
	function SaveNew()
	{
		$this->appId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `app` where `appid`='".$this->appId."'";
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
			$pog_query = "delete from `app` where ";
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