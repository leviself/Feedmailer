<?php	// Load the templates class
	$template = new templates();

	$mysql = new db();
	$mysql->connect();
	$id = USERID;
	$vacation = mysql_result(mysql_query("SELECT `vacation` FROM {$mysql->prefix}users` WHERE id='$id'"),0);
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Vacation</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						The vacation option allows you to opt out receiving any
						email messages from Feedmailer. Use this option if you intend
						to go on vacation, and do not want your email inbox full of feed
						notifications.<br /><br />
						<table>
							<tbody>
								<tr>
									<td><input type="radio" name="vacation" value="on" <?php if($vacation == "on"){ echo 'checked="checked" ';} ?>/></td>
									<td>Enable</td>
								</tr>
								<tr>
									<td><input type="radio" name="vacation" value="off" <?php if($vacation == "off"){ echo 'checked="checked" ';}?>/></td>
									<td>Disable</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" class="submit" value="Change Setting" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
				<br /><br />
				<br /><br />
