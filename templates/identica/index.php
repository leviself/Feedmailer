<?php 	// Load the templates class
	$template = new templates();
	$feed = new feeds();
	$mysql = new db();
	include dirname(__FILE__)."/functions.php";

?>

<?php 	// IF user is logged in, show INDEX page
	if ($_COOKIE[COOKIE_NAME."_user"]): 
		// IF the cookie_auth matches the cookie
		if (COOKIE_AUTH == $_COOKIE[COOKIE_NAME."_auth"]):
		
			$template->header();
?>

<?php pagenav("Home"); ?>
<?php $template->sidebar(); ?>
<div id="sidebar_page">
	<div id="boxpadding">
		<p class="smalltext">
			Thanks for using Feedmailer!<br />
			To get started, you can access any of the most important pages right from the
			top button menu for stuff like <a href="user/add">adding</a>, <a href="user/edit">editing</a>,
			and <a href="user/del">deleting</a> feeds. Additional settings for your account
			can be found under the button navigation on the <a href="user/settings">settings</a> page.
			<br />
			<br />
			Feedmailer is still under development, so all input is welcomed.
			<br />
			If you experience any problems, please send us
			note from the <a href="user/contact">Contact</a> page.
			<br />
			<br />
			<?php if($mysql->setting(pop_enable) == "true"):?>
			While you're here, check out the popular feeds!
			<?php $feed->showPopularFeeds(); ?>
			<?php endif; ?>



			
		</p>
	</div>
	<br /><br />
	<br /><br />
	<br /><br />
	<br /><br />
</div>

<?php 	// Display the footer
	$template->footer(); 
	
?>

<?php	// IF cookie_auth does not match cookie
		else:
			$template->header();
			$template->error(
			"Oops! It looks like someone is trying 
			to do something nasty! Please logout and 
			log back in to see if it helps.");
			$template->footer();
		endif;
		
	// If user is not logged in, show login form.
	else: 
		$template->header(); ?>
<?php pagenav(NULL); ?>
<?php	$template->sidebar(); ?>
<div id="sidebar_page">
	<div id="boxpadding">
		<p class="smalltext">
			Welcome to Feedmailer,<br />
			This is a free, opensource project that is designed to deliver you
			RSS/ATOM feed notifications right to your email inbox. Feedmailer does
			all of the work for you, so you can read your favorite feeds right from
			your mail client.<br />
			<br />
			There are a few options that allow you to pick which type of emails you
			would like to have sent to you (plaintext, HTML or both!), along with setting
			your Feedmailer account on vacation so you do not receive any updates while
			you are away and don't have access to your mail.<br />
			<br />
			In order to first use Feedmailer, you need to <a href="<?php echo SITE_URL;?>user/register">Register</a>
			an account, or use the <a href="<?php echo SITE_URL;?>user/openid">OpenID</a> login if you already have
			an OpenID account. Once registered, you'll be all setup and ready to begin subscribing
			to different feeds and have the updates delivered right to your email!<br />
			<br />
			Most of all, have fun, enjoy your updates and happy reading!<br />
			<br />
			Sincerely,<br />
			<em>Feedmailer Staff</em>
	</div>
</div>


<?php
		$template->footer(); 
	endif;
?>
