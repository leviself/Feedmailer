<?php
/**
 * Name: 	class.mail.php
 * Group: 	Feedmailer
 * Author: 	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date: 	Mon Apr  6 12:05:09 EDT 2009
 **/

/**
 * Name: 	Mail
 * Description: Preforms mail functions
 * Usage:	$mail = new mail();
 * Usage:	$mail->function();
 **/
class mail {

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
		$this->debug->write(FF, "mail::db() initiated a connection");
		return $this->mysql->connect();
	}

	/**
	 * Name:	Mail Type
	 * Description:	Load the mail type from user's settings.
	 * Usage:	$this->mailType(userid);
	 **/
	private function mailType($userid){
		// Load MySQL Connection
		$this->db();
		
		// User settings
		$user = $this->mysql->selectUser($userid);

		$this->debug->write(FF, "mail::mailType({$userid}) returned {$user[mail_type]}");
		return $user[mail_type];

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Email Headers
	 * Description:	Set the variables for the email headers.
	 * Usage:	$this->headers(mailtype);
	 **/
	private function headers($type){
		// Load teh MySQL Connection
		$this->db();

		$site_title = $this->mysql->setting(site_title);
		$this->site_title = $site_title;

		// Domain Name.
		preg_match("@^(?:http://)?([^/]+)@i", $this->mysql->setting(site_url), $matches);
		$dom = $matches[1];

		$header  = "MIME-Version: 1.0\n";

		if ($type == "multipart/alternative"){
			$header .= 'Content-type: '.$type.'; boundary="feedmailer-multipart"; charset=iso-8859-1'."\n";
		} else {
			$header .= "Content-type: {$type}; charset=iso-8859-1\n";
		}

		$header .= 'From: "'.$this->feedtitle.'" <feedmailer@'.$dom.'>'."\n";
		$header .= "Reply-To: noreply@{$dom}\n";
		$header .= "X-Mailer: PHP/".phpversion()."\n";
		$header .= 'X-Application: Feedmailer/2.0 "http://launchpad.net/feedmailer"'."\n";

		return $header;

		$this->mysql->disconnect();
	}

	/**
	 * Name:	Plain Text Email
	 * Description:	Format the email primarily for text/plain
	 * Usage:	$this->text(itemtitle, url, pubdate, commenturl, description, unsubscribelink);
	 **/
	private function text($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink){
		// Since this is text/plain, let's treat our users like customers
		// and go ahead and strip out any HTML tags that may be in the feed
		// description, to make the content more readable...
		$description = strip_tags($description);

		// Also, let's convert special HTML entities back to characters
		$description = htmlspecialchars_decode($description, ENT_QUOTES);

		// Those annoying little Wordpress quotes need to be changed.
		// (Add more to this list, as reported)
		$search = array("&#8217;", "&#8220;", "&#8221;", "&#8216;", "&#8212;");
		$replace = array("'", '"', '"', "`", "--");
		$description = str_replace($search, $replace, $description);

		$plain  = "Title: {$itemtitle}\n";
		$plain .= "URL: {$url}\n";
		$plain .= "Date: {$pubdate}\n";

		if ($commenturl != NULL){
			$plain .= "Comments: {$commenturl}\n";
		}

		$plain .= "\n";
		$plain .= $description."\n\n";
		$plain .= "--\n";
		$plain .= "Powered by Feedmailer...\n";
		$plain .= "Unsubscribe:: {$unsubscribelink}";

		return $plain;
	}

	/**
	 * Name:	HTML Email
	 * Description:	Format the email primarily for text/html
	 * Usage:	$this->html(itemtitle, url, pubdate, commenturl, description, unsubscribelink);
	 **/
	private function html($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink){
		$html  = "Title: {$itemtitle}<br />";
		$html .= "URL: <a href='{$url}'>{$url}</a><br />";
		$html .= "Date: {$pubdate}<br />";

		if ($commenturl != NULL){
			$html .= "Comments: <a href='{$commenturl}'>{$commenturl}</a><br />";
		}

		$html .= "<br />";
		$html .= "<blockquote>{$description}</blockquote><br />";
		$html .= "<br />";
		$html .= "--<br />";
		$html .= "Powered by Feedmailer...<br />";
		$html .= "Unsubscribe:: <a href='{$unsubscribelink}'>{$unsubscribelink}</a>";

		return $html;
	}

	/**
	 * Name:	Multipart Email
	 * Description:	Format a multipart/alternative
	 * Usage:	$this->multipart(itemtitle, url, pubdate, commenturl, description, unsubscribelink);
	 **/
	private function multipart($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink){

		$multipart  = "--feedmailer-multipart\n";
		$multipart .= "Content-Type: text/plain; charset=UTF-8; format=flowed; delsp=yes\n";
		$multipart .= "Content-Transfer-Encoding: 7bit\n\n";
		$multipart .= $this->text($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink);
		$multipart .= "\n\n";
		$multipart .= "--feedmailer-multipart\n";
		$multipart .= "Content-Type: text/html; charset=UTF-8\n";
		$multipart .= "Content-Transfer-Encoding: quoted-printable\n\n";
		$multipart .= $this->html($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink);
		$multipart .= "\n\n";
		$multipart .= "--feedmailer-multipart--";

		return $multipart;
	}

	/**
	 * Name:	Send Email
	 * Description:	Collect data, set variables, determine MIME type,
	 		and send the email. :)
	 * Usage:	$mail->sendEmail(userid, feedtitle, itemtitle, url, pubdate, commenturl, description, unsubscribelink);
	 **/
	public function sendEmail($userid, $to, $feedtitle, $itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink){
		// Determine the user's mail type.
		$type = $this->mailType($userid);
		$this->feedtitle = $feedtitle;
		$subject = "[update] {$itemtitle}";
			
		// Set the headers
		$headers = $this->headers($type);

		if ($type == "text/plain"){
			$mail = $this->text($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink);
			mail($to, $subject, $mail, $headers);
		} elseif ($type == "text/html"){
			$mail = $this->html($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink);
			mail($to, $subject, $mail, $headers);
		} elseif ($type == "multipart/alternative"){
			$mail = $this->multipart($itemtitle, $url, $pubdate, $commenturl, $description, $unsubscribelink);
			mail($to, $subject, $mail, $headers);
		}

		$this->debug->write(FF, "mail::sendEmail() completed successfully");
		return true;
	}

	/**
	 * Name:	Activation Email
	 * Description:	Send an activation email
	 * Usage:	$mail->sendActivation(to, username, password, activationkey);
	 **/
	 public function sendActivation($to, $username, $password, $activation_key){
		// Load the MySQL Connection
		$this->db();

		// For highest security, and less chance of being able
		// to exploit the activation key, this has been formed
		// from the md5sum of the username, password, email and time.
		// Beat that one, bud.

		// User's IP Address
		$ip = $_SERVER[REMOTE_ADDR];

	 	// Set text/plain headers
		$headers = $this->headers("text/plain");

		// Set the subject line
		$subject = "[Account Activation]";

		// Email content
		$mail  = "Someone with the IP Address of {$ip} has registered an\n";
		$mail .= "account with Feedmailer. The account is currently not activated.\n";
		$mail .= "To activate the account, please click the following link:\n";
		$mail .= "{$this->mysql->setting(site_url)}activate.php?email={$to}&key={$activation_key}\n\n";
		$mail .= "\n";
		$mail .= "Here are your login crendentials. Please store this email\n";
		$mail .= "in a safe place.\n\n";
		$mail .= "Username: {$username}\n";
		$mail .= "Password: {$password}\n";
		$mail .= "Activation Key: {$activation_key}\n\n";
		$mail .= "Best Regards,\n";
		$mail .= "Feedmailer Staff";

		
		// Deliver the message.
		if (mail($to, $subject, $mail, $headers)){
			$this->debug->write(FF, "mail::sendActivation() completed successfully");
			return true;			
		} else {
			$this->debug->write(FF, "mail::sendActivation() failed.");
			return false;
		}
	}

	/**
	 * Name:	Send Email Key
	 * Description:	Send the email activation key to the specified
	 		email address.
	 * Usage:	$mail->sendEmailKey($to, $key);
	 **/
	public function sendEmailKey($to, $key){
		// Load the MySQL Connection
		$this->db();

		// User's IP Address
		$ip = $_SERVER[REMOTE_ADDR];

	 	// Set text/plain headers
		$headers = $this->headers("text/plain");

		// Set the subject line
		$subject = "[Email Activation]";

		// Email content
		$mail  = "Someone with the IP Address of {$ip} has changed their email\n";
		$mail .= "address to yours. If this is not you, please do not activate\n";
		$mail .= "the email address, and you will receive no further notification\n";
		$mail .= "from Feedmailer. If on the other hand, it was you who registered\n";
		$mail .= "the account, please click the following activation link to confirm\n";
		$mail .= "this email account:\n";
		$mail .= "{$this->mysql->setting(site_url)}activate.php?email={$to}&emailkey={$key}\n\n";
		$mail .= "\n";
		$mail .= "Best Regards,\n";
		$mail .= "Feedmailer Staff";


		// Deliver the message.
		if (mail($to, $subject, $mail, $headers)){
			$this->debug->write(FF, "mail::sendEmailKey() completed successfully");
			return true;
		} else {
			$this->debug->write(FF, "mail::sendEmailKey() failed");
			return false;
		}
	}

	/**
	 * Name:	Mass Email
	 * Description:	Send out a mass email.
	 * Usage:	$mail->massEmail($to, $subject, $message);
	 **/
	public function massEmail($to, $subject, $message){
		
		// set the text/plain headers
		$headers = $this->headers("text/plain");

		if (mail($to, $subject, $message, $headers)){
			$this->debug->write(FF, "mail::massEmail() completed successfully");
			return true;
		} else {
			$this->debug->write(FF, "mail::massEmail() failed");
			return false;
		}
	}
}

// While we're here, let's assign the $mail variable
$mail = new mail();

?>
