<?php	// Load the templates class
	$template = new templates();

	// Load the feed class
	$feed = new feeds();
?>


<?php	// If no feed has been selected.
	if (!$_POST[urlid]):
?>
				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Edit Feed</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="editfeed.php" method="post">
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


						<p class="submit">
							<input type="submit" class="submit" value="Go" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
				<br /><br />
				<br /><br />
				<br /><br />
				<br /><br />

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
				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Edit Feed</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
					<br />
					The Feed Title can be changed to something different for
					the subject line, when receiving notification. If you should
					wish that it be returned back to default, simply leave this
					field blank.
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Feed Title:<br />
											<input type="text" name="feed_title" value="<?php echo $feed_title;?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Feed URL:<br />
											<input type="text" name="feed_url" value="<?php echo $feed_url; ?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="hidden" name="feed_id" value="<?php echo $_POST[urlid];?>" />
							<input type="submit" class="submit" value="Edit" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
				<br /><br />
				<br /><br />
<?php		endif;
	// End this nonsense
	endif;
?>
