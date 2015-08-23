<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `tips` (
	`tipsid` int(11) NOT NULL auto_increment,
	`tiptitle` VARCHAR(255) NOT NULL,
	`tipimage` VARCHAR(255) NOT NULL,
	`tipdesc` TEXT NOT NULL,
	`tiplink` VARCHAR(255) NOT NULL,
	`dateadded` DATETIME NOT NULL, PRIMARY KEY  (`tipsid`)) ENGINE=MyISAM;
*/

/**
* <b>tips</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=tips&attributeList=array+%28%0A++0+%3D%3E+%27tiptitle%27%2C%0A++1+%3D%3E+%27tipimage%27%2C%0A++2+%3D%3E+%27tipdesc%27%2C%0A++3+%3D%3E+%27tiplink%27%2C%0A++4+%3D%3E+%27dateadded%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27DATETIME%27%2C%0A%29
*/
include_once('class.pog_base.php');
class tips extends POG_Base
{
	public $tipsId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $tiptitle;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $tipimage;
	
	/**
	 * @var TEXT
	 */
	public $tipdesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $tiplink;
	
	/**
	 * @var DATETIME
	 */
	public $dateadded;
	
	public $pog_attribute_type = array(
		"tipsId" => array('db_attributes' => array("NUMERIC", "INT")),
		"tiptitle" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"tipimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"tipdesc" => array('db_attributes' => array("TEXT", "TEXT")),
		"tiplink" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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
	
	function tips($tiptitle='', $tipimage='', $tipdesc='', $tiplink='', $dateadded='')
	{
		$this->tiptitle = $tiptitle;
		$this->tipimage = $tipimage;
		$this->tipdesc = $tipdesc;
		$this->tiplink = $tiplink;
		$this->dateadded = $dateadded;
	}
	
	
	/**
	* Gets object from database
	* @param integer $tipsId 
	* @return object $tips
	*/
	function Get($tipsId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `tips` where `tipsid`='".intval($tipsId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->tipsId = $row['tipsid'];
			$this->tiptitle = $this->Unescape($row['tiptitle']);
			$this->tipimage = $this->Unescape($row['tipimage']);
			$this->tipdesc = $this->Unescape($row['tipdesc']);
			$this->tiplink = $this->Unescape($row['tiplink']);
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
	* @return array $tipsList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `tips` ";
		$tipsList = Array();
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
			$sortBy = "tipsid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$tips = new $thisObjectName();
			$tips->tipsId = $row['tipsid'];
			$tips->tiptitle = $this->Unescape($row['tiptitle']);
			$tips->tipimage = $this->Unescape($row['tipimage']);
			$tips->tipdesc = $this->Unescape($row['tipdesc']);
			$tips->tiplink = $this->Unescape($row['tiplink']);
			$tips->dateadded = $row['dateadded'];
			$tipsList[] = $tips;
		}
		return $tipsList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $tipsId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->tipsId!=''){
			$this->pog_query = "select `tipsid` from `tips` where `tipsid`='".$this->tipsId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `tips` set 
			`tiptitle`='".$this->Escape($this->tiptitle)."', 
			`tipimage`='".$this->Escape($this->tipimage)."', 
			`tipdesc`='".$this->Escape($this->tipdesc)."', 
			`tiplink`='".$this->Escape($this->tiplink)."', 
			`dateadded`='".$this->dateadded."' where `tipsid`='".$this->tipsId."'";
		}
		else
		{
			$this->pog_query = "insert into `tips` (`tiptitle`, `tipimage`, `tipdesc`, `tiplink`, `dateadded` ) values (
			'".$this->Escape($this->tiptitle)."', 
			'".$this->Escape($this->tipimage)."', 
			'".$this->Escape($this->tipdesc)."', 
			'".$this->Escape($this->tiplink)."', 
			'".$this->dateadded."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->tipsId == "")
		{
			$this->tipsId = $insertId;
		}
		return $this->tipsId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $tipsId
	*/
	function SaveNew()
	{
		$this->tipsId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `tips` where `tipsid`='".$this->tipsId."'";
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
			$pog_query = "delete from `tips` where ";
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