<?php
interface IQueryParser {

	public function isSelect();

	public function isInsert();

	public function isUpdate();

	public function isDelete();

	public function isCreate();

	public function addLimit($limit);
	
	public function addOffset($offset);
	
	public function setup();
	
}
?>