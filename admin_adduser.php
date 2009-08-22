<?php
/**
 * Name:	admin_adduser.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr 22 11:31:26 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			
			$username = $_POST[username];
			$password = $_POST[password];
			$email    = $_POST[email];
			$hashpass = md5($password);
			$admin = $_POST[is_admin];
			$activation = $_POST[send_activation];
			$vacation = $_POST[set_vacation];

			if ($username && $password && $email){
				if (!$auth->userExists($username)){
					if (!$auth->emailExists($email)){
						if ($mysql->addUser($username, $hashpass, $email, $key=NULL, $admin)){
					
							// Obtain the id for the newly created user
							$userid = $mysql->usernameToID($username);
					
							if ($admin == "true"){
								$mysql->setAdmin($userid, $admin);
								$message = "Setting user as administrator.... done<br />";
							}

							if ($vacation == "on"){
								$mysql->setVacation($userid, $vacation);
								$message .= "Setting user to vacation.... done<br />";
							}

							if ($activation == "true"){
								include dirname(__FILE__)."/inc/class.mail.php";
								$key = md5($username.$password.$email.time());
								$mysql->setActivationKey($userid, $key);
								$mail->sendActivation($email, $username, $password, $key);
								$message .= "Sending user email activation.... done<br />";
							}

							header("Refresh: 3; url=admin.php");
							$template->header();
							$template->message($message.
							"New User has been successfully created.");
							$template->footer();
						} else {
							$template->header();
							$template->error(
							"There was an error with addUser() function.");
							$template->footer();
						}
					} else {
						$template->header();
						$template->error("Email address already exists.");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("Username already exists!");
					$template->footer();
				}
			} else {
				$template->header();
				$template->admin_adduser();
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
