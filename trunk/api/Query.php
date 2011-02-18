<?php
class Query extends Object {
	
	private $query;
	
	public function __construct($query=null) {
		$this->query = $query;
	}
	
	public static function execute($query, $connectionId) {
		$conn = val(val(Connection::get(array("id"=>$connectionId)), "records"), 0);
		debug(print_r($conn, true));
		$db = DatabaseFactory::createDatabase($conn);
		$rows = array();
		
		if ($db) {
			$db->query($query);
			while ($db->getRecord()) {
				$rows[] = $db->record;
			}
		}
		
		$result = array(
			"success"=>true,
			"query"=>$query,
			"rows"=>$rows
		);
		
		return $result;
	}
	
}
?>