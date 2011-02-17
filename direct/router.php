<?php
// Include Helix configuration and library
require_once("../config/settings.php");
require_once("../system/Helix.php");

// Transform the raw post data from serialized JSON to associative array
$request = isset(Request::$data["extAction"]) ? Request::$data : JSON::decode(Request::$input, true);
$transactions = is_array($request) ? (array_key_exists("tid", $request) ? array($request) : $request) : array();
debug(print_r($transactions, true));
$responses = array();
foreach ($transactions as $t) {
	// Copy all of the request metadata into the response, but not the request data
	$action = alt(val($t, "extAction"), val($t, "action"));
	$method = alt(val($t, "extMethod"), val($t, "method"));
	$tid = intval(alt(val($t, "extTID"), val($t, "tid")));
	$type = alt(val($t, "extType"), val($t, "type"));
	$data = isset($t["extAction"]) ? array($t) : $t["data"];
	$isFileUpload = alt(isTrue(val($t, "extUpload")), false);
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
			try {
				$class = new ReflectionClass($action);
				$method = $class->getMethod($method);
				$object = $method->isStatic() ? null : $class->newInstance();
				$args = is_array($data) ? $data : array();
				$response["result"] = $method->invokeArgs($object, $args);
			} catch (Exception $e) {
				helixExceptionHandler($e);
				$response["type"] = "exception";
				$response["message"] = $e->getMessage();
				$response["where"] = $e->getTraceAsString();
			}
		} else {
			Helix::setError(500, "API requires a method");
		}
	} else {
		Helix::setError(500, "API requires an action");
	}
	$responses[] = $response;
}
//$responses = count($responses)===1 ? $responses[0] : $responses;
debug(print_r($responses, true));

if ($isFileUpload) {
	echo "<html><body><textarea>" . JSON::encode($responses) . "</textarea></body></html>";
} else {
	JSON::send($responses);
}
?>