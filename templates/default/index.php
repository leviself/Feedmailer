<?php 	// Load the templates class
	$template = new templates();
?>

<?php 	// IF user is logged in, show INDEX page
	if ($_COOKIE[COOKIE_NAME."_user"]): 
		// IF the cookie_auth matches the cookie
		if (COOKIE_AUTH == $_COOKIE[COOKIE_NAME."_auth"]):
		
			$template->header();
?>

	<p>
		<table class="headertext">
			<td>Stats</td>
			<td>Dashboard</td>
		</table>
	</p>
<?php
	$template->sidebar();
?>
<div id="sidebar_page">
	<div id="dashboard">
		<table cellpadding="10" width="100%">
			<tr>
				<td><a class="links" href="addfeed.php">Add Feed</a></td>
				<td><a class="links" href="settings.php">User Settings</a></td>
			</tr>
			<tr>
				<td><a class="links" href="delfeeds.php">Delete Feeds</a></td>
				<td><a class="links" href="mailtype.php">Mail Content Type</a></td>
			</tr>
			<tr>
				<td><a class="links" href="editfeed.php">Edit Feed</a></td>
				<td><a class="links" href="vacation.php">Set Vacation</a></td>
			</tr>
			<tr>
				<td><a class="links" href="backup.php">Import / Export</a></td>
			</tr>
		</table>
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
		$template->header();
		$template->login();
		$template->footer(); 
	endif;
?>
