<?php
class ConnectionTree extends Object {
	
	public function __construct() {}
	
	public static function getChildNodes($node) {
		$nodeType = substr(strrchr($node, "-"), 1);
		switch ($nodeType) {
			case "connectiongroup":
				$children = self::getConnectionGroupChildNodes($node);
				break;
			default:
				$children = array();
		}
		return $children;
	}
	
	// Each child node must pass a "connectionId" attribute
	public static function getConnectionGroupChildNodes($node) {
		$children = array();
		foreach (val(Connection::get(), "records") as $conn) {
			$children[] = array(
				"text"=>$conn["name"],
				"nodeType"=>"monoql-tree-connectionnode",
				"connectionId"=>$conn["id"]
			);
		}
		return $children;
	}
	
}
?>