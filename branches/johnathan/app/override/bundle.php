<?php
foreach (glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . "Ext.*.js") as $path) {
	include($path);
}
?>