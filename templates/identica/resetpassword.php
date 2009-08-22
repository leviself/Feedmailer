<?php	$template = new templates(); ?>
<div id="sidebar_page_nav">
	<ul id="pagenav">
		<li id="childnav"><a href="<?php echo SITE_URL; ?>user/login">Login</a></li>
		<li id="childnav"><a href="<?php echo SITE_URL;?>user/register">Register</a></li>
		<li id="childnav"><a href="<?php echo SITE_URL;?>user/openid">OpenID</a></li>
	</ul>
</div>
		<form action="<?php echo SITE_URL;?>resetpassword.php" method="post">
				<?php $template->sidebar();?>
				<div id="sidebar_page">
					<div id="boxpadding">
					<table id="form_data">
						<tr>
							<td><strong>Email Address</strong></td>
							<td><input type="text" name="email" class="inputbox" value="<?php echo $_POST[email];?>" size="16" /></td>
							<tr>
								<td></td>
								<td>
									<p class="form_help">
										The email address that you registered with.
									</p>
								</td>
							</tr>
						</tr>
					</table>
					<p>
						<input type="submit" name="submit" value="Reset" class="submit" />
					</p>
					</div>
				</div>
				</form>
