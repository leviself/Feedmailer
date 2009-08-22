<?php	// Load the templates class
	$template = new templates();

	$mysql = new db();
	$mysql->connect();
	include dirname(__FILE__)."/functions.php";
?>

				<?php pagenav(NULL); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>contact.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<p class="smalltext">
							Please use this contact form to report errors, bugs
							or any other unexplained things that may happen to you while
							using this service. You may also use this form to request an
							explanation of help on specific objects. Do not hesitate to contact
							us. But please, <em>do not over abuse this feature</em> or it will
							be disabled.
						</p>
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
						<p>
							<input type="submit" class="submit" value="Send" />
						</p>
					</div>
				</div>
				</form>
