<?php
interface IDatabase {

	public function getAffectedRows();
	
	public function getNumRows();
	
	public function getTotalRows();
		
	public function getRecord($associative=true);
		
	public function getClientInfo();
	
	public function getClientVersion();
	
	public function getConnectionStats();
	
	public function getConnectErrno();
	
	public function getConnectError();
	
	public function getErrno();
	
	public function getError();
	
	public function getFieldCount();
	
	public function getHostInfo();
	
	public function getProtocolVersion();
	
	public function getServerInfo();
	
	public function getServerVersion();
	
	public function getInfo();
	
	public function getInsertedID();
	
	public function getSQLState();
	
	public function getWarnings();
	
	public function getWarningCount();

	public function getCharset();

	public function getStatus();

	public function getCollation();
	
	public function getDatabase();
	
	public function getDatabases();
	
	public function getFunctions($database=null);
	
	public function getStoredProcedures($database=null);
	
	public function getTables($search=null, $database=null);
	
	public function getTriggers($database=null);
	
	public function getViews($database=null);
	
	public function changeUser($username, $password, $database=null);
	
	public function changeDatabase($database);
	
	public function changeCharset($charset);
	
	public function connect($host=null, $username=null, $password=null, $database=null, $port=null);
	
	public function close();
	
	public function query($query);
	
	public function commit();
	
	public function rollback();
	
	public function queryValue($string);
	
	public function escape($string);
	
	public function encapsulate($string);
	
	public function createDatabase($database, $overwrite=false, array $options=null);
	
	public function dropDatabase($database);
	
	public function createTable($table, $properties, $enforceConstraints=true, $database=null);
	
	public function dropTable($table, $enforceConstraints=true, $database=null);
	
	public function truncateTable($table, $enforceConstraints=true);
	
	public function emptyDatabase($enforceConstraints=true);
	
	public function truncateDatabase($enforceConstraints=true);
	
}
?>