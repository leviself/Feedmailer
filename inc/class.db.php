<?php
/**
 * Name: 	class.db.php
 * Group: 	Simple Login
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Wed Mar 25 21:17:09 EDT 2009
**/

/**
 * Name: 	DB
 * Description: Preforms Database functions
 * Usage:	$mysql = new db();
 * Usage:	$mysql->function();
**/
class db{
	
	/**
	 * Public Variables
	**/
	public $prefix;

	/**
	 * Private Variables
	**/
	private $connection, $debug;

	public function __construct(){
		require_once dirname(__FILE__)."/class.debug.php";
		$this->debug = new debugger();
	}

	/**
	 * Name:	Connect
	 * Description:	Connect to the MySQL Server.
	 * Usage:	$mysql->connect();
	**/
	public function connect(){
		// Include Database Settings File
		include dirname(__FILE__)."/config.php";

		// Assign the table prefix
		$this->prefix = $prefix;

		// Assign the connection
		$this->connection = @mysql_connect($sqlhost, $sqluser, $sqlpass);

		if (!$this->connection){
			die("Could not connect to MySQL Server '{$sqlhost}'<br />".mysql_error());
		}
		

		// Select the MySQL Database
		@mysql_select_db($db, $this->connection)
		or die("Could not select MySQL database '{$db}'<br />".mysql_error());

	}

	/**
	 * Name:	Disconnect
	 * Description: Disconnect from the MySQL Server.
	 * Usage:	$mysql->disconnect();
	**/
	public function disconnect(){
		// Disconnect from the server.
		@mysql_close($this->connection)
		or die("Could not disconnect from MySQL Server.<br />".mysql_error());
	}

	/**
	 * Name:	Add User
	 * Description: Add a user to the database. Use 'true' if you plan
	 		to make the account admin accessible.
	* Usage:	$mysql->addUser($username, $password, $email, $key);
	* Usage:	$mysql->addUser($username, $password, $email, $key, true);
	**/
	public function addUser($username, $password, $email, $key = NULL, $is_admin = NULL){
		// Generate a unique userid
		$userid = $this->makeUserID();

		// Generate a timestamp. I love timestamps :)
		$timestamp = time();

		// Obtain the user's ip address.
		$ip = $_SERVER[REMOTE_ADDR];

		// Set the admin variable.
		$admin = is_null($is_admin) ? 'false' : 'true';

		// Set the activation key
		// if the key variable isn't specified, it must be an admin user
		// who doesn't need to activate himself. got it? :)
		$key = is_null($key) ? NULL : $key;

		// The email_key is not set when adding a user, so
		// we'll set it to NULL. The email_key is only to be
		// set and used when changing/verifying email addressses.
		$email_key = NULL;

		// All emails should be sent as multipart/alternative by default
		$mail_type = "multipart/alternative";

		// Default vacation should be set to off
		$vacation = "off";

		// Default cookie expire
		$cookie_expire = "604800"; // 1 week (60*60*24*7)

		// Assign the query, so we can see if it suceeds, or not.
		$query = mysql_query(
		"INSERT INTO `{$this->prefix}users`
		(id, username, password, email, last_login, last_ip, 
		cookie_auth, cookie_expire, reg_ip, mail_type, email_count, 
		vacation, activation_key, email_key, is_admin) 
		VALUES
		('$userid', '$username', '$password', '$email', '$timestamp', '$ip',
		NULL, '$cookie_expire', '$ip', '$mail_type', '0', 
		'$vacation', '$key', '$email_key', '$admin')");

		if ($query){
			$this->debug->write(II, "db::adduser() completed");
			return true;
		} else {
			$this->debug->write(EE, "db::adduser() failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Delete User
	 * Description:	Delete a user from the database.
	 * Usage:	$mysql->deleteUser($id);
	 **/
	public function deleteUser($id){
		// Assign the query
		$query = @mysql_query(
		"DELETE FROM `{$this->prefix}users`
		WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db:deleteUser({$id}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::deleteUser({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}
		

	/**
	 * Name:	Make User ID
	 * Description: Generate a unique user id
	 * Usage: 	$this->makeUserID();
	**/
	private function makeUserID(){
		// Assign the query, so we can see if it suceeds, or not.
		$query =  mysql_query(
		"SELECT `id` FROM `{$this->prefix}users`
		ORDER BY `id` DESC LIMIT 0,1");
		
		// We use @ here, incase no users exists, and mysql_result throws an error.
		$result = @mysql_result($query,0) + 1;

		if ($result){
			$this->debug->write(II, "db::makeUserID() returned {$result}");
			return $result;
		} else {
			$this->debug->write(EE, "db::makeUserID() failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Username to ID
	 * Description: Convert a username to an id number.
	 * Usage:	$mysql->usernameToID($username);
	**/
	public function usernameToID($username){
		// Assign the query
		$query = mysql_query(
		"SELECT `id` FROM `{$this->prefix}users`
		WHERE username='$username'");

		// Assign the id
		$id = @mysql_result($query,0);

		if ($id){
			$this->debug->write(II, "db::usernameToID({$username}) returned {$id}");
			return $id;
		} else {
			$this->debug->write(EE, "db::usernameToID({$username}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	ID to Username
	 * Description: Convert an id number to a username.
	 * Usage:	$mysql->idToUsername($id);
	**/
	public function idToUsername($id){
		// Assign the query
		$query = mysql_query(
		"SELECT `username` FROM `{$this->prefix}users`
		WHERE id=`$id'");

		// Assign the username
		$username = @mysql_result($query,0);

		if ($username){
			$this->debug->write(II, "db::idToUsername({$id}) returned {$username}");
			return $username;
		} else {
			$this->debug->write(EE, "db::idToUsername({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Email to ID
	 * Description:	Convert an email address to an userid
	 * Usage:	$mysql->emailToID($email);
	 **/
	public function emailToID($email){
		// Assign the query
		$query = mysql_query(
		"SELECT `id` FROM `{$this->prefix}users`
		WHERE email='$email'");

		// Assign the id
		$id = @mysql_result($query, 0);

		if ($id){
			$this->debug->write(II, "db::emailToID({$email}) returned {$id}");
			return $id;
		} else {
			$this->debug->write(EE, "db::emailToID({$email}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Select User
	 * Description: Return a selected user's data in an array.
	 * Usage:	$mysql->selectUser($id);
	**/
	public function selectUser($value){
		// Assign the query
		$query = mysql_query(
		"SELECT * FROM `{$this->prefix}users` 
		WHERE id='$value'");

		// Assign the result
		$result = mysql_fetch_assoc($query);

		if ($result){
			$this->debug->write(II, "db::selectUser({$value}) completed");
			return $result;
		} else {
			$this->debug->write(EE, "db::selectUser({$value}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * From this point on, we are dealing with user settings
	 * which directly affect the database. All of these functions
	 * require the id of the user. You may use the usernameToID($user)
	 * function to obtain the user's id.
	 *
	 * Dr Small
	**/


	/**
	 * Name:	Change User Password
	 * Description:	Change the user's password in the database.
	 * Usage:	$mysql->setPassword($id, $password);
	**/
	public function setPassword($id, $password){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		password='$password' WHERE id='$id'");

		if ($query){
			#$this->debug->write(II, "db::setPassword({$id}, {$password}) completed"); // For non-honest folks
			$this->debug->write(II, "db::setPassword({$id}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setPassword({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Change User Email
	 * Description: Change the user's email in the database.
	 * Usage:	$mysql->setEmail($id, $email);
	**/
	public function setEmail($id, $email){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		email='$email' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setEmail({$id}, {$email}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setEmail({$id}, {$email}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Change Username
	 * Description: Change the user's username in the database.
	 * Usage:	$mysql->setUsername($id, $username);
	**/
	public function setUsername($id, $username){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		username='$username' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setUsername({$id}, {$username}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setUsername({$id}, {$username}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Change Admin
	 * Description: Change whether the user is an admin or not.
	 * Usage:	$mysql->setAdmin(int, boolean);
	 * Usage:	$mysql->setAdmin($id, true);
	 * Usage:	$mysql->setAdmin($id, false);
	**/
	public function setAdmin($id, $value){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		is_admin='$value' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setAdmin({$id}, {$value}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setAdmin({$id}, {$value}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Last Login
	 * Description:	Set the last_login timestamp for user.
	 * Usage:	$mysql->setTimestamp($id);
	**/
	public function setLastLogin($id){
		// Generate the timestamp.  I love timestamps :)
		$timestamp = time();

		// Assign the query
		$query = mysql_query(
		"UPDATE `{$this->prefix}users` SET
		last_login='$timestamp' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setLastLogin({$id}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setLastLogin({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Last IP
	 * Description: Set the last ip address of logged in user.
	 * Usage:	$mysql->setLastIP($id);
	 **/
	 public function setLastIP($id){
	 	// Obtain the IP address of user
		$ip = $_SERVER[REMOTE_ADDR];

		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		last_ip='$ip' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setLastIP({$id}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setLastIP({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Cookie Auth
	 * Description:	Set the user's cookie auth in the database.
	 * Usage:	$mysql->setCookieAuth($id, $auth);
	 **/
	 public function setCookieAuth($id, $auth){
	 	// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		cookie_auth='$auth' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setCookieAuth({$id}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setCookieAuth({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Cookie Expire Time
	 * Description:	Set the user's cookie expire time in the database.
	 * Usage:	$mysql->setCookieExpireTime($id, $expire);
	 **/
	 public function setCookieExpireTime($id, $expire){
	 	// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		cookie_expire='$expire' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setCookieExpireTime({$id}) completed");
			return $query;
		} else {
			$this->debug->write(EE, "db::setCookieExpireTime({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Email Count
	 * Description:	Set the user's email count in the database.
	 * Usage:	$mysql->setEmailCount($id);
	 **/
	public function setEmailCount($id){
		// Assign the query
		$query = mysql_query(
		"SELECT `email_count` FROM `{$this->prefix}users`
		WHERE id='$id'");

		// Assign the result
		$result = @mysql_result($query, 0) + 1;

		if ($result){
			// Assign the query.
			$update = mysql_query(
			"UPDATE `{$this->prefix}users` SET
			email_count='$result' WHERE id='$id'");

			if ($update){
				$this->debug->write(II, "db::setEmailCount({$id}) was set to {$result}");
				return true;
			} else {
				$this->debug->write(EE, "db:setEmailCount({$id}) failed on UPDATE. See ERROR below:");
				$this->debug->write(EE, mysql_error());
				return false;
			}
		} else {
			$this->debug->write(EE, "db:setEmailCount({$id}) failed on RESULT. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Mail Type
	 * Description:	Set the user's prefered mail type in the database.
	 * Usage:	$mysql->setMailType($id, $type);
	 **/
	public function setMailType($id, $type){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		mail_type='$type' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setMailType({$id}, {$type}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::setMailType({$id}, {$type}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Vacation
	 * Description:	Set the vacation option for the user, in the database.
	 * Usage:	$mysql->setVacation($id, $value);
	 **/
	public function setVacation($id, $value){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		vacation='$value' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setVacation({$id}, {$value}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::setVacation({$id}, {$value}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Activation Key
	 * Description:	(primarily used for setting the activation_key to NULL)
	 * Usage:	$mysql->setActivationKey($id, $key);
	 **/
	public function setActivationKey($id, $key = NULL){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		activation_key='$key' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setActivationKey({$id}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::setActivationKey({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Email Key
	 * Description:	Set the email key (for when changing email
	 		addresses) to force the user to verify the
			email account.
	 * Usage:	$mysql->setEmailKey($id, $key);
	 **/
	public function setEmailKey($id, $key = NULL){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}users` SET
		email_key='$key' WHERE id='$id'");

		if ($query){
			#$this->debug->write(II, "db::setEmailKey({$id}, {$key})"); // You should not uncomment this...
			$this->debug->write(II, "db::setEmailKey({$id}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::setEmailKey({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Show All Users (table style)
	 * Description:	Output all users (based on limit) to a table row.
	 * Usage:	$mysql->showAllUsers($orderby, $start, $finish);
	 **/
	public function showAllUsers($orderby, $start, $finish){
		// Assign the query
		$query = mysql_query(
		"SELECT id, username, email FROM `{$this->prefix}users`
		ORDER BY `id` $orderby LIMIT $start,$finish");

		while ($row = mysql_fetch_array($query)){
			$id = $row[id];
			$username = $row[username];
			$email = $row[email];

			echo "<tr>\n";
			echo "<td><input type='checkbox' name='user_id[]' value='{$id}' /></td>";
			echo "<td>{$id}</td>";
			echo "<td><a href='edituser?id={$username}'>{$username}</a></td>";
			echo "<td><a href='mailto:{$email}'>{$email}</a></td>\n";
			echo "</tr>\n";
		}
		$this->debug->write(FF, "db::showAllUsers() finished");
	}

	/**
	 * Name:	Add Setting
	 * Description:	Quickly add a setting to the database.
	 * Usage:	$mysql->addSetting(id, value);
	 **/
	public function addSetting($id, $value){
		// Assign the query
		$query = @mysql_query(
		"INSERT INTO `{$this->prefix}settings`
		(id, value) VALUES ('$id', '$value')");

		if ($query){
			$this->debug->write(II, "db::addSetting({$id}, {$value}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::addSetting({$id}, {$value}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Set Setting
	 * Description:	Change the setting value.
	 * Usage:	$mysql->setSetting(id, value);
	 **/
	public function setSetting($id, $value){
		// Assign the query
		$query = @mysql_query(
		"UPDATE `{$this->prefix}settings` SET
		value='$value' WHERE id='$id'");

		if ($query){
			$this->debug->write(II, "db::setSetting({$id}, {$value}) completed");
			return true;
		} else {
			$this->debug->write(EE, "db::setSetting({$id}, {$value}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}

	/**
	 * Name:	Setting
	 * Description:	Retreive the value of a setting from the `settings` table.
	 * Usage:	$mysql->setting(id);
	 **/
	public function setting($id){
		// Assign the query
		$query = mysql_query(
		"SELECT `value` FROM `{$this->prefix}settings`
		WHERE id='$id'");

		// Assign the result
		$result = @mysql_result($query, 0);

		if ($result){
			$this->debug->write(II, "db::setting({$id}) returned {$result}");
			return $result;
		} else {
			$this->debug->write(EE, "db::setting({$id}) failed. See ERROR below:");
			$this->debug->write(EE, mysql_error());
			return false;
		}
	}
	
	/**
	 * Name:	Sanitize Input
	 * Description:	Sanitize the inuput fields.
	 * Usage:	$mysql->sanitize($input);
	 **/
	public function sanitize($input){
		if (is_array($input)){
			foreach($input as $var => $val){
				$output[$var] = mysql_real_escape_string($val);
				return $output;
			}
		} else {
			$output = mysql_real_escape_string($input);
			$this->debug->write(FF, "db::sanitize() finished");
			return $output;
		}
	}
}

// Go ahead and assign the $mysql variable, while we're here.
$mysql = new db();

?>
