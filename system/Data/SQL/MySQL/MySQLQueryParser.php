<?php
class MySQLQueryParser extends AbstractQueryParser implements IQueryParser {

	public function isSelect() {
		$q = trim($this->query);
		return !!preg_match('/^SELECT/i', $q);
	}

	public function isInsert() {}

	public function isUpdate() {}

	public function isDelete() {}

	public function isCreate() {}

	public function addLimit($limit) {
		if ($this->isSelect() && !preg_match('/limit\s[0-9]+/i', $this->query)) {
			$this->query = $this->query . " LIMIT {$limit}";
		}
		return $this;
	}
	
	public function addOffset($offset) {
		if ($this->isSelect() && !preg_match('/(limit\s[0-9]+,\s+[0-9]+|offset\s[0-9]+)/i', $this->query)) {
			$this->query = $this->query . " OFFSET {$offset}";
		}
		return $this;
	}
	
	public function addSQLCalcFoundRows() {
		if ($this->isSelect() && !preg_match('/SQL_CALC_FOUND_ROWS/i', $this->query)) {
			$this->query = preg_replace('/^SELECT/i', 'SELECT SQL_CALC_FOUND_ROWS', $this->query);
		}
		return $this;
	}
	
	public function removeComments() {
		$this->query = preg_replace('/--(.*)/i', '', $this->query);
		return $this;
	}
	
	public function removeLastDelimiter() {
		$this->query = preg_replace('/(.*);/i', '$1', $this->query);
		return $this;
	}
	
	public function setup() {
		$this->trim();
		$this->removeLastDelimiter();
		$this->removeComments();
		$this->addSQLCalcFoundRows();
		return $this;
	}
	
	public function trim() {
		$this->query = trim($this->query);
		return $this;
	}
	
}
?>