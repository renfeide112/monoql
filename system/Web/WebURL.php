<?php
/**
 * A WebURL Object
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class WebURL extends Object {
	/**
	 * The URL string used to construct this object
	 * @var string
	 */
	public $rawURL;
	
	/**
	 * The scheme used in the URL, like "http"
	 * @var string
	 */
	public $scheme;
	
	/**
	 * The host name in the URL, like "www.example.com"
	 * @var string
	 */
	public $host;
	
	/**
	 * The port number in the URL
	 * @var int
	 */
	public $port;
	
	/**
	 * The user specified in the URL 
	 * @var string
	 */
	public $user;
	
	/**
	 * The password specified in the URL
	 * @var string
	 */
	public $password;
	
	/**
	 * The path in the URL, beginning with "/" and including everything up to
	 * the question mark if it exists
	 * @var string
	 */
	public $path;
	
	/**
	 * The query string in the URL, beginning with the first character after
	 * the question mark and up to the hash if it exists
	 * @var string
	 */
	public $query;
	
	/**
	 * The hash data beginning with the first character after the hash and up
	 * to the last character in the URL
	 * @var string
	 */
	public $hash;
	
	/**
	 * Indicates if the URL contains "https" as the protocol
	 * @var bool
	 */
	public $https;
	
	/**
	 * Initialize all of the URL properties
	 * @param string $rawURL The absolute or relative URL
	 */
	public function __construct($rawURL) {
		$this->rawURL = $rawURL;
		
		$urlData = parse_url($rawURL);
		if (is_array($urlData)) {
			$this->scheme = val($urlData,"scheme");
			$this->https = $this->scheme==="https";
			$this->host = val($urlData,"host");
			$this->port = val($urlData,"port");
			$this->user = val($urlData,"user");
			$this->password = val($urlData,"pass");
			$this->path = self::decode(val($urlData,"path"));
			$this->query = val($urlData,"query");
			$this->hash = val($urlData,"fragment");
		}
	}
	
	/**
	 * This will parse a URL and return a WebURL object with all of the URL
	 * components set
	 * 
	 * This allows for the determination of URL components in one line of code
	 * like WebURL::parse($url)->query to get the query string inline.
	 * @param string $rawURL An absolute or relative URL
	 */
	public static function parse($rawURL) {
		return new WebURL($rawURL);
	}
	
	public function getPrePath() {
		if ($this->isFullURL()) {
			$scheme = $this->scheme;
			$host = $this->host;
			$port = $this->port;
		} else {
			$scheme = Request::$url->scheme;
			$host = Request::$url->host;
			$port = Request::$url->port;
		}
	
		$url = "{$scheme}://{$host}";
		if (isset($port)) {
			$url .= ":{$port}";
		}
		
		return $url;
	}
	
	public function getPreQuery() {
		return $this->getPrePath() . $this->path;
	}
	
	public function getPreHash() {
		$preHash = $this->getPreQuery();
		
		if (isset($this->query)) {
			$preHash .= "?" . $this->query;
		}
		
		return $preHash;
	}
	
	public function getFullURL() {
		if ($this->isFullURL()) {
			$url = $this->rawURL;
		} else if ($this->rawURL[0]==="/") {
			$url = $this->getPrePath() . $this->rawURL;
		} else if ($this->rawURL[0]==="?") {
			$url = $this->getPreQuery() . $this->rawURL;
		} else {
			$url = $this->getPrePath() . dirname($this->path) . "/" . $this->rawURL;
		}
		
		return $url;
	}
	
	/**
	 * Indicates if the URL is a full URL with a scheme and host as a minimum
	 * 
	 * @return bool
	 */
	public function isFullURL() {
		$isFullURL = (isset($this->scheme) && isset($this->host));
		
		return $isFullURL;
	}
	
	public static function encode($string) {
		return rawurlencode($string);
	}
	
	public static function decode($string) {
		return rawurldecode($string);
	}
	
	public function _toString() {
		return $this->getFullURL();
	}
}
?>