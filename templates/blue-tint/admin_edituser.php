<?php	// Load the templates class
	$template = new templates();

	// Load the mysql class
	$mysql = new db();
	$mysql->connect();

	// Settings page, if user is loaded.

	$input = $_GET[id];

	$userid = @mysql_result(mysql_query(
	"SELECT `id` FROM `{$mysql->prefix}users` 
	WHERE email='$input' OR username='$input'
	OR id='$input'"),0);

	if ($input){

		if (!$userid){
			die($template->error("User does not exist.").$template->footer());
		}

		$user = $mysql->selectUser($userid);
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Edit User</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_edituser.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Change Username:<br />
											<input type="text" name="username" value="<?php echo $user[username];?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Change Password:<br />
											<input type="password" name="password" value="" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Change Email Address:
											<input type="text" name="email" value="<?php echo $user[email];?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											<abbr title="In seconds">Cookie Expire Time</abbr>:<br />
											<input type="text" name="cookie_expire" value="<?php echo $user[cookie_expire];?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Registered IP:
											<input type="text" disabled="disabled" value="<?php echo $user[reg_ip];?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Email Count:
											<input type="text" disabled="disabled" value="<?php echo $user[email_count];?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Mail Type:
											<select name="mail_type" class="inputbox" style="width:98%;">
												<option value="text/plain" <?php if($user[mail_type] == "text/plain"){ echo 'selected="selected" ';}?>>text/plain</option>
												<option value="text/html" <?php if($user[mail_type] == "text/html"){ echo 'selected="selected" ';}?>>text/html</option>
												<option value="multipart/alternative" <?php if($user[mail_type] == "multipart/alternative"){ echo 'selected="selected" ';}?>>multipart/alternative</option>
											</select>
										</p>
									</td>
								</tr>


							</tbody>
						</table>
						<p>
							<h3>Options</h3>
							<input type="checkbox" name="is_admin" value="true" <?php if($user[is_admin] == "true"){echo 'checked="checked" ';}?> /> Administrator<br />
							<input type="checkbox" name="vacation" value="on" <?php if($user[vacation] == "on"){echo 'checked="checked" ';}?> /> Set Vacation


						</p>



						<p class="submit">
							<input type="hidden" name="userid" value="<?php echo $userid;?>" />
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />

<?php
	}	// Display the main edit page (inputting user)
	else {
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Edit User</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_edituser.php" method="get">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											ID, Username or Email Address:<br />
											<input type="text" name="id" value="" class="inputbox" />
										</p>
									</td>
								</tr>
							</tbody>
						</table>

						<p class="submit">
							<input type="submit" class="submit" value="Edit User" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />
				<br />
				<br />

<?php	// end it
	}
?>
