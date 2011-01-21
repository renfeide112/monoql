<?php
class String extends Object {
	
	private function __construct() {
		
	}
	
	public static function upper($string) {
		return strtoupper($string);
	}
	
	public static function lower($string) {
		return strtolower($string);
	}
	
	public static function slice($string, $start, $length=null) {
		return isset($length) ? substr($string, $start, $length) : substr($string, $start);
	}
	
	public static function lpad($string, $size, $char=" ") {
		$padding = $size-strlen($string)>0 ? implode("", array_fill(0, $size-strlen($string), $char)) : "";
		return $padding . $string;
	}
	
	public static function rpad($string, $size, $char=" ") {
		$padding = $size-strlen($string)>0 ? implode("", array_fill(0, $size-strlen($string), $char)) : "";
		return $string . $padding;
	}
	
}
?>