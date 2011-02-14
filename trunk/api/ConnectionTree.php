<?php
class ConnectionTree extends Object {
	
	public function __construct() {}
	
	public static function getChildNodes($node) {
		$nodeType = substr(strrchr($node, "-"), 1);
		switch ($nodeType) {
			case "connectiongroup":
				$children = self::getConnectionNodeChildren($node);
				break;
			default:
				$children = array();
		}
		return $children;
	}
	
	public static function getConnectionGroupChildNodes($node) {
		$children = array();
		foreach (val(Connection::get(), "records") as $conn) {
			$children[] = array_merge($conn, array(
				"text"=>$conn["name"],
				"nodeType"=>"monoql-tree-connectionnode"
			));
		}
		return $children;
	}
	
}
?>