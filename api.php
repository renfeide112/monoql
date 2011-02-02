<?php
// Include Helix configuration and library
if (!is_file("config/server.php")) copy("config/server.default.php", "config/server.php");
require_once("config/server.php");
require_once("system/Helix.php");

// Route action and method to appropriate class and method
$action = toPascalCase(Request::val("action"));
$method = toCamelCase(Request::val("method"));
if (isset($action)) {
	if (isset($method)) {
		$object = new $action(Request::$data);
		$result = $object->$method();
	} else {
		Helix::setError(500, "API requires a method");
	}
} else {
	Helix::setError(500, "API requires an action");
}
?>