<?php
/**
 * Name: 	class.debug.php
 * Group: 	Feedmailer
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Wed Apr 29 19:16:25 EDT 2009
**/

/**
 * Name: 	Debugger
 * Description: A simplfied debugger.
 * Usage:	$debug = new debugger();
 * Usage:	$debug->function();
**/
class debugger{


	/**
	 * Private Variables
	 **/
	private $connection, $prefix;	// This variable is available from the class.db.php
					// and can be used from the DB_PREFIX object.

	
	// The debugger must make it's own database connection
	// and function independantly of any of the other classes.

	/**
	 * Name:	Database Connection
	 * Description:	Initiate a connection to the database.
	 * Usage:	$this->connect();
	 **/
	private function connect(){
		
		// Include Database Settings File
		include dirname(__FILE__)."/config.php";

		// Assign the table prefix
		$this->prefix = $prefix;

		// Assign the connection
		$this->debug_connection = @mysql_connect($sqlhost, $sqluser, $sqlpass);

		if (!$this->debug_connection){
			die("Debugger could not connect to MySQL Server '{$sqlhost}'<br />".mysql_error());
		}

		// Select the MySQL Database
		@mysql_select_db($db, $this->debug_connection)
		or die("Debugger could not select MySQL database '{$db}'<br />".mysql_error());
	}

	/**
	 * Name:	Disconnect
	 * Description:	Used for closing connections to the database.
	 * Usage:	$this->disconnect();
	 **/
	private function disconnect(){		
		// Disconnect from the server.
		@mysql_close($this->debug_connection)
		or die("Debugger could not disconnect from MySQL Server.<br />".mysql_error());
	}

	/**
	 * Name:	Build table
	 * Description:	Build the table structure for recording
	 		debug information in the database.
	 * Usage:	$debug->buildTable();
	 **/
	public function buildTable(){
		// Connect to the database.
		$this->connect();

		// Assign the query
		$query = mysql_query(
		"CREATE TABLE `{$this->prefix}debug`
		(id INT, timestamp INT, type VARCHAR(2), message TEXT)");

		if ($query){
			return true;
		} else {
			return false;
		}

		$this->disconnect();
	}

	/**
	 * Name:	Destroy table
	 * Description:	Destroy the debug table at a whim.
	 * Usage:	$debug->destroyTable();
	 **/
	public function destroyTable(){
		// Connect to the database.
		$this->connect();

		// Assign the query
		$query = mysql_query(
		"DROP TABLE `{$this->prefix}debug`");

		if ($query){
			return true;
		} else {
			return false;
		}

		$this->disconnect();
	}

	/**
	 * Name:	Make ID
	 * Description:	Generate a new unique id
	 * Usage:	$this->makeID();
	 **/
	private function makeID(){
		// Connect to the database
		$this->connect();

		// Assign the query
		$query =  mysql_query(
		"SELECT `id` FROM `{$this->prefix}debug`
		ORDER BY `id` DESC LIMIT 0,1");
		
		// We use @ here, incase no users exists, and mysql_result throws an error.
		$result = @mysql_result($query,0) + 1;

		if ($result){
			return $result;
		} else {
			return false;
		}
	}

	/**
	 * Name:	Write
	 * Description:	Append a formated row to the debug table.
	 		Available types:
				FF	II
				EE	WW
	 * Usage:	$debug->write(TYPE, $message);
	 **/ 
	public function write($type, $message){
		// Connect to the database
		$this->connect();


		// We must determine if debug_enable is true
		// so we can write the debug line to the database.
		$debug_enable = mysql_result(mysql_query(
		"SELECT `value` FROM `{$this->prefix}settings`
		WHERE `id`='debug_enable'"),0);

		if ($debug_enable == "true"){

			// A brief list of the types
			// and what they stand for.
			//
			// FF	= 	Function
			// II	=	Information
			// EE	=	Error
			// WW	=	Warning

			// Current time
			$timestamp = time();

			// Generate a new row id
			$id = $this->makeID();

			// Assign the query
			$query = mysql_query(
			"INSERT INTO `{$this->prefix}debug`
			(id, timestamp, type, message) VALUES
			('$id', '$timestamp', '$type', '$message')");

			if ($query){
				return true;
			} else {
				return false;
			}
		}

		// Can't disconnect, as I found out.
		// It disconnects midstream in all the other classes, too. :(
		#$this->disconnect();
	}

	/**
	 * Name:	Delete
	 * Description:	Delete row that matches id.
	 * Usage:	$debug->delete($id);
	 **/
	public function delete($id){
		// Load the MySQL connection
		$this->connect();

		// Assign the query
		$query = @mysql_query(
		"DELETE FROM `{$this->prefix}debug`
		WHERE id='$id'");

		if ($query){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Name:	Show Debug
	 * Description:	Show the debug log
	 * Usage:	$debug->showDebug();
	 **/
	public function showDebug($search = NULL, $orderby, $start, $finish){
		$this->connect();

		if ($search == NULL){
			$results = mysql_query(
			"SELECT * FROM `{$this->prefix}debug`
			ORDER BY `id` $orderby LIMIT $start,$finish");
		} else {
			$results = mysql_query(
			"SELECT * FROM `{$this->prefix}debug`
			WHERE type LIKE '%$search%' OR message LIKE '%$search%'
			ORDER BY `id` $orderby LIMIT $start,$finish");
		}
			

		while ($row = mysql_fetch_array($results)){
			$id = $row[id];
			$timestamp = date("m/d/Y h:i:s A", $row[timestamp]);
			$message = $row[message];
			$type = $row[type];

			// Alternate row style
			$alt = ($count % 2) ? 'odd' : 'even';
			$count++;

			// Search and replace options
			// Make clickable URLs.
			$message = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([~\-\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $message);

			echo '<tr class="'.$alt.'">'."\n";
			echo '<td>'.$timestamp.'</td>'."\n";
			echo '<td>(<strong>'.$type.'</strong>)</td>'."\n";
			echo '<td>'.$message.'</td>'."\n";
			echo '</tr>'."\n";

		}
	}
}
?>
