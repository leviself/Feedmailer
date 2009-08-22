<?php
/**
 * Name:	logout.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 14:39:10 EDT 2009
 **/

require_once "inc/main.php";
if ($cuser){
	$cookie->deleteCookies(USERID);

	// Set this variable to null, so the navigation quits quirkin'
	$_COOKIE[COOKIE_NAME."_user"] = null;

	$template->header();
	$template->message("You are now logged out.");
	$template->login();
	$template->footer();
} else {
	$template->header();
	$template->error("You are not logged in.");
	$template->footer();
}
