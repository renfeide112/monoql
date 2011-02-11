<?php
class Connection extends Object {
	
	private $data;
	
	public function __construct(array $data=array()) {
		$this->data = $data;
	}
	
	public static function get(array $filters=array()) {
		$db = Database::getInstance("sqlite");
		
		if (isset($filters["id"])) {
			$where = "WHERE id='" . $db->escape($filters["id"]) . "'";
		} else if (isset($filters["name"])) {
			$where = "WHERE name='" . $db->escape($filters["name"]) . "'";
		} else {
			$where = "";
		}
		
		$db->query("
		SELECT * FROM connection {$where};
		");
		
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
	
	public static function create(array $connections) {
		$db = Database::getInstance("sqlite");
		
		$db->query("
			CREATE TABLE IF NOT EXISTS connection (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				name TEXT,
				type TEXT,
				host TEXT,
				username TEXT,
				password TEXT,
				port INTEGER,
				mdate TEXT,
				cdate TEXT,
				deleted INTEGER
			);
		");
		
		// $connections should have a "records" key that is an array of connection records
		foreach ($connections["records"] as $conn) {
			debug("Creating connection: " . print_r($conn, true));
			$now = date("Y-m-d H:i:s");
			$name = $db->escape(alt(val($conn,"name"), "New Connection [" . date("Y-m-d H:i:s") . "]"));
			$type = $db->escape(val($conn,"type"));
			$host = $db->escape(val($conn,"host"));
			$username = $db->escape(val($conn,"username"));
			$password = sha1($db->escape(val($conn,"password")));
			$port = $db->escape(alt(val($conn,"port"), 0));
			
			$db->query("
				INSERT INTO connection
				(name, type, host, username, password, port, mdate, cdate, deleted) VALUES
				('{$name}', '{$type}', '{$host}', '{$username}', '{$password}', {$port}, '{$now}', '{$now}', 0);
			");
		}
		$response = array(
			"success"=>true
		);
		
		
	}
	
	public static function save(array $connection) {
	}
	
	public static function delete(array $connection) {
	}
}
?>