<?php
class Connection extends Object {
	
	public function __construct() {}
	
	public static function get($id=null) {
		try {
			$records = array();
			$connections = empty($id) ? ConnectionRecord::getAll() : array(ConnectionRecord::get($id));
			foreach ($connections as $connection) {
				$record = $connection->getData();
				unset($record["password"]);
				$records[] = $record;
			}
			$success = true;
		} catch (Exception $e) {
			logException($e);
			$success = false;
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function create(array $connections) {
		$success = null;
		$records = array();
		
		try {
			foreach ($connections["records"] as $conn) {
				$records[] = ConnectionRecord::add($conn)->getData();
			}
			$success = true;
		} catch (Exception $e) {
			logException($e);
			$success = false;
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function save(array $connections) {
		$success = null;
		$records = array();
		
		try {
			foreach ($connections["records"] as $conn) {
				$record = ConnectionRecord::get($conn["id"])->set($conn);
				$record->save();
				$records[] = $record->getData();
			}
			$success = true;
		} catch (Exception $e) {
			logException($e);
			$success = false;
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function delete(array $connections) {
		$success = null;
		$records = array();
		
		try {
			foreach ($connections["records"] as $conn) {
				$records[] = ConnectionRecord::get($conn["id"])->delete();
			}
			$success = true;
		} catch (Exception $e) {
			logException($e);
			$success = false;
		}
		
		$result = array(
			"success"=>$success,
			"records"=>$records
		);
		
		return $result;
	}
	
	public static function getDatabases($args) {
		$conn = ConnectionFactory::createConnection(ConnectionRecord::get($args["connectionId"])->getData());
		$result = array("records"=>array());
		foreach ($conn->getDatabases() as $database) {
			$result["records"][] = array("id"=>$database->name, "name"=>$database->name);
		}
		return $result;
	}
	
}
?>