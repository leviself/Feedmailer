<?php 	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
	$gpgauth = new gpgauth();
?>
<?php pagenav("GPG Auth"); ?>
<?php $template->sidebar(); ?>

<?php 

$keyid = $_POST[keyid];
if ($keyid):
	if ($gpgauth->keyIDExists($keyid)){
		$crypt = $gpgauth->OneTimeKey($keyid);
		$crypt = ($crypt != false) ? $crypt : $gpgauth->gpg->error;
?>
<div id="sidebar_page">
	<div id="boxpadding">
		<?php echo "<pre>".$crypt."</pre>"; ?>
	</div>
</div>
<?php	} else { ?>
<div id="sidebar_page">
	<div id="boxpadding">
		Sorry. That KeyID does not exist in the database.
		Have you previously setup your GPG Key from the
		Settings menu?
	</div>
</div>
<?php } ?>
<?php else: ?>

<form action="<?php echo SITE_URL;?>gpglogin.php" method="post">
<div id="sidebar_page">
	<div id="boxpadding">
		<p class="smalltext">
			If you have previously setup your GPG Key in your Feedmailer account
			from the settings menu, you may enter your KeyID in the field below to
			generate a one-time key to login with.
		</p>
		<table id="form_data">
			<tr>
				<td><strong>KeyID:</strong></td>
				<td><input type="text" name="keyid" class="inputbox" value="" size="16" /></td>
			</tr>

			<tr>
				<td></td>
				<td><input type="checkbox" name="downloadcrypt" class="inputbox" value="true" /> Download file</td>
			</tr>
		</table>
		<table>
			<td>
				<p>
					<input type="submit" name="submit" value="Authenticate" class="submit"/>
				</p>
			</td>
		</table>
	</div>
</div>
</form>
<?php endif; ?>
