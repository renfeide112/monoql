<?php
/**
 * An HTML object
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class HTML extends Object {
	
	/**
	 * The arguments passed through the request URL
	 * 
	 * The arguments are passed after the folder name on the
	 * URL and are separated by forward slashes.  For example, if 
	 * the folder name is somefolder, then you could pass
	 * arguments like this:  
	 * http://www.example.com/somefolder/arg1/arg2/arg3
	 * 
	 * @var array
	 */
	public $args = array();
	
	/**
	 * The absolute path to the page folder
	 * 
	 * @var string
	 */
	public $path;
	
	/**
	 * Data that will be used in this HTML object
	 * 
	 * @var array
	 */
	public $data = array();
	
	/**
	 * An array of scripts to be included in this HTML object
	 * 
	 * Add scripts using the addScript() method
	 * @var array
	 */
	public $scripts;
	
	/**
	 * An array of styles to be included in this HTMLDocument
	 * 
	 * Add styles using the addStyle() method
	 * @var array
	 */
	public $styles;
	
	/**
	 * Construct an instance of this class using the HTML (or derived)
	 * object at the given folder location
	 * 
	 * @param array $args The arguments passed on the request URL
	 */
	public function __construct(array $args=null) {
		$this->args = alt($args, array());
		$this->initialize();
	}
	
	public function get($index, $encode=true) {
		$data = val($this->data, $index);
		return $encode ? self::encode($data) : $data;
	}
	
	/**
	 * Encode HTML entities in the string
	 */
	public static function encode($string, $quoteStyle=ENT_COMPAT, $charset="ISO-8859-1", $doubleEncode=true) {
		$encoded = $string;
		if (is_array($string)) {
			foreach ($string as $key=>$value) {
				$encoded[$key] = self::encode($value, $quoteStyle, $charset, $doubleEncode);
			}
		} else if (!is_object($string)) {
			$encoded = htmlentities($string, $quoteStyle, $charset, $doubleEncode);
		}
		return $encoded;
	}
	
	/**
	 * Decode HTML entities in the string
	 */
	public static function decode($string, $quoteStyle=ENT_COMPAT, $charset="ISO-8859-1") {
		$decoded = $string;
		if (is_array($string)) {
			foreach ($string as &$value) {
				$value = self::decode($value, $quoteStyle, $charset);
			}
		} else if (!is_object($string)) {
			$decoded = html_entity_decode($string, $quoteStyle, $charset);
		}
		return $decoded;
	}
	
	/**
	 * Child classes should implement this function to initialize 
	 * any settings for this page
	 */
	public function initialize() {
		
	}
	
	public function authenticate() {
		
	}
	
	public function authorize() {
		
	}
	
	public function executeAction($action) {
		return method_exists($this, $action) ? $this->{$action}() : null;
	}
	
	public function gatherData() {
		
	}
	
	public function format() {
		$this->render();
	}
	
	public function filter() {
		
	}
	
	public function cleanup() {
		
	}
	
	public function log() {
		global $config, $session;
		if (isTrue($config["enable_session"])) {
			$session->save();
		}
	}
	
	public function setHeaders() {
		
	}
	
	public function cache() {
//		if (OutputBuffer::isActive()) {
//			file_put_contents(dirname($this->path) . "/cache.html",OutputBuffer::getContents());
//		}
	}
	
	public function render() {
		include($this->path);
	}
	
	public function send() {
		
	}

	/**
	 * Add a script to be used in this HTMLDocument 
	 * 
	 * @param string $location Either the filesystem path or a URL
	 * @param bool $inline Set to true to include script file inline or false to load externally
	 */
	public function addScript($location, $inline=false) {
		$this->scripts[$inline ? "inline" : "external"][] = $location;
	}

	/**
	 * Add a stylesheet to be used in this HTMLDocument 
	 * 
	 * @param string $location Either the filesystem path or a URL
	 * @param bool $inline Set to true to include style file inline or false to load externally
	 */
	public function addStyle($location, $inline=false) {
		$this->styles[$inline ? "inline" : "external"][] = $location;
	}
	
	public function insertScripts() {
		if (is_array($this->scripts)) {
			if (isset($this->scripts["external"]) && is_array($this->scripts["external"])) {
				foreach ($this->scripts["external"] as $url) {
					echo "<script type=\"text/javascript\" src=\"{$url}\"></script>" . NL;
				}
			}
			if (isset($this->scripts["inline"]) && is_array($this->scripts["inline"])) {
				foreach ($this->scripts["inline"] as $path) {
					echo "<script type=\"text/javascript\">" . NL;
						include($path);
					echo NL . "</script>" . NL;
				}
			}
		}
	}
	
	public function insertStyles() {
		if (is_array($this->styles)) {
			if (isset($this->styles["external"]) && is_array($this->styles["external"])) {
				foreach ($this->styles["external"] as $url) {
					echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$url}\"></link>" . NL;
				}
			}
			if (isset($this->styles["inline"]) && is_array($this->styles["inline"])) {
				foreach ($this->styles["inline"] as $path) {
					echo "<style type=\"text/css\">" . NL;
						include($path);
					echo NL . "</style>" . NL;
				}
			}
		}
	}
	
	public static function url($url=null) {
		global $config;
		return $url[0]==="/" && strlen($config["webroot"])>1 ? "{$config["webroot"]}{$url}" : $url;
	}
	
}
?>
