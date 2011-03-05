<?php
class ResultGrid extends Object {
	
	public function __construct() {}
	
	// $args should contain at least keys for "query", "connectionId", "start", "limit"
	public static function load($query, $connectionId, $limit=null, $offset=0, $database=null) {
		return Query::execute($query, $connectionId, $limit, $offset, $database);
	}
	
}
?>