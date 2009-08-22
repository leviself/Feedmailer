<?php	// Load the templates class
	$template = new templates();
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Add User</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_adduser.php" method="post">
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
									<td>
										<p>
											Password:<br />
											<input type="password" name="password" value="" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											Email Address:
											<input type="text" name="email" value="" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<h3>Options:</h3>
											<input type="checkbox" name="is_admin" value="true" /> Administrator<br />
											<input type="checkbox" name="send_activation" value="true" /> Send Activation Email<br />
											<input type="checkbox" name="set_vacation" value="on" /> Set Vacation<br />

										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p class="submit">
							<input type="submit" class="submit" value="Add User" />
						</p>
					</div>
				</div>
				</form>
				<br />
				<br />

