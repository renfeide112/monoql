<?php
interface IColumn {
	
	public function __construct($tablename, $columnname, $database);
	
	public function add();
	
	public function alter();
	
	public function drop();
	
	public function isPrimary();
	
	public function isUnique();
	
	public function getCreateString();
	
	public function getNiceName();
	
	public function __toString();
	
}
?>