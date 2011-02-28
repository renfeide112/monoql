<?php
/**
 * A class containing the response data and methods to manipulate it
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class ErrorResponse extends Response {
	
	public $html;
	
	public function __construct($statusCode=null, $message=null) {
		$this->html = new ErrorLayout($statusCode, $message);
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
		$this->html->gatherData();
	}
	
	/**
	 * Set any response headers before the output is sent to the client system
	 */
	public function setHeaders() {
		$this->html->setHeaders();
	}
	
	/**
	 * Format the response data to appropriately handle the request
	 */
	public function format() {
		$this->html->format();
	}
	
	/**
	 * Filter the response output if necessary
	 */
	public function filter() {
		$this->html->filter();
	}
	
	/**
	 * Cache the response if necessary
	 */
	public function cache() {
		$this->html->cache();
	}
	
	/**
	 * Log any information for this response
	 */
	public function log() {
		$this->html->log();
	}
}
?>
