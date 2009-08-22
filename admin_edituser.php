<?php
/**
 * Name:	admin_edituser.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr 22 14:01:12 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if(IS_ADMIN == "true"){

			$username = $_POST[username];
			$password = $_POST[password];
			$email = $_POST[email];
			$vacation = $_POST[vacation];
			$admin = $_POST[is_admin];
			$mailtype = $_POST[mail_type];
			$userid = $_POST[userid];

			if ($username && $email){
			
				// Collect user variables...
				$user = $mysql->selectUser($userid);

				$message = '';
					
				if ($username != $user[username]){
					if (!$auth->userExists($username)){
						$mysql->setUsername($userid, $username);
						$message .= 'Setting new username.... done<br />';
					} else {
						$message .= 'Setting new username.... error<br />';
					}
				}

				if ($password != null){
					$hashpass = md5($password);

					if ($hashpass != $user[password]){
						$mysql->setPassword($userid, $hashpass);
						$message .= 'Setting new password.... done<br />';
					}
				}

				if ($email != $user[email]){
					if (!$auth->emailExists($email)){
						$mysql->setEmail($userid, $email);
						$message .= 'Setting new email.... done<br />';
					} else {
						$message .= 'Setting new email.... error<br />';
					}
				}

				if ($mailtype != $user[mail_type]){
					$mysql->setMailType($userid, $mailtype);
					$message .= 'Setting new mailtype.... done<br />';
				}

				$vacation = is_null($vacation) ? 'off' : 'on';
				if ($vacation != $user[vacation]){
					$mysql->setVacation($userid, $vacation);
					$message .= 'Setting vacation.... done<br />';
				}

				if ($admin != $user[is_admin]){
					$admin = is_null($admin) ? 'false' : 'true';
					$mysql->setAdmin($userid, $admin);
					$message .= 'Setting administrator option.... done<br />';
				}
			
				header("Refresh: 3; url=admin.php");
				$template->header();
				$template->message($message.
				"User has been succussfully edited.");
				$template->footer();
				
			} else {
				$template->header();
				$template->admin_edituser();
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
			

					
