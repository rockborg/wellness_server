<?php
/**
* <b>Database Connection</b> class.
* @author Php Object Generator
* @version 3.2 / PHP5
* @see http://www.phpobjectgenerator.com/
* @copyright Free for personal & commercial use. (Offered under the BSD license)
*/
 Class Database
{
	public $connection;

	private function Database()
	{
		$databaseName = $GLOBALS['configuration']['db'];
		$serverName = $GLOBALS['configuration']['host'];
		$databaseUser = $GLOBALS['configuration']['user'];
		$databasePassword = $GLOBALS['configuration']['pass'];
		$databasePort = $GLOBALS['configuration']['port'];
		$this->connection = mysqli_connect ($serverName.":".$databasePort, $databaseUser, $databasePassword);
		if ($this->connection)
		{
			if (!mysqli_select_db ($this->connection,$databaseName))
			{
				throw new Exception('I cannot find the specified database "'.$databaseName.'". Please edit configuration.php.');
			}
		}
		else
		{
			throw new Exception('I cannot connect to the database. Please edit configuration.php with your database configuration.');
		}
	}

	public static function Connect()
	{
		static $database = null;
		if (!isset($database))
		{
			$database = new Database();
		}
		return $database->connection;
	}

	public static function Reader($query, $connection)
	{
		$cursor = mysqli_query( $connection,$query);
		return $cursor;
	}

	public static function Read($cursor)
	{
		return mysqli_fetch_assoc($cursor);
	}

	public static function NonQuery($query, $connection)
	{
		mysqli_query($connection,$query);
		$result = mysqli_affected_rows($connection);
		if ($result == -1)
		{
			return false;
		}
		return $result;

	}

	public static function Query($query, $connection)
	{
		$result = mysqli_query($connection,$query);
		return mysqli_num_rows($result);
	}

	public static function InsertOrUpdate($query, $connection)
	{
		$result = mysqli_query($connection,$query);
		return intval(mysqli_insert_id($connection));
	}
}
?>
