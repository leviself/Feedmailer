<?php
/**
 * Name:	addfeed.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Mon Apr  6 15:55:28 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if($cauth == COOKIE_AUTH){
		$template->header();
		$template->addfeed();
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
