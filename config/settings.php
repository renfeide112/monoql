<?php
if (!is_file("settings.server.php")) copy("settings.server.default.php", "settings.server.php");
require_once("settings.server.php");
require_once("settings.site.php");
?>