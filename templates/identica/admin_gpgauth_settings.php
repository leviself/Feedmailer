<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("GPG_ENABLE",	$mysql->setting(gpg_enable));
	define("GPG_EXECPATH",	$mysql->setting(gpg_execpath));
	define("GPG_HOMEDIR",	$mysql->setting(gpg_homedir));
?>

				<?php admin_pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<p class="smalltext">
							GPG Auth allows users to import their public key into Feedmailer
							so they can have the system encrypt a one-time key to them for
							logging into the system. Instead of using a username/password,
							they would use their GPG Keyid and it would generate an encrypted,
							random, one-time key for them to login with.<br /><br />
						</p>
						<table id="form_data">
							<tr>
								<td><strong>GPG Auth</strong></td>
								<td>
									<select name="enable" class="inputbox" <?php if(ini_get("safe_mode")){ echo 'disabled="disabled"';}?>>
										<option value="true" <?php if(GPG_ENABLE == "true"){ echo 'selected="selected" ';}?>>Enable</option>
										<option value="false" <?php if(GPG_ENABLE == "false"){ echo 'selected="selected" ';}?>>Disable</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
<?php if(ini_get("safe_mode")): ?>
											You can not enable this setting. Php Safe_mode is enabled.
<?php else: ?>
											Enable or Disable this feature.
<?php endif; ?>
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Executive Path</strong></td>
								<td>
									<input class="inputbox" type="text" name="gpgexec" value="<?php echo GPG_EXECPATH;?>" />
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The path to the GnuPG executive.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Home Directory</strong></td>
								<td>
									<input class="inputbox" type="text" name="gpghomedir" value="<?php echo GPG_HOMEDIR;?>" />
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The path to the GnuPG Home Directory.
										</p>
									</td>
								</tr>
							</tr>
<?php if (GPG_ENABLE == "true"): ?>
							<tr>
								<td><strong>Destroy Table</strong></td>
								<td>
									<input class="inputbox" type="checkbox" name="destroytable" value="true"/>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Destroy the table in the database.
										</p>
									</td>
								</tr>
							</tr>
<?php endif; ?>
						</table>
						<p>
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

