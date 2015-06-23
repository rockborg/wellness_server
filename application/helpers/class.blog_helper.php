<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `blog` (
	`blogid` int(11) NOT NULL auto_increment,
	`blogname` VARCHAR(255) NOT NULL,
	`blogdesc` VARCHAR(255) NOT NULL,
	`blogimage` VARCHAR(255) NOT NULL,
	`bloglink` VARCHAR(255) NOT NULL,
	`createddate` TIMESTAMP NOT NULL,
	`categoryid` int(11) NOT NULL, INDEX(`categoryid`), PRIMARY KEY  (`blogid`)) ENGINE=MyISAM;
*/

/**
* <b>blog</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=blog&attributeList=array+%28%0A++0+%3D%3E+%27blogname%27%2C%0A++1+%3D%3E+%27blogdesc%27%2C%0A++2+%3D%3E+%27blogimage%27%2C%0A++3+%3D%3E+%27bloglink%27%2C%0A++4+%3D%3E+%27createddate%27%2C%0A++5+%3D%3E+%27category%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27TIMESTAMP%27%2C%0A++5+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
//include_once('class.pog_base.php');
class blog extends POG_Base
{
	public $blogId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $blogname;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $blogdesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $blogimage;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $bloglink;
	
	/**
	 * @var TIMESTAMP
	 */
	public $createddate;
	
	/**
	 * @var INT(11)
	 */
	public $categoryId;
	
	public $pog_attribute_type = array(
		"blogId" => array('db_attributes' => array("NUMERIC", "INT")),
		"blogname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"blogdesc" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"blogimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"bloglink" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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
	
	function blog($blogname='', $blogdesc='', $blogimage='', $bloglink='', $createddate='')
	{
		$this->blogname = $blogname;
		$this->blogdesc = $blogdesc;
		$this->blogimage = $blogimage;
		$this->bloglink = $bloglink;
		$this->createddate = $createddate;
	}
	
	
	/**
	* Gets object from database
	* @param integer $blogId 
	* @return object $blog
	*/
	function Get($blogId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `blog` where `blogid`='".intval($blogId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->blogId = $row['blogid'];
			$this->blogname = $this->Unescape($row['blogname']);
			$this->blogdesc = $this->Unescape($row['blogdesc']);
			$this->blogimage = $this->Unescape($row['blogimage']);
			$this->bloglink = $this->Unescape($row['bloglink']);
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
	* @return array $blogList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `blog` ";
		$blogList = Array();
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
			$sortBy = "blogid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$blog = new $thisObjectName();
			$blog->blogId = $row['blogid'];
			$blog->blogname = $this->Unescape($row['blogname']);
			$blog->blogdesc = $this->Unescape($row['blogdesc']);
			$blog->blogimage = $this->Unescape($row['blogimage']);
			$blog->bloglink = $this->Unescape($row['bloglink']);
			$blog->createddate = $row['createddate'];
			$blog->categoryId = $row['categoryid'];
			$blogList[] = $blog;
		}
		return $blogList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $blogId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->blogId!=''){
			$this->pog_query = "select `blogid` from `blog` where `blogid`='".$this->blogId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `blog` set 
			`blogname`='".$this->Escape($this->blogname)."', 
			`blogdesc`='".$this->Escape($this->blogdesc)."', 
			`blogimage`='".$this->Escape($this->blogimage)."', 
			`bloglink`='".$this->Escape($this->bloglink)."', 
			`createddate`='".$this->createddate."', 
			`categoryid`='".$this->categoryId."' where `blogid`='".$this->blogId."'";
		}
		else
		{
			$this->pog_query = "insert into `blog` (`blogname`, `blogdesc`, `blogimage`, `bloglink`, `createddate`, `categoryid` ) values (
			'".$this->Escape($this->blogname)."', 
			'".$this->Escape($this->blogdesc)."', 
			'".$this->Escape($this->blogimage)."', 
			'".$this->Escape($this->bloglink)."', 
			'".$this->createddate."', 
			'".$this->categoryId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->blogId == "")
		{
			$this->blogId = $insertId;
		}
		return $this->blogId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $blogId
	*/
	function SaveNew()
	{
		$this->blogId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `blog` where `blogid`='".$this->blogId."'";
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
			$pog_query = "delete from `blog` where ";
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