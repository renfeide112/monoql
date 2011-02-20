<?php
class Connection extends Object {
	
	public function __construct() {}
	
	public static function get(array $filters=array()) {
		global $config;
		$db = DatabaseFactory::createDatabase("sqlite", $config["monoql_db_path"]);
		
		if (isset($filters["id"])) {
			$where = "WHERE id=" . $db->escape($filters["id"]);
		} else if (isset($filters["name"])) {
			$where = "WHERE name=" . $db->escape($filters["name"]);
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
	
	public static function getById($id) {
		$records = val(self::get(array("id"=>$id)), "records");
		$data = count($records)===1 ? $records[0] : null;
		$result = array(
			"success"=>isset($data),
			"data"=>$data
		);
		return $result;
	}
	
	// This method must be called by form posts from the client
	// and will always handle at most 1 new record
	public static function formCreate($connection) {
		$connections = array("records"=>array($connection));
		return self::create($connections);
	}
	
	// This will create 1 or more connections
	public static function create(array $connections) {
		global $config;
		$db = DatabaseFactory::createDatabase("sqlite", $config["monoql_db_path"]);
		$success = null;
		$records = array();
		
		// $connections should have a "records" key that is an array of connection records
		foreach ($connections["records"] as $conn) {
			$now = date("Y-m-d H:i:s");
			if (!$db->connect()) {break;}
			try {
				$p = array(
					"name"=>$db->quote(alt(val($conn,"name"), "New Connection [{$now}]")),
					"type"=>$db->quote(val($conn,"type")),
					"host"=>$db->quote(val($conn,"host")),
					"username"=>$db->quote(val($conn,"username")),
					"password"=>$db->quote(val($conn,"password")),
					"port"=>$db->quote(intval(alt(val($conn,"port"), 0))),
					"defaultDatabase"=>$db->quote(val($conn,"defaultDatabase")),
					"mdate"=>$db->quote($now),
					"cdate"=>$db->quote($now),
					"deleted"=>$db->quote(0)
				);
				$statement = $db->connection->prepare("
					INSERT INTO connection
					(name, type, host, username, password, port, default_database, mdate, cdate, deleted) VALUES
					({$p["name"]}, {$p["type"]}, {$p["host"]}, {$p["username"]}, {$p["password"]}, {$p["port"]}, {$p["defaultDatabase"]}, {$p["mdate"]}, {$p["cdate"]}, {$p["deleted"]});
				");
				$result = $statement->execute();
			} catch (Exception $e) {
				debug($e->getMessage());
				debug($e->getTraceAsString());
			}	
			$insertedRecords = val(self::get(array("id"=>$db->getInsertedId())), "records");
			if (is_array($insertedRecords)) {
				$records = array_merge($insertedRecords, $records);
			}
		}
		
		$result = array(
			"success"=>!!$result,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function save(array $connections) {
		global $config;
		$db = new SQLite($config["monoql_db_path"]);
		$success = null;
		$records = array();
		
		// $connections should have a "records" key that is an array of connection records
		foreach ($connections["records"] as $conn) {
			$now = date("Y-m-d H:i:s");
			
			
			if (isset($conn["id"])) {
				$where = "WHERE id=" . $db->escape($conn["id"]);  //this is not secure
			} else if (isset($conn["name"])) {
				$where = "WHERE name=" . $db->quote($conn["name"]); //this is not secure
			} else {
				$where = "WHERE 0=1";  //so all records are not updated
			}
			
			if (!$db->connect()) {break;}
			try {
				$p = array(
					"name"=>$db->quote(alt(val($conn,"name"), "New Connection [{$now}]")),
					"type"=>$db->quote(val($conn,"type")),
					"host"=>$db->quote(val($conn,"host")),
					"username"=>$db->quote(val($conn,"username")),
					"password"=>$db->quote(val($conn,"password")),
					"port"=>$db->quote(intval(alt(val($conn,"port"), 0))),
					"defaultDatabase"=>$db->quote(val($conn,"defaultDatabase")),
					"mdate"=>$db->quote($now),
					"deleted"=>$db->quote(0)
				);
				$statement = $db->connection->prepare("
					UPDATE connection set
						name = {$p["name"]},
						type = {$p["type"]},
						host = {$p["host"]},
						username = {$p["username"]},
						password = {$p["password"]},
						port = {$p["port"]},
						default_database = {$p["defaultDatabase"]},
						mdate = {$p["mdate"]},
						deleted = {$p["deleted"]}
					{$where}
				");
				$result = $statement->execute();
			} catch (Exception $e) {
				debug($e->getMessage());
				debug($e->getTraceAsString());
			}	
			$records = val(Connection::get(), "records");
		}
		
		$result = array(
			"success"=>!!$result,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function delete(array $connections) {
		global $config;
		$db = DatabaseFactory::createDatabase("sqlite", $config["monoql_db_path"]);
		$success = null;
		$records = array();
		
		// $connections should have a "records" key that is an array of connection records
		foreach ($connections["records"] as $connId) {
			if (!$db->connect()) {break;}
			try {
				$p = array(
					"id"=>$db->quote($connId),
				);
				$statement = $db->connection->prepare("
					DELETE FROM connection WHERE id={$p["id"]};
				");
				$result = $statement->execute();
			} catch (Exception $e) {
				debug($e->getMessage());
				debug($e->getTraceAsString());
			}
		}
		
		$result = array(
			"success"=>!!$result,
			"records"=>$records
		);
		
		return $result;
	}
}
?>