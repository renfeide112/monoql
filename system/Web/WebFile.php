<?php


/**
 * A WebFile Object
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class WebFile extends File {
	
	public $absolutePath;
	
	public function __construct($absolutePath) {
		$this->absolutePath = $absolutePath;
	}
	
	public function gatherData() {
		
	}
	
	public function setHeaders() {
		global $config;
		
		Response::setHeader("Content-Type", File::mime($this->absolutePath));
		Response::setHeader("Cache-Control", "max-age={$config["default_resource_cache"]}");
		
		if (isset(Request::$data["attachment"])) {
			$value = "attachment";
			if (isset(Request::$data["filename"]) && strlen(Request::$data["filename"])>0) {
				$value .= ";filename=\"" . Request::$data["filename"] . "\"";
				Response::setHeader("Content-Type",File::mime(Request::$data["filename"]));
			}
			Response::setHeader("Content-Disposition",$value);
		}
		
		if (File::extension($this->absolutePath)==="php") {
			Response::setHeader("Content-Type","text/html");
		}
	}
	
	public function format() {
		
	}
	
	public function filter() {
		
	}
	
	public function cache() {
		
	}
	
	public function send() {
		if (preg_match('/^(php|css|js)$/i', File::extension($this->absolutePath))) {
			if (!OutputBuffer::isActive()) {
				OutputBuffer::start();
			}
			include($this->absolutePath);
			Response::setHeader("Content-Length", OutputBuffer::getLength());
			OutputBuffer::end();
		} else {
			Response::setHeader("Content-Length", filesize($this->absolutePath));
			if (OutputBuffer::isActive()) {
				OutputBuffer::end();
			}
			readfile($this->absolutePath);
		}
	}
	
	public function cleanup() {
		
	}
	
	public function log() {
		
	}
	
}
?>
