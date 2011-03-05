<?php
interface IQuery {
	
	public function __construct($query);
		
	public function execute();
	
	public function pageQuery($limit=null, $offset=null);
	
	public function sqlCalcRowsQuery();
	
	public function orderQuery($orderBy=null);
	
	public function cleanQuery();
	
	public function isSelect();
	
	public function hasOrderBy();
	
	public function hasLimit();
	
	public function hasOffset();
	
}
?>