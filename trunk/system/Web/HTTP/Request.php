<?php
/**
 * A class containing all of the data provided in the HTTP request
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class Request extends Object {
	
	/**
	 * Unique ID of this request
	 * 
	 * @var int
	 */
	public static $id;
	
	/**
	 * Action to be executed on this request
	 * 
	 * @var string
	 */
	public static $action;
	
	/**
	 * An array of all request headers
	 * 
	 * The keys of the array are the header names and have the corresponding
	 * values.  The case of the header names match the HTTP standard, e.g.
	 * "Accept-Encoding", where each word is capitalized and separated by dash
	 * @var array
	 */
	public static $headers;
	
	/**
	 * A WebURL object built from the URL that generated this request
	 * 
	 * @var WebURL
	 */
	public static $url;
	
	/**
	 * A Browser object containing information about the requesting browser
	 * 
	 * @var Browser
	 */
	public static $browser;
	
	/**
	 * A copy of the PHP $_POST array
	 * 
	 * @var array
	 */
	public static $post;
	
	/**
	 * A copy of raw input data from the request body copied from the PHP input
	 * stream, php://input
	 * 
	 * @var string
	 */
	public static $input;
	
	/**
	 * A copy of the PHP $_GET array
	 * 
	 * @var array
	 */
	public static $get;
	
	/**
	 * A copy of the PHP $_COOKIE array
	 * 
	 * @var array
	 */
	public static $cookies;
	
	/**
	 * A copy of the PHP $_FILES array
	 * 
	 * @var array
	 */
	public static $files;
	
	/**
	 * An array of UploadFile objects representing the uploaded files
	 * 
	 * @var array
	 */
	public static $uploadFiles;
	
	/**
	 * A copy of the PHP $_REQUEST array
	 * 
	 * @var array
	 */
	public static $data;
	
	/**
	 * The HTTP method used to make the request, e.g. GET, POST, HEAD, etc.
	 * 
	 * @var string
	 */
	public static $method;
	
	/**
	 * The IP Address of the client system that made the request
	 * 
	 * @var string
	 */
	public static $remoteAddress;
	
	/**
	 * The port on the server where the request is handled
	 * 
	 * @var int
	 */
	public static $port;
	
	/**
	 * The HTTP protocol version specified in the request, e.g. 1.0, 1.1
	 * 
	 * @var string
	 */
	public static $httpVersion;
	
	/**
	 * This class only contains static properties and methods, and should not be constructed
	 */
	private function __construct() {}
	
	/**
	 * Initialize all of the properties with data for this request
	 */
	public static function initialize() {
		self::$id            = uniqid();
		self::$headers       = self::extractHeaders();
		self::$method        = $_SERVER["REQUEST_METHOD"];
		self::$port          = (int)$_SERVER["SERVER_PORT"];
		self::$httpVersion   = str_replace("HTTP/","",$_SERVER["SERVER_PROTOCOL"]);
		self::$remoteAddress = $_SERVER["REMOTE_ADDR"];
		self::$url           = new WebURL(self::buildRequestURL());
		self::$browser       = new Browser(self::$headers["User-Agent"]);
		self::$post          = $_POST;
		self::$input         = file_get_contents("php://input");
		self::$get           = $_GET;
		self::$cookies       = $_COOKIE;
		self::$files         = $_FILES;
		self::$uploadFiles   = self::extractUploadedFiles();
		self::$data          = $_REQUEST;
		self::$action        = val(self::$data, "action");
	}

	/**
	 * Use PHP request and server data to construct the full request string
	 * 
	 * @return string
	 */
	public static function buildRequestURL() {
		$scheme = self::$port==443 ? "https" : "http";
		$host = self::$headers["Host"];
		$requestURI = $_SERVER["REQUEST_URI"];
		$requestURL = "{$scheme}://{$host}{$requestURI}";
		
		return $requestURL;
	}
	
	/**
	 * Extract header names and values from the request data and return
	 * associative array with header names as keys and their values
	 * 
	 * The header names will be altered so that the case matches the HTTP
	 * standard, e.g. "Accept-Encoding", where each word is capitalized and 
	 * separated by dash
	 * @return array
	 */
	public static function extractHeaders() {
		$headers = array();
		
		foreach ($_SERVER as $key=>$value) {
			if (substr($key,0,5)==="HTTP_") {
				$header = str_replace(" ","-",ucwords(str_replace("_"," ",substr(strtolower($key),5))));
				$headers[$header] = $value;
			}
		}
		
		return $headers;
	}
	
	/**
	 * Extract uploaded files and store as an array of UploadFile objects
	 * 
	 * @return array
	 */
	public static function extractUploadedFiles() {
		$uploadFiles = array();
		
		foreach (self::$files as $name=>$file) {
			$uploadFiles[$name] = new UploadFile($file);
		}
		
		return $uploadFiles;
	}
	
	public static function hasAction() {
		return isset(self::$data["action"]);
	}
}

//-----------------------------------------------------------------------------

/**
 * Initialize the Request object with the data for this request 
 */
Request::initialize();

?>
