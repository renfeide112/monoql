<?php
class Query extends Object {
	
	private $data = array();
	
	public function __construct(array $data) {
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
		
		$response = array(
			"success"=>true,
			"query"=>$this->p("query"),
			"rows"=>$rows
		);
		
		JSON::send($response);
		return true;
	}
	
}
?>