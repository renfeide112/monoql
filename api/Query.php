<?php
class Query extends Object {
	
	private $query;
	
	public function __construct($query=null) {
		$this->query = $query;
	}
	
	public static function execute($query, $connectionId, $database=null) {
		$conn = val(val(Connection::get(array("id"=>$connectionId)), "records"), 0);
		$db = DatabaseFactory::createDatabase($conn);
		$rows = array();
		$messages = array();
		$metaData = array();
		$total = null;
		$__id__ = 0;
		
		if ($db) {
			$db->changeDatabase(alt($database, $conn["default_database"]));
			$q = $db->getQueryParser($query)->setup()->addLimit(100)->addOffset(0)->getQuery();
			debug("Original Query: {$query}");
			debug("Modified Query: {$q}");
			$db->query($q);
			while ($db->getRecord()) {
				// Add an internal row id that the client side knows will be unique
				$db->record["__id__"] = $__id__++;
				$rows[] = $db->record;
			}
			$total = $db->getTotalRows();
		}
		
		
		$metaData = self::buildMetaData($rows);
		
		$result = array(
			"success"=>true,
			"total"=>$total,
			"query"=>$query,
			"rows"=>$rows,
			"messages"=>$messages,
			"metaData"=>$metaData
		);
		
		return $result;
	}
	
	// This must be a configuration array for the client-side
	// result grid reader.  It should contain a "fields" key to
	// configure the grid record fields, and thus the column model
	public static function buildMetaData(array $rows=array()) {
		$meta = array(
			"idProperty"=>"__id__",
			"root"=>"rows",
			"totalProperty"=>"total",
			"successProperty"=>"success",
			"messageProperty"=>"message",
			"fields"=>array()
		);
		if (count($rows)>0) {
			foreach ($rows[0] as $field=>$value) {
				$meta["fields"][] = array("name"=>$field);
			}
		}
		return $meta;
	}
	
}
?>