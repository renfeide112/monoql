<?php
interface ITable {
	public function __construct($tablename, $dbname=DB_NAME);

	public function getParent();

	public function getIsChild();
	
	public function getUniqueKeys();
	
	public function getColumns();
}
?>