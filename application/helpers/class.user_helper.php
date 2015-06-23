<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `user` (
	`userid` int(11) NOT NULL auto_increment,
	`firstname` VARCHAR(255) NOT NULL,
	`lastname` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`pass` VARCHAR(255) NOT NULL,
	`regdate` TIMESTAMP NOT NULL,
	`lastlogindate` TIMESTAMP NOT NULL,
	`categoryid` int(11) NOT NULL,
	`token` VARCHAR(255) NOT NULL, INDEX(`categoryid`), PRIMARY KEY  (`userid`)) ENGINE=MyISAM;
*/

/**
* <b>user</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=user&attributeList=array+%28%0A++0+%3D%3E+%27firstname%27%2C%0A++1+%3D%3E+%27lastname%27%2C%0A++2+%3D%3E+%27email%27%2C%0A++3+%3D%3E+%27pass%27%2C%0A++4+%3D%3E+%27regdate%27%2C%0A++5+%3D%3E+%27lastlogindate%27%2C%0A++6+%3D%3E+%27category%27%2C%0A++7+%3D%3E+%27token%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27TIMESTAMP%27%2C%0A++5+%3D%3E+%27TIMESTAMP%27%2C%0A++6+%3D%3E+%27BELONGSTO%27%2C%0A++7+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
//include_once('class.pog_base.php');
class user extends POG_Base
{
	public $userId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $firstname;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $lastname;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $email;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $pass;
	
	/**
	 * @var TIMESTAMP
	 */
	public $regdate;
	
	/**
	 * @var TIMESTAMP
	 */
	public $lastlogindate;
	
	/**
	 * @var INT(11)
	 */
	public $categoryId;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $token;
	
	public $pog_attribute_type = array(
		"userId" => array('db_attributes' => array("NUMERIC", "INT")),
		"firstname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"lastname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"email" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"pass" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"regdate" => array('db_attributes' => array("NUMERIC", "TIMESTAMP")),
		"lastlogindate" => array('db_attributes' => array("NUMERIC", "TIMESTAMP")),
		"category" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"token" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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
	
	function user($firstname='', $lastname='', $email='', $pass='', $regdate='', $lastlogindate='', $token='')
	{
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->pass = $pass;
		$this->regdate = $regdate;
		$this->lastlogindate = $lastlogindate;
		$this->token = $token;
	}
	
	
	/**
	* Gets object from database
	* @param integer $userId 
	* @return object $user
	*/
	function Get($userId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `user` where `userid`='".intval($userId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->userId = $row['userid'];
			$this->firstname = $this->Unescape($row['firstname']);
			$this->lastname = $this->Unescape($row['lastname']);
			$this->email = $this->Unescape($row['email']);
			$this->pass = $this->Unescape($row['pass']);
			$this->regdate = $row['regdate'];
			$this->lastlogindate = $row['lastlogindate'];
			$this->categoryId = $row['categoryid'];
			$this->token = $this->Unescape($row['token']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $userList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `user` ";
		$userList = Array();
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
			$sortBy = "userid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$user = new $thisObjectName();
			$user->userId = $row['userid'];
			$user->firstname = $this->Unescape($row['firstname']);
			$user->lastname = $this->Unescape($row['lastname']);
			$user->email = $this->Unescape($row['email']);
			$user->pass = $this->Unescape($row['pass']);
			$user->regdate = $row['regdate'];
			$user->lastlogindate = $row['lastlogindate'];
			$user->categoryId = $row['categoryid'];
			$user->token = $this->Unescape($row['token']);
			$userList[] = $user;
		}
		return $userList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $userId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->userId!=''){
			$this->pog_query = "select `userid` from `user` where `userid`='".$this->userId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `user` set 
			`firstname`='".$this->Escape($this->firstname)."', 
			`lastname`='".$this->Escape($this->lastname)."', 
			`email`='".$this->Escape($this->email)."', 
			`pass`='".$this->Escape($this->pass)."', 
			`regdate`='".$this->regdate."', 
			`lastlogindate`='".$this->lastlogindate."', 
			`categoryid`='".$this->categoryId."', 
			`token`='".$this->Escape($this->token)."' where `userid`='".$this->userId."'";
		}
		else
		{
			$this->pog_query = "insert into `user` (`firstname`, `lastname`, `email`, `pass`, `regdate`, `lastlogindate`, `categoryid`, `token` ) values (
			'".$this->Escape($this->firstname)."', 
			'".$this->Escape($this->lastname)."', 
			'".$this->Escape($this->email)."', 
			'".$this->Escape($this->pass)."', 
			'".$this->regdate."', 
			'".$this->lastlogindate."', 
			'".$this->categoryId."', 
			'".$this->Escape($this->token)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->userId == "")
		{
			$this->userId = $insertId;
		}
		return $this->userId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $userId
	*/
	function SaveNew()
	{
		$this->userId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `user` where `userid`='".$this->userId."'";
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
			$pog_query = "delete from `user` where ";
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