<?php
class MySQLConnection extends AbstractConnection implements IConnection {
	
	public function __construct($host=null, $username=null, $password=null, $dbname=null, $port=null) {
		global $config;
		
		$this->le = "`";
		$this->re = "`";
		
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->dbname = $dbname;
		$this->port = intval(alt($port, 3306));
	}
	
	public function getAffectedRows() {
		return max(0, $this->connect()->affected_rows);
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
		return new MySQLDatabase($this->dbname,$this);
	}
	
	public function getDatabases() {
		$dbnames = array();
		$query = "SHOW DATABASES";
		$tmp=$this;
		$this->query($query);
		while ($this->getRecord()) {
			$dbnames[] = $this->record["Database"];
		}
		return $dbnames;
	}
	
	public function changeUser($username, $password, $dbname=null) {
		$dbname = isset($dbname) ? $dbname : $this->dbname;
		$this->connect(null, $username, $password, $dbname);
		return $this;
	}
	
	public function changeDatabase($dbname) {
		if (isset($dbname)) {
			$this->connect()->select_db($dbname);
		}
		return $this;
	}
	
	public function changeCharset($charset) {
		$this->connect();
		$this->connection->set_charset($charset);
	}
	
	public function connect($host=null, $username=null, $password=null, $dbname=null, $port=null) {
		if (!isset($this->connection)) {
			$host = alt($host, $this->host);
			$username = alt($username, $this->username);
			$password = alt($password, $this->password);
			$dbname = alt($dbname, $this->dbname);
			$port = intval(alt($port, $this->port));
			$this->connection = new mysqli($host, $username, $password, $dbname, $port);
			if (!!$this->getConnectErrno()) {throw new Exception($this->getConnectError());}
		}
		return $this->connection;
	}
	
	public function close() {
		$this->connection->close();
	}
	
	public function query($query) {
		debug($query);
		$this->result = $this->connect()->query($query);
		if ($this->result===false) {throw new Exception($this->getError());}
		return $this->result;
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
	
	public function getQueryParser($query) {
		return isset($this->queryParser) ? $this->queryParser->setQuery($query) : new MySQLQueryParser($query);
	}
	
	public function enforceConstraint($query,$enforceConstraints=true){
		if($enforceConstraints)
		{
			return " SET foreign_key_checks = 0;{$query};SET foreign_key_checks = 1;";
		}
		return $query;
	}

}
?>