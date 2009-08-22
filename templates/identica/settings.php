<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>

				<?php pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table id="form_data">						
							<tr>
								<td><strong>Change Username</strong></td>
								<td><input type="text" name="username" value="<?php echo USER;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Your system username.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Change Password</strong></td>
								<td><input type="password" name="password" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Leave blank if you do not wish to change it.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Change Email Address</strong></td>
								<td><input type="text" name="email" value="<?php echo EMAIL;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The email address to which you receive updates.
										</p>
									</td>
							</tr>
							<tr>
								<td><strong>Cookie Expire Time</strong></td>
								<td><input type="text" name="cookie_expire" value="<?php echo COOKIE_EXPIRE;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Specified in seconds.
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

