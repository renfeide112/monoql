<?php
require("../../system/Helix.php");

$db = Database::getInstance("sqlite");

$db->query("
	CREATE TABLE IF NOT EXISTS connection (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		name TEXT,
		type TEXT,
		host TEXT,
		username TEXT,
		password TEXT,
		port INTEGER,
		mdate TEXT,
		cdate TEXT,
		deleted INTEGER
	);
");

$now = date("Y-m-d H:i:s");
$name = $db->escape(alt(req("name"), "New Connection [" . date("Y-m-d H:i:s") . "]"));
$type = $db->escape(req("type"));
$host = $db->escape(req("host"));
$username = $db->escape(req("username"));
$password = sha1($db->escape(req("password")));
$port = $db->escape(alt(req("port"), 0));

$db->query("
	INSERT INTO connection
	(name, type, host, username, password, port, mdate, cdate, deleted) VALUES
	('{$name}', '{$type}', '{$host}', '{$username}', '{$password}', {$port}, '{$now}', '{$now}', 0);
");

$response = array(
	"success"=>true
);

JSON::send($response);
?>