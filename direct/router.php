<?php
// Include Helix configuration and library
require_once("../config/settings.php");
require_once("../system/Helix.php");

// Transform the raw post data from serialized JSON to associative array
$request = JSON::decode(Request::$input, true);

// Copy all of the request metadata into the response, but not the request data
$action = alt(val($request, "action"), req("extAction"));
$method = alt(val($request, "method"), req("extMethod"));
$tid = intval(alt(val($request, "tid"), req("extTID")));
$type = alt(val($request, "type"), req("extType"));
$upload = alt(isTrue(req("extUpload")), false);
$response = array(
	"action"=>$action,
	"method"=>$method,
	"tid"=>$tid,
	"type"=>$type,
	"result"=>null
);

// Route action and method to appropriate class and method
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