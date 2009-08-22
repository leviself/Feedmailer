<?php	// Load the templates class
	$template = new templates();

	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("CONTACT_ENABLE", $mysql->setting(contact_enable));
	define("CONTACT_WHO",	 $mysql->setting(contact_who));
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Contact Settings</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_contact_settings.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
					<br />
					The contact settings allows the administrator to choose whether
					or not a user may use the contact form to communicate with the
					administrator(s) by either specifying an email address to send
					the messages to, or by using the word '<strong>alladmins</strong>'
					to send a copy of the message to every admininstrator.<br />
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											<abbr title="Select a value">Contact Page</abbr>:<br />
											<select name="enable" class="inputbox">
												<option value="true" <?php if(CONTACT_ENABLE == "true"){ echo 'selected="selected" ';}?>>Enable</option>
												<option value="false" <?php if(CONTACT_ENABLE == "false"){ echo 'selected="selected" ';}?>>Disable</option>
										</p>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<p>
											Deliver messages to:<br />
											<input type="text" name="who" value="<?php echo CONTACT_WHO;?>" class="inputbox" />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

