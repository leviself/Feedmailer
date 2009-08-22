<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("DEBUG_ENABLE", $mysql->setting(debug_enable));
	define("DEBUG_MAX_TTL", $mysql->setting(debug_max_ttl));
?>

				<?php admin_pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>admin_debug_settings.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<p class="smalltext">
							Debug mode enables an adminstrator, developer or project owner to
							log all the actions, which go on behind the scenes, to a database
							table. This debug log is then readable and searchable to the user
							for analysis and debugging.<br/><br />
							<strong>WARNING:</strong> by (enabling|disabling) the debugger, it
							will auto-(build|destroy) the table and rows in the database to preserve
							space. Debug mode is not recommended for daily usage, as there will
							be many thousands of rows in the debug table.
						</p>
						<table id="form_data">
							<tr>
								<td><strong>Debug Mode</strong></td>
								<td>
									<select name="enable" class="inputbox">
										<option value="true" <?php if(DEBUG_ENABLE == "true"){ echo 'selected="selected" ';}?>>Enable</option>
										<option value="false" <?php if(DEBUG_ENABLE == "false"){ echo 'selected="selected" ';}?>>Disable</option>
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
						</table>
<?php	// If Debug mode is enabled
	if (DEBUG_ENABLE == "true"):
?>
						<p class="smalltext">
							The following setting allows you to specify how long a debug line
							is permitted to stay in the database. To keep the amount of rows
							in the database to a minimum, the maximum amount of time that we
							allow you to specify is 48 hours.<br />
						</p>
						<table id="form_data">
							<tr>
								<td><strong>Clear Debug When?</strong></td>
								<td>
									<select name="max_ttl" class="inputbox">
										<option value="3600" <?php if(DEBUG_MAX_TTL == "3600"){ echo 'selected="selected" ';}?>>Every hour</option>
										<option value="7200" <?php if(DEBUG_MAX_TTL == "7200"){ echo 'selected="selected" ';}?>>Every 2 hours</option>
										<option value="10800" <?php if(DEBUG_MAX_TTL == "10800"){ echo 'selected="selected" ';}?>>Every 3 hours</option>
										<option value="14400" <?php if(DEBUG_MAX_TTL == "14400"){ echo 'selected="selected" ';}?>>Every 4 hours</option>
										<option value="18000" <?php if(DEBUG_MAX_TTL == "18000"){ echo 'selected="selected" ';}?>>Every 5 hours</option>
										<option value="21600" <?php if(DEBUG_MAX_TTL == "21600"){ echo 'selected="selected" ';}?>>Every 6 hours</option>
										<option value="25200" <?php if(DEBUG_MAX_TTL == "25200"){ echo 'selected="selected" ';}?>>Every 7 hours</option>
										<option value="28800" <?php if(DEBUG_MAX_TTL == "28800"){ echo 'selected="selected" ';}?>>Every 8 hours</option>
										<option value="32400" <?php if(DEBUG_MAX_TTL == "32400"){ echo 'selected="selected" ';}?>>Every 9 hours</option>
										<option value="36000" <?php if(DEBUG_MAX_TTL == "36000"){ echo 'selected="selected" ';}?>>Every 10 hours</option>
										<option value="43200" <?php if(DEBUG_MAX_TTL == "43200"){ echo 'selected="selected" ';}?>>Every 12 hours</option>
										<option value="50400" <?php if(DEBUG_MAX_TTL == "50400"){ echo 'selected="selected" ';}?>>Every 14 hours</option>
										<option value="57600" <?php if(DEBUG_MAX_TTL == "57600"){ echo 'selected="selected" ';}?>>Every 16 hours</option>
										<option value="64800" <?php if(DEBUG_MAX_TTL == "64800"){ echo 'selected="selected" ';}?>>Every 18 hours</option>
										<option value="72000" <?php if(DEBUG_MAX_TTL == "72000"){ echo 'selected="selected" ';}?>>Every 20 hours</option>
										<option value="86400" <?php if(DEBUG_MAX_TTL == "86400"){ echo 'selected="selected" ';}?>>Every 24 hours</option>
										<option value="100800" <?php if(DEBUG_MAX_TTL == "100800"){ echo 'selected="selected" ';}?>>Every 28 hours</option>
										<option value="115200" <?php if(DEBUG_MAX_TTL == "115200"){ echo 'selected="selected" ';}?>>Every 32 hours</option>
										<option value="129600" <?php if(DEBUG_MAX_TTL == "129600"){ echo 'selected="selected" ';}?>>Every 36 hours</option>
										<option value="144000" <?php if(DEBUG_MAX_TTL == "144000"){ echo 'selected="selected" ';}?>>Every 40 hours</option>
										<option value="158400" <?php if(DEBUG_MAX_TTL == "158400"){ echo 'selected="selected" ';}?>>Every 44 hours</option>
										<option value="172800" <?php if(DEBUG_MAX_TTL == "172800"){ echo 'selected="selected" ';}?>>Every 48 hours</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The maximum amount of time an entry to live.
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p style="text-align:center;font-size:1.5em;">
							<a href="<?php echo SITE_URL;?>dashboard/debug">VIEW DEBUG LOG</a>
						</p>
<?php	// end
	endif;
?>
						<p>
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

