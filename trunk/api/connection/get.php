<?php
require("../../system/Helix.php");

$db = Database::getInstance("sqlite");

if (isset(Request::$data["id"])) {
	$where = "WHERE id='" . Request::$data["id"] . "'";
} else if (isset(Request::$data["name"])) {
	$where = "WHERE name='" . Request::$data["name"] . "'";
} else {
	$where = "";
}

$db->query("
SELECT * FROM connection {$where};
");

$rows = array();
while ($db->getRecord()) {
	$rows[] = $db->record;
}

$response = array(
	"success"=>true,
	"rows"=>$rows
);

JSON::send($response);
?>