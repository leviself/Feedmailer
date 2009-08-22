<?php
/**
 * Name:	contact.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Sun Apr 26 12:51:57 EDT 2009
 **/

require_once "inc/main.php";

// Only registered users may access this page.
if ($cuser){
	if ($cauth == COOKIE_AUTH){
		
		// Only continue if contact_enable is true
		if ($mysql->setting(contact_enable) == "true"){

		
			// variables
			$subject = $_POST[subject];
			$message = $_POST[message];

			if (!$subject && !$message){
				$template->header();
				$template->contact();
				$template->footer();
			} else {
				
				if ($subject){
					if ($message){

						require_once dirname(__FILE__)."/inc/class.mail.php";

						// Email message details
						$msgdetails .= "User ID: ".USERID."\n";
						$msgdetails .= "Username: ".USER."\n";
						$msgdetails .= "Public IP: ".$_SERVER[REMOTE_ADDR]."\n";
						$msgdetails .= "Timestamp: ".date("m/d/Y h:i A", time())."\n\n";

						// Attach the message details to the message.
						$message = $msgdetails.$message;


						// Determine who to send the message to.
						if ($mysql->setting(contact_who) == "alladmins"){
						
							$alladmins = mysql_query(
							"SELECT `email` FROM `{$mysql->prefix}users`
							WHERE `is_admin`='true'");

							while ($row = mysql_fetch_array($alladmins)){
								// Deliver the user's message to all admins.
								$mail->massEmail($row[email], "[".SITE_TITLE." Contact] ".$subject, $message);
							}

						} else {
							$mail->massEmail($mysql->setting(contact_who), "[".SITE_TITLE." Contact] ".$subject, $message);
						}
	
						header("Refresh: 3; url=index.php");
						$template->header();
						$template->message("Thank-you. Your message
						has been sent to the administrator(s).");
						$template->footer();
					} else {
						$template->header();
						$template->error("message can not be left blank.");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("subject can not be left blank.");
					$template->footer();
				}
			}
		} else {
			$template->header();
			$template->error("The contact form is currently disabled. Sorry.");
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
