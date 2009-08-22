<?php 	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>
<?php pagenav("Login"); ?>
<?php $template->sidebar(); ?>
<form action="<?php echo SITE_URL;?>login.php" method="post">
<div id="sidebar_page">
	<div id="boxpadding">
		<p class="smalltext">
			Login with your username and password. Don't have an account yet?
			<a href="<?php echo SITE_URL;?>user/register">Register</a> a new account, or try
			<a href="<?php echo SITE_URL;?>user/openid">OpenID</a>. 
<?php if(GPG_ENABLE == "true"): ?>
			Have you previously setup
			your GPG Key with Feedmailer? Check out <a href="<?php echo SITE_URL;?>user/gpglogin">GPG Auth</a>.
<?php endif; ?>
		</p>
		<table id="form_data">
			<tr>
				<td><strong>Username:</strong></td>
				<td><input type="text" name="username" class="inputbox" value="" size="16" /></td>
			</tr>
			<tr>
				<td><strong>Password:</strong></td>
				<td><input type="password" name="password" class="inputbox" value="" size="16" /></td>
			</tr>
		</table>
		<table>
			<td>
				<p>
					<input type="submit" name="submit" value="Login" class="submit"/>
				</p>
			</td>
		</table>
		<p class="smalltext">
			<a href="<?php echo SITE_URL;?>user/resetpassword">Lost or forgotten password?</a>
		</p>
	</div>
</div>
</form>
