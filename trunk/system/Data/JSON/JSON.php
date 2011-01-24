<?php
class JSON extends Object {
	
	public static function encode($data) {
		return json_encode($data);
	}
	
	public static function setMIMEType() {
		Response::setHeader("Content-Type", "application/json");
	}
	
	public static function send($data) {
//		self::setMIMEType();
		echo self::encode($data);
		OutputBuffer::flush();
	}
	
}
?>