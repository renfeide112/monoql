<?php
/**
 * A class containing the response data and methods to manipulate it
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class ServiceResponse extends Response {
	
	public $service;
	public $action;
	public $args;
	
	/**
	 * Construct the new Response object
	 */
	public function __construct($segments) {
		$service = $segments[0];
		$class = toPascalCase($service) . "Service";
		$path = is_file("services/{$service}/{$class}.php") ? "services/{$service}/{$class}.php" : (is_file("services/{$class}.php") ? "services/{$class}.php" : null);
		if (is_file($path)) {
			include($path);
			$this->service = new $class();
			$this->action = val($segments, 1);
			$this->args = count($segments)>2 ? array_slice($segments, 2) : array();
		}
	}
	
	public function authenticate() {
		
	}
	
	public function authorize() {
		
	}
	
	/**
	 * Execute any action specified in the request
	 */
	public function executeAction() {
		if (method_exists($this->service, $this->action)) {
			$this->service->executeAction($this->action, $this->args);
		} else {
			Helix::setError(400, "The requested action is not available for this service: {$this->action}");
		}
	}

	/**
	 * Gather all of the response data in a common format
	 */
	public function gatherData() {
		$this->service->gatherData();
	}
	
	/**
	 * Set any response headers before the output is sent to the client system
	 */
	public function setHeaders() {
		$this->service->setHeaders();
	}
	
	/**
	 * Format the response data to appropriately handle the request
	 */
	public function format() {
		$this->service->format();
	}
	
	/**
	 * Filter the response output if necessary
	 */
	public function filter() {
		$this->service->filter();
	}
	
	/**
	 * Cache the response if necessary
	 */
	public function cache() {
		$this->service->cache();
	}
	
	/**
	 * Send the response to the client system
	 */
	public function send() {
		$this->service->send();
	}
	
	/**
	 * Cleanup any resources and/or temp files used by the response
	 */
	public function cleanup() {
		$this->service->cleanup();
	}
	
	/**
	 * Log any information for this response
	 */
	public function log() {
		$this->service->log();
	}
}
?>