<?php
class Connection extends Object {
	
	public function __construct() {}
	
	public static function get(array $filters=array()) {
		global $config;
		$db = new SQLite($config["monoql_db_path"]);
		
		if (isset($filters["id"])) {
			$where = "WHERE id='" . $db->escape($filters["id"]) . "'";
		} else if (isset($filters["name"])) {
			$where = "WHERE name='" . $db->escape($filters["name"]) . "'";
		} else {
			$where = "";
		}
		
		$db->query("SELECT * FROM connection {$where};");
		
		$records = array();
		while ($db->getRecord()) {
			$records[] = $db->record;
		}
		
		$result = array(
			"success"=>true,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function formCreate(array $connections) {
		return self::create($connections);
	}
	
	public static function create(array $connections) {
		global $config;
		$db = new SQLite($config["monoql_db_path"]);
		$success = null;
		$records = array();
		
		// $connections should have a "records" key that is an array of connection records
		foreach ($connections["records"] as $conn) {
			$now = date("Y-m-d H:i:s");
			if (!$db->connect()) {break;}
			try {
				$statement = $db->connection->prepare("
					INSERT INTO connection
					(name, type, host, username, password, port, default_database, mdate, cdate, deleted) VALUES
					(:name, :type, :host, :username, :password, :port, :defaultDatabase, :mdate, :cdate, 0);
				");
				$success = $statement->execute(array(
					"name"=>alt(val($conn,"name"), "New Connection [{$now}]"),
					"type"=>val($conn,"type"),
					"host"=>val($conn,"host"),
					"username"=>val($conn,"username"),
					"password"=>val($conn,"password"),
					"port"=>intval(alt(val($conn,"port"), 0)),
					"defaultDatabase"=>val($conn,"defaultDatabase"),
					"mdate"=>$now,
					"cdate"=>$now
				));
			} catch (Exception $e) {
				debug($e->getMessage());
				debug($e->getTraceAsString());
			}	
			$records = val(Connection::get(), "records");
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function save(array $connection) {
	}
	
	public static function delete(array $connection) {
	}
}
?>