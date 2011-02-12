<?php
// Include Helix configuration and library
require_once("../config/settings.php");
require_once("../system/Helix.php");

// Build descriptor of remoteable methods
$descriptor = buildDescriptor();
$jsonDescriptor = JSON::encode($descriptor);

// Output descriptor as either executable javascript code or json configuration
if (array_key_exists("json", Request::$data)) {
	JSON::send($jsonDescriptor);
} else {
	Response::setHeader("Content-Type", "text/javascript");
	echo implode(RN, array(
		"Ext.ns('monoql.direct');",
		"Ext.Direct.addProvider({$jsonDescriptor});"
	));
}

// Build remoteable descriptor for server side classes
function buildDescriptor() {
	global $config;
	$descriptor = array(
		"url"=>"direct/router.php",
		"type"=>"remoting",
		"namespace"=>"monoql.direct",
		"actions"=>array()
	);
	foreach (Helix::$classes as $class=>$path) {
		try {
			// Only remote classes in the api/ folder 
			if (!preg_match('/^' . preg_quote($config["root"] . "/api/", "/") . '/', $path)) continue;
			$reflector = new ReflectionClass($class);
			$descriptor["actions"][$class] = array();
			foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if ($method->getDeclaringClass()->name===$class && $method->isUserDefined() && $method->name[0]!=="_") {
					$descriptor["actions"][$class][] = array(
						"name"=>$method->name,
						"len"=>$method->getNumberofParameters(),
						"formHandler"=>!!preg_match('/^form/', $method->name)
					);
				}
			}
		} catch (Exception $e) {}
	}
	return $descriptor;
}
?>