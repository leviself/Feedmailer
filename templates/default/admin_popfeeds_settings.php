<?php	// Load the templates class
	$template = new templates();

	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("POP_ENABLE",	$mysql->setting(pop_enable));
	define("POP_LIMIT",	$mysql->setting(pop_limit));
	define("POP_ORDERBY",	$mysql->setting(pop_orderby));
	define("POP_HOWPOPULAR",$mysql->setting(pop_howpopular));
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Popular Feeds Setting</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_popfeeds_settings.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											<abbr title="Select a value">Popular Feeds</abbr>:<br />
											<select name="enable" class="inputbox">
												<option value="true" <?php if(POP_ENABLE == "true"){ echo 'selected="selected" ';}?>>Enable</option>
												<option value="false" <?php if(POP_ENABLE == "false"){ echo 'selected="selected" ';}?>>Disable</option>
											</select>
										</p>
									</td>
									<td>
										<abbr title="How many feeds to display">Display</abbr>:<br />
										<select name="limit" class="inputbox">
											<option value="5" <?php if(POP_LIMIT == "5"){ echo 'selected="selected" ';}?>>5 feeds</option>
											<option value="10" <?php if(POP_LIMIT == "10"){ echo 'selected="selected" ';}?>>10 feeds</option>
											<option value="15" <?php if(POP_LIMIT == "15"){ echo 'selected="selected" ';}?>>15 feeds</option>
										</select>
									</td>
									<td>
										<p>
											<abbr title="Displaying order">Order</abbr>:<br />
											<select name="orderby" class="inputbox">
												<option value="ASC" <?php if(POP_ORDERBY == "ASC"){ echo 'selected="selected" ';}?>>Ascending</option>
												<option value="DESC" <?php if(POP_ORDERBY == "DESC"){ echo 'selected="selected" ';}?>>Descending</option>
											</select>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											<abbr title="When does a feed become popular?">Amount of subscribers to become popular</abbr>:<br />
											<input type="text" name="howpopular" value="<?php echo POP_HOWPOPULAR;?>" class="inputbox" />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<?php $template->popularfeeds();?>
							
							
						<p class="submit">
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />

