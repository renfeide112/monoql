<?php
class MySQLColumn extends AbstractColumn implements IColumn {
	
	public function __construct($name, $table) {
		$this->name=$name;
		$this->table = $table;
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function getTable() {
		return $this->table;
	}
	
	public function add() {
		
	}
	
	public function alter() {
		
	}
	
	public function drop() {
		
	}
	
	public function isPrimary() {
		
	}
	
	public function isUnique() {
		
	}
	
	public function getCreateString() {
		
	}
	
	public function getNiceName() {
		
	}
	
	public function __toString() {
		return $this->name;
	}
	
}
?>
