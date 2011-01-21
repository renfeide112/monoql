<?php
abstract class DatabaseFactory extends Object {
	
	public static function createDatabase($type=null, $host=null, $username=null, $password=null, $database=null, $port=null) {
		global $config;
		
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
		}
		
		return $instance;
	}
	
}
?>