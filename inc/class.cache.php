<?php
/**
 * Name: 	class.cache.php
 * Group: 	Feedmailer
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Tue Apr  7 19:47:59 EDT 2009
 **/

/**
 * Name: 	Cache
 * Description: Preforms cache functions for updatefeeds.php
 * Usage:	$cache = new cache();
 * Usage:	$cache->function();
 **/
class cache {

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
	 * Description:	Import the MySQL connection, for queries.
	 * Usage:	$this->db();
	 **/
	private function db(){
		// Import db class and assign $this->mysql to the class.
		$this->mysql = new db();
		$this->debug->write(FF, "cache::db() initiated a connection");
		return $this->mysql->connect();
	}

	/**
	 * Name:	Feed ID Exists
	 * Description:	Check and see if the fid exists.
	 * Usage:	$cache->fidExists($id);
	 **/
	public function fidExists($id){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}cache`
		WHERE fid='$id'");

		// Assign the row
		$row = mysql_fetch_array($query);

		if ($row[fid]){
			$this->debug->write(II, "cache::fidExists({$id}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "cache::fidExists({$id}) returned false; FID does not exist");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	PubDate Exists
	 * Description:	Return true if the PubDate exists in the cache for the FeedID
	 * Usage:	$cache->urlExists($pubdate, $feedid);
	 **/
	public function pubDateExists($pubdate, $feedid){
		// Load the MySQL Connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}cache`
		WHERE pubdate='$pubdate' AND fid='$feedid'");

		// Assign the row
		$row = mysql_fetch_assoc($query);

		if ($row[pubdate]){
			$this->debug->write(II, "cache::pubDateExists({$pubdate}, {$feedid}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "cache::pubDateExists({$pubdate}, {$feedid}) returned false. pubDate does not exist");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Add PubDate to Cache
	 * Description:	Add a pubdate to the cache.
	 * Usage:	$cache->addURL($feedid, $pubdate);
	 **/
	public function addPubDate($fid, $pubdate){
		// Load the MySQL Connection
		$this->db();

		// Generate a id
		$id = @mysql_result(mysql_query(
		"SELECT `id` FROM `{$this->mysql->prefix}cache`
		ORDER BY `id` DESC LIMIT 0,1"),0) + 1;

		// Assign the query
		$query = @mysql_query(
		"INSERT INTO `{$this->mysql->prefix}cache`
		(id, fid, pubdate) VALUES ('$id', '$fid', '$pubdate')");

		if ($query){
			$this->debug->write(II, "cache::addPubDate({$fid}, {$pubdate}) returned true");
			return true;
		} else {
			$this->debug->write(EE, "cache::addPubDate({$fid}, {$pubdate}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Delete
	 * Description:	Delete row that matches id.
	 * Usage:	$cache->delete($id);
	 **/
	public function delete($id){
		// Load the MySQL connection
		$this->db();

		// Assign the query
		$query = @mysql_query(
		"DELETE FROM `{$this->mysql->prefix}cache`
		WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "cache::delete({$id}) returned true");
			return true;
		} else {
			$this->debug->write(EE, "cache::delete({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			
			// Don't return false, or you will break the while loop
			// in delfeeds.php, if the user should delete the feed before
			// feedmailer runs the tasks/updatefeeds.php file.
			#return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Delete All for Feed ID
	 * Description:	Delete all the rows that match the fid.
	 		(Cleanup for when editing/deleting feeds,
			just clean up the cache too.)
	 * Usage:	$cache->deleteAll(fid);
	 **/
	public function deleteAll($fid){
		// Load the MySQL connection
		$this->db();

		if ($this->fidExists($fid)){
			// Assign the query
			$query = mysql_query(
			"SELECT `id` FROM `{$this->mysql->prefix}cache`
			WHERE fid='$fid'");

			while ($row = mysql_fetch_array($query)){
				// Delete individual rows, one at a time.
				$this->delete($row[id]);
			
			}
			// Write out a debug, so an administrator can verify
			$this->debug->write(FF, "cache::deleteAll({$fid}) completed.");
		}
		return true;
		$this->mysql->disconnect();
	}
}
$cache = new cache();
?>
