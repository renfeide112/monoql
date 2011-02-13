<?php
/**
 * Database class to be extended by various vendor database types
 */
class AbstractDatabase extends Object {
	
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
	
}
?>