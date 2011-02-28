<?php
class SQLite extends AbstractDatabase implements IDatabase {
	
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
			if ($this->record===false && !!val($this->result->errorInfo(), 2)) {
				throw new Exception("Unable to fetch PDO SQLite record from PDO Statement for query: {$this->result->queryString}");
			}
			return $this->record;
		}
		throw new Exception("PDO SQLite Statement object not set");
	}
		
	public function getClientInfo() {}
	
	public function getClientVersion() {}
	
	public function getConnectionStats() {}
	
	public function getConnectErrno() {}
	
	public function getConnectError() {}
	
	public function getErrno() {
		return isset($this->connection) ? val($this->connection->errorInfo(), 1) : null;
	}
	
	public function getError() {
		return isset($this->connection) ? val($this->connection->errorInfo(), 2) : null;
	}
	
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
	
	public function getColumns($table, $database=null) {}
	
	public function getTriggers($database=null) {}
	
	public function getViews($database=null) {}
	
	public function changeUser($username, $password, $database=null) {}
	
	public function changeDatabase($database) {}
	
	public function changeCharset($charset) {}
	
	public function connect($host=null, $username=null, $password=null, $database=null, $port=null) {
		if (!isset($this->connection)) {
			// The PDO constructor will throw an exception on failure
			$this->connection = new PDO("sqlite:" . alt($host, $this->host));
		};
		return $this->connection;
	}
	
	public function close() {}
	
	public function query($query) {
		if (empty($query)) {return false;}
		if (!$this->connect()) {return false;}
		$result = null;
		$queries = is_array($query) ? $query : array($query);
		foreach ($queries as $q) {
			if (strlen(trim($q)) > 0) {
				$result = $this->connection->query($q);
				if ($result===false) {
					throw new Exception($this->getError() . " for {$q}");
				}
			}
		}
		$this->result = $result===false ? false : $result;
		return $this->result;
	}
	
	public function commit() {}
	
	public function rollback() {}
	
	public function quote($string) {
		if (!$this->connect()) {return false;}
		$quoted = $this->connection->quote($string);
		return $quoted===false ? string : $quoted;
	}
	
	public function escape($string) {
		return substr($this->quote($string), 1, -1);
	}
	
	public function encapsulate($string) {}
	
	public function createDatabase($database, $overwrite=false, array $options=null) {}
	
	public function dropDatabase($database) {}
	
	public function createTable($table, $properties, $enforceConstraints=true, $database=null) {}
	
	public function dropTable($table, $enforceConstraints=true, $database=null) {}
	
	public function truncateTable($table, $enforceConstraints=true) {}
	
	public function emptyDatabase($enforceConstraints=true) {}
	
	public function truncateDatabase($enforceConstraints=true) {}
	
	public function getQueryParser($query) {
		return isset($this->queryParser) ? $this->queryParser->setQuery($query) : new SQLiteQueryParser($query);
	}

}
?>
