<?php
/**
 * Name:	index.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 12:53:23 EDT 2009
 **/

require_once "inc/main.php";

// Short, simple, and to the point. Don't you just love it? :)
$template->index();

if ($cuser){
	$mysql->setLastLogin(USERID);
	$mysql->setLastIP(USERID);
}

?>
