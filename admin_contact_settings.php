<?php
/**
 * Name:	admin_contact_settings.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Sun Apr 26 12:42:20 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){

			$enable = $_POST[enable];
			$who	= $_POST[who];

			if (!$enable && !$who){
				$template->header();
				$template->admin_contact_settings();
				$template->footer();
			} else {
				
				if ($enable){
					if ($who){

						if ($enable != $mysql->setting(contact_enable)){
							$mysql->setSetting("contact_enable", $enable);
							$message .= "Updating contact_enable.... done<br />";
						}

						if ($who != $mysql->setting(contact_who)){
							$mysql->setSetting("contact_who", $who);
							$message .= "Updating contact_who.... done<br />";
						}

						header("Refresh: 3; url=admin.php");
						$template->header();
						$template->message($message.
						"Settings updated successfully.");
						$template->footer();
					} else {
						$template->header();
						$template->error("contact_who can not be left blank.");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("contact_enable can not be left blank.");
					$template->footer();
				}
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
