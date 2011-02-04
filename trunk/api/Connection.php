<?php
class Connection extends Object {
	
	private $data;
	
	public function __construct(array $data=array()) {
		$this->data = $data;
	}
	
	public function get() {
		$db = Database::getInstance("sqlite");
		
		if (isset($this->$data["id"])) {
			$where = "WHERE id='" . $this->$data["id"] . "'";
		} else if (isset($this->$data["name"])) {
			$where = "WHERE name='" . $this->$data["name"] . "'";
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
		
		$response = array(
			"success"=>true,
			"rows"=>$rows
		);
		
		JSON::send($response);		
	}
	
	public function add() {
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
	
}
?>