<?php 	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>

<?php 	// IF user is logged in, show INDEX page
	if ($_COOKIE[COOKIE_NAME."_user"]){ 
		// IF the cookie_auth matches the cookie
		if (COOKIE_AUTH == $_COOKIE[COOKIE_NAME."_auth"]){
			if (IS_ADMIN == "true"){
?>

<?php	// Display a custom page
	$template->header();
?>

<?php // Default page to load, if no subpages are requested.
if (!isset($_GET[page])):
?>

<?php	admin_pagenav("Home"); ?>
<?php	$template->sidebar(); ?>
	<div id="sidebar_page">
		<div id="boxpadding">
			The Feedmailer Administrator Control panel allows you
			to customize your setup to the max. Add, Edit and Delete
			your users from the <em>Users</em> tab; Add, Edit and Delete
			your feeds from the <em>Feeds</em> tab... Customize settings
			for productivity, check stats, setup options, view debug, 
			use GPG Auth for Authentication methods, etc...
			<br /><br />
			The new Feedmailer Identica theme takes a simple approach at
			customizing your new setup. Hopefully things shouldn't be too
			hard to find now. Most of all, have fun, and stay informed! :-)
			<br /><br />
			<em>&nbsp;&nbsp;&nbsp;&nbsp;-- Mycroft Development Team</em>
		</div>
	</div>

<?php endif; ?>

<?php // Begin the users page.
if ($_GET[page] == "users"):
?>

<?php	admin_pagenav("Users"); ?>
<?php	$template->sidebar(); ?>
	<div id="sidebar_page">
		<div id="boxpadding">
			<p class="smalltext">
				The Feedmailer User Dashboard allows you to quickly manage your users
				and their settings. From the menu above, select the Add, Edit or Delete
				buttons to begin that task. You can also quickly select a user for editing
				by clicking their Username on the Delete page.
			</p>
		</div>
	</div>
<?php endif; ?>

<?php // Begin the feds page.
if ($_GET[page] == "feeds"):
?>

<?php	admin_pagenav("Feeds"); ?>
<?php	$template->sidebar(); ?>
	<div id="sidebar_page">
		<div id="boxpadding">
			<p class="smalltext">
				From the menu above, you can view all of the feeds that your registered
				users have subscribed to, along with adding, editing and deleting these
				feeds for their account.
			</p>
		</div>
	</div>
<?php endif; ?>

<?php 	// Display the footer
	$template->footer(); 
	
?>

<?php		// User is not an admin.
		} else {
			$template->header();
			$template->error("Access Denied. Authorized Personel Only");
			$template->footer();
		}
	// IF cookie_auth does not match cookie
	} else {
		$template->header();
		$template->error(
		"Oops! It looks like someone is trying 
		to do something nasty! Please logout and 
		log back in to see if it helps.");
		$template->footer();
	}
// If user is not logged in, show login form.
} else { 
	$template->header();
	$template->login();
	$template->footer(); 
}
?>
