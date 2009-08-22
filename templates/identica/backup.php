<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>

				<?php pagenav("Backup"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<div id="sidebar_page">
					<div id="boxpadding">
						<p class="smalltext">
							Import/Export feature lets you backup all of your subscribed feed URLs
							to an XML file that is stored on your computer, which you can import
							back in with the import option at a later time. Also useful for if you
							are moving to a new Feedmailer system, and you have alot of feeds.
						</p>
						<table>
							<tbody>
								<tr>
									<td>
										<p>										
											<form action="<?php echo SITE_URL;?>backup.php" method="get">
											<input type="hidden" name="export" value="true" />
											<input type="submit" value="Export" class="submit" />
											</form>
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<form enctype="multipart/form-data" action="<?php echo SITE_URL;?>backup.php" method="post">
											<input type="hidden" name="import" value="true" />
											<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
											<input name="xml" class="inputbox" type="file" />
											<input type="submit" class="submit" value="Import" />
											</form>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
						<br /><br /><br />
					</div>
				</div>
