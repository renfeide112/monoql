<?php
class Query extends Object {
	
	private $data;
	
	public function __construct(array $data=array()) {
		$this->data = $data;
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