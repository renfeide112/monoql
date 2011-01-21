<?php
/**
 * A class containing the response data and methods to manipulate it
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class Response extends Object {
	
	/**
	 * An array with HTTP status codes as the keys and status text as the
	 * values
	 * 
	 * @var array
	 */
	public static $statusTexts = array(
		100=>"Continue",
		101=>"Switching Protocols",
		200=>"OK",
		201=>"Created",
		202=>"Accepted",
		203=>"Non-Authoritative Information",
		204=>"No Content",
		205=>"Reset Content",
		206=>"Partial Content",
		300=>"Multiple Choices",
		301=>"Moved Permanently",
		302=>"Found",
		303=>"See Other",
		304=>"Not Modified",
		305=>"Use Proxy",
		307=>"Temporary Redirect",
		400=>"Bad Request",
		401=>"Unauthorized",
		402=>"Payment Required",
		403=>"Forbidden",
		404=>"Not Found",
		405=>"Method Not Allowed",
		406=>"Not Acceptable",
		407=>"Proxy Authentication Required",
		408=>"Request Timeout",
		409=>"Conflict",
		410=>"Gone",
		411=>"Length Required",
		412=>"Precondition Failed",
		413=>"Request Entity Too Large",
		414=>"Request-URI Too Long",
		415=>"Unsupported Media Type",
		416=>"Requested Range Not Satisfiable",
		417=>"Expectation Failed",
		500=>"Internal Server Error",
		501=>"Not Implemented",
		502=>"Bad Gateway",
		503=>"Service Unavailable",
		504=>"Gateway Timeout",
		505=>"HTTP Version Not Supported"
	);
	
	private function __construct() {
		
	}
	
	/**
	 * Initialize the response properties
	 */
	public static function initialize() {
		OutputBuffer::initialize();
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
		
	}
	
	/**
	 * Set any response headers before the output is sent to the client system
	 */
	public function setHeaders() {
		
	}
	
	/**
	 * Format the response data to appropriately handle the request
	 */
	public function format() {
		
	}
	
	/**
	 * Filter the response output if necessary
	 */
	public function filter() {
		
	}
	
	/**
	 * Cache the response if necessary
	 */
	public function cache() {
		
	}
	
	/**
	 * Send the response to the client system
	 */
	public function send() {
		if (OutputBuffer::isActive()) {
			OutputBuffer::flush();
		}
	}
	
	/**
	 * Cleanup any resources and/or temp files used by the response
	 */
	public function cleanup() {
		if (OutputBuffer::isActive()) {
			OutputBuffer::end();
		}
	}
	
	/**
	 * Log any information for this response
	 */
	public function log() {
		
	}
	
	/**
	 * Send an HTTP redirect header code
	 * 
	 * @param string $url The URL to redirect the response
	 * @param bool $permanent Set to true to send permanent redirect, false for temporary redirect code
	 */
	public static function redirect($url, $permanent=false) {
		$urlObject = new WebURL($url);
		
		if ($urlObject->isFullURL()) {
			$redirectURL = $url;
		} else if ($url[0]==="/") {
			$redirectURL = $urlObject->getPrePath() . $url;
		} else if ($url[0]==="?") {
			$redirectURL = $urlObject->getPreQuery() . $url;
		} else {
			$redirectURL = $urlObject->getPrePath() . dirname($urlObject->path) . "/{$url}";
		}
		
		self::setStatus($permanent ? 301 : 302);
		self::setHeader("Location", $redirectURL);
	}
	
	/**
	 * Send a permanent HTTP redirect header code
	 * 
	 * @param string $url The URL to redirect the response
	 */
	public static function redirectPermanent($url) {
		self::redirect($url, true);
	}
	
	/**
	 * Set a cookie on the client system
	 * 
	 * If this is the first time the cookie is being set, it will not be availabe in the
	 * Request->cookies array until the next request, when it is sent by the client system.
	 * 
	 * @param string|Cookie $cookie The name of the cookie or a Cookie object
	 * @param string $value The (unencoded) value of the cookie
	 * @param int $expire The expiration time as a Unix timestamp or general date string 
	 * @param string $path The path on the server in which the cookie will be available on
	 * @param string $domain The domain that the cookie is available
	 * @param bool $secure Indicates that the cookie should only be transmitted over a secure HTTPS connection
	 * @param bool $httpOnly The cookie will be made accessible only through the HTTP protocol and not client scripts
	 */
	public static function setCookie($cookie, $value="", $expire=0, $path="", $domain="", $secure=false, $httpOnly=false) {
		$pieces = explode("/", $path);
		foreach ($pieces as &$piece) {
			$piece = rawurlencode($piece);
		}
		$path = implode("/", $pieces);
		setcookie($cookie,$value,$expire,$path,$domain,$secure,$httpOnly);
	}
	
	public static function unauthorized() {
		self::setStatus(401);
	}
	
	public static function forbidden() {
		self::setStatus(403);
	}
	
	public static function notFound() {
		self::setStatus(404);
	}
	
	public static function notModified() {
		self::setStatus(304);
	}
	
	public static function setStatus($statusCode) {
		if (array_key_exists($statusCode, self::$statusTexts)) {
			header("HTTP/1.1 {$statusCode} " . self::$statusTexts[$statusCode]);
		}
	}
	
	public static function setHeader($name, $value) {
		if ($name==="Location") {
			debug("REDIRECT");
		}
		header("{$name}: {$value}");
	}
	
	public static function getHeader($name) {
		$headers = self::getHeaders();
		return $headers[$name];
	}
	
	public static function getHeaders() {
		preg_match_all(
			'/^([^:]+):\s*(.*)$/mi',
			implode("\r\n",headers_list()),
			$matches,
			PREG_PATTERN_ORDER
		);
		return array_combine($matches[1],$matches[2]);
	}
	
}

/**
 * Initialize the response properties
 */
Response::initialize();

?>
