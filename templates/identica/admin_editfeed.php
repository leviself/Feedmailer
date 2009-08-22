<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the mysql class
	$mysql = new db();
	$mysql->connect();

	// Settings page, if user is loaded.

	$input = $_GET[id];

	$feedid = @mysql_result(mysql_query(
	"SELECT `id` FROM `{$mysql->prefix}feeds` 
	WHERE `id`='$input'"),0);
?>

				<?php admin_pagenav("Feeds"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>

<?php
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

				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>Feed ID</strong></td>
								<td><input type="text" disabled="disabled" value="<?php echo FEED_ID;?>" class="inputbox" /></td>
							</tr>
							<tr>
								<td><strong>Feed UserID</strong></td>
								<td><input type="text" disabled="disabled" value="<?php echo FEED_UID;?>" class="inputbox" /></td>
							</tr>
							<tr>
								<td><strong>Feed Title</strong></td>
								<td><input type="text" name="title" value="<?php echo FEED_TITLE;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The alternate title for notifications.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Feed URL</strong></td>
								<td><input type="text" name="url" value="<?php echo FEED_URL;?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The subscription feed url.
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<input type="hidden" name="feedid" value="<?php echo $feedid;?>" />
							<input type="submit" class="submit" value="Edit" />
						</p>
					</div>
				</div>
				</form>
<?php
	}	// Display the main edit page (inputting user)
	else {
?>

				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="get">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>Feed ID</strong></td>
								<td><input type="text" name="id" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The ID Number of the Feed.
										</p>
									</td>
								</tr>
							</tr>
						</table>

						<p>
							<input type="submit" class="submit" value="Edit Feed" />
						</p>
					</div>
				</div>
				</form>
<?php	// end it
	}
?>
