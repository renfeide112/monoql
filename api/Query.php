<?php
class Query extends Object {
	
	private $query;
	
	public function __construct($query=null) {
		$this->query = $query;
	}
	
	public static function execute($query, $connectionId, $limit=null, $offset=0, $database=null) {
		$conn = val(val(Connection::get(array("id"=>$connectionId)), "records"), 0);
		$db = DatabaseFactory::createDatabase($conn);
		$success = null;
		$rows = array();
		$message = null;
		$metaData = array();
		$total = null;
		$__id__ = 0;
		
		if ($db) {
			$db->changeDatabase(alt($database, $conn["default_database"]));
			if (!$db->getErrno()) {
				$q = $db->getQueryParser($query)->setup()->addLimit($limit)->addOffset($offset)->getQuery();
				if (!$db->getErrno()) {
					$db->query($q);
					if (!$db->getErrno()) {
						while ($db->getRecord()) {
							if (!!$db->getErrno()) {break;}
							// Add an internal row id that the client side knows will be unique
							$db->record["__id__"] = $__id__++;
							$rows[] = $db->record;
						}
						$total = $db->getTotalRows();
					}
				}
			}
			$success = !$db->getErrno();
			$message = "Error " . $db->getErrno() . ": " . $db->getError();
		}
		
		$metaData = self::buildMetaData($rows);
		$result = array(
			"success"=>$success,
			"total"=>$total,
			"query"=>$query,
			"rows"=>$rows,
			"message"=>$message,
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