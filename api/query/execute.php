<?php
require("../../system/Helix.php");

$conn = json_decode(req("connection"));
$db = DatabaseFactory::createDatabase($conn->type, $conn->host, $conn->username, $conn->password, $conn->database, $conn->port);
$rows = array();

if ($db) {
	$result = $db->query(req("query"));
	while ($db->getRecord()) {
		$rows[] = $db->record;
	}
}

$response = array(
	"success"=>true,
	"query"=>req("query"),
	"rows"=>$rows
);

JSON::send($response);
?>