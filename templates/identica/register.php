<?php	// Load the MySQL Connection for Captcha image.
	$mysql = new db();
	$mysql->connect();

	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>
<?php pagenav("Register"); ?>
<?php $template->sidebar(); ?>

<form action="<?php echo SITE_URL;?>register.php" method="post">
	<div id="sidebar_page">
		<div id="boxpadding">
			<p class="smalltext">
				With this form, you can create a new account and begin subscribing
				to various RSS/ATOM Feeds to have delivered to your email. (Have an 
				<a href="http://openid.net/">OpenID</a>? Try our 
				<a href="<?php echo SITE_URL;?>user/openid">OpenID registration</a>!)
			</p>
			<table id="form_data">
				<tr>
					<td><strong>Username</strong></td>
					<td>
						<input type="text" name="username" class="inputbox" value="<?php echo $_POST[username];?>" size="16" />
					</td>
					<tr>
						<td></td>
						<td>
							<p class="form_help">
								A unique username, which can include spaces. Required.
							</p>
						</td>
					</tr>
				</tr>
				<tr>
					<td><strong>Password</strong></td>
					<td>
						<input type="password" name="password" class="inputbox" value="" size="16" />
					</td>
					<tr>
						<td></td>
						<td>
							<p class="form_help">
								Enter a strong password. Required.
							</p>
						</td>
					</tr>
				</tr>
				<tr>
					<td><strong>Confirm</strong></td>
					<td>
						<input type="password" name="password2" class="inputbox" value="" size="16" />
					</td>
					<tr>
						<td></td>
						<td>
							<p class="form_help">
								Same as password above. Required.
							</p>
						</td>
					</tr>
				</tr>
				<tr>
					<td><strong>Email</strong></td>
					<td>
						<input type="text" name="email" class="inputbox" value="<?php echo $_POST[email];?>" size="16" />
					</td>
					<tr>
						<td></td>
						<td>
							<p class="form_help">
								The email address that will receive notifications. You
								must verify this address. Required.
							</p>
						</td>
					</tr>
				</tr>
				<tr>
					<td><strong>Security Code</strong></td>
					<td>
						<img style="margin-left: 40px;" src="<?php echo SITE_URL;?>captcha.php?charaters=<?php echo $mysql->setting(captcha_characters);?>&width=162&height=30" alt="Please enter the charaters in the box below" title="Please enter the characters in the box below" /><br />
						<input type="text" name="security_code" class="inputbox" value="" size="16" />
					</td>
					<tr>
						<td></td>
						<td>
							<p class="form_help">
								Enter the case-sensitive characters in the box. Required.
							</p>
						</td>
					</tr>
				</tr>
			</table>
			<p>
				<input type="submit" name="submit" value="Register" class="submit" />
			</p>
			</div>
		</div>
		</form>
