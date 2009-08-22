<?php 	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("CONTACT_ENABLE", $mysql->setting(contact_enable));
	// If the user is not logged in.
	if (!$_COOKIE[COOKIE_NAME."_user"]){
		

?>
<div id="navigation">
			<a href="index.php">Login</a> | <a href="register.php">Register</a> | <a href="resetpassword.php">Reset Password</a>
		</div>

<?php 	// If the logged in user IS_ADMIN = 'true.
	} elseif(IS_ADMIN == 'true'){
?>
<div id="navigation">
			<a href="admin.php">Admin Dashboard</a> | <a href="index.php">User Dashboard</a> | <?php if(CONTACT_ENABLE == "true"){ echo '<a href="contact.php">Contact</a> |';}?> <a href="logout.php">Logout</a> 
		</div>
<?php	// If User is logged in, and is not an admin.
	} else {
?>
<div id="navigation">
			<a href="index.php">Dashboard</a> | <?php if(CONTACT_ENABLE == "true"){ echo '<a href="contact.php">Contact</a> |';}?> <a href="logout.php">Logout</a>
		</div>
<?php } ?>
