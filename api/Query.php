<?php
class Query extends Object {
	
	private $data = array();
	
	public function __construct(array $data=null) {
		$this->data = $data;
	}
	
	public function execute() {
		$conn = JSON::decode($this->p("connection"));
		$db = DatabaseFactory::createDatabase($conn->type, $conn->host, $conn->username, $conn->password, $conn->database, $conn->port);
		$rows = array();
		
		if ($db) {
			$result = $db->query($this->p("query"));
			while ($db->getRecord()) {
				$rows[] = $db->record;
			}
		}
		
		$result = array(
			"success"=>true,
			"query"=>$this->p("query"),
			"rows"=>$rows
		);
		
		return $result;
	}
	
}
?>