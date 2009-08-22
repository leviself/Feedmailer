<?php	// Load the templates class
	$template = new templates();
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Settings</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Change Username:<br />
											<input type="text" name="username" value="<?php echo USER;?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Change Password:<br />
											<input type="password" name="password" value="" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Change Email Address:
											<input type="text" name="email" value="<?php echo EMAIL;?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											<abbr title="In seconds">Cookie Expire Time</abbr>:<br />
											<input type="text" name="cookie_expire" value="<?php echo COOKIE_EXPIRE;?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />
				<br />
				<br />
				<br />

