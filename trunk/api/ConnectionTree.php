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
		$conn = val(Connection::getById($args["connectionId"]), "data");
		$db = DatabaseFactory::createDatabase($conn);
		$databases = $db->getDatabases();
		foreach ($databases as $database) {
			$children[] = array(
				"text"=>$database,
				"nodeType"=>"monoql-tree-databasenode",
				"connectionId"=>$conn["id"]
			);
		}
		return $children;
	}
	
}
?>