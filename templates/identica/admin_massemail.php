<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	$mysql = new db();
	$mysql->connect();
?>

				<?php admin_pagenav("MassEmail"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>admin_massemail.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
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

						<p>
							<input type="submit" class="submit" value="Send" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
