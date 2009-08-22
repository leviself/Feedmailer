<?php
/**
 * Name:	resend.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Tue May 12 21:12:22 EDT 2009
 **/

require_once "inc/main.php";

$email = $mysql->sanitize($_GET[email]);
if ($email){
	// Preform a check, to make sure the email address exists.
	// otherwise, someone could be using this file to send spam.
	if ($auth->emailExists($email)){

		// Collect the userid
		$userid = $mysql->emailToID($email);

		// Verify that the user is not currently activated.
		if (!$auth->userIsActivated($userid)){

			// Get the email_key for the user who matches the email address.
			$key = mysql_result(mysql_query(
			"SELECT `email_key` FROM `{$mysql->prefix}users`
			WHERE email='$email'"),0);

			// Load the mail class
			include dirname(__FILE__)."/inc/class.mail.php";

			// Send the activation email
			$mail->sendEmailKey($email, $key);

			header("Refresh: 3; url=index.php");
			$template->header();
			$template->message(
			"The activation key has been resent to your email address.");
			$template->footer();
		} else {
			$template->header();
			$template->error("You are not authorized to view this page.");
			$template->footer();
		}
	} else {
		// Instead of giving some sort of output that a user could take
		// and then be able to bruteforce the system for detecting email
		// addresses, let's make a generic message as the one above. This way
		// a (bot|user) can't determine if the email exists or not.
		$template->header();
		$template->error("You are not authorized to view this page.");
		$template->footer();
	}
} else {
	// Act as if nothing happened.
	header("Location: index.php");
}
?>
