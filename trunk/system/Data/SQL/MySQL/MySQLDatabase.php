<?php
class MySQLDatabase extends AbstractDatabase implements IDatabase {

	public function __construct($name=DB_NAME, $connection) {
		$this->name=$name;
		$this->connection = $connection;
	}

	public function getName(){
		return $this->name;	
	}
	
	public function getConnection(){
		return $this->connection;
	}
	
	public function getFunctions(){
		$functions = array();
        $query = "SHOW FUNCTION STATUS WHERE Db='{$this->name}'";
        $this->connection->query($query);
        while ($this->connection->getRecord()) {
			$functions[] = $this->connection->record["Name"];
        }
        $functions = array_unique($functions);
        sort($functions);
        return $functions;
	}
	
	public function getStoredProcedures(){
		$sprocs = array();
        $query = "SHOW PROCEDURE STATUS WHERE Db='{$this->name}'";
        $this->connection->query($query);
        while ($this->connection->getRecord()) {
			$sprocs[] = $this->connection->record["Name"];
        }
        $sprocs = array_unique($sprocs);
        sort($sprocs);
        return $sprocs;
	}
	
	public function getTableNames($search=null){
		$tableNames = array();
		$query = "SHOW FULL TABLES FROM {$this->name} WHERE Table_type='BASE TABLE'" . (isset($search) ? " AND Tables_in_{{$this->name}} LIKE '%{$search}%'" : "");
		$this->connection->query($query);
		while ($this->connection->getRecord(false)) {
			$tableNames[] = $this->connection->record[0];
		}
		$tableNames = array_unique($tableNames);
		sort($tableNames);
		return $tableNames;
	}
	
	public function getTables($search=null){
		$tables = array();
		foreach($this->tableNames as $tablename) {
			$tables[$tablename] = new MySQLTable($tablename,$this);
		}
		return $tables;
	}
	
	public function getTriggers(){
		$triggers = array();
		$query = "SHOW TRIGGERS FROM {$this->name}";
		$this->connection->query($query);
		while ($this->connection->getRecord()) {
		        $triggers[] = $this->connection->record["Trigger"];
		}
		$triggers = array_unique($triggers);
		sort($triggers);
		return $triggers;
	}
	
	public function getViews(){
		 $views = array();
         $query = "SHOW FULL TABLES FROM {$this->name} WHERE Table_type='VIEW'" . (isset($search) ? " AND Tables_in_{$this->name} LIKE '%{$search}%'" : "");
         $this->connection->query($query);
         while ($this->connection->getRecord(false)) {
                 $views[] = $this->connection->record[0];
         }
         $views = array_unique($views);
         sort($views);
         return $views;
	}
	
	public function changeCharset($charset){}
	
	public function dropDatabase(){
		$query = "DROP DATABASE IF EXISTS " . $this->encapsulate($database);
        $status = $this->connection->query($query);
        return $status;
	}
	
	public function createDatabase()
	{
		$query = "CREATE DATABASE";
        $query .= $overwrite ? " IF NOT EXISTS" : "";
        $query .= " {$this->encapsulate($database)}";
        if (isset($options)) {
                $query .= isset($options["charset"]) ? " DEFAULT CHARACTER SET = {$options["charset"]}" : "";
                $query .= isset($options["collation"]) ? " DEFAULT COLLATE = {$options["collation"]}" : "";
        }
        $status = $this->connection->query($query);
        return $status;
	}
	
	public function emptyDatabase($enforceConstraints=true){}
	
	public function truncateDatabase($enforceConstraints=true){}
	
}
?>
