<?php
/**
 * An class representing the Helix Class Library
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class Helix {
	
	/**
	 * Path to the Helix folder
	 * 
	 * @var string 
	 */
	public static $path;
	
	/**
	 * An object that inherits Response, depending on the type of request 
	 * 
	 * @var Response
	 */
	public static $response;
	
	/**
	 * An array for the __autoload() method to lookup class paths 
	 * 
	 * @var array
	 */
	public static $classes = array();
	
	/**
	 * An array of the autoloaded classes 
	 * 
	 * @var array
	 */
	public static $autoloaded = array(); 
	
	/**
	 * An array of the predefined PHP errors 
	 * 
	 * @var array
	 */
	public static $errors = array(
		E_ERROR              => 'Error',
		E_WARNING            => 'Warning',
		E_PARSE              => 'Parsing Error',
		E_NOTICE             => 'Notice',
		E_CORE_ERROR         => 'Core Error',
		E_CORE_WARNING       => 'Core Warning',
		E_COMPILE_ERROR      => 'Compile Error',
		E_COMPILE_WARNING    => 'Compile Warning',
		E_USER_ERROR         => 'User Error',
		E_USER_WARNING       => 'User Warning',
		E_USER_NOTICE        => 'User Notice',
		E_STRICT             => 'Runtime Notice',
		E_RECOVERABLE_ERROR  => 'Catchable Fatal Error',
		E_DEPRECATED         => 'Deprected Notice',
		E_USER_DEPRECATED    => 'User Deprecated Notice',
		E_ALL                => 'All Errors'	
	);

	/**
	 * This class contains only static methods and should not be constructed
	 */
	private function __construct() {}

	// Throw and catch an ErrorException for every error o get access to a nicely formatted stack trace
	// Return false to allow PHP to handle the error in the normal way
	public static function errorHandler($errno, $error, $file=null, $line=null, $errcontext=null) {
		$e = new ErrorException($error, 0, $errno, $file, $line);
		debug(self::$errors[$e->getSeverity()] . ": " . $e->getMessage() . NL . $e->getTraceAsString());
		return false;
	}
	
	/**
	 * Initialize the static properties, define constants, etc
	 */
	public static function initialize() {
		global $config, $session;
		if (isset(self::$path)) return;
		require_once("Global.php");
		set_error_handler(array("Helix", "errorHandler"));
		self::$path = dirname(__FILE__);
		spl_autoload_register("autoload");
		self::defineConstants();
		self::mapSystemClasses();
		self::mapSystemClasses(dirname(self::$path) . "/api");
		self::logRequest();
		strpos();
	}
	
	public static function mapSystemClasses($folder=null) {
		$folder = is_null($folder) ? self::$path : $folder;
		foreach (glob("{$folder}/*") as $path) {
			if (is_file($path) && preg_match('/^[A-Z].*\.php$/', basename($path))) {
				self::$classes[basename($path, ".php")] = $path;
			} else if (is_dir($path)) {
				self::mapSystemClasses($path);
			}
		}
	}
	
	private static function logRequest() {
		global $config, $session;
		if (!preg_match('/^\/_shared/i', Request::$url->path)) {
			$sessionHash = is_object($session) ? "[{$session->hash}]" : "[]";
			$username = "[" . (isTrue(val($config, "enable_session")) && $session->getUserId()>0 ? $session->user->username : "Anonymous") . "]";
			$method = Request::$method;
			$url = Request::$url->getFullURL();
			$ip = "[" . Request::$remoteAddress . "]";
			$postData = Request::$method==="POST" ? " [POST:" . (count(Request::$post)>0 ? paramify(Request::$post, null, "&", "/password/i") : Request::$input) . "]" : " [NO POST]";
			$fileData = count(Request::$files)>0 ? " [FILES:" . paramify(Request::$files, "name") . "]" : " [NO FILES]";
			$cookies = count(Request::$cookies)>0 ? " [COOKIES:" . paramify(Request::$cookies) . "]" : " [NO COOKIES]";
			$userAgent = " [USER-AGENT:{$_SERVER["HTTP_USER_AGENT"]}]";
			$referer = strlen(val($_SERVER, "HTTP_REFERER"))>0 ? " [REFERER:{$_SERVER["HTTP_REFERER"]}]" : " [NO REFERER]";
			debug("{$sessionHash} {$username} {$ip} {$method} {$url}{$postData}{$fileData}{$cookies}{$referer}{$userAgent}");
		}
	}

	/**
	 * Locate and include the class file using the given class name
	 * 
	 * @param string $class The path to the class to be loaded
	 * @return bool True if the class was successfully included
	 */
	private static function loadClass($class) {
		$loaded = false;
		
		$paths = array(
			$class,
			"{$class}.php",
			self::$path . "/{$class}",
			self::$path . "/{$class}.php"
		);
		
		foreach ($paths as $path) {
			if (is_file($path)) {
				$loaded = (bool)include_once($path);
				break;
			}
		}
		
		return $loaded;
	}
	
	/**
	 * Locate and include all the class files in the given namespace
	 * 
	 * @param string $namespace The path to the namespace relative to Helix/
	 * @return bool True if all classes in the namespace were included
	 */
	private static function loadNamespace($namespace) {
		$loaded = true;
		
		$path = self::$path . "/{$namespace}";
		if (file_exists($path)) {
			$classPathList = glob("{$path}/*.php");
			foreach ($classPathList as $classPath) {
				$loaded = $loaded && self::loadClass($classPath);
			}
		}
		
		return $loaded;
	}
	
	/**
	 * Load the class or namespace given
	 * 
	 * @param string $classOrNamespace
	 */
	public static function load($classOrNamespace) {
		$loaded = false;
		
		// Attempt to load the class or alternatively the namespace
		$loaded = self::loadClass($classOrNamespace) || self::loadNamespace($classOrNamespace);
		
		return $loaded;
	}
	
	/**
	 * Set the Response to an ErrorResponse with the given status code
	 * 
	 * This will set the static response property of this object to be
	 * reset to an ErrorResponse object for the remainder of the processing
	 * @param int $statusCode The HTTP status code of the error
	 */
	public static function setError($statusCode, $message=null) {
		debug("Helix ERROR: [{$statusCode}] {$message}");
		// Need to implement for monoql-style folder structure
	}
	
	public static function defineConstants() {
		define("DS", DIRECTORY_SEPARATOR);
		define("PS", PATH_SEPARATOR);
		define("CR", "\r");
		define("NL", "\n");
		define("RN", "\r\n");
		define("TB", "\t");
	}
	
}

//-----------------------------------------------------------------------------

/**
 * Initialize the Helix Class Library
 * 
 * This method will load basic classes and set foundation properties of the
 * Helix Class Library.
 */
Helix::initialize();
?>
