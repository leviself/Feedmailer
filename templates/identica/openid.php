<?php 	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";
?>
<?php pagenav("OpenID"); ?>
<?php $template->sidebar(); ?>
<form action="<?php echo SITE_URL;?>openid.php" method="post">
<div id="sidebar_page">
	<div id="boxpadding">
		<p class="smalltext">
			Login with an <a href="http://openid.net">OpenID</a> account.
		<table id="form_data">
			<tr>
				<td><strong>OpenID URL</strong></td>
				<td><input type="text" name="openid_url" class="inputbox" value="" size="16" />
				<input type="hidden" name="openid_action" value="login" /></td>
				<tr>
					<td></td>
					<td>
						<p class="form_help">
							Your OpenID URL
						</p>
					</td>
				</tr>
			</tr>
		</table>

		<table>
			<td>
				<p>
					<input type="submit" name="submit" value="Login" class="submit"/>
				</p>
			</td>
		</table>
	</div>
</div>
</form>
