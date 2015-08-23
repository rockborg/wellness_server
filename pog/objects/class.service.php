<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `services` (
	`servicesid` int(11) NOT NULL auto_increment,
	`servicename` VARCHAR(255) NOT NULL,
	`facility` VARCHAR(255) NOT NULL,
	`community` VARCHAR(255) NOT NULL,
	`address_street` VARCHAR(255) NOT NULL,
	`address_city` VARCHAR(255) NOT NULL,
	`address_postcode` VARCHAR(255) NOT NULL,
	`phone` VARCHAR(255) NOT NULL,
	`website` VARCHAR(255) NOT NULL,
	`peopleserved_gender` VARCHAR(255) NOT NULL,
	`peopleserved_age` VARCHAR(255) NOT NULL,
	`programtype` VARCHAR(255) NOT NULL,
	`programfocus` VARCHAR(255) NOT NULL,
	`zone` VARCHAR(255) NOT NULL, PRIMARY KEY  (`servicesid`)) ENGINE=MyISAM;
*/

/**
* <b>services</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=services&attributeList=array+%28%0A++0+%3D%3E+%27servicename%27%2C%0A++1+%3D%3E+%27facility%27%2C%0A++2+%3D%3E+%27community%27%2C%0A++3+%3D%3E+%27address_street%27%2C%0A++4+%3D%3E+%27address_city%27%2C%0A++5+%3D%3E+%27address_postcode%27%2C%0A++6+%3D%3E+%27phone%27%2C%0A++7+%3D%3E+%27website%27%2C%0A++8+%3D%3E+%27peopleserved_gender%27%2C%0A++9+%3D%3E+%27peopleserved_age%27%2C%0A++10+%3D%3E+%27programtype%27%2C%0A++11+%3D%3E+%27programfocus%27%2C%0A++12+%3D%3E+%27zone%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++7+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++8+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++9+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++10+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++11+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++12+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
*/
include_once('class.pog_base.php');
class services extends POG_Base
{
	public $servicesId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $servicename;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $facility;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $community;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $address_street;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $address_city;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $address_postcode;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $phone;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $website;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $peopleserved_gender;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $peopleserved_age;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $programtype;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $programfocus;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $zone;
	
	public $pog_attribute_type = array(
		"servicesId" => array('db_attributes' => array("NUMERIC", "INT")),
		"servicename" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"facility" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"community" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"address_street" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"address_city" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"address_postcode" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"phone" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"website" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"peopleserved_gender" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"peopleserved_age" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"programtype" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"programfocus" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"zone" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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
	
	function services($servicename='', $facility='', $community='', $address_street='', $address_city='', $address_postcode='', $phone='', $website='', $peopleserved_gender='', $peopleserved_age='', $programtype='', $programfocus='', $zone='')
	{
		$this->servicename = $servicename;
		$this->facility = $facility;
		$this->community = $community;
		$this->address_street = $address_street;
		$this->address_city = $address_city;
		$this->address_postcode = $address_postcode;
		$this->phone = $phone;
		$this->website = $website;
		$this->peopleserved_gender = $peopleserved_gender;
		$this->peopleserved_age = $peopleserved_age;
		$this->programtype = $programtype;
		$this->programfocus = $programfocus;
		$this->zone = $zone;
	}
	
	
	/**
	* Gets object from database
	* @param integer $servicesId 
	* @return object $services
	*/
	function Get($servicesId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `services` where `servicesid`='".intval($servicesId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->servicesId = $row['servicesid'];
			$this->servicename = $this->Unescape($row['servicename']);
			$this->facility = $this->Unescape($row['facility']);
			$this->community = $this->Unescape($row['community']);
			$this->address_street = $this->Unescape($row['address_street']);
			$this->address_city = $this->Unescape($row['address_city']);
			$this->address_postcode = $this->Unescape($row['address_postcode']);
			$this->phone = $this->Unescape($row['phone']);
			$this->website = $this->Unescape($row['website']);
			$this->peopleserved_gender = $this->Unescape($row['peopleserved_gender']);
			$this->peopleserved_age = $this->Unescape($row['peopleserved_age']);
			$this->programtype = $this->Unescape($row['programtype']);
			$this->programfocus = $this->Unescape($row['programfocus']);
			$this->zone = $this->Unescape($row['zone']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $servicesList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `services` ";
		$servicesList = Array();
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
			$sortBy = "servicesid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$services = new $thisObjectName();
			$services->servicesId = $row['servicesid'];
			$services->servicename = $this->Unescape($row['servicename']);
			$services->facility = $this->Unescape($row['facility']);
			$services->community = $this->Unescape($row['community']);
			$services->address_street = $this->Unescape($row['address_street']);
			$services->address_city = $this->Unescape($row['address_city']);
			$services->address_postcode = $this->Unescape($row['address_postcode']);
			$services->phone = $this->Unescape($row['phone']);
			$services->website = $this->Unescape($row['website']);
			$services->peopleserved_gender = $this->Unescape($row['peopleserved_gender']);
			$services->peopleserved_age = $this->Unescape($row['peopleserved_age']);
			$services->programtype = $this->Unescape($row['programtype']);
			$services->programfocus = $this->Unescape($row['programfocus']);
			$services->zone = $this->Unescape($row['zone']);
			$servicesList[] = $services;
		}
		return $servicesList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $servicesId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->servicesId!=''){
			$this->pog_query = "select `servicesid` from `services` where `servicesid`='".$this->servicesId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `services` set 
			`servicename`='".$this->Escape($this->servicename)."', 
			`facility`='".$this->Escape($this->facility)."', 
			`community`='".$this->Escape($this->community)."', 
			`address_street`='".$this->Escape($this->address_street)."', 
			`address_city`='".$this->Escape($this->address_city)."', 
			`address_postcode`='".$this->Escape($this->address_postcode)."', 
			`phone`='".$this->Escape($this->phone)."', 
			`website`='".$this->Escape($this->website)."', 
			`peopleserved_gender`='".$this->Escape($this->peopleserved_gender)."', 
			`peopleserved_age`='".$this->Escape($this->peopleserved_age)."', 
			`programtype`='".$this->Escape($this->programtype)."', 
			`programfocus`='".$this->Escape($this->programfocus)."', 
			`zone`='".$this->Escape($this->zone)."' where `servicesid`='".$this->servicesId."'";
		}
		else
		{
			$this->pog_query = "insert into `services` (`servicename`, `facility`, `community`, `address_street`, `address_city`, `address_postcode`, `phone`, `website`, `peopleserved_gender`, `peopleserved_age`, `programtype`, `programfocus`, `zone` ) values (
			'".$this->Escape($this->servicename)."', 
			'".$this->Escape($this->facility)."', 
			'".$this->Escape($this->community)."', 
			'".$this->Escape($this->address_street)."', 
			'".$this->Escape($this->address_city)."', 
			'".$this->Escape($this->address_postcode)."', 
			'".$this->Escape($this->phone)."', 
			'".$this->Escape($this->website)."', 
			'".$this->Escape($this->peopleserved_gender)."', 
			'".$this->Escape($this->peopleserved_age)."', 
			'".$this->Escape($this->programtype)."', 
			'".$this->Escape($this->programfocus)."', 
			'".$this->Escape($this->zone)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->servicesId == "")
		{
			$this->servicesId = $insertId;
		}
		return $this->servicesId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $servicesId
	*/
	function SaveNew()
	{
		$this->servicesId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `services` where `servicesid`='".$this->servicesId."'";
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
			$pog_query = "delete from `services` where ";
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