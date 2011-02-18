<?php
class Query extends Object {
	
	private $query;
	
	public function __construct($query=null) {
		$this->query = $query;
	}
	
	public static function execute($query, $connectionId) {
		$conn = val(val(Connection::get(array("id"=>$connectionId)), "records"), 0);
		$db = DatabaseFactory::createDatabase($conn);
		$rows = array();
		$messages = array();
		$metaData = array();
		
		if ($db) {
			$db->query($query);
			while ($db->getRecord()) {
				$rows[] = $db->record;
			}
		}
		
		$metaData = self::buildMetaData($rows);
		
		$result = array(
			"success"=>true,
			"query"=>$query,
			"rows"=>$rows,
			"messages"=>$messages,
			"metaData"=>$metaData
		);
		
		return $result;
	}
	
	// This must be an configuration array for the client-side
	// result grid reader.  It should contain a "fields" key to
	// configure the grid record fields, and thus the column model
	public static function buildMetaData(array $rows=array()) {
		$meta = array();
		return $meta;
	}
	
}
?>