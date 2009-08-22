<?php	// Load the templates class
	$template = new templates();
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Add Feed</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						Please insert the RSS or ATOM feed url into the URL
						input box, to subscribe to that feed's notification.

						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Feed URL:<br />
											<input type="text" name="url" value="<?php echo $_GET[url]; ?>" class="inputbox" size="30"/>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<?php $template->popularfeeds(); ?>
						<p class="submit">
							<input type="submit" class="submit" value="Subscribe" />
						</p>
					</div>
				</div>
				</form>
				<br /><br /><br />
