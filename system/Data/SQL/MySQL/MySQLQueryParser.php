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
		return $this;
	}
	
	public function addOffset($offset) {
		return $this;
	}
	
	public function addSQLCalcFoundRows() {
		if ($this->isSelect() && !preg_match('/SQL_CALC_FOUND_ROWS/i', $this->query)) {
			$this->query = preg_replace('/^SELECT/i', 'SELECT SQL_CALC_FOUND_ROWS', $this->query);
		}
		return $this;
	}
	
}
?>