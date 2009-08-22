<?php
/**
 * Name:	main.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Description:	The main file which imports all the
 		necessary classes and objects.
 * Date:	Thu Mar 26 12:46:04 EDT 2009
 **/

// Nab the current working directory.
$cwd = dirname(__FILE__)."/";

// Load error support
require_once $cwd."class.errors.php";

require_once $cwd."class.db.php";
$mysql->connect();
require_once $cwd."class.auth.php";
require_once $cwd."class.feeds.php";
require_once $cwd."class.cookies.php";
require_once $cwd."class.templates.php";

$cuser = $_COOKIE[COOKIE_NAME."_user"];
$cauth = $_COOKIE[COOKIE_NAME."_auth"];

?>
