<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the MySQL connection (for a few small queries).
	$mysql = new db();
	$mysql->connect();
?>

				<?php admin_pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>admin_captcha.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<br />
						<table id="form_data">
							<tr>
								<td><strong>Font Name</strong></td>
								<td><input type="text" name="font_name" value="<?php echo $mysql->setting(captcha_font);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The filename of the font.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Font Size</strong></td>
								<td><input type="text" name="font_size" value="<?php echo $mysql->setting(captcha_fontsize);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Specifed in pixels.
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Background Color</strong></td>
								<td><input type="text" name="color_background" value="<?php echo $mysql->setting(captcha_color_background);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											In RGB, like "255, 255, 255"
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Text Color</strong></td>
								<td><input type="text" name="color_text" value="<?php echo $mysql->setting(captcha_color_text);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											In RGB, like "255, 255, 255"
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Noise Color</strong></td>
								<td><input type="text" name="color_noise" value="<?php echo $mysql->setting(captcha_color_noise);?>" class="inputbox"/></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											In RGB, like "255, 255, 255"
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Lines</strong></td>
								<td><input type="text" name="lines" value="<?php echo $mysql->setting(captcha_lines);?>" class="inputbox"/></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Specify an integer
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Dots</strong></td>
								<td><input type="text" name="dots" value="<?php echo $mysql->setting(captcha_dots);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Specify an integer
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Characters</strong></td>
								<td><input type="text" name="characters" value="<?php echo $mysql->setting(captcha_characters);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Specify an integer
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Height</strong></td>
								<td><input type="text" name="height" value="<?php echo $mysql->setting(captcha_image_height);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The heigth of the image.									
										</p>
									</td>
								</tr>
							</tr>
							<tr>
								<td><strong>Width</strong></td>
								<td><input type="text" name="width" value="<?php echo $mysql->setting(captcha_image_width);?>" class="inputbox" /></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											The width of the image.	
										</p>
									</td>
								</tr>								
							</tr>
						</table>
						<p>
							<center>
								<img src="<?php echo SITE_URL;?>captcha.php?characters=<?php echo $mysql->setting(captcha_characters);?>&width=<?php echo $mysql->setting(captcha_image_width);?>&height=<?php echo $mysql->setting(captcha_image_height);?>" />
							</center>
						</p>
						<p>
							<input type="submit" class="submit" value="Update" />
						</p>
					</div>
				</div>
				</form>

