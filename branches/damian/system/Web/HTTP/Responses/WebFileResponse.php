<?php
/**
 * A class containing the response data and methods to manipulate it
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class WebFileResponse extends Response {
	
	public $resource;
	
	/**
	 * Construct the new Response object
	 */
	public function __construct($path, $isAbsolutePath=false) {
		global $config;
		$absolutePath = $isAbsolutePath ? $path : $config["root"] . $path;
		$this->resource = new WebFile($absolutePath);
	}
	
	public function authenticate() {
		
	}
	
	public function authorize() {
		
	}
	
	/**
	 * Execute any action specified in the request
	 */
	public function executeAction() {
		
	}

	/**
	 * Gather all of the response data in a common format
	 */
	public function gatherData() {
		$this->resource->gatherData();
	}
	
	/**
	 * Set any response headers before the output is sent to the client system
	 */
	public function setHeaders() {
		$this->resource->setHeaders();
	}
	
	/**
	 * Format the response data to appropriately handle the request
	 */
	public function format() {
		$this->resource->format();
	}
	
	/**
	 * Filter the response output if necessary
	 */
	public function filter() {
		$this->resource->filter();
	}
	
	/**
	 * Cache the response if necessary
	 */
	public function cache() {
		$this->resource->cache();
	}
	
	/**
	 * Send the response to the client system
	 */
	public function send() {
		$this->resource->send();
	}
	
	/**
	 * Cleanup any resources and/or temp files used by the response
	 */
	public function cleanup() {
		$this->resource->cleanup();
	}
	
	/**
	 * Log any information for this response
	 */
	public function log() {
		$this->resource->log();
	}
}
?>
