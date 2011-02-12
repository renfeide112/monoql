<?php
class ConnectionTree extends Object {
	
	public function __construct() {}
	
	public static function getChildNodes($node) {
		$nodeType = substr(strrchr($node, "-"), 1);
		$children = array();
		foreach (val(Connection::get(), "records") as $conn) {
			$children[] = array_merge($conn, array(
				"text"=>$conn["name"]
			));
		}
		return $children;
	}
	
}
?>