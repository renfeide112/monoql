<?php
class Query extends Object {
	
	private $query;
	
	public function __construct($query=null) {
		$this->query = $query;
	}
	
	public static function execute($query, $conn) {
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