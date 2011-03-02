<?php
class ConnectionTree extends Object {

	public function __construct() {}
	
	public static function getChildNodes($args) {
		debug("nodeType: ".$args["nodeType"]);
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
			case "monoql-tree-viewgroupnode":
				$children = self::getViewGroupChildNodes($args);
				break;
			case "monoql-tree-sprocgroupnode":
				$children = self::getSprocGroupChildNodes($args);
				break;
			case "monoql-tree-functiongroupnode":
				$children = self::getFunctionGroupChildNodes($args);
				break;
			case "monoql-tree-triggergroupnode":
				$children = self::getTriggerGroupChildNodes($args);
				break;
			case "monoql-tree-tablenode":
				$children = self::getTableChildNodes($args);
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
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases as $database) {
			$children[] = array(
				"text"=>$database->name,
				"nodeType"=>"monoql-tree-databasenode",
				"database"=>$database->name
			);
		}
		return $children;
	}
	
	public static function getTableGroupChildNodes($args) {
		$children = array();
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases[$args["database"]]->tables as $table) {
			$children[] = array(
				"text"=>$table->name,
				"nodeType"=>"monoql-tree-tablenode",
				"table"=>$table->name
			);
		}
		return $children;
	}
	
	public static function getViewGroupChildNodes($args) {
		$children = array();
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases[$args["database"]]->views as $view) {
			$children[] = array(
				"text"=>$view,
				"nodeType"=>"monoql-tree-viewnode",
				"view"=>$view,
				"leaf"=>true
			);
		}
		return $children;
	}
	
	public static function getSprocGroupChildNodes($args) {
		$children = array();
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases[$args["database"]]->storedProcedures as $sproc) {
			$children[] = array(
				"text"=>$sproc,
				"nodeType"=>"monoql-tree-sprocnode",
				"sproc"=>$sproc,
				"leaf"=>true
			);
		}
		return $children;
	}
	
	public static function getFunctionGroupChildNodes($args) {
		$children = array();
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases[$args["database"]]->functions as $function) {
			$children[] = array(
				"text"=>$function,
				"nodeType"=>"monoql-tree-functionnode",
				"function"=>$function,
				"leaf"=>true
			);
		}
		return $children;
	}
	
	public static function getTriggerGroupChildNodes($args) {
		$children = array();
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases[$args["database"]]->triggers as $trigger) {
			$children[] = array(
				"text"=>$trigger,
				"nodeType"=>"monoql-tree-triggernode",
				"trigger"=>$trigger,
				"leaf"=>true
			);
		}
		return $children;
	}
	
	public static function getTableChildNodes($args) {
		$children = array();
		$conn = ConnectionFactory::createConnection(Connection::getById($args["connectionId"]));
		foreach ($conn->databases[$args["database"]]->tables[$args["table"]]->columns as $column) {
			$key = $column->key ? ($column->primary ? "primary" : "key") : "normal";
			$children[] = array(
				"text"=>$column->name,
				"nodeType"=>"monoql-tree-columnnode",
				"column"=>$column->name,
				"leaf"=>true,
				"cls"=>"monoql-tree-columnnode-{$key}"
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