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
				<form action="admin_addfeed.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Username:<br />
											<input type="text" name="username" value="" class="inputbox" /><br />
										</p>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<p>
											Feed URL:<br />
											<input type="text" name="url" value="" class="inputbox" />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" class="submit" value="Add Feed" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />

