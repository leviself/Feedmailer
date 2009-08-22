<?php
/**
 * Name:	clearcache.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri Apr 24 15:07:04 EDT 2009
 **/

require_once dirname(__FILE__)."/../inc/main.php";
require_once dirname(__FILE__)."/../inc/class.debug.php";

$debug = new debugger();

define("DEBUG_ENABLE",	$mysql->setting(debug_enable));
define("DEBUG_MAX_TTL",	$mysql->setting(debug_max_ttl));

$getdebug = mysql_query(
"SELECT id, timestamp FROM `{$mysql->prefix}debug`
ORDER BY `id` DESC");

// Preform only if CACHE_ENABLE is true.
if (DEBUG_ENABLE == "true"){
	
	while ($row = mysql_fetch_array($getdebug)){
		$id = $row[id];
		$timestamp = $row[timestamp];
		$time = time();

		// Cache timestamp is less than
		// (current time - max life time).
		if ($timestamp < ($time - DEBUG_MAX_TTL)){
			// Delete the old cache.
			$debug->delete($id);
		}
	}
}
?>		
