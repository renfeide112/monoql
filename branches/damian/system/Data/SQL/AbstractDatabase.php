<?php
/**
 * Database class to be extended by various vendor database types
 */
class AbstractDatabase extends Object {
	
	public $record;
	public $le;
	public $re;
	
	protected $connection;
	protected $result;
	protected $host;
	protected $username;
	protected $password;
	protected $database;
	protected $port;
	protected $cache = array();
	protected $queryParser;
	
}
?>