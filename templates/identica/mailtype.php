<?php	// Load the templates class
	$template = new templates();

	$mysql = new db();
	$mysql->connect();
	include dirname(__FILE__)."/functions.php";

	$id = USERID;
	$mailtype = mysql_result(mysql_query("SELECT `mail_type` FROM {$mysql->prefix}users` WHERE id='$id'"),0);
?>

				<?php pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<p class="smalltext">
							Feedmailer gives you the option to set the email
							content type of your notifications. Some mail clients
							may only support plain text (or vise versa), so we allow
							you to change which type as the default for sending all
							messages to you.<br /><br />
							<strong>Plain Text</strong> sends only plain text to your inbox.<br />
							<strong>HTML Format</strong> sends you a rich HTML message.<br />
							<strong>BOTH</strong> is multipart, and sends both <strong>Plain Text</strong>
							and <strong>HTML Format</strong> in one email.
						</p>
						<table>
							<tbody>
								<tr>
									<td><input type="radio" name="mailtype" value="text/plain" <?php if($mailtype == "text/plain"){ echo 'checked="checked" ';} ?>/></td>
									<td>Plain Text</td>
								</tr>
								<tr>
									<td><input type="radio" name="mailtype" value="text/html" <?php if($mailtype == "text/html"){ echo 'checked="checked" ';}?>/></td>
									<td>HTML Format</td>
								</tr>
								<tr>
									<td><input type="radio" name="mailtype" value="multipart/alternative" <?php if($mailtype == "multipart/alternative"){ echo 'checked="checked" ';}?>/></td>
									<td>BOTH</td>
								</tr>
							</tbody>
						</table>
						<p>
							<input type="submit" class="submit" value="Change Mail Type" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
				<br /><br />
				<br /><br />
