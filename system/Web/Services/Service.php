<?php
/**
 * A Service Object
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class Service extends Object {
	
	public $data;
	public $output;
	
	public function __construct() {
		
	}
	
	public function executeAction($action, array $args) {
		$this->data = $this->{$action}($args);
	}

	public function gatherData() {
	}
	
	public function setHeaders() {
		if (val(Request::$data, "format")==="json") {
			Response::setHeader("Content-Type", "application/json");
		}
	}
	
	public function format() {
		if (val(Request::$data, "format")==="json") {
			$this->output = json_encode($this->data);
		} else {
			$this->output = print_r($this->data,true);
		}
	}
	
	public function filter() {
	}
	
	public function cache() {
	}
	
	public function send() {
		echo $this->output;
		if (OutputBuffer::isActive()) {
			OutputBuffer::flush();
		}
	}
	
	public function cleanup() {
	}
	
	public function log() {
	}
	
}
?>