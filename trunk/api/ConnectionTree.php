<?php
class ConnectionTree extends Object {
	
	private $data;
	
	public function __construct(array $data=array()) {
		$this->data = $data;
	}
	
	public static function getChildNodes($node) {
		$nodeType = substr(strrchr($node, "-"), 1);
		debug($nodeType);
		$children = array();
		return $children;
	}
	
}
?>