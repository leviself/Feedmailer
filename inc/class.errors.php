<?php
/**
 * Name: 	class.errors.php
 * Group: 	Feedmailer
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Tue Apr  7 17:21:17 EDT 2009
 **/

/**
 * Name: 	errors
 * Description: Functions for error messages.
 * Usage:	$error = new errors();
 * Usage:	$error->function();
 **/
class errors {
	/**
	 * Private Variables
	 **/
	private $debug;

	/**
	 * The contsructor function executes
	 * at runtime. Load the debugger for this class.
	 **/
	public function __construct(){
		require_once dirname(__FILE__)."/class.debug.php";
		$this->debug = new debugger();
	}

	/**
	 * Name:	Server Boo-boo
	 * Usage:	$error->boo_boo($type);
	 **/
	public function boo_boo($type){
		$message = "Uh oh... The server made a boo-boo. {$type}
			died with an error. Please contact an administrator.";
		$this->debug->write(WW, "errors::boo_boo({$type}) ... check the debug log");
		return $message;
	}

	/**
	 * Name:	Exploit Warning
	 * Usage:	$erorr->exploit();
	 **/
	public function exploit(){
		$message = "Oww, how'd you get here? Trust me, I know...
			Please don't try that again.";
		$this->debug->write(WW, "errors:exploit()");
		return $message;
	}

	/**
	 * Name:	Cookie Auth
	 * Usage:	$error->auth();
	 **/
	public function auth(){
		$message = "Oops! It looks like someone is trying to do
			something nasty! Please logout and log back in to
			see if it helps.";
		$this->debug->write(WW, "errors::auth()");
		return $message;
	}

	/**
	 * Name:	Snoopers will be prosecuted...
	 * Usage:	$error->snooper();
	 **/
	public function snooper(){
		$message = "Snoopers will be prosecuted to the full extent
			of the law.";
		$this->debug->write(WW, "errors::snooper()");
		return $message;
	}

	/**
	 * Name:	Access Denied
	 * Usage:	$error->accessdenied();
	 **/
	public function accessdenied(){
		$message = "Access Denied. Authorized Personel Only";
		$this->debug->write(WW, "errors::accessdenied()");
		return $message;
	}

}

// While we're here, lets assign the $error variable
$error = new errors();
?>
