<?php
function pagenav($resource){
	$page = 'id="childnav"';
	$current = 'id="childnav_current"';

	if ($_COOKIE[COOKIE_NAME."_user"]):
?>

<div id="sidebar_page_nav">
	<ul id="pagenav">
		<li <?php if($resource == "Home"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>">Home</a></li>
		<li <?php if($resource == "Add"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/add">Add</a></li>
		<li <?php if($resource == "Edit"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/edit">Edit</a></li>
		<li <?php if($resource == "Delete"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/del">Delete</a></li>
		<li <?php if($resource == "Backup"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/backup">Backup</a></li>
		<li <?php if($resource == "Settings"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/settings">Settings</a>
		<?php if($resource == "Settings"): ?>
			<ul id="subchildnav">
				<li><a href="<?php echo SITE_URL;?>user/settings/mailtype">Mail Type</a></li>
				<li><a href="<?php echo SITE_URL;?>user/settings/vacation">Vacation</a></li>
				<?php if(GPG_ENABLE == "true"): ?>
				<li><a href="<?php echo SITE_URL;?>user/settings/gpgauth">GPG Auth</a></li>
				<?php endif; ?>
			</ul>
		</li>
		<?php endif; ?>
	</ul>
</div>
<?php	else: ?>
<div id="sidebar_page_nav">
	<ul id="pagenav">
		<li <?php if($resource == "Login"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/login">Login</a></li>
	<?php if(REGISTER_ENABLE == "true"): ?>
		<li <?php if($resource == "Register"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/register">Register</a></li>
	<?php endif; ?>
		<li <?php if($resource == "OpenID"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/openid">OpenID</a></li>
	<?php if(GPG_ENABLE == "true"): ?>
		<li <?php if($resource == "GPG Auth"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>user/gpglogin">GPG Auth</a></li>
	<?php endif; ?>
	</ul>
</div>
<?php	endif; ?>

<?php } ?>

<?php
function admin_pagenav($resource){
	$page = 'id="childnav"';
	$current = 'id="childnav_current"';
?>

<div id="sidebar_page_nav">
	<ul id="pagenav">
		<li <?php if($resource == "Home"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>dashboard/">Home</a></li>
		<li <?php if($resource == "Users"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>dashboard/?page=users">Users</a>
		<?php if ($resource == "Users"):?>
	<ul style="display:inline;position:absolute;left:10px;top:160px;font-weight:bold;font-size:0.9em;text-decoration:none;">
				<li><a href="<?php echo SITE_URL;?>dashboard/adduser">Add User</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/edituser">Edit User</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/delusers">Delete Users</a></li>
			</ul>
		</li>
		<?php endif; ?>
		<li <?php if($resource == "Feeds"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>dashboard/?page=feeds">Feeds</a>
		<?php if ($resource == "Feeds"): ?>
	<ul style="display:inline;position:absolute;left:10px;top:160px;font-weight:bold;font-size:0.9em;text-decoration:none;">
				<li><a href="<?php echo SITE_URL;?>dashboard/addfeed">Add Feed</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/editfeed">Edit Feed</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/delfeeds">Delete Feeds</a></li>
			</ul>
		</li>
		<?php endif; ?>
		<li <?php if($resource == "Settings"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>dashboard/settings">Settings</a>
		<?php if($resource == "Settings"):?>
	<ul style="display:inline;position:absolute;left:10px;top:160px;font-weight:bold;font-size:0.9em;text-decoration:none;">
				<li><a href="<?php echo SITE_URL;?>dashboard/settings/popfeeds">Popular Feeds</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/settings/gpgauth">GPG Auth</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/captcha">Captcha</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/settings/contact">Contact</a></li>
				<li><a href="<?php echo SITE_URL;?>dashboard/settings/debug">Debug</a></li>
			</ul>
		</li>
			<?php endif;?>	
		<li <?php if($resource == "MassEmail"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>dashboard/massemail">Mass Email</a></li>
	<?php if(DEBUG_ENABLE == "true"): ?>
		<li <?php if($resource == "Debug"){ echo $current; } else { echo $page; }?>><a href="<?php echo SITE_URL;?>dashboard/debug">Debug</a></li>
	<?php endif;?>
	</ul>
</div>

<?php } ?>

