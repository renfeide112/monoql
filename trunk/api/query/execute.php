<?php
require("../../system/Helix.php");

$response = array(
	"success"=>true,
	"query"=>req("query")
);

JSON::send($response);
?>