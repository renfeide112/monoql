<?php
// Include Helix configuration and library
require_once("../config/settings.php");
require_once("../system/Helix.php");

// Transform the raw post data from serialized JSON to associative array
$rawPostData = file_get_contents("php://input");
$request = JSON::decode($rawPostData, true);

// Copy all of the request metadata into the response, but not the request data
$response = $request;
unset($response["data"]); 

// Route action and method to appropriate class and method
$action = val($request, "action");
$method = val($request, "method");
if (isset($action)) {
	if (isset($method)) {
		$class = new ReflectionClass($action);
		$method = $class->getMethod($method);
		$object = $method->isStatic() ? null : $class->newInstance();
		$data = val($request, "data");
		$args = is_array($data) ? $data : array();
		$response["result"] = $method->invokeArgs($object, $args);
	} else {
		Helix::setError(500, "API requires a method");
	}
} else {
	Helix::setError(500, "API requires an action");
}

JSON::send($response);
?>