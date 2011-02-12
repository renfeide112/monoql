<?php
class SQLite extends Database {
	
	public function __construct($host=null, $username=null, $password=null, $database=null, $port=null){
		global $config;
		$this->host = alt($host, $this->host, $config["monoql_db_path"]); //this is the path to the file
	}
	
	public function getAffectedRows() {}
	
	public function getNumRows() {}
	
	public function getTotalRows() {}
		
	public function getRecord($associative=true) {
		if (isset($this->result)) {
			$this->record = $this->result->fetch($associative ? PDO::FETCH_ASSOC : PDO::FETCH_BOTH);
			return $this->record;
		}
		return null;
	}
		
	public function getClientInfo() {}
	
	public function getClientVersion() {}
	
	public function getConnectionStats() {}
	
	public function getConnectErrno() {}
	
	public function getConnectError() {}
	
	public function getErrno() {}
	
	public function getError() {}
	
	public function getFieldCount() {}
	
	public function getHostInfo() {}
	
	public function getProtocolVersion() {}
	
	public function getServerInfo() {}
	
	public function getServerVersion() {}
	
	public function getInfo() {}
	
	public function getInsertedID() {
		return isset($this->connection) ? $this->connection->lastInsertId() : null;
	}
	
	public function getSQLState() {}
	
	public function getWarnings() {}
	
	public function getWarningCount() {}

	public function getCharset() {}

	public function getStatus() {}

	public function getCollation() {}
	
	public function getDatabase() {}
	
	public function getDatabases() {}
	
	public function getFunctions($database=null) {}
	
	public function getStoredProcedures($database=null) {}
	
	public function getTables($search=null, $database=null) {}
	
	public function getTriggers($database=null) {}
	
	public function getViews($database=null) {}
	
	public function changeUser($username, $password, $database=null) {}
	
	public function changeDatabase($database) {}
	
	public function changeCharset($charset) {}
	
	public function connect($host=null, $username=null, $password=null, $database=null, $port=null) {
		$error = null;
		if (isset($this->connection)) {
			$error = null;
			return $this->connection;
		} else {
			try {
				$this->connection = new PDO("sqlite:" . alt($host, $this->host));
				return $this->connection;
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		return $error;
	}
	
	public function close() {}
	
	public function query($query) {
		if (strlen($query)>0) {
			$queries = is_array($query) ? $query : array($query);
		}
		if (empty($queries)) {return 0;}
		if (!$this->connect()) {return false;}

		foreach ($queries as $q) {
			if (strlen(trim($q)) > 0) {
				$this->result = $this->connection->query($q);
			}
		}
		
		if (!$this->result) {
			debug("SQLite error on for: $q");
		}
		return $this->result;
	}
	
	public function commit() {}
	
	public function rollback() {}
	
	public function queryValue($value, $emptyStringAsNull=true) {}
	
	public function escape($string) {
		if (!$this->connect()) {return false;}
		return $this->connection->quote($string);
	}
	
	public function encapsulate($string) {}
	
	public function createDatabase($database, $overwrite=false, array $options=null) {
		
	}
	
	public function dropDatabase($database) {}
	
	public function createTable($table, $properties, $enforceConstraints=true, $database=null) {}
	
	public function dropTable($table, $enforceConstraints=true, $database=null) {}
	
	public function truncateTable($table, $enforceConstraints=true) {}
	
	public function emptyDatabase($enforceConstraints=true) {}
	
	public function truncateDatabase($enforceConstraints=true) {}

}
?>
