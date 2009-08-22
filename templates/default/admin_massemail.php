<?php	// Load the templates class
	$template = new templates();

	$mysql = new db();
	$mysql->connect();
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Mass Email</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_massemail.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table>
							<tbody>
								<tr>
									<td>Subject:<br />
									<input class="inputbox" type="text" name="subject" value="" /></td>
								</tr>
								<tr>
									<td>Message:<br />
									<textarea class="inputbox" name="message" cols="45" rows="10"></textarea></td>
								</tr>
							</tbody>
						</table>
						
						<br />
						<input type="checkbox" name="override" value="true" /> Override user's vacation settings<br />

						<p class="submit">
							<input type="submit" class="submit" value="Send" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
