<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

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
								<td><strong>Change Username</strong></td>
								<td><input type="text" name="username" value="<?php echo $user[username];?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The user's system username.
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
								<td><input type="text" name="email" value="<?php echo $user[email];?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The email address that receives updates.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Cookie Expire Time</strong></td>
								<td><input type="text" name="cookie_expire" value="<?php echo $user[cookie_expire];?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Specified in seconds.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Registered IP</strong></td>
								<td><input type="text" readonly="readonly" value="<?php echo $user[reg_ip];?>" class="inputbox" /></td>
							</tr>
							<tr>
								<td><strong>Email Count</strong></td>
								<td><input type="text" readonly="readonly" value="<?php echo $user[email_count];?>" class="inputbox" /></td>
							</tr>
							<tr>
								<td><strong>Mail Type</strong>
								<td>
									<select name="mail_type" class="inputbox">
											<option value="text/plain" <?php if($user[mail_type] == "text/plain"){ echo 'selected="selected" ';}?>>text/plain</option>
											<option value="text/html" <?php if($user[mail_type] == "text/html"){ echo 'selected="selected" ';}?>>text/html</option>
											<option value="multipart/alternative" <?php if($user[mail_type] == "multipart/alternative"){ echo 'selected="selected" ';}?>>multipart/alternative</option>
									</select>
								</td>
							</tr>
						</table>
						<p>
							<h3>Options</h3>
							<input type="checkbox" name="is_admin" value="true" <?php if($user[is_admin] == "true"){echo 'checked="checked" ';}?> /> Administrator<br />
							<input type="checkbox" name="vacation" value="on" <?php if($user[vacation] == "on"){echo 'checked="checked" ';}?> /> Set Vacation
						</p>
						<p>
							<input type="hidden" name="userid" value="<?php echo $userid;?>" />
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

<?php
	}	// Display the main edit page (inputting user)
	else {
?>

				<?php admin_pagenav("Users"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="get">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>User</strong></td>
								<td><input type="text" name="id" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											UserID, Username or Email Address.
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<input type="submit" class="submit" value="Edit User" />
						</p>
					</div>
				</div>
				</form>

<?php	// end it
	}
?>
