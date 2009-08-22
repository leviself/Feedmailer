<?php
/**
 * Name:	inc/class.auth.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 11:53:46 EDT 2009
 **/

/**
 * Name:	Auth
 * Description:	Preforms Authorization functions.
 * Usage:	$auth = new auth();
 * Usage:	$auth->function();
 **/
class auth{

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
		$this->debug->write(FF, "auth::db() initiated a connection");
		return $this->mysql->connect();
	}

	/**
	 * Name:	Check Login
	 * Description:	Determine if a login is true or false.
	 		Returns boolean.
	 * Usage:	$auth->checkLogin($username, $password);
	 **/
	 public function checkLogin($username, $password){
	 	// Load the MySQL connection.
		$this->db();

		// Change the username into an ID.
		$id = $this->mysql->usernameToID($username);

		// Select row for username.
		$user = $this->mysql->selectUser($id);

		// Check the login.
		// We only have to check the password, since $user will
		// fail if $username does not exist, and we are grabbing
		// the password for $username.
		if ($user[password] == $password){
			#$this->debug->write(II, "auth::checkLogin({$username}, {$password}) was successful"); // Don't uncomment this...
			$this->debug->write(II, "auth::checkLogin({$username}) was successful");
			return true;
		} else {
			$this->debug->write(WW, "auth::checkLogin({$username}) failed. Incorrect Login.");
			return false;
		}

		// Disconnect from the database.
		$this->mysql->disconnect();
	}

	/**
	 * Name:	User Exists
	 * Description: Preform check to see if user exists.
	 * Usage:	$auth->userExists($username);
	 **/
	 public function userExists($username){
	 	// Load the MySQL connection
		$this->db();

		// Change the username into an ID.
		$id = $this->mysql->usernameToID($username);

		// Select row for username.
		$user = $this->mysql->selectUser($id);

		// If we get a result from $user[id], then user exists.
		if ($user[id]){
			$this->debug->write(II, "auth::userExists({$username}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "auth::userExists({$username}) returned false");
			return false;
		}

		// Disconnect from the database.
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Email Exists
	 * Description:	Preform check to see if email exists.
	 * Usage:	$auth->emailExists($email);
	 **/
	 public function emailExists($email){
	 	// Load the MySQL connection
		$this->db();

		// Assign the query
		$query = mysql_query(
		"SELECT * FROM `{$this->mysql->prefix}users`
		WHERE email='$email'");

		// Assign the row
		$row = mysql_fetch_assoc($query);

		if ($row[email]){
			$this->debug->write(II, "auth::emailExists({$email}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "auth::emailExists({$email}) returned false");
			return false;
		}

		// Disconnect from the database.
		$this->mysql->disconnect();
	}

	/**
	 * Name:	User Is Activated
	 * Description:	Return true if userid is activated.
	 * Usage:	$auth->userIsActivated($username);
	 **/
	public function userIsActivated($userid){
		// Load the MySQL Connection
		$this->db();

		// Basically, if the activation_key is null, the
		// user is activated.

		// Select the user
		$user = $this->mysql->selectUser($userid);

		if ($user[activation_key] == NULL){
			$this->debug->write(II, "auth::userIsActivated({$userid}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "auth::userIsActivated({$userid}) returned false");
			return false;
		}

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Activate User
	 * Description:	Verify that the email and key match, then
	 		set the activation key to NULL for the user.
	 * Usage:	$auth->activateUser($email, $key);
	 **/
	public function activateUser($email, $key){
		// Load the MySQL Connection
		$this->db();

		// Obtain userid
		$id = $this->mysql->emailToID($email);

		// Select user
		$user = $this->mysql->selectUser($id);

		if ($user[activation_key] == $key){
			// Set the activation key to NULL
			$this->mysql->setActivationKey($id, $key = NULL);
			$this->debug->write(II, "auth::activateUser({$email}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "auth::activateUser({$email}) returned false; Key did not match");
			return false;
		}
	}

	/**
	 * Name:	Activate Email Address
	 * Description:	Verify that the email and email_key match, then
	 		set the email_key to NULL for the user.
	 * Usage:	$auth->activateEmail($email, $key);
	 **/
	public function activateEmail($email, $key){
		// Load the MySQL Connection
		$this->db();

		// Obtain userid
		$id = $this->mysql->emailToID($email);

		// Select user
		$user = $this->mysql->selectUser($id);

		if ($user[email_key] == $key){
			// Set the email_key to NULL
			$this->mysql->setEmailKey($id, $key = NULL);
			$this->debug->write(II, "auth::activateEmail({$email}) returned true");
			return true;
		} else {
			$this->debug->write(WW, "auth::activateEmail({$email}) returned false; Key did not match");
			return false;
		}
	}



	/**
	 * Name:	Generate a random password
	 * Description:	Return a random password based on a length.
	 * Usage:	$auth->generateRandomPassword($limit);
	 **/
	 public function generateRandomPassword($limit){
	 	// Assign all the possible characters.
		$possible = 'abcdefghijklmnopqrstuvwxyz0123456789';
		
		// Current password is null.
		$password = '';

		// The starting point.
		$i = 0;
		
		// while $i is less than $limit, preform function.
		while ($i < $limit){
			$password .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		// If you admire honesty, don't uncomment this...
		#$this->debug->write(FF, "auth::generateRandomPassword({$limit}) returned {$password}");

		return $password;
	}

	/**
	 * Name:	Smart Time
	 * Description:	Think realistically. Everyone likes you when you do :)

	 		Note to self: You need to rewrite this function to use
			CASE instead of elseif statements, because nathan doesn't
			like it... :|

	 * Usage:	$auth->smartTime(timestamp);
	 **/
	public function smartTime($timestamp){
		// Current timestamp
		$current_time = time();

		// Days
		$longtime = $timestamp+60*60*24*14; // Two weeks.
		$week = $timestamp+60*60*24*7;
		$severaldays = $timestamp+60*60*24*3;
		$yesterday = $timestamp+60*60*24;

		// Hours
		$twelvehours = $timestamp+60*60*12;
		$tenhours = $timestamp+60*60*10;
		$ninehours = $timestamp+60*60*9;
		$eighthours = $timestamp+60*60*8;
		$sevenhours = $timestamp+60*60*7;
		$sixhours = $timestamp+60*60*6;
		$fivehours = $timestamp+60*60*5;
		$fourhours = $timestamp+60*60*4;
		$threehours = $timestamp+60*60*3;
		$twohours = $timestamp+60*60*2;
		$onehour = $timestamp+60*60;

		// Minutes
		$fourtyfive = $timestamp+60*45;
		$thirty = $timestamp+60*30;
		$fifteen = $timestamp+60*15;
		$five = $timestamp+60*5;
		$four = $timestamp+60*4;
		$three = $timestamp+60*3;
		$two = $timestamp+60*2;
		$one = $timestamp+60;

		// Seconds
		$seconds = $timestamp+30;

		switch($current_time) {
			case ($current_time > $longtime):
				$smart_time = date("F jS Y", $timestamp);
				break;

			case ($current_time > $week):
				$smart_time = "A week ago";
				break;

			case ($current_time > $severaldays):
				$smart_time = "Several days ago";
				break;

			case ($current_time > $yesterday):
				$smart_time = "Yesterday";
				break;

			case ($current_time > $twelvehours):
				$smart_time = "12 hours ago";
				break;

			case ($current_time > $tenhours):
				$smart_time = "10 hours ago";
				break;

			case ($current_time > $ninehours):
				$smart_time = "9 hours ago";
				break;

			case ($current_time > $eighthours):
				$smart_time = "8 hours ago";
				break;

			case ($current_time > $sevenhours):
				$smart_time = "7 hours ago";
				break;
			
			case ($current_time > $sixhours):
				$smart_time = "6 hours ago";
				break;
			
			case ($current_time > $fivehours):
				$smart_time = "5 hours ago";
				break;
			
			case ($current_time > $fourhours):
				$smart_time = "4 hours ago";
				break;
			
			case ($current_time > $threehours):
				$smart_time = "3 hours ago";
				break;
			
			case ($current_time > $twohours):
				$smart_time = "2 hours ago";
				break;
			
			case ($current_time > $onehour):
				$smart_time = "1 hour ago";
				break;
			
			case ($current_time > $fourtyfive):
				$smart_time = "45 minutes ago";
				break;
			
			case ($current_time > $thirty):
				$smart_time = "30 minutes ago";
				break;
			
			case ($current_time > $fifteen):
				$smart_time = "15 minutes ago";
				break;
			
			case ($current_time > $five):
				$smart_time = "5 minutes ago";
				break;
			
			case ($current_time > $four):
				$smart_time = "4 minutes ago";
				break;
			
			case ($current_time > $three):
				$smart_time = "3 minutes ago";
				break;
			
			case ($current_time > $two):
				$smart_time = "2 minutes ago";
				break;
			
			case ($current_time > $one):
				$smart_time = "1 minute ago";
				break;
			
			case ($current_time > $seconds):
				$smart_time = "less than a minute ago";
				break;

			default:
				$smart_time = "a few seconds ago";
		}

		$this->debug->write(FF, "auth::smartTime({$timestamp}) returned {$smart_time}");
		return $smart_time;

	}
}

// While we're here, lets go ahead and assign the $auth varable.
$auth = new auth();


?>
