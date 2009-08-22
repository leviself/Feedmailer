<?php
/**
 * Name:	vacation.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Tue Apr  7 17:08:21 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
			$template->header();
			$template->vacation();
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
