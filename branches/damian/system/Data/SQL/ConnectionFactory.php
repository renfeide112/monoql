<?php
abstract class ConnectionFactory extends Object {
	
	private static $connections = array();
	
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
	public static function createConnection($type=null, $host=null, $username=null, $password=null, $dbname=null, $port=null) {
		if (is_array($type)) {
			$args = $type;
			$type = val($args, "type");
			$host = val($args, "host");
			$username = val($args, "username");
			$password = val($args, "password");
			$dbname = val($args, "database");
			$port = val($args, "port");
		}
		if (isset(self::$connections[$type])) {return self::$connections[$type];}
		switch ($type) {
			case "mssql":
				$connection = new MSSQLConnection($host, $username, $password, $dbname, $port);
				break;
			case "oracle":
				$connection = new OracleConnection($host, $username, $password, $dbname, $port);
				break;
			case "sqlite":
				debug("SQLiteConnection: 1 ({$host}, {$username}, {$password}, {$dbname}, {$port})");
				$connection = new SQLiteConnection($host, $username, $password, $dbname, $port);
				debug("SQLiteConnection: 2");
				break;
			case "mysql":
				$connection = new MySQLConnection($host, $username, $password, $dbname, $port);
				break;
			case "pgsql":
				$connection = new PostGreSQLConnection($host, $username, $password, $dbname, $port);
				break;
		}
		self::$connections[$type] = $connection;
		return $connection;
	}

}
?>