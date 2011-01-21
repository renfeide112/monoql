<?php
/**
 * A class containing the response data and methods to manipulate it
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class HTMLResponse extends Response {
	
	public $html;
	
	/**
	 * Construct the new Response object
	 */
	public function __construct(array $segments=null) {
		global $config;
		$segments = (isset($segments) && count($segments)>0) ? $segments : explode("/", $config["home"]);
		if (val($segments, 0)==="_blocks") {
			if (count($segments)>=2) {
				$this->html = Block::load($segments[1], array_slice($segments, 2));
			}
		} else if (val($segments, 0)==="_layouts") {
			if (count($segments)>=2) {
				$this->html = Layout::load($segments[1], array_slice($segments, 2));
			}
		} else {
			for ($len=count($segments); $len>0; $len--) {
				$path = $config["root"] . "/pages/" . implode("/", array_slice($segments, 0, $len));
				$page = basename($path);
				if (is_file("{$path}.php") || is_file("{$path}/{$page}.php")) {
					$folder = is_file("{$path}.php") ? dirname($path) : $path;
					$args = array_slice($segments, $len);
					$className = toPascalCase($page) . "Page";
					if (is_file("{$folder}/{$page}.cb.php")) {
						include("{$folder}/{$page}.cb.php");
						$class = $className;
					} else {
						$class = isset($config["default_layout"]) ? toPascalCase($config["default_layout"]) . "Layout" : "Layout";
					}
					$this->html = new $class($args);
					$this->html->name = $page;
					$this->html->path = "{$folder}/{$page}.php";
					break;
				}
			}
		}
	}
	
	public function authenticate() {
		return $this->html->authenticate();
	}
	
	public function authorize() {
		return $this->html->authorize();
	}
	
	/**
	 * Execute any action specified in the request
	 */
	public function executeAction() {
		return $this->html->executeAction(Request::$action);
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
