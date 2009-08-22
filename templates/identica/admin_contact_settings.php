<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("CONTACT_ENABLE", $mysql->setting(contact_enable));
	define("CONTACT_WHO",	 $mysql->setting(contact_who));
?>

				<?php admin_pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>admin_contact_settings.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
					<br />
						<p class="smalltext">
							The contact settings allows the administrator to choose whether
							or not a user may use the contact form to communicate with the
							administrator(s) by either specifying an email address to send
							the messages to, or by using the word '<strong>alladmins</strong>'
							to send a copy of the message to every admininstrator.
						</p>
						<table id="form_data">
							<tr>
								<td><strong>Contact Page</strong></td>
								<td>
									<select name="enable" class="inputbox">
										<option value="true" <?php if(CONTACT_ENABLE == "true"){ echo 'selected="selected" ';}?>>Enable</option>
										<option value="false" <?php if(CONTACT_ENABLE == "false"){ echo 'selected="selected" ';}?>>Disable</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Enable or Disable this setting.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Deliver messages to</strong></td>
								<td><input type="text" name="who" value="<?php echo CONTACT_WHO;?>" class="inputbox" />
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											To whom the contact messages get sent.
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

