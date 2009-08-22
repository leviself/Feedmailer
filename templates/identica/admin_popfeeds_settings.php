<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the MySQL connection
	$mysql = new db();
	$mysql->connect();

	define("POP_ENABLE",	$mysql->setting(pop_enable));
	define("POP_LIMIT",	$mysql->setting(pop_limit));
	define("POP_ORDERBY",	$mysql->setting(pop_orderby));
	define("POP_HOWPOPULAR",$mysql->setting(pop_howpopular));
	define("POP_DISPLAY",	$mysql->setting(pop_display));
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
								<td><strong>Popular Feeds</strong></td>
								<td>
									<select name="enable" class="inputbox">
										<option value="true" <?php if(POP_ENABLE == "true"){ echo 'selected="selected" ';}?>>Enable</option>
										<option value="false" <?php if(POP_ENABLE == "false"){ echo 'selected="selected" ';}?>>Disable</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Enable or Disable this feature.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Style</strong></td>
								<td>
									<select name="display" class="inputbox">
										<option value="simple" <?php if(POP_DISPLAY == "simple"){ echo 'selected="selected" ';}?>>Simple</option>
										<option value="extended" <?php if(POP_DISPLAY == "extended"){ echo 'selected="selected" ';}?>>Extended</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Simple displays URLs only. Extended displays Feed Titles with Favicons.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Display</strong></td>
								<td>
									<select name="limit" class="inputbox">
										<option value="5" <?php if(POP_LIMIT == "5"){ echo 'selected="selected" ';}?>>5 feeds</option>
										<option value="10" <?php if(POP_LIMIT == "10"){ echo 'selected="selected" ';}?>>10 feeds</option>
										<option value="15" <?php if(POP_LIMIT == "15"){ echo 'selected="selected" ';}?>>15 feeds</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											How many feeds to display.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Order</strong></td>
								<td>
									<select name="orderby" class="inputbox">
										<option value="ASC" <?php if(POP_ORDERBY == "ASC"){ echo 'selected="selected" ';}?>>Ascending</option>
										<option value="DESC" <?php if(POP_ORDERBY == "DESC"){ echo 'selected="selected" ';}?>>Descending</option>
									</select>
								</td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Displaying order.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Subscribers</strong></td>
								<td><input type="text" name="howpopular" value="<?php echo POP_HOWPOPULAR;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The amount of subscribers a feed must have before it becomes popular.
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<?php $template->popularfeeds();?>
						<p>
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

