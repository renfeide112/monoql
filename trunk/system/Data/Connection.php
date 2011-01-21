<?php
class DataConnection extends Object implements IDataConnection {
	
	private $nodeTypes = array(
		"backupgroup", 
		"backup", 
		"connectiongroup", 
		"connection", 
		"database", 
		"functiongroup",
		"function", 
		"scriptgroup", 
		"script", 
		"sprocgroup", 
		"sproc", 
		"tablegroup", 
		"table", 
		"triggergroup", 
		"trigger", 
		"usergroup", 
		"user", 
		"viewgroup", 
		"view"
	);
	
	public function getConnections() {
		$response = array("connections"=>array());
		$response["connections"][] = array(
			"id"=>1,
			"name"=>"localhost",
			"host"=>"localhost",
			"username"=>"root",
			"type"=>"mysql",
			"port"=>3306
		);
		return $response;
	}
	
	public function getChildNodes() {
		$nodeId = alt(val(Request::$data, "node"), 0);
		
		$children = array();
		
		$numChildren = rand(1, 5);
		for ($i=1; $i<=$numChildren; $i++) {
			$nodeType = $this->getRandomNodeType();
			$children[] = array("text"=>str_replace("monoql-tree-", "", $nodeType) . "-" . uniqid(), "nodeType"=>$nodeType);
		}
		
		return $children;
	}
	
	private function getRandomNodeType() {
		return "monoql-tree-" . $this->nodeTypes[rand(0, 19)] . "node";
	}
	
	public function getDatabaseNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getBackupGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getBackupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getConnectionGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getConnectionNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getFunctionGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getFunctionNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getScriptGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getScriptNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getSprocGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getSprocNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getTableGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getTableNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getTriggerGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getTriggerNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getUserGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getUserNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getViewGroupNodeChildren() {
		return $this->getChildNodes();
	}
	
	public function getViewNodeChildren() {
		return $this->getChildNodes();
	}
	
}
?>