<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `settings` (
	`settingsid` int(11) NOT NULL auto_increment,
	`aboutimage` VARCHAR(255) NOT NULL,
	`abouttext` TEXT NOT NULL, PRIMARY KEY  (`settingsid`)) ENGINE=MyISAM;
*/

/**
* <b>settings</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=settings&attributeList=array+%28%0A++0+%3D%3E+%27aboutimage%27%2C%0A++1+%3D%3E+%27abouttext%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A%29
*/
include_once('class.pog_base.php');
class settings extends POG_Base
{
	public $settingsId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $aboutimage;
	
	/**
	 * @var TEXT
	 */
	public $abouttext;
	
	public $pog_attribute_type = array(
		"settingsId" => array('db_attributes' => array("NUMERIC", "INT")),
		"aboutimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"abouttext" => array('db_attributes' => array("TEXT", "TEXT")),
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
	
	function settings($aboutimage='', $abouttext='')
	{
		$this->aboutimage = $aboutimage;
		$this->abouttext = $abouttext;
	}
	
	
	/**
	* Gets object from database
	* @param integer $settingsId 
	* @return object $settings
	*/
	function Get($settingsId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `settings` where `settingsid`='".intval($settingsId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->settingsId = $row['settingsid'];
			$this->aboutimage = $this->Unescape($row['aboutimage']);
			$this->abouttext = $this->Unescape($row['abouttext']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $settingsList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `settings` ";
		$settingsList = Array();
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
			$sortBy = "settingsid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$settings = new $thisObjectName();
			$settings->settingsId = $row['settingsid'];
			$settings->aboutimage = $this->Unescape($row['aboutimage']);
			$settings->abouttext = $this->Unescape($row['abouttext']);
			$settingsList[] = $settings;
		}
		return $settingsList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $settingsId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->settingsId!=''){
			$this->pog_query = "select `settingsid` from `settings` where `settingsid`='".$this->settingsId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `settings` set 
			`aboutimage`='".$this->Escape($this->aboutimage)."', 
			`abouttext`='".$this->Escape($this->abouttext)."' where `settingsid`='".$this->settingsId."'";
		}
		else
		{
			$this->pog_query = "insert into `settings` (`aboutimage`, `abouttext` ) values (
			'".$this->Escape($this->aboutimage)."', 
			'".$this->Escape($this->abouttext)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->settingsId == "")
		{
			$this->settingsId = $insertId;
		}
		return $this->settingsId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $settingsId
	*/
	function SaveNew()
	{
		$this->settingsId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `settings` where `settingsid`='".$this->settingsId."'";
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
			$pog_query = "delete from `settings` where ";
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