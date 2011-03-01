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
		$columns = array();
        $database = isset($database) ? $database : $this->database;
        $this->changeDatabase($database);
        $query = "SHOW COLUMNS FROM {$table}" . (isset($search) ? " AND Field LIKE '%{$search}%'" : "");
        $this->query($query);
        while ($this->getRecord()) {
                $columns[] = array(
                        "name"=>$this->record["Field"],
                        "key"=>strlen(trim($this->record["Key"]))>0,
                        "primary"=>val($this->record, "Key")==="PRI"
                );
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
		WHERE TABLE_SCHEMA = '{{$this->dbname}}' and TABLE_NAME='{{$this->tablename}}'";
		
	}
	
}
?>
