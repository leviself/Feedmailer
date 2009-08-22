<?php
/**
 * Name: 	class.backupfeeds.php
 * Group: 	Feedmailer
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Sat May 16 21:52:48 EDT 2009
 **/

/**
 * Name: 	backupfeeds
 * Description: Preform functions for the Import/Export feature
 * Usage:	$backup = new backupfeeds();
 * Usage:	$backup->function();
 **/
class backupfeeds {

	/**
	 * Public Variables
	 **/
	public $XML;

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

		$this->feed = new feeds();
	}

	/**
	 * Name:	Load MySQL Connection
	 * Description:	Import MySQL Connection, for queries.
	 * Usage:	$this->db();
	 **/
	private function db(){
		// Import db class and assign $this->mysql to the class.
		$this->mysql = new db();
		$this->debug->write(FF, "backupfeeds::db() initiated a connection");
		return $this->mysql->connect();
	}
	/**
	 * Name:	Export
	 * Description:	Build the XML file for the user to download
	 		by using other special internal functions.
			Requires the user's ID to execute.
	 * Usage:	$backup->export(uid);
	 **/
	public function export($uid){
		// Load the MySQL Connection
		$this->db();

		// Check and see if the user has any feeds.
		$count = @mysql_result(mysql_query(
		"SELECT COUNT(id) FROM `{$this->mysql->prefix}feeds` 
		WHERE uid='$uid'"),0);

		if ($count < 1){
			return false;
		}

		// Assign the query
		$query = mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}feeds`
		WHERE uid='$uid' ORDER BY `id` DESC");

		$this->buildXML($query);
		
		// Use $backup->XML to retrive output.

		$this->debug->write(FF, "backupfeeds::export({$uid}) completed successfully");
		return true;
	}


	/**
	 * Name:	Build XML
	 * Description:	Build the XML output for the export function
	 		and assign it to the $xml variable.
	 * Usage:	$this->buildXML(array);
	 **/
	private function buildXML($query){
		// Build the XML document.
		$DOM = new DOMDocument();
		$DOM->formatOutput = true;

		// Create the root element.
		$root = $DOM->appendChild($DOM->createElement("feedmailer"));

		while ($row = mysql_fetch_array($query)){
			// Create feed element.
			$feedtag = $root->appendChild($DOM->createElement("feed"));

			// Create the child elements which contain the data.
			$feedtag->appendChild($DOM->createElement("title", $row[title]));
			$feedtag->appendChild($DOM->createElement("url", $row[url]));
		}

		// Assign the XML data to the $XML variable.
		$this->XML = $DOM->saveXML();
		$this->debug->write(FF, "backupfeeds::buildXML() completed successfully");
	}

	/**
	 * Name:	Import
	 * Description:	The meta-function that does all of the importing
	 		from the XML file. Required input must be the uid
			of the user, and the complete path to the file.
	 * Usage:	$backup->import(uid, file);
	 **/
	public function import($uid, $file){
		// Load the MySQL Connection
		$this->db();

		// Open the file
		$file = file_get_contents($file);

		// Pass the file contents to backupfeeds::parse and
		// then return an array with the contents.
		$this->parse($file);

		foreach ($this->feeds as $feed){
			// Sanitize the input, so no one can run exploits
			// from the XML file. (oww! that would be bad!!!)
			$url   = $this->mysql->sanitize($feed[url]);
			$title = $this->mysql->sanitize($feed[title]);

			// Insert the feed
			// The feeds::addFeed() function checks for already
			// existing URLs by the uid in the db.
			if ($this->feed->addFeed($uid, $url)){

				// Set the title, if one is specified.
				if ($title != NULL){

					// Get the ID of the feed.
					$fid = mysql_result(mysql_query(
					"SELECT `id` FROM `{$this->mysql->prefix}feeds`
					WHERE uid='$uid' AND url='$url'"),0);

					$this->feed->setTitle($fid, $title);
				}
			}
			
		}
		return true;
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Parse
	 * Description:	Parse the XML file, and return data.
	 		Requires the file contents as an input.
	 * Usage:	$this->parse(file);
	 **/
	private function parse($file){
		$xml = new SimpleXMLElement($file);

		foreach ($xml->feed as $feed){
			$title   = (string)$feed->title;
			$url     = (string)$feed->url;

			$feeds[] = array(
					"title" => $title,
					"url" => $url);

			$this->feeds = $feeds;
		}
	}
}
$backup = new backupfeeds();
?>
