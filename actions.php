<?php
/**
 * Name:	actions.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Jun  3 21:42:01 EDT 2009
 **/

require_once "inc/main.php";

/**
 * This file serves to be a 'catch-all' Action file
 * for POST requests to Feedmailer, for settings. Set
 * your <form> action to this file, and it will auto-process
 * the input, change settings (if necessary) and write output
 * to the $msg variable which will be printed to $template->message();
 *
 * I should have thought of this idea before hand, and it would
 * have saved me alot of time from recoding things, but you live
 * and learn, I guess.
 *
 * This should greatly expound possibilities for designing and coding
 * templates, and not restict specific items to certain files.
 **/

if ($cuser){
	if ($cauth == COOKIE_AUTH){

		/**
		 * Here is a list of possible POST field names that you
		 * can use for submitting data to this file. (This should
		 * be helpful when creating new template sets.)
		 * 	
		 *	* username	* password
		 *	* email		* cookie_expire
		 *	* url		* mailtype
		 *	* vacation	* feed_url
		 *	* feed_title	* feed_id
		 *	* delfeed_id	* pubkeyblock
		 *	* delete
		 **/

		// Begin defining some variables
		$username      = $mysql->sanitize($_POST[username]);
		$password      = $mysql->sanitize($_POST[password]);
		$email         = $mysql->sanitize($_POST[email]);
		$cookie_expire = $mysql->sanitize($_POST[cookie_expire]);
		$url	       = $mysql->sanitize($_POST[url]);
		$mailtype      = $mysql->sanitize($_POST[mailtype]);
		$vacation      = $mysql->sanitize($_POST[vacation]);
		$feed_url      = $mysql->sanitize($_POST[feed_url]);	// Editing Feeds
		$feed_title    = $mysql->sanitize($_POST[feed_title]);	// Editing Feeds
		$feed_id       = $mysql->sanitize($_POST[feed_id]);	// Editing Feeds
		$delfeed_id    = serialize($_POST[delfeed_id]);		// Deleteing Feeds
		$delfeed_array = unserialize($delfeed_id);		// Deleteing Feeds
		$pubkeyblock   = $_POST[pubkeyblock];
		$delete        = $_POST[delete];			// Deleteing KeyID.
		// Delete is a hidden field for GPG Auth to delete the keyid. The value should
		// always be true, so don't let anyone change this value by editing the source.
		
		// Import user settings into an array
		$user = $mysql->selectUser($id);


		// Begin the POST process
		if ($username){
			if ($username != $cuser){
				if (!$auth->userExists($username)){
					// Change the username.
					$mysql->setUsername($id, $username);
				
					// Unset the cookie
					$cookie->deleteCookies($id);
							
					// Set the new expire time.
					$mysql->setCookieExpireTime($id, $user[cookie_expire]);
	
					// Reset the cookie with new expire time and old auth.
					// This keeps the user logged in, after updating settings.
					$cookie->setCookie($id, $user[cookie_auth]);

					$msg .= "Username changed successfully.<br />";
				} else {
					$errormsg .= "Username already exists.";
				}
			}
		}

		if ($password){
			// Hash the new password
			$hash = md5($password);

			// Check the hashes.
			if ($hash != $user[password]){
				$mysql->setPassword($id, $hash);
				$msg .= "Password changed successfully.<br />";
			}
		}

		if ($email){
			if ($email != $user[email]){
				if (!$auth->emailExists($email)){
					// Load the mail class
					include dirname(__FILE__)."/inc/class.mail.php";

					// Generate a email_key
					$key = md5($username.$email.$cauth.time());

					// Set the email_key
					$mysql->setEmailKey($id, $key);

					// Send the email activation
					$mail->sendEmailKey($email, $key);
								
					// Set the new email address
					$mysql->setEmail($id, $email);

					$msg .= "Email changed successfully.<br />";
				} else {
					$errormsg .= "Email already is use!<br />";
				}
			}
		}

		if ($cookie_expire){
			if ($cookie_expire != $user[cookie_expire]){
				// Unset the cookie
				$cookie->deleteCookies($id);
								
				// Set the new expire time.
				$mysql->setCookieExpireTime($id, $cookie_expire);
		
				// Reset the cookie with new expire time and old auth.
				// This keeps the user logged in, after updating settings.
				$cookie->setCookie($id, $user[cookie_auth]);

				$msg .= "Cookie Expiration changed.<br />";
			}
		}

		if ($url){
			// Load the XML Parser
			include dirname(__FILE__)."/inc/class.xmlparser.php";
			$xml = new xmlparser();

			if ($xml->checkURL($url)){
				// Check the file to see if it XML, by using the
				// checkxml public variable in class.xmlparser.php
				if ($xml->check_string($xml->file)){
					if ($feed->addFeed($id, $url)){
						$msg .= "You successfully subscribed to the feed.<br />";
					} else {
						$errormsg .= "Oops! Did you make an error? It looks
							like you are already subscribed to this feed!";
					}
				} else {
					$errormsg .= "URL is not an XML Document.";
				}
			} else {
				$errormsg .= "Uh oh. Could not retrive URL.
					Are you sure it's spelled correctly?";
			}
		}

		if ($mailtype){
			if ($mailtype != $user[mail_type]){
				$mysql->setMailType($id, $mailtype);
				$msg .= "Mail type changed.<br />";
			}
		}

		if ($vacation) {
			if ($vacation != $user[vacation]){
				$mysql->setVacation($id, $vacation);

				// Be bold.
				$msg .= ($vacation == "on") 
					? "Vacation has been enabled.<br />" 
					: "Vacation has been disabled.<br />";
			}
		}

		if ($feed_url){
			// Write the feed data to an array.
			$feeddata = $feed->selectFeedData($feed_id);

			// Check and see if there have been any changes.
			if ($feed_url != $feeddata[url]){
				if (!$feed->feedExists($id, $feed_url)){

					// Load the XML parser
					include dirname(__FILE__)."/inc/class.xmlparser.php";
					$xml = new xmlparser();

					// Load the Cache Class
					include dirname(__FILE__)."/inc/class.cache.php";

					// Check the URL to see if it exists on the web.
					if ($xml->checkURL($feed_url)){
						// Check the file to see if it XML, by using the
						// checkxml public variable in class.xmlparser.php
						if ($xml->check_string($xml->file)){
							// Set the feed URL
							$feed->setURL($feed_id, $feed_url);

							// Delete the old cache for the feed.
							$cache->deleteAll($feed_id);

							$msg .= "Editing Feed URL.<br />";
						} else {
							$errormsg .= "URL is not an XML Document.";
						}
					} else {
						$errormsg .= "Oh oh. It looks like the URL does not
							exist. Are you sure it's spelled correctly?";
					}
				} else {
					$errormsg .= "You are already subscribed to this feed!";
				}
			}

			if ($feed_title != $feeddata[title]){
	 			if ($feed_title != NULL){
					$feed->setTitle($feed_id, $feed_title);
					$msg .= "Feed Title has been set.";
				} else {
					$feed->setTitle($feed_id, NULL);
					$msg .= "Feed Title has been unset.";
				}
			}
		}

		if ($delfeed_id && $delfeed_id != "N;"){
			// Import the cache class
			require_once "inc/class.cache.php";

			foreach ($delfeed_array as $singlefeed){
				// Check userid to the feeduid
				$feeduid = @mysql_result(mysql_query(
					"SELECT `uid` FROM `{$mysql->prefix}feeds` 
					WHERE id='$singlefeed'"),0);
				
				if ($feeduid == $id){
					// Remove the feed from the database.
					$feed->deleteFeed($singlefeed);

					// Delete the old cached urls.
					$cache->deleteAll($singlefeed);
				}
			}
			$msg .= "Selected feed(s) have been successfully deleted.";
		}

		if ($pubkeyblock){
			// Import the GPG Auth class
			require_once "inc/class.gpgauth.php";

			$import = $gpgauth->import($id, $pubkeyblock);
			if ($import == true){
				$msg .= "Public Key has been imported!";
			} else {
				$errormsg .= "Public Key could not be imported. Please
					report this incident to an administrator.";
			}
		}

		if ($delete  && ($delete == "true")){
			// Import the GPG Auth Class
			require_once "inc/class.gpgauth.php";

			if ($gpgauth->keyExistsForUID($id)){
				$keyid = $gpgauth->uidToKeyID($id);

				if ($gpgauth->delKey($id, $keyid)){
					$msg .= "Your Key has been removed from the keyring/database.";
				} else {
					$errormsg .= "An error occured. Could not remove key from keyring/database.";
				}
			}
		}

		
		// Keep people from directly accessing this file.
		if (!$_POST){
			$template->header();
			$template->error($error->snooper());
			$template->footer();
			die();
		}


		// Sometimes, a user may just click the submit button without
		// actually changing anything. This variable keeps the user from
		// viewing a random blank page...
		$msg = (is_null($msg) && is_null($errormsg)) ? "No data was submitted and no settings were changed." : $msg;

		// Redirect back to the previous page, if HTTP_REFERER is set.
		// php.net says that this option is set by the user agent, and can not
		// really be trusted.
		$ref = isset($_SERVER[HTTP_REFERER]) ? $_SERVER[HTTP_REFERER] : "index.php";

		// Begin Page output.
		header("Refresh: 3; url={$ref}");
		$template->header();

		// Show the errors.
		if (isset($errormsg)){
			$template->error($errormsg);
		}

		// Show the messages.
		if (isset($msg)){
			$template->message($msg);
		}
		$template->footer();


			
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
