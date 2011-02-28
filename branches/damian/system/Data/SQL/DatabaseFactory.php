<?php
abstract class DatabaseFactory extends Object {
	
	private static $instances = array();
	
	/**
	 * Get a new or used instance of one of the database object types 
	 * 
	 * @param string $type The type of database connection [mysql|mssql|oracle|sqlite]
	 * @param string $host The host name of the database server (give full path for SQLite database)
	 * @param string $username The username for the connection
	 * @param string $password The password for the username
	 * @param string $database The default database for the connection
	 * @param int $port The port number to connect to the server
	 */
	public static function createDatabase($type=null, $host=null, $username=null, $password=null, $database=null, $port=null) {
		global $config;
		if (is_array($type)) {
			$args = $type;
			$type = val($args, "type");
			$host = val($args, "host");
			$username = val($args, "username");
			$password = val($args, "password");
			$database = val($args, "database");
			$port = val($args, "port");
		}
		if (isset(self::$instances[$type])) {return self::$instances[$type];}
		switch ($type) {
			case "mssql":
				$instance = new MSSQLConnection($host, $username, $password, $database, $port);
				break;
			case "oracle":
				$instance = new OracleConnection($host, $username, $password, $database, $port);
				break;
			case "sqlite":
				$instance = new SQLiteConnection($host, $username, $password, $database, $port);
				break;
			case "mysql":
				$instance = new MySQLConnection($host, $username, $password, $database, $port);
				break;
			case "pgsql":
				$instance = new PostGreSQLConnection($host, $username, $password, $database, $port);
				break;
		}
		self::$instances[$type] = $instance;
		return $instance;
	}
	
}
?>