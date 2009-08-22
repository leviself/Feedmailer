<?php
/**
 * Name:	admin_delfeeds.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr 22 19:44:45 EDT 2009
 **/

require_once "inc/main.php";
require_once "inc/class.cache.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){

			$feed_id = serialize($_POST[feed_id]);
			$feed_array = unserialize($feed_id);

			if (($feed_id) && ($feed_id != "N;")){
				foreach ($feed_array as $singlefeed){
					// No need to preform checks. We're admin. :)
					$feed->deleteFeed($singlefeed);

					// Delete the old cache for the feed.
					$cache->deleteAll($singlefeed);
				}
				header("Refresh: 3; url=admin.php");
				$template->header();
				$template->message(
				"Selected Feed(s) have been deleted.");
				$template->footer();

			} else {
				$template->header();
				$template->admin_delfeeds();
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
