<?php
abstract class DatabaseFactory extends Object {
	
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
			$config = $type;
			$type = val($config, "type");
			$host = val($config, "host");
			$username = val($config, "username");
			$password = val($config, "password");
			$database = val($config, "database");
			$port = val($config, "port");
		}
		$type = alt($type, $config["default_database_type"]);
		switch ($type) {
			case "mssql":
				$instance = new MSSQL($host, $username, $password, $database, $port);
				break;
			case "oracle":
				$instance = new Oracle($host, $username, $password, $database, $port);
				break;
			case "sqlite":
				$instance = new SQLite($host, $username, $password, $database, $port);
				break;
			case "mysql":
				$instance = new MySQL($host, $username, $password, $database, $port);
				break;
			case "pgsql":
				$instance = new PostGreSQL($host, $username, $password, $database, $port);
				break;
		}
		
		return $instance;
	}
	
}
?>