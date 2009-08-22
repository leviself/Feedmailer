<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
	
	// Load the MySQL connection (for a few small queries).
	$mysql = new db();
	$mysql->connect();

	define("ALL_FEEDS", mysql_result(mysql_query("SELECT COUNT(*) FROM `{$mysql->prefix}feeds`"),0));
?>

				<?php admin_pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>Site Title</strong></td>
								<td><input type="text" name="site_title" value="<?php echo SITE_TITLE;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The global name of this site.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Site URL</strong></td>
								<td><input type="text" name="site_url" value="<?php echo SITE_URL;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The complete URL to the root directory
											of this project. Trailing slash is required.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<!-- I need a function to generate a dropdown
									of all the directories in templates/ -->
								<td><strong>Template Name</strong></td>
								<td><?php $template->themeswitcher(); ?></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The template directory name.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Cookie Prefix</strong></td>
								<td><input type="text" name="cookie_name" value="<?php echo COOKIE_NAME;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The prefix for assigning cookies.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Email Count</strong></td>
								<td><input type="text" readonly="readonly" value="<?php echo EMAIL_COUNT;?>" class="inputbox"/></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The number of updates sent by this system.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Feed Cache Rows</strong></td>
								<td><input type="text" readonly="readonly" value="<?php echo CACHE_COUNT;?>" class="inputbox"/></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The number of rows of cache in the database.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Registered Users</strong></td>
								<td><input type="text" readonly="readonly" value="<?php echo COUNT_REG;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The number of registered users.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Subscribed Feeds</strong></td>
								<td><input type="text" readonly="readonly" value="<?php echo ALL_FEEDS;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The total amount of subscriptions.			
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<h3>Options:</h3>
							<input type="checkbox" name="register_enable" value="true" <?php if(REGISTER_ENABLE == "true"){ echo 'checked="checked" ';}?> /> Users can register<br />
						</p>
						<p>
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>
