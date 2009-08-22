<?php
/**
 * Name:	editfeed.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Mon Apr  6 21:23:04 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){

			$feedid = $_POST[feedid];
			$url = $mysql->sanitize($_POST[url]);
			$title = isset($_POST[title]) ? $mysql->sanitize($_POST[title]) : NULL;

			if ($url){

				// Load the XML parser
				include dirname(__FILE__)."/inc/class.xmlparser.php";
				$xml = new xmlparser();

				// Select feed values
				$feeddata = $feed->selectFeedData($feedid);

				if ($url != $feeddata[url]){
					// Check the URL to see if it exists on the web.
					if ($xml->checkURL($url)){
						// Check the file to see if it XML, by using the
						// checkxml public variable in class.xmlparser.php
						if ($xml->check_string($xml->file)){
							// Set the feed URL
							$feed->setURL($feedid, $url);
							$message .= "Setting url.... done<br />";

							// Delete the old cache for the feed.
							$cache->deleteAll($urlid);
						} else {
							$template->header();
							$template->error(
							"URL is not an XML document.");
							$template->footer();
							exit;
						}
					} else {
						// Oops. URL does not exist.
						$template->header();
						$template->error("Uh oh. It looks like the URL
						does not exist. Are you sure it's spelled right?");
						$template->footer();
					}
				}

				
				if ($title != $data[title]){
					// Only set the feed title, if it is not NULL
					if ($title != NULL){
						$feed->setTitle($feedid, $title);
					} else {
						$feed->setTitle($feedid, NULL);
					}
					$message .= "Setting title.... done<br />";
				}

				// Display a nice message, and exit
				header("Refresh: 3; url=admin.php");
				$template->header();
				$template->message($message.
				"Settings have been successfully updated.");
				$template->footer();
			
			} else {
				$template->header();
				$template->admin_editfeed();
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
