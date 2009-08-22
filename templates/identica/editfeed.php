<?php	// Load the templates class
	$template = new templates();

	// Load the feed class
	$feed = new feeds();

	include dirname(__FILE__)."/functions.php";
?>


<?php	// If no feed has been selected.
	if (!$_POST[urlid]):
?>
<?php pagenav("Edit"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>editfeed.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						Please select a feed from the select box to begin
						editing the properties of it.<br />
						
						<select name="urlid">
						<?php	// Show selectbox for feeds
							$feed->selectFeeds(USERID);
						?>
						</select>


						<p>
							<input type="submit" class="submit" value="Go" />
						</p>
					</div>
				</div>
				</form>

<?php	// Else, if a feed url has been selected.
	else:

	$mysql = new db();
	$mysql->connect();
	$feed_url = mysql_result(mysql_query("SELECT `url` FROM `{$mysql->prefix}feeds` WHERE id='$_POST[urlid]'"),0);
	$feed_uid = mysql_result(mysql_query("SELECT `uid` FROM `{$mysql->prefix}feeds` WHERE id='$_POST[urlid]'"),0);
	$feed_title = stripslashes(mysql_result(mysql_query("SELECT `title` FROM `{$mysql->prefix}feeds` WHERE id='$_POST[urlid]'"),0));

		// Stop users from spoofing $_POST[urlid] and editing
		// another user's feed settings.
		if (USERID == $feed_uid):

?>
<?php pagenav("Edit"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<p class="smalltext">
							The Feed Title can be changed to something different for
							the subject line, when receiving notification. If you should
							wish that it be returned back to default, simply leave this
							field blank.
						</p>
						<table id="form_data">
							<tr>
								<td><strong>Title</strong></td>
								<td><input type="text" name="feed_title" value="<?php echo $feed_title;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											An alternate title for the feed.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>URL</strong></td>
								<td><input type="text" name="feed_url" value="<?php echo $feed_url; ?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The URL to xml feed.	
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<input type="hidden" name="feed_id" value="<?php echo $_POST[urlid];?>" />
							<input type="submit" class="submit" value="Edit" />
						</p>
					</div>
				</div>
				</form>
<?php		endif;
	// End this nonsense
	endif;
?>
