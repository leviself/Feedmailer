<?php
/*
	$id: updatefeeds.php
	$group: FeedMailer
	$author: Dr Small <drsmall@mycroftserver.homelinux.org>
	$datetime: Day 034 2008; 19:58 [EST]
*/

// Only import the absolutely necessary classes.
require_once dirname(__FILE__)."/../inc/class.db.php";
require_once dirname(__FILE__)."/../inc/class.mail.php";
require_once dirname(__FILE__)."/../inc/class.cache.php";
require_once dirname(__FILE__)."/../inc/class.xmlparser.php";

$mysql->connect();
$xml = new xmlparser();

$getusers = mysql_query("SELECT id, email, mail_type, email_key, vacation FROM `{$mysql->prefix}users` ORDER BY id ASC");
while ($row = mysql_fetch_array($getusers)){
	// Assign the specific rows a variable.
	$id = $row[id];
	$email = $row[email];
	$mail_type = $row[mail_type];
	$vacation = $row[vacation];
	$email_key = $row[email_key];

	// Only run if user is not on vacation.
	if($vacation == 'off'){
		// Only continue if user's email is activated
		if ($email_key == NULL){

			$getfeed = mysql_query("SELECT * FROM `{$mysql->prefix}feeds` WHERE uid='$id' ORDER BY id ASC");
			while ($feedrow = mysql_fetch_array($getfeed)){
				// Assign all the feed rows a variable
				$feed[id] = $feedrow[id];
				$feed[uid] = $feedrow[uid];
				$feed[url] = $feedrow[url];
				$feed[title] = $feedrow[title];

				// Check the file, parse the file based on type.
				if($xml->parse($feed[url])){

					// Don't send emails to user if there are no cache.
					if (!$cache->fidExists($feed[id])){
						foreach ($xml->item as $item){
							$cache->addPubDate($feed[id], $item[pubdate]);
						}
					} else {
	
						foreach ($xml->item as $item){
	
							$feedtitle = is_null($feed[title]) ? $item[feedtitle] : $feed[title];
							$itemtitle = $item[title];
							$url = $item[url];
							$pubDate = $item[pubdate];
							$comments = $item[comments];
							$description = $item[description];
							$unsubscribelink = $mysql->setting(site_url)."unsubscribe.php?fid={$feed[id]}";

							// If the url is not in cache for user, continue.
							if(!$cache->pubDateExists($pubDate, $feed[id])){	

								// Set the mail_count setting
								$mail_count = $mysql->setting(email_count) + 1;
								$mysql->setSetting("email_count", $mail_count);

								// Set the user's mail count. (function automatically raises count)
								$mysql->setEmailCount($id);
						
								// Send the email (complex function)
								$mail->sendEmail($id, $email, $feedtitle, $itemtitle, $url,
									$pubDate, $comments, $description, $unsubscribelink);

								// Write the url to cache
								$cache->addPubDate($feed[id], $pubDate);

							}
							// Build query to delete cache
							// that does not exist in XML.
							$delcache .= "AND `pubdate`!='$pubDate' ";
						}

						// This handles the cache control. If there are pubdates
						// that are not listed in the XML file, we automatically
						// delete them from the database. This way, incase the
						// XML feed does not get updated for a long time, the cache
						// remains in the database so long as the pubdate remains
						// in the XML file.
						if (!is_null($delcache)){
							// Select all cache where pubdate did not
							// match the entries in the XML file.
							$selquery = mysql_query("
								SELECT `id` FROM `{$mysql->prefix}cache`
								WHERE `fid`='{$feed[id]}' {$delcache}");
						

							while ($del = mysql_fetch_array($selquery)){
								$cache->delete($del[id]);
							}
						}
					}
				} else {
					// I was practicing with this.
					// Not sure if it was helpful or not...
					continue;
				}
			}
		}
	}
}

$mysql->disconnect();
?>
