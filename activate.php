<?php
/**
 * Name:	activate.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Apr  9 12:27:30 EDT 2009
 **/

require_once "inc/main.php";

$email	= $mysql->sanitize($_GET[email]);
$key 	= $mysql->sanitize($_GET[key]);
$emailkey = $mysql->sanitize($_GET[emailkey]);

if (!$cuser){
	
	if ($email){
		if ($auth->emailExists($email)){
			// Obtain the id of the user with the email
			$id = $mysql->emailToID($email);

			if ($key){
				if (!$auth->userIsActivated($id)){
					if ($auth->activateUser($email, $key)){
						$template->header();
						$template->message(
						"Your account has been activated.
						You may now log in.");
						$template->login();
						$template->footer();
					} else {
						$template->header();
						$template->error(
						"Could not complete activation process.
						Invalid key or email address.");
						$template->login();
						$template->footer();
					}
				} else {
					$template->header();
					$template->error(
					"This account has already been activated.");
					$template->footer();
				}
			} elseif($emailkey){
				if ($auth->activateEmail($email, $emailkey)){
					header("Refresh: 3; url=index.php");
					$template->header();
					$template->message(
					"You email address has been activated.
					You will now receive updates to this new
					email address.");
					$template->footer();
				} else {
					$template->header();
					$template->error(
					"Could not complete activation process.
					Invalid key or email address.");
					$template->login();
					$template->footer();
				}					
			} else {
				$template->header();
				$template->error("Activation key can not be left blank!");
				$template->footer();
			}
		} else {
			$template->header();
			$template->error("Email address does not exist.");
			$template->footer();
		}
	} else {
		$template->header();
		$template->error("The email address can not be left blank!");
		$template->footer();
	}
} elseif($email){
	if ($auth->emailExists($email)){
		// Obtain the id of the user with the email
		$id = $mysql->emailToID($email);

		if ($emailkey){
			if ($auth->activateEmail($email, $emailkey)){
				header("Refresh: 3; url=index.php");
				$template->header();
				$template->message(
				"You email address has been activated.
				You will now receive updates to this new
				email address.");
				$template->footer();
			} else {
				$template->header();
				$template->error(
				"Could not complete activation process.
				Invalid key or email address.");
				$template->footer();
			}
		} else {
			$template->header();
			$template->error("Activation key can not be left blank!");
		}			
	} else {	
		$template->header();
		$template->error("Email address does not exist.");
		$template->footer();
	}
} else {
	header("Location: index.php");
}
?>
