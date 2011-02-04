<?php
// Include Helix configuration and library
if (!is_file("../config/server.php")) copy("../config/server.default.php", "../config/server.php");
require_once("../config/server.php");
require_once("../system/Helix.php");

if (array_key_exists("config", Request::$data)) {
	Response::setHeader("Content-Type", "text/javascript");
	$descriptor = buildDescriptor();
	$jsonDescriptor = JSON::encode($descriptor);
	if (req("format")==="json") {
		JSON::send($jsonDescriptor);
	}
	$lines = implode(RN, array(
		"Ext.ns('monoql.direct');",
		"Ext.Direct.addProvider({$jsonDescriptor})"
	));
	echo $lines;
} else {
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
}

function buildDescriptor() {
	$descriptor = array(
		"url"=>"direct/api.php",
		"type"=>"remoting",
		"namespace"=>"monoql.direct",
		"actions"=>array()
	);
	foreach (Helix::$classes as $class=>$path) {
		try {
			$reflector = new ReflectionClass($class);
			$descriptor["actions"][$class] = array();
			foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if ($method->getDeclaringClass()->name===$class && $method->isUserDefined() && $method->name[0]!=="_") {
					$descriptor["actions"][$class][] = array("name"=>$method->name, "len"=>$method->getNumberofParameters());
				}
			}
		} catch (Exception $e) {}
	}
	return $descriptor;
}
?>