<?php
/**
 * Name:	gpgauth.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Mon Jul  6 16:45:10 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (GPG_ENABLE == "true"){
			$template->header();
			$template->gpgauth();
			$template->footer();
		} else {
			$template->header();
			$template->error("GPG Auth is disabled. Sorry.");
			$template->footer();
		}
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
