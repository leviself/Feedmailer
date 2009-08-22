<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>

				<?php admin_pagenav("Feeds"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>Username</strong></td>
								<td><input type="text" name="username" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The account (username) that the feed will be added to.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Feed URL</strong></td>
								<td><input type="text" name="url" value="" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The subscription Feed URL
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<p>
							<input type="submit" class="submit" value="Add Feed" />
						</p>
					</div>
				</div>
				</form>
