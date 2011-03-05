<?php
/**
 * A Layout object
 * 
 * @author Johnathan Hebert <johnathan@jdcommerce.com>
 */
class Layout extends HTML {
	
	/**
	 * The name of this layout
	 */
	public $name;
	
	/**
	 * Path to the layout file
	 * 
	 * @var string
	 */
	public $layoutPath;
	
	/**
	 * Set to false to restrict rendering of the Layout
	 * 
	 * @var bool
	 */
	public $renderLayout = true;
	
	/**
	 * The title of this HTMLDocument that will be used in the <title> element 
	 * if it exists
	 * 
	 * @var string
	 */
	public $title;
	
	/**
	 * The charset to use for this HTMLDocument -- this will be set in a <meta>
	 * element and in a Content-Type HTTP header if applicable
	 * 
	 * @var string
	 */
	public $charset = "UTF-8";
	
	/**
	 * The page generator that will be used in the <meta> generator element
	 * 
	 * @var string
	 */
	public $generator = "Helix - http://helix.sourceforge.net";
	
	/**
	 * The page description that will be used in the <meta> description element
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * Comma-separated list of keywords that will be used in the <meta> keywords element
	 * 
	 * @var string
	 */
	public $keywords;
	
	/**
	 * The page author that will be used in the <meta> author element
	 * 
	 * @var string
	 */
	public $author;
	
	public function __construct(array $args=null) {
		global $config;
		parent::__construct($args);
		$parent = $this->getClass();
		while (strlen($parent)>0 && preg_match('/Layout$/', $parent)===0) {
			$parent = get_parent_class($parent);
		}
		$name = strtolower(preg_replace('/Layout$/', '', $parent));
		$this->layoutPath = "{$config["root"]}/layouts/{$name}/{$name}.php";
	}
	
	public static function load($name) {
		$folder = "layouts/{$name}";
		$class = toPascalCase($name) . "Layout";
		if (is_file("{$folder}/{$class}.php")) {
			include("{$folder}/{$class}.php");
			$layout = new $class();
			$layout->layoutPath = "{$folder}/{$name}.php";
			return $layout;
		} else {
			$message = "Failed to load layout: {$name}";
			return Helix::setError(404, $message)->html;
		}
	}
	
	public function insertPage() {
		global $config, $session;
		$d = $this->data;
		$e = HTML::encode($this->data);
        extract($this->data, EXTR_PREFIX_ALL | EXTR_REFS, "");
		if (is_file($this->path)) {
			include($this->path);
		}
	}
	
	public function setHeaders() {
		global $config, $session;
		if (isTrue($config["enable_session"])) {
			Response::setCookie("session", $session->hash, time() + (3600*24), $config["webroot"]);
		}
	}
	
	public function render() {
		global $config, $session;
		$d = $this->data;
		$e = HTML::encode($this->data);
        extract($this->data, EXTR_PREFIX_ALL | EXTR_REFS, "");
		if (is_file($this->layoutPath) && is_null(val(Request::$data, "nolayout"))) {
			include($this->layoutPath);
		} else {
			$this->insertPage();
		}
	}
	
}
?>
