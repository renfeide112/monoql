<?php
class OutputBuffer extends Object {
	
	private static $isActive = false;
	
	private function __construct() {}
	
	public static function initialize() {
		global $config;
		
		if ($config["output_buffering"]) {
			self::start();
		}
	}

	public static function getContents() {
		return ob_get_contents();
	}
	
	public static function getLength() {
		return ob_get_length();
	}
	
	public static function getStatus() {
		$status = ob_get_status();
		return $status["status"];
	}
	
	public static function isActive() {
		return self::$isActive;
	}
	
	public static function clean() {
		return ob_clean();
	}
	
	public static function flush() {
		return ob_flush();
	}
	
	public static function start() {
		self::$isActive = ob_start();
		
		return self::$isActive;
	}
	
	public static function end($clean=false) {
		if ($clean) {
			self::$isActive = !ob_end_clean();
		} else {
			self::$isActive = !ob_end_flush();
		}
		
		return self::$isActive;
	}
	
}
?>