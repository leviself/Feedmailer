<?php
/**
 * Name:	login.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 14:13:02 EDT 2009
 **/

require_once "inc/main.php";

$username = $mysql->sanitize($_POST[username]);
$password = $mysql->sanitize($_POST[password]);
$hashedpass = md5($password);

if (!$cuser){
	if ($username){
		if ($username == "about::feedmailer" && $password == NULL){
			$template->header();
			$template->easteregg();
			$template->footer();
		} else {
			if ($password){
				if($auth->userExists($username)){
					if ($auth->checkLogin($username, $hashedpass)){
						// Obtain the userid for username.
						$id = $mysql->usernameToID($username);

						if($auth->userIsActivated($id)){					
							// The timestamp
							$timestamp = time();
		
							// The cookie auth
							$auth = md5($username.$password.$id.$timestamp);

							// Set cookie_auth
							$mysql->setCookieAuth($id, $auth);
		
							// Set the cookie for the browser.
							$cookie->setCookie($id, $auth);
			
							// Redirect user back to index.
							header("Location: index.php");
						} else {
							$template->header();
							$template->error(
							"Your account is currently not activated. Please
							check your email for the activation code, in order
							to continue. Thank-you.");
							$template->login();
							$template->footer();
						}
					} else {
						// Spit out an error
						$template->header();
						$template->error(
						"Could not complete authentication process.
						Incorrect Username or Password");
						$template->login();
						$template->footer();
					}
				} else {
					// Spit out an error
					$template->header();
					$template->error(
					"Username does not exist with this system. Sorry!");
					$template->login();
					$template->footer();
				}
			} else {
				// Spit out an error
				$template->header();
				$template->error("You did not fill in the password field!");
				$template->login();
				$template->footer();
			}
		}
	} else {
		// Spit out an error
		$template->header();
		#$template->error("You did not fill in the username field!");
		$template->login();
		$template->footer();
	}
} else {
	// Spit out an error
	$template->header();
	$template->error("You are already logged in!");
	$template->footer();
}

?>

