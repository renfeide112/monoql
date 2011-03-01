<?php
class MySQLDatabase extends AbstractDatabase implements IDatabase {

	public function __construct($dbname=DB_NAME, $connection) {
		$this->dbname=$dbname;
		$this->connection = $connection;
	}

	public function getFunctions(){
		$functions = array();
        $query = "SHOW FUNCTION STATUS WHERE Db='{$this->dbname}'";
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
        $query = "SHOW PROCEDURE STATUS WHERE Db='{$this->dbname}'";
        $this->connection->query($query);
        while ($this->connection->getRecord()) {
                $sprocs[] = $this->connection->record["Name"];
        }
        $sprocs = array_unique($sprocs);
        sort($sprocs);
        return $sprocs;
	}
	
	public function getTables($search=null){
		$tables = array();
		$query = "SHOW FULL TABLES FROM {$this->dbname} WHERE Table_type='BASE TABLE'" . (isset($search) ? " AND Tables_in_{{$this->dbname}} LIKE '%{$search}%'" : "");
		$this->connection->query($query);
		while ($this->connection->getRecord(false)) {
		        $tables[] = $this->connection->record[0];
		}
		$tables = array_unique($tables);
		sort($tables);
		return $tables;
	}
	
	public function getTriggers(){
		$triggers = array();
		$query = "SHOW TRIGGERS FROM {$this->dbname}";
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
         $query = "SHOW FULL TABLES FROM {$this->dbname} WHERE Table_type='VIEW'" . (isset($search) ? " AND Tables_in_{$this->dbname} LIKE '%{$search}%'" : "");
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
