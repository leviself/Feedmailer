<?php
/**
 * Name:	admin_gpgauths_settings.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Mon Jul  6 12:39:56 EDT 2009
 **/

require_once "inc/main.php";
require_once "inc/class.gpgauth.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			
			$enable    = 	$_POST[enable];
			$gpgexec   = 	$_POST[gpgexec];
			$gpghomedir= 	$_POST[gpghomedir];
			$destroytable =$_POST[destroytable];
			$destroytable =(empty($destroytable)) ? 'false' : 'true';

			if (!$enable && !$gpgexec && !$gpghomedir){
				$template->header();
				$template->admin_gpgauth_settings();
				$template->footer();
			} else {
				// If we do not meet the above conditions,
				// start applying these conditions.

				if ($enable){
					if ($enable != $mysql->setting(gpg_enable)){
						if ($enable == "true"){
							if ($gpgauth->enable($gpgexec, $gpghomedir)){
								$message .= "Setting gpg_enable.... done<br />";
							}
						} elseif($enable == "false"){
							if ($gpgauth->disable($destroytable)){
								$message .= "Disabling gpg_enable.... done<br />";
							}
						}
					}

					header("Refresh: 3; url=admin.php");
					$template->header();
					$template->message($message.
					"Settings have been successfully updated.");
					$template->footer();
				} else {
					$template->header();
					$template->error("gpg_enable can not be left blank.");
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
