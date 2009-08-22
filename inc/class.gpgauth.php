<?php
/**
 * Name:	inc/class.gpgauth.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri Jun 12 11:22:10 EDT 2009
 **/

/**
 * Name:	GPG Auth
 * Description:	Preforms authorization functions that interact
 * 		with the gpg table, along with being used as
 * 		an interface to the gpg class.
 * Usage:	$gpgauth = new gpgauth();
 * Usage:	$gpgauth->function();
 **/
class gpgauth{

	/**
	 * Public Variables
	 **/

	/**
	 * Private Varibles
	 **/
	private $debug, $mysql, $gpg;

	/**
	 * Constructor; Load a few settings.
	 **/
	public function __construct(){
		// Load the debugger.
		require_once dirname(__FILE__)."/class.debug.php";
		$this->debug = new debugger();

		// Load the MySQL connection
		$mysql = new db();
		$mysql->connect();

		$gpgexec = $mysql->setting(gpg_execpath);
		$gpghome = $mysql->setting(gpg_homedir);

		// Load the GPG class
		require_once dirname(__FILE__)."/class.gpg.php";
		$this->gpg = new gnuPG($gpgexec, $gpghome);
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
	 * Name:	Build Table
	 * Description:	Build the gpg table, and columns.
	 * Usage:	$gpgauth->build();
	 **/
	private function build(){
		$this->db();

		// Assign the query
		$query = @mysql_query(
			"CREATE TABLE `{$this->mysql->prefix}gpg`
			(uid INT, keyid VARCHAR(8), userid TEXT, otk TEXT)");

		if ($query){
			$this->debug->write(FF, "gpgauth::build() returned true");
			return true;
		} else {
			$this->debug->write(EE, "gpgauth::build() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Destroy Table
	 * Description:	Destroy the gpg table, and columns.
	 * Usage:	$gpgauth->destroy();
	 **/
	private function destroy(){
		$this->db();

		// Assign the query
		$query = @mysql_query("DROP TABLE `{$this->mysql->prefix}gpg`");

		if ($query){
			$this->debug->write(FF, "gpgauth::destroy() returned true");
			return true;
		} else {
			$this->debug->write(EE, "gpgauth::destroy() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Enable GPGAuth
	 * Description:	Build the table, and enable the settings.
	 * Usage:	$gpgauth->enable($gpgexec, $gpghomedir);
	 **/
	public function enable($gpgexec, $gpghomedir){
		$this->db();

		// Check if safe_mode is enabled. (class.gpg.php can not
		// function if safemode is enabled.)
		if (ini_get("safe_mode")){
			// Use an elseif statement to throw a safemode error.
			return 'safemode';
		} else {

			// Check and see if the table exists (we don't want
			// to build it, if it does.)
			$tbexists = @mysql_query(
				"SELECT * FROM `{$this->mysql->prefix}gpg` LIMIT 0");

			if ($tbexists){
				// Just set the settings.
				$this->mysql->setSetting("gpg_enable", "true");
				$this->mysql->setSetting("gpg_homedir", $gpghomedir);
				$this->mysql->setSetting("gpg_execpath", $gpgexec);

				$this->debug->write(FF, "gpgauth::enable() returned true");
				return true;
			} else {
				if ($this->build()){
					$this->mysql->setSetting("gpg_enable", "true");
					$this->mysql->setSetting("gpg_homedir", $gpghomedir);
					$this->mysql->setSetting("gpg_execpath", $gpgexec);
					return true;
				} else {
					$this->debug->write(WW, "gpgauth::enable() returned false");
					return false;
				}
			}
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Disable GPGAuth
	 * Description:	Destroy the table (if set) and disable the setting.
	 * Usage:	$gpgauth->disable($destroytable);
	 **/
	public function disable($destroytable){
		$this->db();

		// Destroy table is boolean. Sometimes an admin
		// may only want to disable the settings, but not
		// lose all of his data in the database.

		if ($destroytable == "true"){
			if ($this->destroy()){
				// Remove the pubring file
				unlink($this->mysql->setting(gpg_homedir)."/pubring.gpg");

				$this->mysql->setSetting("gpg_enable", "false");
				$this->debug->write(FF, "gpgauth::disable({$destroytable}) returned true");
				return true;
			} else {
				$this->debug->write(WW, "gpgauth::disable({$destroytable}) returned false");
				return true;
			}
		}
		$this->mysql->setSetting("gpg_enable", "false");
		// The rest of the settings can stay in tack.
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Shorten KeyID
	 * Description:	Shorten the KeyID to 8 characters.
	 * Usage:	$this->shortKeyID($key);
	 **/
	private function shortenKeyID($key){
		$key = substr($key, -8);
		return $key;
	}

	/**
	 * Name:	Import Key
	 * Description:	Use the Import function from class.gpg.php
	 * Usage:	$gpgauth->imort($uid, $pubkeyblock);
	 **/
	public function import($uid, $pubkeyblock){
		$this->db();

		$import = $this->gpg->Import($pubkeyblock);
		if ($import){
			$keyid = $this->shortenKeyID($import[0][KeyID]);
			$userid = $this->mysql->sanitize($import[0][UserID]);

			if ($this->addKey($uid, $keyid, $userid)){
				$this->debug->write(II, "gpgauth::import() returned true");
				return true;
			} else {
				$this->debug->write(EE, "gpgauth::import() returned false");
				return false;
			}
		} else {
			$this->debug->write(EE, "gpgauth::import() returned {$this->gpg->error}");
			// Return the error.
			return $this->gpg->error;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	AddKey
	 * Description:	Add a key to the database.
	 * Usage:	$this->addKey($uid, $keyid);
	 **/
	private function addKey($uid, $keyid, $userid){
		$this->db();

		// Assign the query
		$query = @mysql_query(
			"INSERT INTO `{$this->mysql->prefix}gpg`
			(uid, keyid, userid) VALUES ('$uid', '$keyid', '$userid')");

		if ($query){
			$this->debug->write(II, "gpgauth::addKey() returned true");
			return true;
		} else {
			$this->debug->write(EE, "gpgauth::addKey() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	DelKey
	 * Description:	Delete a key from the keyring and the database.
	 * Usage:	$gpgauth->delKey($uid, $keyid);
	 **/
	public function delKey($uid, $keyid){
		$this->db();

		if ($this->gpg->DeleteKey($keyid)){
			// Assign the query
			$query = @mysql_query(
				"DELETE FROM `{$this->mysql->prefix}gpg`
				WHERE uid='$uid'");

			if ($query){
				$this->debug->write(II, "gpgauth::delKey() returned true");
				return true;
			} else {
				$this->debug->write(EE, "gpgauth::delKey() returned false");
				return false;
			}
		} else {
			return $this->gpg->error;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	KeyID Exists
	 * Description:	Preform a check to see if the keyid exists
	 * 		in the database.
	 * Usage:	$gpgauth->keyIDExists($keyid);
	 **/
	public function keyIDExists($keyid){
		$this->db();

		// Assign the query
		$select = @mysql_result(mysql_query(
			"SELECT `uid` FROM `{$this->mysql->prefix}gpg`
			WHERE keyid='$keyid'"),0);

		if ($select){
			$this->debug->write(FF, "gpgauth::keyIDExists() returned true");
			return true;
		} else {
			$this->debug->write(WW, "gpgauth::keyIDExists() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Key Exists for User
	 * Description:	Key exists for userid
	 * Usage:	$gpgauth->keyExistsForUID($uid);
	 **/
	public function keyExistsForUID($uid){
		$this->db();

		$select = @mysql_result(mysql_query(
			"SELECT * FROM `{$this->mysql->prefix}gpg`
			WHERE uid='$uid'"),0);

		if ($select){
			$this->debug->write(II, "gpgauth::keyExistsForUID() returned true");
			return true;
		} else {
			$this->debug->write(WW, "gpgauth::keyExistsForUID() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	UserID to KeyID
	 * Description:	Select the KeyID that matches the UserID
	 * Usage:	$gpgauth->uidToKeyID($uid);
	 **/
	public function uidToKeyID($uid){
		$this->db();

		$result = @mysql_result(mysql_query(
			"SELECT `keyid` FROM `{$this->mysql->prefix}gpg`
			WHERE uid='$uid'"),0);

		if ($result){
			$this->debug->write(II, "gpgauth::uidToKeyID({$uid}) returned {$result}");
			return $result;
		} else {
			$this->debug->write(EE, "gpgauth::uidToKeyID({$uid}) returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	UID (User's ID Number) to UserID (in public key)
	 * Usage:	$gpgauth->uidToUserID($uid);
	 **/
	public function uidToUserID($uid){
		$this->db();

		$result = @mysql_result(mysql_query(
			"SELECT `userid` FROM `{$this->mysql->prefix}gpg`
			WHERE uid='$uid'"),0);

		if ($result){
			$this->debug->write(II, "gpgauth::uidToUserID({$uid}) returned {$result}");
			return $result;
		} else {
			$this->debug->write(EE, "gpgauth::uidToUserID({$uid}) returned false");
			return false;
		}
		$this->mysql->disconnect();
	}


	/**
	 * Name:	OneTimeKey
	 * Description:	Generate the OneTimeKey, add it to the database,
	 * 		and encrypt the message, send it back as output.
	 * Usage:	$gpgauth->oneTimeKey($keyid);
	 **/
	public function oneTimeKey($keyid){
		$this->db();

		// Select the uid
		$uid = @mysql_result(mysql_query(
			"SELECT `uid` FROM `{$this->mysql->prefix}gpg`
			WHERE keyid='$keyid'"),0);

		// Generate the one time key.
		$otk = md5($uid.$keyid.time());

		if ($this->setOneTimeKey($uid, $otk)){
			// The encrypted message.
			$msg  = "Enter the following URL into your browser.\n";
			$msg .= "It contains your One Time Key (OTK) that will\n";
			$msg .= "allow you to login:\n\n";
			$msg .= $this->mysql->setting(site_url)."gpglogin.php?otk={$otk}";

			$crypt = $this->gpg->encrypt($keyid, $msg);
			return $crypt;
		} else {
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Set One Time Key
	 * Description:	Set the one time key in the database, for the user.
	 * Usage:	$this->setOneTimeKey($uid, $otk);
	 **/
	private function setOneTimeKey($uid, $otk){
		$this->db();

		// Assign the query
		$query = mysql_query(
			"UPDATE `{$this->mysql->prefix}gpg` SET
			otk='$otk' WHERE uid='$uid'");

		if ($query){
			$this->debug->write(II, "gpgauth::setOneTimeKey() returned true");
			return true;
		} else {
			$this->debug->write(EE, "gpgauth::setOneTimeKey() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Unset One Time Key
	 * Description:	Set the One time key to null for user.
	 * Usage:	$this->unsetOneTimeKey($uid);
	 **/
	private function unsetOneTimeKey($uid){
		$this->db();

		// Assign the query
		$query = @mysql_query(
			"UPDATE `{$this->mysql->prefix}gpg` SET
			otk=NULL WHERE uid='$uid'");

		if ($query){
			$this->debug->write(II, "gpgauth::unsetOneTimeKey() returned true");
			return true;
		} else {
			$this->debug->write(EE, "gpgauth::unsetOneTimeKey() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}

	/**
	 * Name:	Check (for Login)
	 * Description:	Check the one time key for authentication login.
	 * Usage:	$gpgauth->check($uid, $otk);
	 **/
	public function check($otk){
		$this->db();

		// Select the uid
		$uid = @mysql_result(mysql_query(
			"SELECT `uid` FROM `{$this->mysql->prefix}gpg`
			WHERE otk='$otk'"),0);

		$dbotk = @mysql_result(mysql_query(
			"SELECT `otk` FROM `{$this->mysql->prefix}gpg`
			WHERE uid='$uid'"),0);

		if ($dbotk == $otk){
			$this->unsetOneTimeKey($uid);
			$this->debug->write(II, "gpgauth::check() returned true");
			$this->uid = $uid;
			return true;
		} else {
			$this->debug->write(WW, "gpgauth::check() returned false");
			return false;
		}
		$this->mysql->disconnect();
	}
}

$gpgauth = new gpgauth();

?>
