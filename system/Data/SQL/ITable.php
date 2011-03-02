<?php
interface ITable {
	public function __construct($name, $database);

	public function getName();
	
	public function getDatabase();
	
	public function getParent();

	public function getIsChild();
	
	public function getUniqueKeys();
	
	public function getColumns();
	
	public function getColumnNames($search=null);
}
?>