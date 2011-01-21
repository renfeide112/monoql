<?php
class Collection extends Object implements IteratorAggregate, Countable {
	
	private $array;
	
	public function __construct(array $list=null) {
		$this->array = isset($list) ? $list : array();
	}
	
	public function getArray() {
		return $this->array;
	}
	
	public function getIterator() {
		return new ArrayIterator($this->array);
	}
	
	public function count() {
		return count($this->array);
	}
	
	public function first() {
		return val($this->array, 0);
	}
	
	public function last() {
		return val($this->array, $this->count()-1);
	}
	
	public function join($delimiter="") {
		return implode($delimiter, $this->array);
	}
	
	public function sum() {
		return array_sum($this->array);
	}
	
	public function get($index) {
		return val($this->array, $index);
	}
	
	public function slice($offset, $length=null) {
		$this->array = array_slice($this->array, $offset, $length);
		return $this;
	}
	
	public function extract($offset, $length=null) {
		$array = $this->array;
		return new Collection(array_slice($array, $offset, $length));
	}
	
	public function merge(array $array) {
		array_merge($this->array, $array);
		return $this;
	}
	
	public function pop() {
		return array_pop($this->array);
	}
	
	public function append($item) {
		array_push($this->array, $item);
		return $this;
	}
	
	public function prepend($item) {
		array_unshift($this->array, $item);
		return $this;
	}
	
	public function shift() {
		array_shift($this->array);
		return $this;
	}
	
	public function insert($item, $index) {
		array_splice($this->array, $index, 0, $item);
		return $this;
	}
	
	public function remove($index) {
		if (isset($this->array[$index])) {
			unset($this->array[$index]);
		}
		return $this;
	}
	
	public function reverse($preserveKeys=false) {
		$this->array = array_reverse($this->array, $preserveKeys);
		return $this;
	}
	
	public function sort() {
		sort($this->array);
		return $this;
	}
	
	public function sortByIndex() {
		ksort($this->array);
		return $this;
	}
	
	public function sortNatural($caseSensitive=false) {
		if ($caseSensitive) {
			natsort($this->array);
		} else {
			natcasesort($this->array);
		}
		return $this;
	}
	
}
?>