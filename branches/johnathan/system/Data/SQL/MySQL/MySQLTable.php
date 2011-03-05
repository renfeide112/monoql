<?php
class MySQLTable extends AbstractTable implements ITable {

	public function __construct($name, $database) {
		$this->name=$name;
		$this->database = $database;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDatabase() {
		return $this->database;
	}
	
	public function getParent() {
		
	}

	public function getIsChild() {
		
	}
	
	public function getUniqueKeys() {
		
	}
	
	public function getColumnNames($search=null) {
		$columnNames = array();
        $query = "SHOW COLUMNS FROM `{$this->database->name}`.`{$this->name}`" . (isset($search) ? " AND Field LIKE '%{$search}%'" : "");
        $this->database->connection->query($query);
        while ($this->database->connection->getRecord()) {
           $columnNames[]=$this->database->connection->record["Field"];
        }
        return $columnNames;
	}
	
	public function getColumns() {
		$columns = array();
		foreach($this->columnNames as $columnname) {
			$columns[$columnname] = new MySQLColumn($columnname,$this);
		}
		return $columns;
	}
	
	public function getEngine() {
		$this->query($query);
		while ($this->getRecord()) {
			$databases[] = $this->record["Database"];
		}
		return $databases;
		$query = "SELECT TABLE_NAME,ENGINE
		FROM information_schema.TABLES 
		WHERE TABLE_SCHEMA = '{{$this->dbname}}' and TABLE_NAME='{{$this->name}}'";
		
	}
	
}
?>
