<?php	// Load the templates class
	$template = new templates();

	// Load the mysql class
	$mysql = new db();
	$mysql->connect();

	// Settings page, if user is loaded.

	$input = $_GET[id];

	$feedid = @mysql_result(mysql_query(
	"SELECT `id` FROM `{$mysql->prefix}feeds` 
	WHERE `id`='$input'"),0);

	if ($input){

		if (!$feedid){
			die($template->error("Feed does not exist.").$template->footer());
		}

		$feed = mysql_fetch_assoc(mysql_query(
		"SELECT * FROM `{$mysql->prefix}feeds`
		WHERE `id`='$feedid'"));

		define("FEED_ID", $feed[id]);
		define("FEED_UID", $feed[uid]);
		define("FEED_TITLE", $feed[title]);
		define("FEED_URL", $feed[url]);

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
				<form action="admin_editfeed.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Feed ID:<br />
											<input type="text" disabled="disabled" value="<?php echo FEED_ID;?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Feed UserID:<br />
											<input type="text" disabled="disabled" value="<?php echo FEED_UID;?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Feed Title:<br />
											<input type="text" name="title" value="<?php echo FEED_TITLE;?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Feed URL:<br />
											<input type="text" name="url" value="<?php echo FEED_URL;?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="hidden" name="feedid" value="<?php echo $feedid;?>" />
							<input type="submit" class="submit" value="Edit" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />

<?php
	}	// Display the main edit page (inputting user)
	else {
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
				<form action="admin_editfeed.php" method="get">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Feed ID:<br />
											<input type="text" name="id" value="" class="inputbox" />
										</p>
									</td>
								</tr>
							</tbody>
						</table>

						<p class="submit">
							<input type="submit" class="submit" value="Edit Feed" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />
				<br />
				<br />

<?php	// end it
	}
?>
