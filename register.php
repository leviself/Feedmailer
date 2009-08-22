<?php
/**
 * Name:	register.php
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 15:38:43 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	$template->header();
	$template->error("Oops! You are already registered and logged in. :S");
	$template->footer();
} else {
	
	// Continue if registration is turned on.
	if (REGISTER_ENABLE == "true"){
	
		$username  = $mysql->sanitize($_POST[username]);
		$password  = $mysql->sanitize($_POST[password]);
		$password2 = $mysql->sanitize($_POST[password2]);
		$email	   = $mysql->sanitize($_POST[email]);

		// start a session, for the captcha
		session_start();

		$security_code = $_SESSION[security_code];
		$post_code = $_POST[security_code];

		if ($username){
			if (($security_code == $post_code) && (!empty($security_code))){
				if ($password == $password2){
					if ($email){
						if(!$auth->userExists($username)){
							if(!$auth->emailExists($email)){
								// For database.
								$hashedpass = md5($password);
						
								// Activation key
								$time = time()+56;
								$key = md5($username.$password.$email.$time);

								if ($mysql->addUser($username, $hashedpass, $email, $key)){
	
									// Load the mail class
									include dirname(__FILE__)."/inc/class.mail.php";
						
									if ($mail->sendActivation($email, $username, $password, $key)){
										$template->header();
										$template->message("Registration Complete. An activation
										email has been sent. Please click the link in the email
										to activate your account.");
										$template->login();
										$template->footer();
									} else {
										$template->header();
										$template->error($error->boo_boo("sendActivation()"));
										$template->footer();
									}
								} else {
									$template->header();
									$template->error($error->boo_boo("addUser()"));
									$template->footer();
								}
							} else {
								$template->header();
								$template->error("That email is already being used by someone else!");
								$template->register();
								$template->footer();
							}
						} else {
							$template->header();
							$template->error("That user already exists!");
							$template->register();
							$template->footer();
						}
					} else {
						$template->header();
						$template->error("The email field was blank!");
						$template->register();
						$template->footer();
					}
				} else {
					$template->header();
					$template->error(
					"The two passwords did not match. Good thing we
					caught this, or you would have had some complications...");
					$template->register();
					$template->footer();
				}
			} else {
				$template->header();
				$template->error("Invalid security code.");
				$template->register();
				$template->footer();

				// Unset the session
				unset($security_code);
			}
		} else {
			$template->header();
			$template->register();
			$template->footer();
		}
	} else {
		// Registration page is disabled.
		$template->header();
		$template->error(
		"Registration is currently disabled.");
		$template->footer();
	}
}

?>
