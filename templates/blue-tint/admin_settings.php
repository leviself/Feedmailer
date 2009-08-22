<?php	// Load the templates class
	$template = new templates();

	// Load the MySQL connection (for a few small queries).
	$mysql = new db();
	$mysql->connect();

	define("ALL_FEEDS", mysql_result(mysql_query("SELECT COUNT(*) FROM `{$mysql->prefix}feeds`"),0));
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>System Settings</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_settings.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Site Title:<br />
											<input type="text" name="site_title" value="<?php echo SITE_TITLE;?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											<abbr title="Trailing slash is required">Site URL</abbr>:<br />
											<input type="text" name="site_url" value="<?php echo SITE_URL;?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Template Name:<br />
											<?php $template->themeswitcher(); ?>
										</p>
									</td>
									<td>
										<p>
											<abbr title="The prefix for assigning cookies">Cookie Prefix</abbr>:<br />
											<input type="text" name="cookie_name" value="<?php echo COOKIE_NAME;?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Email Count:<br />
											<input type="text" disabled="disabled" value="<?php echo EMAIL_COUNT;?>" class="inputbox"/>
										</p>
									</td>
									<td>
										<p>
											Feed Cache Rows:<br />
											<input type="text" disabled="disabled" value="<?php echo CACHE_COUNT;?>" class="inputbox"/>
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Registered Users:<br />
											<input type="text" disabled="disabled" value="<?php echo COUNT_REG;?>" class="inputbox" />
										</p>
									</td>
									<td>
										<p>
											Subscribed Feeds:<br />
											<input type="text" disabled="disabled" value="<?php echo ALL_FEEDS;?>" class="inputbox" />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<h3>Options:</h3>
											<input type="checkbox" name="register_enable" value="true" <?php if(REGISTER_ENABLE == "true"){ echo 'checked="checked" ';}?> /> Users can register<br />
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
				<br />
				<br />
				<br />
				<br />
				<br />

