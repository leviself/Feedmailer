<?php
/**
 * Name: 	class.feeds.php
 * Group: 	Feedmailer
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Mon Apr  6 10:47:38 EDT 2009
 **/

/**
 * Name: 	feeds
 * Description: Preferms feed functions
 * Usage:	$feed = new feeds();
 * Usage:	$feed->function();
 **/
class feeds {

	/**
	 * Private Variables
	 **/
	private $mysql, $debug;

	/**
	 * The contsructor function executes
	 * at runtime. Load the debugger for this class.
	 **/
	public function __construct(){
		require_once dirname(__FILE__)."/class.debug.php";
		$this->debug = new debugger();
	}

	/**
	 * Name:	Load MySQL Connection
	 * Description:	Import MySQL Connection, for queries.
	 * Usage:	$this->db();
	 **/
	private function db(){
		// Import db class and assign $this->mysql to the class.
		$this->mysql = new db();
		$this->debug->write(FF, "feeds::db() initiated a connection");
		return $this->mysql->connect();
	}

	/**
	 * Name:	Show All Feeds
	 * Description:	Displays all feeds inside a table.
	 		(For administrator usage.)
	 * Usage:	$feed->showAllFeeds($orderby, $start, $finish);
	 **/
	public function showAllFeeds($orderby, $start, $finish){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		ORDER BY `id` $orderby LIMIT $start,$finish");

		if ($query){
			while ($row = mysql_fetch_array($query)){

				// Instead of showing a userid for the feed owner, show a username
				$username = @mysql_result(mysql_query(
				"SELECT `username` FROM `{$this->mysql->prefix}users`
				WHERE id='$row[uid]'"),0);

				echo '<tr>';
				echo '<td><input type="checkbox" name="feed_id[]" value="'.$row[id].'" /></td>';
				echo '<td><a title="Edit Feed" href="editfeed?id='.$row[id].'">'.$row[id].'</a></td>';
				echo '<td><a title="Edit User" href="edituser?id='.$row[uid].'">'.$username.'</a></td>';
				echo '<td><a title="Visit feed" href="'.$row[url].'">'.$row[url].'</a></td>';
				echo '</tr>'."\n";
			}
		} else {
			return false;
		}
		$this->debug->write(FF, "feeds::showAllFeeds({$orderby}, {$start}, {$finish}) completed successfully");

		$this->mysql->disconnect();
	}
	
	/**
	 * Name:	Show Feeds
	 * Description:	Show the feeds for the specified userid.
	 * Usage:	$feed->showFeeds(orderby, start, finish, userid);
	 **/
	public function showFeeds($orderby, $start, $finish, $id){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		WHERE uid='$id' ORDER BY `id` $orderby LIMIT $start,$finish");

		if ($query){
			while ($row = mysql_fetch_array($query)){
				echo '<tr>';
				echo '<td><input type="checkbox" name="delfeed_id[]" value="'.$row[id].'" /></td>';
				echo '<td><a href="'.$row[url].'">'.$row[url].'</a></td>';
				echo '</tr>';
			}
		} else {
			return false;
		}
		$this->debug->write(FF, "feeds::showFeeds({$id}) completed successfully");

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Select Feeds
	 * Description:	Shows feeds for userid in a <select> box.
	 * Usage:	$feed->selectFeeds(userid);
	 **/
	public function selectFeeds($id){
		// Load the MySQL connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		WHERE uid='$id' ORDER BY `id` DESC");

		if ($query){
			while ($row = mysql_fetch_array($query)){
				echo '<option value="'.$row[id].'">'.$row[url].'</option>'."\n";
			}
			$this->debug->write(II, "feeds::selectFeeds({$id}) completed successfully");
		} else {
			$this->debug->write(EE, "feeds::selectFeeds({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Select Feed Data (!)
	 * Description:	Selects the Feed data from the database and returns it
	 		in an array (!Different than Select Feeds, the above function!)
			Requires the id of the feed (fid)
	 * Usage:	$feed->selectFeedData(feedid);
	 **/
	public function selectFeedData($fid){
		// Load the MySQL connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		WHERE id='$fid'");

		$result = mysql_fetch_assoc($query);

		if ($result){
			$this->debug->write(FF, "feeds::selectFeedData({$fid}) returned an array");
			return $result;
		} else {
			$this->debug->write(EE, "feeds::selectFeedData({$fid}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Make Feed ID
	 * Description:	Generate a unique (unused) ID for a Feed.
	 * Usage:	$this->makeFeedID();
	 **/
	private function makeFeedID(){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = mysql_query(
		"SELECT `id` FROM `{$this->mysql->prefix}feeds`
		ORDER BY `id` DESC LIMIT 0,1");

		// Assign the result
		$result = @mysql_result($query, 0) + 1;

		if ($result){
			$this->debug->write(FF, "feeds::makeFeedID() returned {$result}");
			return $result;
		} else {
			$this->debug->write(EE, "feeds::makeFeedID() returned false. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Feed Exists
	 * Description:	Return boolean for url.
	 * Usage:	$feed->feedExists(userid, url);
	 **/
	public function feedExists($userid, $url){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		WHERE url='$url' AND uid='$userid'");

		// Assign the row
		$row = mysql_fetch_assoc($query);

		if ($row[url]){
			$this->debug->write(II, "feeds::feedExists({$userid}, {$url}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "feeds::feedExists({$userid}, {$url}) returned false");
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Feed Exists for User (!)
	 * Description: Checks to see if the feed id (fid) exists for
	 		the user id (uid). Returns boolean. (This function
			is different from the above feedExists function).
	 * Usage:	$feed->feedExistsForUser(fid, uid);
	 **/
	public function feedExistsForUser($fid, $uid){
		// Load the MySQL connection
		$this->db();

		// Assign the query
		$query = mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		WHERE id='$fid' AND uid='$uid'");

		// Assign the row
		$row = mysql_fetch_assoc($query);

		if ($row[id]){
			$this->debug->write(II, "feeds:feedExistsForUser({$fid}, {$uid}) returned true");
			return true;
		} else {
			$this->debug->write(EE, "feeds:feedExistsForUser({$fid}, {$uid}) returned false");
			return false;
		}

		$this->mysql->disconnect();
	}
			

	/**
	 * Name:	Add Feed
	 * Description:	Add a Feed to the database.
	 * Usage:	$feed->addFeed(userid, url);
	 **/
	public function addFeed($userid, $url){
		// Load the MySQL Connection
		$this->db();

		// Obtain a new feed id
		$id = $this->makeFeedID();

		// Sanitize the url
		$url = $this->mysql->sanitize($url);

		if (!$this->feedExists($userid, $url)){
			// Assign the query
			$query = @mysql_query(
			"INSERT INTO `{$this->mysql->prefix}feeds`
			(id, uid, url) VALUES
			('$id', '$userid', '$url')");

			if ($query){
				$this->debug->write(II, "feeds::addFeed({$userid}, {$url}) returned true");
				return true;
			} else {
				$this->debug->write(EE, "feeds::addFeed({$userid}, {$url}) returned false. See ERROR below:");
				$this->debug->write(EE, mysql_error());
				return false;
			}
		} else {
			return false;
		}

		$this->mysql->disconnect();
	}
	
	/**
	 * Name:	Delete Feed
	 * Description:	Delete a Feed from the database.
	 * Usage:	$feed->deleteFeed(id);
	 **/
	public function deleteFeed($id){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"DELETE FROM `{$this->mysql->prefix}feeds`
		WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "feeds::deleteFeed({$id}) returned true");
			return true;
		} else {
			$this->debug->write(EE, "feeds::deleteFeed({$id}) returned false. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Set Feed URL
	 * Description:	Set the feeds URL to a different value.
	 * Usage:	$feed->setURL($id, $url);
	 **/
	public function setURL($id, $url){
		// Load the MySQL connection
		$this->db();

		// Assign the query
		$query = mysql_query(
		"UPDATE `{$this->mysql->prefix}feeds` SET
		url='$url' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "feeds::setURL({$id}, {$url}) returned true");
			return true;
		} else {
			$this->debug->write(EE, "feeds::setURL({$id}, {$url}) returned false. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Set Feed Title
	 * Description:	Set the feeds title to a different value.
	 		$title can be set to NULL, to NULLify the
			title table.
	 * Usage:	$feed->setTitle($id, $title);
	 **/
	public function setTitle($id, $title = NULL){
		// Load the MySQL Connection
		$this->db();


		// updatefeeds.php checks for NULL feed titles
		// so the user can either have a default feed
		// title, or a custom title.
		if ($title == NULL){
			// Assign the query
			$query = @mysql_query(
			"UPDATE `{$this->mysql->prefix}feeds` SET
			title=NULL WHERE id='$id'");
		} else {
			// Assign the query
			$query = @mysql_query(
			"UPDATE `{$this->mysql->prefix}feeds` SET
			title='$title' WHERE id='$id'");
		}

		if ($query){
			$this->debug->write(II, "feeds::setTitle({$id}, {$title}) returned true");
			return true;
		} else {
			$this->debug->write(EE, "feeds::setTitle({$id}, {$title}) returned false. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Show Popular Feeds
	 * Description:	Show the most popular feeds (based on
	 		values in the database).
	 * Usage:	$template->showPopularFeeds();
	 **/
	public function showPopularFeeds(){
		// Load the MySQL Connection
		$this->db();

		// Find out if it's even enabled..
		$pop_enable = $this->mysql->setting(pop_enable);
		if ($pop_enable == "true"){
			
			// Gather the rest of the queries
			$pop_limit = $this->mysql->setting(pop_limit);
			$pop_orderby = $this->mysql->setting(pop_orderby);
			$pop_howpopular = $this->mysql->setting(pop_howpopular);
			$pop_display = $this->mysql->setting(pop_display);

			// Assign the query
			$query = mysql_query(
			"SELECT COUNT(*) as popular, url FROM `{$this->mysql->prefix}feeds`
			GROUP BY url HAVING popular >= $pop_howpopular ORDER BY popular $pop_orderby
			LIMIT 0,$pop_limit");
			
			if ($pop_display == "simple"){
				while ($row = @mysql_fetch_array($query)){
					echo '<a href="'.SITE_URL.'subscribe.php?url='.$row[url].'">'.$row[url].'</a>'."<br />\n";
				}
			} elseif ($pop_display == "extended"){
				// Load the XML Parser.
				include dirname(__FILE__)."/class.xmlparser.php";
				$xml = new xmlparser();

				while ($row = @mysql_fetch_array($query)){
					// Parse the URL
					$xml->parse($row[url]);

					// Determine a Favicon
					preg_match("@^(?:http://)?([^/]+)@i", $row[url], $matches);
					$favicon = "http://".$matches[1]."/favicon.ico";

					foreach ($xml->item as $item){
						// Get the Feed Title
						$title = $item[feedtitle];
					}

					echo '<img src="'.$favicon.'" border="0" height="16" width="16" alt="favicon" title="favicon"/> ';
					echo '<a href="'.SITE_URL.'subscribe.php?url='.$row[url].'">'.$title.'</a>'."<br />\n";
				}
			}



			$this->debug->write(FF, "feeds::showPopularFeeds() completed successfully");
			return true;		
		} else {
			// It's better to just keep this commented. Saves on debug lines...
			#$this->debug->write(FF, "feeds::showPopularFeeds() is not enabled");
			return false;
		}
		$this->mysql->disconnect();
	}
	

}


// While we're here, go ahead and assign the $feed variable
$feed = new feeds();

?>
