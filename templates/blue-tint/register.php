<?php	// Load the MySQL Connection for Captcha image.
	$mysql = new db();
	$mysql->connect();
?>

			<form action="register.php" method="post">
			<div id="login">
				<div id="boxpadding">
				<p>
					Username:<br />
					<input type="text" name="username" class="inputbox" value="<?php echo $_POST[username];?>" size="16" />
				</p>
				<p>
					Password:<br />
					<input type="password" name="password" class="inputbox" value="" size="16" />
					<input type="password" name="password2" class="inputbox" value="" size="16" />
				</p>
				<p>
					Email:<br />
					<input type="text" name="email" class="inputbox" value="<?php echo $_POST[email];?>" size="16" />
				</p>
				<p>
					<img src="captcha.php?charaters=<?php echo $mysql->setting(captcha_characters);?>&width=<?php echo $mysql->setting(captcha_image_width);?>&height=<?php echo $mysql->setting(captcha_image_height);?>" alt="Please enter the charaters in the box below" title="Please enter the characters in the box below" /><br />
					<input type="text" name="security_code" class="inputbox" value="" size="16" />
				</p>

				<p class="submit">
					<input type="submit" name="clusterauth_submit" value="Register" class="submit" />
				</p>
				</div>
			</div>
			</form>
			<br />
			<br />
			<br />
