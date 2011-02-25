<?php
class MySQL extends AbstractDatabase implements IDatabase {
	
	public function __construct($host=null, $username=null, $password=null, $database=null, $port=null) {
		global $config;
		
		$this->le = "`";
		$this->re = "`";
		
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->port = alt($port, 3306);
	}
	
	public function getAffectedRows() {
	}
	
	public function getNumRows() {
		if (isset($this->result)) {
			return $this->result->num_rows;
		} else {
			return false;
		}
	}
	
	public function getTotalRows() {
		$result = $this->query("SELECT FOUND_ROWS();");
		return $this->getRecord(false) ? $this->record[0] : null;
	}
		
	public function getRecord($associative=true) {
		$format = $associative ? MYSQLI_ASSOC : MYSQLI_NUM;
		$this->record = is_object($this->result) ? $this->result->fetch_array($format) : null;
		return $this->record;
	}
		
	public function getClientInfo() {
	}
	
	public function getClientVersion() {
	}
	
	public function getConnectionStats() {
	}
	
	public function getConnectErrno() {
		return $this->connection ? $this->connection->connect_errno : null;
	}
	
	public function getConnectError() {
		return $this->connection ? $this->connection->connect_error : null;
	}
	
	public function getErrno() {
		return $this->connection ? $this->connection->errno : null;
	}
	
	public function getError() {
		return $this->connection ? $this->connection->error : null;
	}
	
	public function getFieldCount() {
	}
	
	public function getHostInfo() {
	}
	
	public function getProtocolVersion() {
	}
	
	public function getServerInfo() {
	}
	
	public function getServerVersion() {
	}
	
	public function getInfo() {
	}
	
	public function getInsertedID() {
		return $this->connection->insert_id;
	}
	
	public function getSQLState() {
	}
	
	public function getWarnings() {
	}
	
	public function getWarningCount() {
	}

	public function getCharset() {
	}

	public function getStatus() {
	}

	public function getCollation() {
	}
	
	public function getDatabase() {
		return $this->database;
	}
	
	public function getDatabases() {
		$databases = array();
		$query = "SHOW DATABASES";
		$this->query($query);
		while ($this->getRecord()) {
			$databases[] = $this->record["Database"];
		}
		return $databases;
	}
	
	public function getFunctions($database=null) {
		$functions = array();
		$database = isset($database) ? $database : $this->database;
		$query = "SHOW FUNCTION STATUS WHERE Db='{$database}'";
		$this->query($query);
		while ($this->getRecord()) {
			$functions[] = $this->record["Name"];
		}
		$functions = array_unique($functions);
		sort($functions);
		return $functions;
	}
	
	public function getStoredProcedures($database=null) {
		$sprocs = array();
		$database = isset($database) ? $database : $this->database;
		$query = "SHOW PROCEDURE STATUS WHERE Db='{$database}'";
		$this->query($query);
		while ($this->getRecord()) {
			$sprocs[] = $this->record["Name"];
		}
		$sprocs = array_unique($sprocs);
		sort($sprocs);
		return $sprocs;
	}
	
	public function getTables($search=null, $database=null) {
		$tables = array();
		$database = isset($database) ? $database : $this->database;
		$query = "SHOW FULL TABLES FROM {$database} WHERE Table_type='BASE TABLE'" . (isset($search) ? " AND Tables_in_{$database} LIKE '%{$search}%'" : "");
		$this->query($query);
		while ($this->getRecord(false)) {
			$tables[] = $this->record[0];
		}
		$tables = array_unique($tables);
		sort($tables);
		return $tables;
	}
	
	public function getTriggers($database=null) {
		$triggers = array();
		$database = isset($database) ? $database : $this->database;
		$query = "SHOW TRIGGERS FROM {$database}";
		$this->query($query);
		while ($this->getRecord()) {
			$triggers[] = $this->record["Trigger"];
		}
		$triggers = array_unique($triggers);
		sort($triggers);
		return $triggers;
	}
	
	public function getViews($database=null) {
		$views = array();
		$database = isset($database) ? $database : $this->database;
		$query = "SHOW FULL TABLES FROM {$database} WHERE Table_type='VIEW'" . (isset($search) ? " AND Tables_in_{$database} LIKE '%{$search}%'" : "");
		$this->query($query);
		while ($this->getRecord(false)) {
			$views[] = $this->record[0];
		}
		$views = array_unique($views);
		sort($views);
		return $views;
	}
	
	public function changeUser($username, $password, $database=null) {
		$database = isset($database) ? $database : $this->database;
		$this->connect(null, $username, $password, $database);
		return $this;
	}
	
	public function changeDatabase($database) {
		$database = isset($database) ? $database : $this->database;
		if ($database) {
			$this->connect()->select_db($database);
		}
		return $this;
	}
	
	public function changeCharset($charset) {
		$this->connect();
		$this->connection->set_charset($charset);
	}
	
	public function connect($host=null, $username=null, $password=null, $database=null, $port=null) {
		if (!isset($this->connection)) {
			$host = alt($host, $this->host);
			$username = alt($username, $this->username);
			$password = alt($password, $this->password);
			$database = alt($database, $this->database);
			$port = alt($port, $this->port);
			$this->connection = new mysqli($host, $username, $password, $database, $port);
		}
		return $this->connection;
	}
	
	public function close() {
		$this->connection->close();
	}
	
	public function query($query) {
		if (!$this->connect()) {
			throw new Exception("Unable to connect and run query: {$query}");
		}
		$this->result = $this->connection->query($query);
		if ($this->result===false) {
			throw new Exception("Error during MySQL Query: {$query}");
		}
	}
	
	public function commit() {
	}
	
	public function rollback() {
	}
	
	public function quote($string) {
		return "'{$this->escape($string)}'"; 
	}
	
	public function escape($string) {
		$this->connect();
		return $this->connection->real_escape_string($string);
	}
	
	public function encapsulate($string) {
		return $this->le . $string . $this->re;
	}
	
	public function createDatabase($database, $overwrite=false, array $options=null) {
		$query = "CREATE DATABASE";
		$query .= $overwrite ? " IF NOT EXISTS" : "";
		$query .= " {$this->encapsulate($database)}";
		if (isset($options)) {
			$query .= isset($options["charset"]) ? " DEFAULT CHARACTER SET = {$options["charset"]}" : "";
			$query .= isset($options["collation"]) ? " DEFAULT COLLATE = {$options["collation"]}" : "";
		}
		$status = $this->query($query);
		return $status;
	}
	
	public function dropDatabase($database) {
		$query = "DROP DATABASE IF EXISTS " . $this->encapsulate($database);
		$status = $this->query($query);
		return $status;
	}
	
	public function createTable($table, $properties, $enforceConstraints=true, $database=null) {
	}
	
	public function dropTable($table, $enforceConstraints=true, $database=null) {
	}
	
	public function truncateTable($table, $enforceConstraints=true) {
	}
	
	public function emptyDatabase($enforceConstraints=true) {
	}
	
	public function truncateDatabase($enforceConstraints=true) {
	}
	
	public function getQueryParser($query) {
		return isset($this->queryParser) ? $this->queryParser->setQuery($query) : new MySQLQueryParser($query);
	}
	
}
?>