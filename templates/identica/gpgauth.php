<?php	// Load the templates class
	$template = new templates();

	$mysql = new db();
	$mysql->connect();
	include dirname(__FILE__)."/functions.php";
	include dirname(__FILE__)."/../../inc/class.gpgauth.php";

	$id = USERID;
	$vacation = mysql_result(mysql_query("SELECT `vacation` FROM {$mysql->prefix}users` WHERE id='$id'"),0);
?>

				<?php pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">
						<p class="smalltext">
							GPG Auth allows you to login to your account without a
							password, by using your public key to encrypt a message
							to you, which includes your one-time key, for authentication.
							To use GPG Auth, select the "GPG Auth" tab when logging in,
							and enter your KeyID.
						</p>
						<table id="form_data" cellpadding="2">
<?php if (!$gpgauth->keyExistsForUID($id)): ?>
							<tr>
								<td><strong>GnuPG Public Key Block:</strong></td>
							</tr>
							<tr>
								<td>
									<textarea name="pubkeyblock" class="inputbox" cols="65" rows="20"></textarea>
								</td>
							</tr>
						</table>
						<p>
							<input type="submit" class="submit" value="Add Key" />
						</p>
<?php else: ?>
							<tr>
								<td><strong>User ID:</strong></td><td><strong>Key ID:</strong></td>
							</tr>
								<td><?php echo $gpgauth->uidToUserID($id); ?></td><td><?php echo $gpgauth->uidToKeyID($id); ?></td>
							<tr>
							</tr>
						</table>
						<p>
							<input type="hidden" name="delete" value="true" />
							<input type="submit" class="submit" value="Delete" />
						</p>
<?php endif; ?>
					</div>
				</div>
				</form>
