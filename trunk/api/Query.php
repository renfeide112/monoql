<?php
class Query extends Object {
	
	private $query;
	
	public function __construct($query=null) {
		$this->query = $query;
	}
	
	public static function execute($query, $connectionId, $limit=null, $offset=0, $database=null) {
		$conn = ConnectionFactory::createConnection(Connection::getById($connectionId));
		$success = null;
		$rows = array();
		$message = null;
		$metaData = array();
		$total = null;
		$__id__ = 0;
		
		try {
			$conn->changeDatabase($database);
			$q = $conn->getQueryParser($query)->setup()->addLimit($limit)->addOffset($offset)->getQuery();
			$conn->query($q);
			while ($conn->getRecord()) {
				$conn->record["__id__"] = $__id__++;
				$rows[] = $conn->record;
			}
			$total = $conn->getTotalRows();
			$success = !$conn->getErrno();
			$message = $success ? "Success!" : "Error " . $conn->getErrno() . ": " . $conn->getError();
		} catch (Exception $e) {
			logException($e);
			$success = false;
			$message = "Error " . $conn->getErrno() . ": " . $conn->getError();
		}
		$message = $message . NL . NL . $conn->getAffectedRows() . " rows affected";
		
		$metaData = self::buildMetaData($rows);
		$result = array(
			"success"=>$success,
			"total"=>$total,
			"query"=>$query,
			"rows"=>$rows,
			"message"=>nl2br($message),
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