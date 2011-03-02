<?php
interface IFunction {
	public function __construct($name, $database);
	
	public function getParameters();

	public function execute();
}
?>