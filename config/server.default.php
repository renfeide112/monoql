<?php
/**
 * The Helix Class Library configuration settings for this server
 * 
 * This array will contain configuration for the server environment where Helix
 * is installed.
 */
$config = array();

// Initialize the global configuration arrray
$config["root"] = dirname(dirname(__FILE__));

// Path to the site root on the URL for this server - Must begin, but not end with slash [/]
$config["webroot"] = "/";

// Enable the use of session data in the database [false]
$config["enable_session"] = false;

// Enable debug log messages within the Helix System [true]
$config["enable_log"] = true;

// Prefix for log files [null]
$config["log_prefix"] = null;

// Enable debug log messages within the Helix System [true]
$config["default_database_type"] = "mysql";

// Log every change to the database in a separate database [false]
$config["enable_database_log"] = false;

// MySQL host for database connections [localhost]
$config["mysql_host"] = "localhost";

// MySQL default database to use on connection []
$config["mysql_database"] = "somedatabase";

// MySQL username for the given host []
$config["mysql_user"] = "someuser";

// MySQL password for the given user and host []
$config["mysql_password"] = ""; 

// MySQL port for connection to host [3306]
$config["mysql_port"] = 3306;

// MSSQL host for database connections [localhost]
$config["mssql_host"] = "localhost";

// MSSQL default database to use on connection []
$config["mssql_database"] = "";

//-----------------------------------------------------------------------------

/**
 * Set the default PHP environment configuration
 * 
 * This configuration will probably need to be different for development and 
 * live production servers.  You can see a description of all of these on the
 * PHP website at http://www.php.net/manual/en/ini.list.php
 * 
 * Defaults are shown in brackets in the comment.
 */

// The timezone of this server [America/Chicago]
ini_set("date.timezone","America/Chicago");

// Include PHP errors in the response [true]
ini_set("display_errors",true);

// Set the path to the PHP error log file [logs/error.log]
ini_set("error_log","logs/error.log");

// Error reporting level [E_ALL & ~E_NOTICE]
ini_set("error_reporting",E_ALL & ~E_NOTICE);

// Order of precedence for request variables [GPC]
ini_set("gpc_order","GPC");

// Use HTML markup in PHP error messages [false]
ini_set("html_errors",false);

// Enable error logging for PHP errors [true]
ini_set("log_errors",true);

// Max characters to use in an error message [0]
ini_set("log_errors_max_len",0);

// Automatically escape request variables [false]
ini_set("magic_quotes_gpc",false);

// Automatically escape runtime values [false]
ini_set("magic_quotes_runtime",false);

// Max PHP script execution time in sec [30]
ini_set("max_execution_time",300);

// Max number of uploads in one request [20]
ini_set("max_file_uploads",20);

// Max PHP script memory usage in bytes [128M]
ini_set("memory_limit","128M");

// From: address used with PHP mail() function []
ini_set("sendmail_from","");

// SMTP host used by PHP mail() function [localhost]
ini_set("SMTP","localhost");

// SMTP port used by PHP mail() function [25]
ini_set("smtp_port",25);

// Automatically compress response [true]
ini_set("zlib.output_compression", false);
?>