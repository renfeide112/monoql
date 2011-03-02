<?php
interface IView {
	public function __construct($name, $database);
	
	public function getColumns();
}
?>