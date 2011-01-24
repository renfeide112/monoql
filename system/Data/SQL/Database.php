<?php
/**
 * Database class to be extended by various vendor database types
 */
class Database extends Object {
	
	public $connection;
	public $result;
	public $record;
	public $le;
	public $re;
	
	protected $host;
	protected $username;
	protected $password;
	protected $database;
	protected $port;
	protected $cache = array();
	
	private static $instances = array();
	
	private function __construct() {}
	
	/**
	 * Get a new or used instance of one of the database object types 
	 * 
	 * @param string $type The type of database connection [mysql|mssql|oracle|sqlite]
	 * @param string $host The host name of the database server (give full path for SQLite database)
	 * @param string $username The username for the connection
	 * @param string $password The password for the username
	 * @param string $database The default database for the connection
	 * @param int $port The port number to connect to the server
	 */
	public static function getInstance($type=null, $host=null, $username=null, $password=null, $database=null, $port=null) {
		global $config;
		
		$type = alt($type, $config["default_database_type"]);
		if (isset(self::$instances[$type])) {
			$instance = self::$instances[$type];
		} else {
			switch ($type) {
				case "mssql":
					$instance = new MSSQL($host, $username, $password, $database, $port);
					break;
				case "oracle":
					$instance = new Oracle($host, $username, $password, $database, $port);
					break;
				case "sqlite":
					$instance = new SQLite($host, $username, $password, $database, $port);
					break;
				case "mysql":
					$instance = new MySQL($host, $username, $password, $database, $port);
					break;
			}
			self::$instances[$type] = $instance;
		}
		
		return $instance;
	}
	
	public function getAffectedRows() {
	
	}
	
	public function getNumRows() {
	
	}
	
	public function getTotalRows() {
	
	}
		
	public function getRecord($associative=true) {
	
	}
		
	public function getClientInfo() {
	
	}
	
	public function getClientVersion() {
	
	}
	
	public function getConnectionStats() {
	
	}
	
	public function getConnectErrno() {
	
	}
	
	public function getConnectError() {
	
	}
	
	public function getErrno() {
	
	}
	
	public function getError() {
	
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
	
	}
	
	public function getDatabases() {
	
	}
	
	public function getFunctions($database=null) {
	
	}
	
	public function getStoredProcedures($database=null) {
	
	}
	
	public function getTables($search=null, $database=null) {
	
	}
	
	public function getTriggers($database=null) {
	
	}
	
	public function getViews($database=null) {
	
	}
	
	public function changeUser($username, $password, $database=null) {
	
	}
	
	public function changeDatabase($database) {
	
	}
	
	public function changeCharset($charset) {
	
	}
	
	public function connect($host=null, $username=null, $password=null, $database=null, $port=null) {
	
	}
	
	public function close() {
	
	}
	
	public function query($query) {
	
	}
	
	public function commit() {
	
	}
	
	public function rollback() {
	
	}
	
	public function queryValue($string) {
	
	}
	
	public function escape($string) {
	
	}
	
	public function encapsulate($string) {
	
	}
	
	public function createDatabase($database, $overwrite=false, array $options=null) {
	
	}
	
	public function dropDatabase($database) {
	
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
	
}
?>