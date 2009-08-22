<?php
/**
 * Name:	openid.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Mon Jun  1 16:14:08 EDT 2009
 **/

require_once "inc/main.php";
require_once "inc/class.openid.v3.php";
$openid = new SimpleOpenID();

$action = $mysql->sanitize($_POST[openid_action]);
$url    = $mysql->sanitize($_POST[openid_url]);
$mode   = $mysql->sanitize($_GET[openid_mode]);
$ident  = $mysql->sanitize($_GET[openid_identity]);

if (!$cuser){
	
	if ($action == "login"){
		// Set Required Fields for OpenID Server.
		$openid->SetIdentity($url);
		$openid->SetTrustRoot("http://{$_SERVER[HTTP_HOST]}");
		$openid->SetRequiredFields(array('email'));

		// Begin Authentication Process to OpenID Server.
		if ($openid->GetOpenIDServer()){
			$openid->SetApprovedURL($mysql->setting(site_url)."openid.php");
			$openid->Redirect();
		} else {
			// There was an error somewhere. Let's be pretty about it.
			$error = $openid->GetError();

			$template->header();
			$template->error($error[description]);
			$template->footer();

			#echo "ERROR CODE: ". $error[code]."<br />";
			#echo "ERROR DESCRIPTION: ".$error[description]."<br />";
		}
		exit;
	} elseif ($mode == "id_res"){
		// Begin Validation Proccess.
		$openid->SetIdentity($ident);
		$openid_validation_result = $openid->ValidateWithServer();
		
		// If we can validate with the server, everything is A-OK.
		if ($openid_validation_result == true){
			// Now, this OpenID Login form acts as a two way proccess.
			// First, it can register the account for you automatically,
			// and second, it can log you in.

			// We base the username off of the OpenID URL.
			// The OpenID_Standarize() function strips off the http:// and slashes.
			$username = $openid->OpenID_Standarize($ident);
			$email    = $_GET["openid_sreg_email"];


			if ($auth->userExists($username)){
				// The OpenID Login does not use Usernames and Passwords,
				// (the OpenID Server does the authentication), so we don't
				// have to preform any $auth->checkLogin() functions. We can
				// assume that if we have reached it this far, that the OpenID
				// server has authenticated the login.
				// Just set the cookies and proceed as normal.


				// Obtain the userid for username.
				$id = $mysql->usernameToID($username);

				// The timestamp
				$timestamp = time();

				// The cookie auth
				$auth = md5($username.$email.$id.$timestamp);

				// Set the cookie_auth
				$mysql->setCookieAuth($id, $auth);

				// Set the cookie for the browser
				$cookie->setCookie($id, $auth);

				// Redirect user back to index.
				header("Location: index.php");
			} else {
				// Halt. Don't proceed if registration is disabled.
				if (REGISTER_ENABLE == "true"){

					// So, the username does not exist in the database.
					// This must be the user's first time at logging in with
					// their OpenID account. Let's go ahead and create an account
					// for them anyhow.

					// The user will probably never authenticate with a
					// username/password, so just generate some long random
					// password so nobody else can ever login with it. (Not even
					// the user knows this password. All they should have to know
					// is their OpenID password.)
					$randompass = md5($auth->generateRandomPassword(64));

					// Check to make sure someone didn't setup an OpenID Account to
					// bypass the email verification, and use someone else's email.
					if ($auth->emailExists($email)){
						$template->header();
						$template->error(
						"Your OpenID Email address is currently used
						by another user.");
						$template->footer();
						die();
					}

					// If the above does not fail, continue with registration.
					if ($mysql->addUser($username, $randompass, $email, $key=null)){
						// Make the registration process seamless, and unnoticed.

						// Obtain the userid for username.
						$id = $mysql->usernameToID($username);

						// The timestamp
						$timestamp = time();

						// The cookie auth
						$auth = md5($username.$email.$id.$timestamp);

						// Set the cookie_auth
						$mysql->setCookieAuth($id, $auth);

						// Set the cookie for the browser
						$cookie->setCookie($id, $auth);

						header("Location: index.php");
					} else {
						$template->header();
						$template->error($error->boo_boo("db::addUser()"));
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("Registration for this site is currently disabled. Sorry.");
					$template->footer();
				}
			}

		} elseif ($openid->IsError() == true){
			// There was an error. Let's be pretty about it.
			$error = $openid->GetError();

			$template->header();
			$template->error($error[description]);
			$template->openid();
			$template->footer();
		} else {
			$template->header();
			$template->error("Invalid Authorization");
			$template->openid();
			$template->footer();
		}
	} elseif ($mode == "cancel"){
		$template->header();
		$template->error("The request has been canceled.");
		$template->openid();
		$template->footer();
	} else {

		$template->header();
		$template->openid();
		$template->footer();
	}

} else {
	$template->header();
	$template->error("You are already logged in!");
	$template->footer();
}
?>
