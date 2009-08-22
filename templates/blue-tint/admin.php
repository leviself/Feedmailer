<?php 	// Load the templates class
	$template = new templates();
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


	<p>
		<table class="headertext">
			<td>Stats</td>
			<td>Admin Dashboard</td>
		</table>
	</p>
<?php
	$template->sidebar();
	$template->sidebar_page(
	'<div id="dashboard">
		<table cellpadding="10" width="100%">
			<tr>
				<td><a class="links" href="admin_adduser.php">Add User</a></td>
				<td><a class="links" href="admin_addfeed.php">Add Feed</a></td>
			</tr>
			<tr>
				<td><a class="links" href="admin_delusers.php">Edit & Delete Users</a></td>
				<td><a class="links" href="admin_delfeeds.php">Edit & Delete Feeds</a></td>
			</tr>
			<tr>
				<td><a class="links" href="admin_massemail.php">Mass Email</a></td>
				<td><a class="links" href="admin_settings.php">System Settings</a></td>
			</tr>
			<tr>
				<td><a class="links" href="admin_debug_settings.php">Debug Mode</a></td>
				<td><a class="links" href="admin_popfeeds_settings.php">Popular Feeds Settings</a></td>
			</tr>
			<tr>
				<td><a class="links" href="admin_captcha.php">Captcha Settings</a></td>
				<td><a class="links" href="admin_contact_settings.php">Contact Settings</a></td>
			</tr>

		</table>
	</div>
	<br /><br />
	<br /><br />
	<br /><br />');
?>

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
