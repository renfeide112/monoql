<?php if (!is_file("config/server.php")) copy("config/server.default.php", "config/server.php"); ?>
<?php require_once("config/server.php"); ?>
<?php require_once("system/Helix.php"); ?>
<html>
	<head>
		<title>MonoQL - Data United</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<meta name="description" content="A web-based database administration tool for MySQL" />
		<meta name="keywords" content="MySQL, database, admin, tool, AJAX" />
		<meta name="author" content="Johnathan Hebert, Damian O'Brien" />
		<script src="ext/adapter/ext/ext-base-debug.js"></script>
		<script src="ext/ext-all-debug.js"></script>
		<script src="direct/api.php"></script>
		<script src="app/bundle.php"></script>
		<link rel="stylesheet" href="ext/resources/css/ext-all.css" />
		<link rel="stylesheet" href="styles/monoql.css" />
	</head>
	<body></body>
</html>