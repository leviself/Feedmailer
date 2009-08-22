<?php
/**
 * Name:	subscribe.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Wed Apr  8 13:35:06 EDT 2009
 **/

require_once "inc/main.php";

$url = $_GET[url];

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if ($url){
			$template->header();
			$template->addfeed();
			$template->footer();
		} else {
			// If no variables have been specified
			// act as if the page doesn't exist.
			header("Location: index.php");
		}
	} else {
		$template->header();
		$template->error($error->auth());
		$template->footer();
	}
} else {
	if ($url){
		// User is not logged in, or not registered.
		// Give them the opertunity to register/login.
		$template->header();
		$template->page(
		'<h2>Welcome to Feedmailer!</h2>
		Feedmailer allows you to subscribe to multiple feed notifications
		(RSS/ATOM) and have them delivered right to your email inbox! No need
		for bulky, hard to navigate Feed Readers; Just subscribe to a feed
		and get the latest updates right in your email.<br /><br />
		Feedmailer allows you to set the mail content type (plaintext, html or
		both!) to give you the best service. Also, if you are away on vacation,
		Feedmailer gives you the option to opt out of any emails, so when you
		return from your vacation your email won\'t be cluttered with updates!
		<br /><br />
		In order for Feedmailer to provide you the best preformace with email
		updates to your favorite feeds, you need to register an account so we
		can know where to send the updates, and so you can adjust your settings.
		<br /><br />
		If you haven\'t registered yet, please stop on by the 
		<a href="register.php">registration</a> page. Or, if you are already
		registered, continue to the <a href="index.php">login</a> page. The
		current page that you are on will auto-fill out the forms for subscribing
		to a feed. Please come 
		<a href="subscribe.php?url='.$url.'">back to this page</a>
		when you are finished!<br /><br /><br />
		Sincerely,<br />
		&nbsp;&nbsp;&nbsp;&nbsp;<em>Feedmailer</em>');
		$template->footer();
	} else {
		// If the url and type are not specified, act as if this page never existed.
		header("Location: index.php");
	}
}
?>
