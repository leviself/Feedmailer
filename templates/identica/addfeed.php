<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>

<?php pagenav("Add"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<p class="smalltext">
							Please insert the RSS or ATOM feed url into the URL
							input box, to subscribe to that feed's notification.
						</p>
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Feed URL:<br />
											<input type="text" name="url" value="<?php echo $_GET[url]; ?>" class="inputbox" size="30"/> <input type="submit" class="submit" value="Subscribe" />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<?php $template->popularfeeds(); ?>
					</div>
				</div>
				</form>
