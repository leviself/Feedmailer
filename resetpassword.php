<?php
/**
 * Name:	resetpassword.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri Mar 27 10:30:20 EDT 2009
 **/

require_once "inc/main.php";

if (!$cuser){
	$email = $mysql->sanitize($_POST[email]);
	if ($email){
		if ($auth->emailExists($email)){
			// Generate a new random password at the length of 14 char
			$newpassword = $auth->generateRandomPassword(14);
			$newhashpass = md5($newpassword);

			 // Determine the userid of the user with the email
			 $id = mysql_result(mysql_query(
			 "SELECT `id` FROM `{$mysql->prefix}users`
			 WHERE email='$email'"),0);

			 // Set the new password for the user
			 $mysql->setPassword($id, $newhashpass);

			 // Set some email headers and content.
			 $subject = SITE_TITLE." Password Reset";

			 $headers .= 'From: "'.SITE_TITLE.'" <noreply@localhost>'."\n";
			 $headers .= 'Reply-To: noreply@localhost'."\n";
			 $headers .= 'X-Mailer: PHP/'.phpversion()."\n";
			 $headers .= 'X-Application: SimpleLogin';

			 $content .= "Someone (hopefully you!) has requested that this email\n";
			 $content .= "be sent to your email account with a new password for\n";
			 $content .= SITE_TITLE.". A new password has been generated, so here\n";
			 $content .= "it is:\n\n";
			 $content .= "Password: {$newpassword}\n\n";
			 $content .= "Best Regards,\n";
			 $content .= SITE_TITLE;

			 // Deliver the new password to the user.
			 mail($email, $subject, $content, $headers);

			$template->header();
			$template->message(
			"Password has been changed for the user
			registered with {$email}. Please check your mail.");
			$template->login();
			$template->footer();
		} else {
			// Raise an error.
			$template->header();
			$template->error(
			"The is no user registered with in this system
			with that email address.");
			$template->resetpassword();
			$template->footer();
		}
	} else {
		// Display the resetpassword page.
		$template->header();
		$template->resetpassword();
		$template->footer();
	}
} else {
	// Raise an error message.
	$template->header();
	$template->error(
	"Feeling a bit ill today? Are you sure you're OK?
	Go change your password from the settings, you dork...");
	$template->footer();
}
?>
