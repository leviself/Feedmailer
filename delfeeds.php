<?php
/**
 * Name:	addfeed.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Mon Apr  6 15:55:28 EDT 2009
 **/

require_once "inc/main.php";
require_once "inc/class.cache.php";

if ($cuser){
	if($cauth == COOKIE_AUTH){
		$template->header();
		$template->delfeeds();
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
