<?php 	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("CONTACT_ENABLE", $mysql->setting(contact_enable));
	// If the user is not logged in.
	if (!$_COOKIE[COOKIE_NAME."_user"]):
		

?>
<div id="navigation">
	<ul id="topnav">
		<li id="topchildnav"><a id="navigation" href="<?php echo SITE_URL;?>user/login">Login</a></li>
	<?php if(REGISTER_ENABLE == "true"): ?>
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>user/register">Register</a></li>
	<?php endif; ?>
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>user/resetpassword">Reset Password</a></li>
	</ul>
</div>

<?php 	elseif(IS_ADMIN == 'true'): ?>

<div id="navigation">
	<ul id="topnav">
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>dashboard/">Admin Dashboard</a></li>
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>">User Dashboard</a></li>
		<?php if(CONTACT_ENABLE == "true"): ?>
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>user/contact">Contact</a></li>
		<?php endif; ?>
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>user/logout">Logout</a></li>
	</ul>
</div>

<?php 	else: ?>

<div id="navigation">
	<ul id="topnav">
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>">Dashboard</a></li>
		<?php if(CONTACT_ENABLE == "true"): ?>
		<li id="topchildnav"><a href="'.SITE_URL.'user/contact">Contact</a></li>
		<?php endif; ?>
		<li id="topchildnav"><a href="<?php echo SITE_URL;?>user/logout">Logout</a></li>
	</ul>
</div>

<?php 	endif; ?>
