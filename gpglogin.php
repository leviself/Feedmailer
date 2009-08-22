<?php
/**
 * Name:	gpglogin.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Jun 11 18:50:14 EDT 2009
 **/
include "inc/main.php";
include "inc/class.gpgauth.php";

if (!$cuser){
	if (GPG_ENABLE == "true"){
		$keyid = $mysql->sanitize($_POST[keyid]);
		$otk   = $mysql->sanitize($_GET[otk]);
		$dlcrypt = $_POST[downloadcrypt];

		if ($keyid){
			if ($gpgauth->keyIDExists($keyid)){
				if ($dlcrypt == "true"){
					$crypt = $gpgauth->OneTimeKey($keyid);
					$crypt = ($crypt != false) ? $crypt : $gpgauth->gpg->error; 

					header("Content-Type: text/plain");
					header("Content-Disposition: attachment; filename=fm-otk.gpg");

					print $crypt;
				} else {
					// Let the gpglogin template take care
					// of error handling. It's prettier.
					$template->header();
					$template->gpglogin();
					$template->footer();
				}
			} else {
				$template->header();
				$template->gpglogin();
				$template->footer();
			}
		} elseif ($otk){
			if ($gpgauth->check($otk)){
				// The timestamp
				$timestamp = time();
			
				// The cookie auth
				$auth = md5($otk.$gpgauth->uid.$timestamp);

				// Set cookie_auth
				$mysql->setCookieAuth($gpgauth->uid, $auth);
		
				// Set the cookie for the browser.
				$cookie->setCookie($gpgauth->uid, $auth);

				header("Location: index.php");
			} else {
				$template->header();
				$template->error("One-time key did not match your account.");
				$template->footer();
			}
		} else {
			$template->header();
			$template->gpglogin();
			$template->footer();
		}
	} else {
		$template->header();
		$template->error("GnuPG Login is disabled. Sorry.");
		$template->footer();
	}
} else {
	$template->header();
	$template->error("You are already logged in...");
	$template->footer();
}
?>
