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
		
		$rows = array();
		while ($db->getRecord()) {
			$rows[] = $db->record;
		}
		
		$result = array(
			"success"=>true,
			"connections"=>$rows
		);
		
		return $result;
	}
	
	public static function create(array $connection) {
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
		
		$now = date("Y-m-d H:i:s");
		$name = $db->escape(alt($this->p("name"), "New Connection [" . date("Y-m-d H:i:s") . "]"));
		$type = $db->escape($this->p("type"));
		$host = $db->escape($this->p("host"));
		$username = $db->escape($this->p("username"));
		$password = sha1($db->escape($this->p("password")));
		$port = $db->escape(alt($this->p("port"), 0));
		
		$db->query("
			INSERT INTO connection
			(name, type, host, username, password, port, mdate, cdate, deleted) VALUES
			('{$name}', '{$type}', '{$host}', '{$username}', '{$password}', {$port}, '{$now}', '{$now}', 0);
		");
		
		$response = array(
			"success"=>true
		);
		
		JSON::send($response);
	}
	
	public static function save(array $connection) {
	}
	
	public static function delete(array $connection) {
	}
}
?>