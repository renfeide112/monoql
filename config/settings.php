<?php
if (!is_file("config/server.php")) copy("config/server.default.php", "config/server.php");
require_once("config/server.php");
require_once("config/site.php");
?>