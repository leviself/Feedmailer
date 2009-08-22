<?php
/**
 * Name:	admin_massemail.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr 22 18:04:33 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			
			$subject = $_POST[subject];
			$message = $_POST[message];
			$override = $_POST[override]; // Override vacation setting.

			if ($subject && $message){

				$getusers = mysql_query(
				"SELECT email, vacation FROM `{$mysql->prefix}users`
				ORDER BY `id` DESC");

				include dirname(__FILE__)."/inc/class.mail.php";
				
				while ($row = mysql_fetch_array($getusers)){
					$email = $row[email];
					$vacation = $row[vacation];
					
					if ($override == "true"){
						$mail->massEmail($email, $subject, $message);
					} else {
						if ($vacation == "off"){
							$mail->massEmail($email, $subject, $message);
						}
					}
				}

				header("Refresh: 3; url=admin.php");
				$template->header();
				$template->message("The Mass Email was sent successfully.");
				$template->footer();
			
			} else {
				$template->header();
				$template->admin_massemail();
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
