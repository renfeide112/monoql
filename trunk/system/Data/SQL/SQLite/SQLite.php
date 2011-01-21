<?php
class SQLite extends Database {
	public function __construct($host=null,$database=null,$username=null,$password=null,$port=null){
		$this->host = alt($host, $this->host); //this is the path to the file
	}
	
	public function getAffectedRows() {}
	
	public function getNumRows() {}
	
	public function getTotalRows() {}
		
	public function getRecord($associative=true) {
		$tmp = $this->connection;
		$this->record = $this->connection->fetchAll();
		return $this->record;
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
	
	public function getInsertedID() {}
	
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
		if (isset($this->connection))
		{
			$error = null;
		} else {
			try
			{
				$this->connection = new PDO("sqlite:".$this->host);
				//$this->connection = new SQLiteDatabase($this->host);
				//$this->connection = sqlite_open($this->host);				
				return $this->connection;
			}
			catch (Exception $e)
			{
				$error = $e->getMessage();
			}
			//$error = $this->getConnectErrno();
		}
		return $error;
	}
	
	public function close() {}
	
	public function query($query) {
		if(strlen($query)>0)
		{
			$queries = is_array($query) ? $query : array($query);
		}
		if (empty($queries))
		{
			return 0;
		}
		if (!$this->connect())
		{
			return false;
		}
		

		foreach ($queries as $q)
		{
			if (strlen(trim($q)) > 0)
			{
				$this->result = $this->connection->query($q);
			}
		}
		if (!$this->result)
		{
			debug("SQLite error on for: $q");
		}
		return $this->result;
	}
	
	public function commit() {}
	
	public function rollback() {}
	
	public function queryValue($value, $emptyStringAsNull=true) {}
	
	public function escape($string) {}
	
	public function encapsulate($string) {}
	
	public function createDatabase($database, $overwrite=false, array $options=null) {}
	
	public function dropDatabase($database) {}
	
	public function createTable($table, $properties, $enforceConstraints=true, $database=null) {}
	
	public function dropTable($table, $enforceConstraints=true, $database=null) {}
	
	public function truncateTable($table, $enforceConstraints=true) {}
	
	public function emptyDatabase($enforceConstraints=true) {}
	
	public function truncateDatabase($enforceConstraints=true) {}
}
?>
