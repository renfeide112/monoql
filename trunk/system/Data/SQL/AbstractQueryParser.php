<?php
class AbstractQueryParser extends Object {
	
	public $originalQuery;
	protected $query;
	
	public function __construct($args) {
		if (is_array($args)) {
			$this->originalQuery = val($args, "query");
		} else {
			$this->originalQuery = $args;
		}
		$this->query = $this->originalQuery;
	}
	
	public function getQuery() {
		return $this->query;
	}
	
}
?>