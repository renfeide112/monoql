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
		
		try {
			$db->query("SELECT * FROM connection {$where};");
			
			$records = array();
			while ($db->getRecord()) {
				$record = $db->record;
				unset($record["password"]);
				$records[] = $record;
			}
			$success = true;
		} catch (Exception $e) {
			logException($e);
			$success = false;
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function getById($id) {
		$records = val(self::get(array("id"=>$id)), "records");
		$data = count($records)===1 ? $records[0] : null;
		return $data;
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
			try {
				$now = date("Y-m-d H:i:s");
				$p = array(
					"name"=>$db->quote(alt(val($conn,"name"), "New Connection [{$now}]")),
					"type"=>$db->quote(val($conn,"type")),
					"host"=>$db->quote(val($conn,"host")),
					"username"=>$db->quote(val($conn,"username")),
					"password"=>$db->quote(val($conn,"password")),
					"port"=>$db->quote(val($conn,"port")),
					"defaultDatabase"=>$db->quote(val($conn,"defaultDatabase")),
					"mdate"=>$db->quote($now),
					"cdate"=>$db->quote($now),
					"deleted"=>$db->quote(0)
				);
				$qresult = $db->query(implode(NL, array(
					"INSERT INTO connection",
					"(name, type, host, username, password, port, default_database, mdate, cdate, deleted) VALUES",
					"({$p["name"]}, {$p["type"]}, {$p["host"]}, {$p["username"]}, {$p["password"]}, {$p["port"]}, {$p["defaultDatabase"]}, {$p["mdate"]}, {$p["cdate"]}, {$p["deleted"]});"
				)));
				$insertedRecords = val(self::get(array("id"=>$db->getInsertedId())), "records");
				if (is_array($insertedRecords)) {
					$records = array_merge($insertedRecords, $records);
				}
				$success = alt($success, true) && ($qresult!==false);
			} catch (Exception $e) {
				logException($e);
				$success = false;
			}
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function save(array $connections) {
		global $config;
		$db = DatabaseFactory::createDatabase("sqlite", $config["monoql_db_path"]);
		$success = null;
		$records = array();
		
		// $connections should have a "records" key that is an array of connection records
		foreach ($connections["records"] as $conn) {
			try {
				if (isset($conn["id"])) {
					$where = "WHERE id=" . $db->escape($conn["id"]);  //this is not secure
				} else if (isset($conn["name"])) {
					$where = "WHERE name=" . $db->quote($conn["name"]); //this is not secure
				} else {
					$where = false;  //so all records are not updated
				}
				if (!!$where) {
					$now = date("Y-m-d H:i:s");
					$updatePassword = strlen(trim(val($conn,"password"))) > 0;
					$p = array(
						"name"=>$db->quote(alt(val($conn,"name"), "New Connection [{$now}]")),
						"type"=>$db->quote(val($conn,"type")),
						"host"=>$db->quote(val($conn,"host")),
						"username"=>$db->quote(val($conn,"username")),
						"password"=>($updatePassword ? $db->quote(val($conn,"password")) : "password"),
						"port"=>$db->quote(val($conn,"port")),
						"defaultDatabase"=>$db->quote(val($conn,"defaultDatabase")),
						"mdate"=>$db->quote($now),
						"cdate"=>$db->quote($now),
						"deleted"=>$db->quote(0)
					);
					$qresult = $db->query(implode(NL, array(
						"UPDATE connection set",
							"name = {$p["name"]},",
							"type = {$p["type"]},",
							"host = {$p["host"]},",
							"username = {$p["username"]},",
							"password = {$p["password"]},",
							"port = {$p["port"]},",
							"default_database = {$p["defaultDatabase"]},",
							"mdate = {$p["mdate"]},",
							"deleted = {$p["deleted"]}",
						"{$where};"
					)));
					$insertedRecords = val(self::get(array("id"=>$db->getInsertedId())), "records");
					if (is_array($insertedRecords)) {
						$records = array_merge($insertedRecords, $records);
					}
					$success = alt($success, true) && ($qresult!==false);
				}
			} catch (Exception $e) {
				logException($e);
				$success = false;
			}
		}		
		
		$result = array(
			"success"=>$success,
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
				$qresult = $db->query(implode(NL, array(
					"DELETE FROM connection WHERE id={$p["id"]};"
				)));
				$success = $qresult!==false;
			} catch (Exception $e) {
				logException($e);
				$success = false;
			}
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function getDatabases($args) {
		$connection = val($args, "connection");
		$db = DatabaseFactory::createDatabase($connection);
		$databases = $db->getDatabases();
		$result = array("records"=>array());
		foreach ($databases as $database) {
			$result["records"][] = array("id"=>$database, "name"=>$database);
		}
		return $result;
	}
	
}
?>