<?php
interface IDatabase {

	public function getFunctions();
	
	public function getStoredProcedures();
	
	public function getTables($search=null);
	
	public function getTriggers();
	
	public function getViews();
	
	public function changeCharset($charset);
	
	public function dropDatabase();
	
	public function emptyDatabase($enforceConstraints=true);
	
	public function truncateDatabase($enforceConstraints=true);
	
}
?>