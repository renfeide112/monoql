<?php
class ConnectionRecord extends Object {

	private $connection;
	private $data = array();
	
	///////////////////////////////////////////////////////////////
	// Public Methods
	///////////////////////////////////////////////////////////////
	
	public function __construct($args=null) {
		global $config;
		$this->connection = ConnectionFactory::createConnection("sqlite", $config["monoql_db_path"]);
		if (is_array($args)) {
			if (isset($args["id"])) {
				$this->constructFromId($args["id"]);
			} else {
				$this->constructFromArray($args);
				$this->data["id"] = null;
			}
		} else if (isset($args)) {
			$this->constructFromId($args);
		}
	}
	
	public static function get($args) {
		return new ConnectionRecord($args);
	}
	
	public static function add($args) {
		self::get($args)->save();
	}
	
	public function save() {
		if ($this->isReal()) {
			$this->create();
		} else {
			$this->update();
		}
	}
	
	public function delete() {
		try {
			$this->connection->query("DELETE FROM connection WHERE id='{$this->properties["id"]}';");
			return true;
		} catch (Exception $e) {
			logException($e);
			return false;
		}
	}
	
	///////////////////////////////////////////////////////////////
	// Private Methods
	///////////////////////////////////////////////////////////////
	
	private function create() {
		try {
			$now = date("Y-m-d H:i:s");
			$p = array(
				"name"=>$this->connection->quote(alt(val($this->data,"name"), "New this->dataection [{$now}]")),
				"type"=>$this->connection->quote(val($this->data,"type")),
				"host"=>$this->connection->quote(val($this->data,"host")),
				"username"=>$this->connection->quote(val($this->data,"username")),
				"password"=>$this->connection->quote(val($this->data,"password")),
				"port"=>$this->connection->quote(val($this->data,"port")),
				"default_database"=>$this->connection->quote(val($this->data,"defaultDatabase")),
				"mdate"=>$this->connection->quote($now),
				"cdate"=>$this->connection->quote($now),
				"deleted"=>$this->connection->quote(0)
			);
			$result = $this->connection->query(implode(NL, array(
				"INSERT INTO connection",
				"(name, type, host, username, password, port, default_database, mdate, cdate, deleted)",
				"VALUES",
				"({$p["name"]}, {$p["type"]}, {$p["host"]}, {$p["username"]}, {$p["password"]}, {$p["port"]}, {$p["defaultDatabase"]}, {$p["mdate"]}, {$p["cdate"]}, {$p["deleted"]});"
			)));
			$this->data["id"] = $this->connection->getInsertedId();
			return true;
		} catch (Exception $e) {
			logException($e);
			return false;
		}
	}
	
	private function update() {
		try {
			$now = date("Y-m-d H:i:s");
			$p = array(
				"name"=>$this->connection->quote(alt(val($this->data,"name"), "New this->dataection [{$now}]")),
				"type"=>$this->connection->quote(val($this->data,"type")),
				"host"=>$this->connection->quote(val($this->data,"host")),
				"username"=>$this->connection->quote(val($this->data,"username")),
				"password"=>$this->connection->quote(val($this->data,"password")),
				"port"=>$this->connection->quote(val($this->data,"port")),
				"default_database"=>$this->connection->quote(val($this->data,"defaultDatabase")),
				"mdate"=>$this->connection->quote($now),
				"cdate"=>$this->connection->quote($now),
				"deleted"=>$this->connection->quote(0)
			);
			$result = $this->connection->query(implode(NL, array(
				"UPDATE connection SET",
					"name = {$p["name"]},",
					"type = {$p["type"]},",
					"host = {$p["host"]},",
					"username = {$p["username"]},",
					"password = {$p["password"]},",
					"port = {$p["port"]},",
					"default_database = {$p["defaultDatabase"]},",
					"mdate = {$p["mdate"]},",
					"deleted = {$p["deleted"]}",
				"WHERE id='{$this->properties["id"]}';"
			)));
			return true;
		} catch {
			logException($e);
			return false;
		}
	}
	
	private function constructFromArray(array $data=array()) {
		foreach ($data as $property=>$value) {
			$this->data[$property] = $value;
		}
	}
	
	private function constructFromId($id) {
		global $config;
		$this->constructFromArray($this->getRecordFromDatabase($id));
	}
	
	private function getRecordFromDatabase($id) {
		try {
			$this->connection->query("SELECT * FROM connection WHERE id='{$id}';");
			return $this->connection->getRecord();
		} catch {
			logException($e);
			return false;
		}
	}
	
	private function isReal() {
		$id = val($this->data, "id");
		return (isset($id) && is_numeric($id) && intval($id)>0);
	}
	
}
?>