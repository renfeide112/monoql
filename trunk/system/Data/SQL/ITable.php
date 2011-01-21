<?php
interface ITable {
	public $database;
	public $name;
	public $type;
	public $is_self_referential;
	public $parent;
	public $primary_key;
	public $is_child;
	public $is_parent;
	public $columns;
	public $primary_keys;
	public $unique_keys;
	public $lookups;
	public $linked_tables;
	public $linked_columns;
	public $referenced_tables;
	public $referenced_columns;
	public $fk_columns;

	public function __construct($tablename, $dbname=DB_NAME);

	public function getParent();

	public function getIsChild();
	
	public function getUniqueKeys();
	
	public function getColumns();
}
?>