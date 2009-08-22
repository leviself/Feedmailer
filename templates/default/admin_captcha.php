<?php	// Load the templates class
	$template = new templates();

	// Load the MySQL connection (for a few small queries).
	$mysql = new db();
	$mysql->connect();
?>

				<p>
					<table class="headertext">
						<td>Stats</td>
						<td>Captcha Settings</td>
					</table>
				</p>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="admin_captcha.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<table>
							<tbody>
								<tr>
									<td>
										<p>
											Font Name:<br />
											<input type="text" name="font_name" value="<?php echo $mysql->setting(captcha_font);?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											Font Size:<br />
											<input type="text" name="font_size" value="<?php echo $mysql->setting(captcha_fontsize);?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<abbr title="RGB style">Background Color</abbr>:
											<input type="text" name="color_background" value="<?php echo $mysql->setting(captcha_color_background);?>" class="inputbox" /><br />
										</p>
									</td>
									<td>
										<p>
											<abbr title="RGB style">Text Color</abbr>:<br />
											<input type="text" name="color_text" value="<?php echo $mysql->setting(captcha_color_text);?>" class="inputbox" /><br />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<abbr title="RGB style">Noise Color</abbr>:<br />
											<input type="text" name="color_noise" value="<?php echo $mysql->setting(captcha_color_noise);?>" class="inputbox"/>
										</p>
									</td>
									<td>
										<p>
											<abbr title="Specify an integer">Lines</abbr>:<br />
											<input type="text" name="lines" value="<?php echo $mysql->setting(captcha_lines);?>" class="inputbox"/>
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<abbr title="Specify an integer">Dots</abbr>:<br />
											<input type="text" name="dots" value="<?php echo $mysql->setting(captcha_dots);?>" class="inputbox" />
										</p>
									</td>
									<td>
										<p>
											<abbr title="Specify an integer">Characters</abbr>:<br />
											<input type="text" name="characters" value="<?php echo $mysql->setting(captcha_characters);?>" class="inputbox" />
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<abbr title="Image Height">Height</abbr>:<br />
											<input type="text" name="height" value="<?php echo $mysql->setting(captcha_image_height);?>" class="inputbox" />
										</p>
									</td>
									<td>
										<p>
											<abbr title="Image Width">Width</abbr>:<br />
											<input type="text" name="width" value="<?php echo $mysql->setting(captcha_image_width);?>" class="inputbox" />
										</p>
									</td>
								</tr>								
							</tbody>
						</table>
						<p>
							<center>
								<img src="captcha.php?characters=<?php echo $mysql->setting(captcha_characters);?>&width=<?php echo $mysql->setting(captcha_image_width);?>&height=<?php echo $mysql->setting(captcha_image_height);?>" />
							</center>
						</p>
						<p class="submit">
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

