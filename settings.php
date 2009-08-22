<?php
/**
 * Name:	settings.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri Mar 27 12:19:29 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		$template->header();
		$template->settings();
		$template->footer();
	} else {
		$template->header();
		$template->error($error->auth());
		$template->footer();
	}
} else {
	$template->header();
	$template->error($error->snooper());
	$template->footer();
}
?>
