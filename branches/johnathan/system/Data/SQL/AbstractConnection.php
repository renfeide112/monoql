<?php
/**
 * Connection class to be extended by various vendor connection types
 */
class AbstractConnection extends Object {
	
	public $record;
	public $le;
	public $re;
	
	protected $connection;
	protected $result;
	protected $host;
	protected $username;
	protected $password;
	protected $dbname;
	protected $port;
	protected $cache = array();
	protected $queryParser;

}
?>