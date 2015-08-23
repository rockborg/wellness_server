<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `category` (
	`categoryid` int(11) NOT NULL auto_increment,
	`categoryname` VARCHAR(255) NOT NULL,
	`categorydesc` TEXT NOT NULL,
	`categoryimage` VARCHAR(255) NOT NULL,
	`createddate` TIMESTAMP NOT NULL, PRIMARY KEY  (`categoryid`)) ENGINE=MyISAM;
*/

/**
* <b>category</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=category&attributeList=array+%28%0A++0+%3D%3E+%27categoryname%27%2C%0A++1+%3D%3E+%27categorydesc%27%2C%0A++2+%3D%3E+%27categoryimage%27%2C%0A++3+%3D%3E+%27createddate%27%2C%0A++4+%3D%3E+%27event%27%2C%0A++5+%3D%3E+%27website%27%2C%0A++6+%3D%3E+%27app%27%2C%0A++7+%3D%3E+%27blog%27%2C%0A++8+%3D%3E+%27user%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27TIMESTAMP%27%2C%0A++4+%3D%3E+%27HASMANY%27%2C%0A++5+%3D%3E+%27HASMANY%27%2C%0A++6+%3D%3E+%27HASMANY%27%2C%0A++7+%3D%3E+%27HASMANY%27%2C%0A++8+%3D%3E+%27HASMANY%27%2C%0A%29
*/
//include_once('class.pog_base.php');
class category extends POG_Base
{
	public $categoryId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $categoryname;
	
	/**
	 * @var TEXT
	 */
	public $categorydesc;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $categoryimage;
	
	/**
	 * @var TIMESTAMP
	 */
	public $createddate;
	
	/**
	 * @var private array of event objects
	 */
	private $_eventList = array();
	
	/**
	 * @var private array of website objects
	 */
	private $_websiteList = array();
	
	/**
	 * @var private array of app objects
	 */
	private $_appList = array();
	
	/**
	 * @var private array of blog objects
	 */
	private $_blogList = array();
	
	/**
	 * @var private array of user objects
	 */
	private $_userList = array();
	
	public $pog_attribute_type = array(
		"categoryId" => array('db_attributes' => array("NUMERIC", "INT")),
		"categoryname" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"categorydesc" => array('db_attributes' => array("TEXT", "TEXT")),
		"categoryimage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"createddate" => array('db_attributes' => array("NUMERIC", "TIMESTAMP")),
		"event" => array('db_attributes' => array("OBJECT", "HASMANY")),
		"website" => array('db_attributes' => array("OBJECT", "HASMANY")),
		"app" => array('db_attributes' => array("OBJECT", "HASMANY")),
		"blog" => array('db_attributes' => array("OBJECT", "HASMANY")),
		"user" => array('db_attributes' => array("OBJECT", "HASMANY")),
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
	
	function category($categoryname='', $categorydesc='', $categoryimage='', $createddate='')
	{
		$this->categoryname = $categoryname;
		$this->categorydesc = $categorydesc;
		$this->categoryimage = $categoryimage;
		$this->createddate = $createddate;
		$this->_eventList = array();
		$this->_websiteList = array();
		$this->_appList = array();
		$this->_blogList = array();
		$this->_userList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $categoryId 
	* @return object $category
	*/
	function Get($categoryId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `category` where `categoryid`='".intval($categoryId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->categoryId = $row['categoryid'];
			$this->categoryname = $this->Unescape($row['categoryname']);
			$this->categorydesc = $this->Unescape($row['categorydesc']);
			$this->categoryimage = $this->Unescape($row['categoryimage']);
			$this->createddate = $row['createddate'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $categoryList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `category` ";
		$categoryList = Array();
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
			$sortBy = "categoryid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$category = new $thisObjectName();
			$category->categoryId = $row['categoryid'];
			$category->categoryname = $this->Unescape($row['categoryname']);
			$category->categorydesc = $this->Unescape($row['categorydesc']);
			$category->categoryimage = $this->Unescape($row['categoryimage']);
			$category->createddate = $row['createddate'];
			$categoryList[] = $category;
		}
		return $categoryList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $categoryId
	*/
	function Save($deep = true)
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->categoryId!=''){
			$this->pog_query = "select `categoryid` from `category` where `categoryid`='".$this->categoryId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `category` set 
			`categoryname`='".$this->Escape($this->categoryname)."', 
			`categorydesc`='".$this->Escape($this->categorydesc)."', 
			`categoryimage`='".$this->Escape($this->categoryimage)."', 
			`createddate`='".$this->createddate."'where `categoryid`='".$this->categoryId."'";
		}
		else
		{
			$this->pog_query = "insert into `category` (`categoryname`, `categorydesc`, `categoryimage`, `createddate`) values (
			'".$this->Escape($this->categoryname)."', 
			'".$this->Escape($this->categorydesc)."', 
			'".$this->Escape($this->categoryimage)."', 
			'".$this->createddate."')";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->categoryId == "")
		{
			$this->categoryId = $insertId;
		}
		if ($deep)
		{
			foreach ($this->_eventList as $event)
			{
				$event->categoryId = $this->categoryId;
				$event->Save($deep);
			}
			foreach ($this->_websiteList as $website)
			{
				$website->categoryId = $this->categoryId;
				$website->Save($deep);
			}
			foreach ($this->_appList as $app)
			{
				$app->categoryId = $this->categoryId;
				$app->Save($deep);
			}
			foreach ($this->_blogList as $blog)
			{
				$blog->categoryId = $this->categoryId;
				$blog->Save($deep);
			}
			foreach ($this->_userList as $user)
			{
				$user->categoryId = $this->categoryId;
				$user->Save($deep);
			}
		}
		return $this->categoryId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $categoryId
	*/
	function SaveNew($deep = false)
	{
		$this->categoryId = '';
		return $this->Save($deep);
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete($deep = false, $across = false)
	{
		if ($deep)
		{
			$eventList = $this->GetEventList();
			foreach ($eventList as $event)
			{
				$event->Delete($deep, $across);
			}
			$websiteList = $this->GetWebsiteList();
			foreach ($websiteList as $website)
			{
				$website->Delete($deep, $across);
			}
			$appList = $this->GetAppList();
			foreach ($appList as $app)
			{
				$app->Delete($deep, $across);
			}
			$blogList = $this->GetBlogList();
			foreach ($blogList as $blog)
			{
				$blog->Delete($deep, $across);
			}
			$userList = $this->GetUserList();
			foreach ($userList as $user)
			{
				$user->Delete($deep, $across);
			}
		}
		$connection = Database::Connect();
		$this->pog_query = "delete from `category` where `categoryid`='".$this->categoryId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array, $deep = false, $across = false)
	{
		if (sizeof($fcv_array) > 0)
		{
			if ($deep || $across)
			{
				$objectList = $this->GetList($fcv_array);
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			else
			{
				$connection = Database::Connect();
				$pog_query = "delete from `category` where ";
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
	
	
	/**
	* Gets a list of event objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of event objects
	*/
	function GetEventList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$event = new event();
		$fcv_array[] = array("categoryId", "=", $this->categoryId);
		$dbObjects = $event->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all event objects in the event List array. Any existing event will become orphan(s)
	* @return null
	*/
	function SetEventList(&$list)
	{
		$this->_eventList = array();
		$existingEventList = $this->GetEventList();
		foreach ($existingEventList as $event)
		{
			$event->categoryId = '';
			$event->Save(false);
		}
		$this->_eventList = $list;
	}
	
	
	/**
	* Associates the event object to this one
	* @return 
	*/
	function AddEvent(&$event)
	{
		$event->categoryId = $this->categoryId;
		$found = false;
		foreach($this->_eventList as $event2)
		{
			if ($event->eventId > 0 && $event->eventId == $event2->eventId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_eventList[] = $event;
		}
	}
	
	
	/**
	* Gets a list of website objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of website objects
	*/
	function GetWebsiteList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$website = new website();
		$fcv_array[] = array("categoryId", "=", $this->categoryId);
		$dbObjects = $website->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all website objects in the website List array. Any existing website will become orphan(s)
	* @return null
	*/
	function SetWebsiteList(&$list)
	{
		$this->_websiteList = array();
		$existingWebsiteList = $this->GetWebsiteList();
		foreach ($existingWebsiteList as $website)
		{
			$website->categoryId = '';
			$website->Save(false);
		}
		$this->_websiteList = $list;
	}
	
	
	/**
	* Associates the website object to this one
	* @return 
	*/
	function AddWebsite(&$website)
	{
		$website->categoryId = $this->categoryId;
		$found = false;
		foreach($this->_websiteList as $website2)
		{
			if ($website->websiteId > 0 && $website->websiteId == $website2->websiteId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_websiteList[] = $website;
		}
	}
	
	
	/**
	* Gets a list of app objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of app objects
	*/
	function GetAppList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$app = new app();
		$fcv_array[] = array("categoryId", "=", $this->categoryId);
		$dbObjects = $app->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all app objects in the app List array. Any existing app will become orphan(s)
	* @return null
	*/
	function SetAppList(&$list)
	{
		$this->_appList = array();
		$existingAppList = $this->GetAppList();
		foreach ($existingAppList as $app)
		{
			$app->categoryId = '';
			$app->Save(false);
		}
		$this->_appList = $list;
	}
	
	
	/**
	* Associates the app object to this one
	* @return 
	*/
	function AddApp(&$app)
	{
		$app->categoryId = $this->categoryId;
		$found = false;
		foreach($this->_appList as $app2)
		{
			if ($app->appId > 0 && $app->appId == $app2->appId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_appList[] = $app;
		}
	}
	
	
	/**
	* Gets a list of blog objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of blog objects
	*/
	function GetBlogList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$blog = new blog();
		$fcv_array[] = array("categoryId", "=", $this->categoryId);
		$dbObjects = $blog->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all blog objects in the blog List array. Any existing blog will become orphan(s)
	* @return null
	*/
	function SetBlogList(&$list)
	{
		$this->_blogList = array();
		$existingBlogList = $this->GetBlogList();
		foreach ($existingBlogList as $blog)
		{
			$blog->categoryId = '';
			$blog->Save(false);
		}
		$this->_blogList = $list;
	}
	
	
	/**
	* Associates the blog object to this one
	* @return 
	*/
	function AddBlog(&$blog)
	{
		$blog->categoryId = $this->categoryId;
		$found = false;
		foreach($this->_blogList as $blog2)
		{
			if ($blog->blogId > 0 && $blog->blogId == $blog2->blogId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_blogList[] = $blog;
		}
	}
	
	
	/**
	* Gets a list of user objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of user objects
	*/
	function GetUserList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$user = new user();
		$fcv_array[] = array("categoryId", "=", $this->categoryId);
		$dbObjects = $user->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all user objects in the user List array. Any existing user will become orphan(s)
	* @return null
	*/
	function SetUserList(&$list)
	{
		$this->_userList = array();
		$existingUserList = $this->GetUserList();
		foreach ($existingUserList as $user)
		{
			$user->categoryId = '';
			$user->Save(false);
		}
		$this->_userList = $list;
	}
	
	
	/**
	* Associates the user object to this one
	* @return 
	*/
	function AddUser(&$user)
	{
		$user->categoryId = $this->categoryId;
		$found = false;
		foreach($this->_userList as $user2)
		{
			if ($user->userId > 0 && $user->userId == $user2->userId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_userList[] = $user;
		}
	}
}
?>