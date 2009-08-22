<?php
/**
 * Name:	class.cookies.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 14:44:15 EDT 2009
 **/

/**
 * Name:	Cookies Class
 * Description:	Initiate the cookies class
 * Usage:	$cookie = new cookies();
 * Usage:	$cookie->function();
 **/
class cookies{


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
		$this->debug->write(FF, "cookies::db() initiated a connection");
		return $this->mysql->connect();
	}

	/**
	 * Name:	Cookie Name
	 * Description:	Return the cookie name.
	 * Usage:	$this->cookieName();
	 **/
	 private function cookieName(){
	 	$cookie_name = $this->mysql->setting(cookie_name);
		return $cookie_name;
	}

	/**
	 * Name:	Set Cookie
	 * Description:	Set a cookie for the user's browser
	 * Usage:	$cookie->setCookie($id, $auth);
	 **/
	public function setCookie($id, $auth){
		// Load the MySQL connection
		$this->db();

		// Retrive user settings for cookie_expire
		$user = $this->mysql->selectUser($id);
		$expire = $user[cookie_expire];

		// Cookie name
		$name = $this->cookieName();

		// Set the cookie
		if ($expire == null){
			setcookie($name."_user", $user[username], 0, "/");
			setcookie($name."_auth", $auth, 0, "/");
		} else {
			setcookie($name."_user", $user[username], time()+$expire, "/");
			setcookie($name."_auth", $auth, time()+$expire, "/");
		}

		$this->debug->write(II, "cookies::setCookie({$id}) returned true");
		return true;
	}

	/**
	 * Name:	Delete Cookies
	 * Description:	Delete the set cookies from the user's browser.
	 * Usage:	$cookie->deleteCookies($id);
	 **/
	 public function deleteCookies($id){
	 	// Load MySQL connection
		$this->db();

		// Retrive user settings for cookie_expire
		$user = $this->mysql->selectUser($id);
		$expire = $user[cookie_expire];

		// Cookie name.
		$name = $this->cookieName();

		// Unset the cookie
		// A cookie with a NULL expiriation time must be handled differently.
		// We must "unset" it, by going back in time.
		if ($expire == null){
			setcookie($name."_user", '', time()-1234567890, "/");
			setcookie($name."_auth", '', time()-1234567890, "/");
		} else {
			setcookie($name."_user", '', time()-$expire, "/");
			setcookie($name."_auth", '', time()-$expire, "/");
		}

		$this->debug->write(II, "cookies::deleteCookies({$id}) returned true");
		return true;
	}
}

// While we're here, lets go ahead and set the $cookie variable
$cookie = new cookies();

?>
