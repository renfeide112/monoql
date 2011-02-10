<?php
$dir = dirname(__FILE__);
if (!is_file("{$dir}/server.php")) copy("{$dir}/server.default.php", "{$dir}/server.php");
require_once("{$dir}/server.php");
require_once("{$dir}/site.php");
?>