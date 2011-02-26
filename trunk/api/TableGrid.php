<?php
class TableGrid extends Object {
	
	public function __construct() {}
	
	// $args should contain at least keys for "query", "connectionId", "start", "limit"
	public static function load($table, $connectionId, $limit=null, $offset=0, $sort=null, $dir=null, $database=null) {
		$order = isset($sort) ? " ORDER BY {$sort}" . (isset($dir) ? " {$dir}" : null) : null;
		$query = "SELECT * FROM {$table}{$order}";
		return Query::execute($query, $connectionId, $limit, $offset, $database);
	}
	
}
?>