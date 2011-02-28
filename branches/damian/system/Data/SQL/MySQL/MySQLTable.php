<?php
class MySQLTable extends AbstractTable implements ITable {

	public function __construct($tablename, $dbname=DB_NAME) {
		
	}

	public function getParent() {
		
	}

	public function getIsChild() {
		
	}
	
	public function getUniqueKeys() {
		
	}
	
	public function getColumns() {
		
	}
	
	public function getEngine() {
		$this->query($query);
		while ($this->getRecord()) {
			$databases[] = $this->record["Database"];
		}
		return $databases;
		$query = "SELECT TABLE_NAME,ENGINE
		FROM information_schema.TABLES 
		WHERE TABLE_SCHEMA = '{{$this->dbname}}' and TABLE_NAME='{{$this->tablename}}'";
		
	}
	
}
?>
