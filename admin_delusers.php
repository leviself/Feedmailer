<?php
/**
 * Name:	admin_delusers.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr 22 19:44:45 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){

			$user_id = serialize($_POST[user_id]);
			$user_array = unserialize($user_id);

			if (($user_id) && ($user_id != "N;")){
				foreach ($user_array as $singleuser){
					// No need to preform checks. We're admin. :)
					$mysql->deleteUser($singleuser);
				}
				header("Refresh: 3; url=admin.php");
				$template->header();
				$template->message(
				"Selected User(s) have been deleted.");
				$template->footer();

			} else {
				$template->header();
				$template->admin_delusers();
				$template->footer();
			}
		} else {
			$template->header();
			$template->error($error->accessdenied());
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
