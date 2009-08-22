<?php
/**
 * Name:	unsubscribe.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr 29 13:56:00 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){

		// The ID of the feed
		$fid = $mysql->sanitize($_GET[fid]);


		if ($fid){
		
			// Verify that the feed _DOES_ exist for the user.
			// $id is the global variable for the user's ID.
			if ($feed->feedExistsForUser($fid, $id)){				

				// A stopping point.
				// So long as the user clicks continue,
				// we'll take that as an OK to move on.
				$verify = $_GET[verify];

				if ($verify == 'true'){
					
					// Drop the feed in the database.
					if ($feed->deleteFeed($fid)){
						header("Refresh: 3; url=index.php");
						$template->header();
						$template->message(
						"You successfully unsubscribed from the feed.");
						$template->footer();
					}
				} else {
					// Oww, the user has not clicked the continue link yet...

					// Retrive data for the Feed.
					$fdata = $feed->selectFeedData($fid);

					$template->header();
					$template->page(
					'<h2>UNSUBSCRIBE</h2>
					You are about to unsubscribe from the following URL:<br />
					<a href="'.$fdata[url].'">'.$fdata[url].'</a><br /><br />
					By doing so, you will no longer receive email updates from
					Feedmailer on this feed. To proceed with unsubscribing, please
					click the Continue button below to verify that you are wanting
					to unsubscribe from the correct feed.<br /><br />
					<center><h1>
					<a href="unsubscribe.php?fid='.$fdata[id].'&verify=true">CONTINUE</a>
					</h1>
					</center>');
					$template->footer();
				}
			} else {
				// Hmm. The feed does not exist for the specified user
				// what were you trying to do, bud?
				$template->header();
				$template->error("Specified Feed ID does not exist, or you are not the owner.");
				$template->footer();

				// We know what you were trying to do... ;)
			}
		} else {
			// No URL was specified. What now? The sky is falling!!
			// Backup plan B. Act as if nothing happened. Who knows,
			// maybe some dork was trying to exploit the system...

			header("Location: index.php");
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
