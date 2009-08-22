<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>

				<?php admin_pagenav("Users"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>Username</strong></td>
								<td><input type="text" name="username" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Their system username.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Password</strong></td>
								<td><input type="password" name="password" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Their system password.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Email Address</strong></td>
								<td><input type="text" name="email" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The email address that will receive updates.
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<h3>Options:</h3>
							<input type="checkbox" name="is_admin" value="true" /> Administrator<br />
							<input type="checkbox" name="send_activation" value="true" /> Send Activation Email<br />
							<input type="checkbox" name="vacation" value="on" /> Set Vacation<br />
						</p>
						<p>
							<input type="submit" class="submit" value="Add User" />
						</p>
					</div>
				</div>
				</form>

