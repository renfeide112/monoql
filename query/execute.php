<?php
require("../system/Helix.php");
$response = array(
	"success"=>true,
	"dummy"=>req("query")
);
echo json_encode($response);
?>