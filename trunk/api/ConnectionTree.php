<?php
class ConnectionTree extends Object {

	public function __construct() {}
	
	public static function getChildNodes($args) {
		switch ($args["nodeType"]) {
			case "monoql-tree-connectiongroupnode":
				$children = self::getConnectionGroupChildNodes($args);
				break;
			case "monoql-tree-connectionnode":
				$children = self::getConnectionChildNodes($args);
				break;
			case "monoql-tree-databasenode":
				$children = self::getDatabaseChildNodes($args);
				break;
			case "monoql-tree-tablegroupnode":
				$children = self::getTableGroupChildNodes($args);
				break;
			default:
				$children = array();
		}
		return $children;
	}
	
	public static function getConnectionGroupChildNodes($args) {
		$children = array();
		foreach (val(Connection::get(), "records") as $conn) {
			$children[] = array(
				"text"=>$conn["name"],
				"nodeType"=>"monoql-tree-connectionnode",
				"connectionId"=>$conn["id"]
			);
		}
		return $children;
	}
	
	public static function getConnectionChildNodes($args) {
		$children = array();
		$conn = Connection::getById($args["connectionId"]);
		$db = DatabaseFactory::createDatabase($conn);
		$databases = $db->getDatabases();
		foreach ($databases as $database) {
			$children[] = array(
				"text"=>$database,
				"nodeType"=>"monoql-tree-databasenode",
				"database"=>$database
			);
		}
		return $children;
	}
	
	public static function getTableGroupChildNodes($args) {
		$children = array();
		$conn = Connection::getById($args["connectionId"]);
		$db = DatabaseFactory::createDatabase($conn);
		$tables = $db->getTables(null, $args["database"]);
		foreach ($tables as $table) {
			$children[] = array(
				"text"=>$table,
				"nodeType"=>"monoql-tree-tablenode",
				"table"=>$table
			);
		}
		return $children;
	}
	
	public static function getDatabaseChildNodes($args) {
		$children = array(
			array(
				"text"=>"Tables",
				"nodeType"=>"monoql-tree-tablegroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Views",
				"nodeType"=>"monoql-tree-viewgroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Stored Procedures",
				"nodeType"=>"monoql-tree-sprocgroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Functions",
				"nodeType"=>"monoql-tree-functiongroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Triggers",
				"nodeType"=>"monoql-tree-triggergroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Users",
				"nodeType"=>"monoql-tree-usergroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Scripts",
				"nodeType"=>"monoql-tree-scriptgroupnode",
				"database"=>$args["database"]
			),
			array(
				"text"=>"Backups",
				"nodeType"=>"monoql-tree-backupgroupnode",
				"database"=>$args["database"]
			)
		);
		return $children;
	}
	
}
?>