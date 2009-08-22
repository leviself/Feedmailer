<?php
/**
 * Name:	admin_addfeed.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri Apr 24 20:59:14 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			
			$url = $_POST[url];
			$username = $_POST[username];

			if ($url && $username){
				
				// Load the XML parser
				include dirname(__FILE__)."/inc/class.xmlparser.php";
				$xml = new xmlparser();


				if ($auth->userExists($username)){
					$userid = $mysql->usernameToID($username);

					if ($xml->checkURL($url)){
						// Check the file to see if it XML, by using the
						// checkxml public variable in class.xmlparser.php
						if ($xml->check_string($xml->file)){
							if ($feed->addFeed($userid, $url)){
								header("Refresh: 3; url=admin.php");
								$template->header();
								$template->message(
								"Feed has been successfully added to the database.");
								$template->footer();
							} else {
								$template->header();
								$template->error("Oops! Did you make an error?
								It looks like you are already subscribed to this feed!");
								$template->footer();
							}
						} else {
							$template->header();
							$template->error(
							"URL is not an XML document.");
							$template->footer();
						}
					} else {
						$template->header();
						$template->error("Uh oh! Could not retreive URL.
						Are you sure it's spelled correctly?");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("Username does not exist.");
					$template->footer();
				}
					
			} else {
				$template->header();
				$template->admin_addfeed();
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
