<?php	// Load the templates class
	$template = new templates();

	$mysql = new db();
	$mysql->connect();
	include dirname(__FILE__)."/functions.php";
	include dirname(__FILE__)."/../../inc/class.gpgauth.php";
	$gpgauth = new gpgauth();
?>

				<?php pagenav("Settings"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<form action="<?php echo SITE_URL;?>actions.php" method="post">
				<div id="sidebar_page">
					<div id="boxpadding">						
						<p class="smalltext">
							The GPG One-time key authentication method is still expirimental
							and not throughly tested with every possible scenario. If you have
							a problem, please <a href="<?php echo SITE_URL;?>user/contact">send us a message</a>.
						</p>
				<?php if(!$gpgauth->keyExistsForUser(USERID)): ?>
						<table id="form_data">
							<tr>
								<td><strong>Public Key</strong></td>
								<td><textarea cols="15" rows="50" name="pubkey"></textarea></td>
								<tr>
									<td></td>
									<td>
										<p class="form_help">
											Your ASCII Armored Public Key											
										</p>
									</td>
								</tr>
							</tr>
						</table>
						<p>
							<input type="submit" class="submit" value="Add Key" />
						</p>
				<?php else:
						$data  = $gpgauth->keyDataByUID(USERID); ?>
				                <script type="text/javascript">
			        	                function checkUncheckAll(theElement) {
							var theForm = theElement.form, z = 0;
							for(z=0; z<theForm.length;z++){
								if(theForm[z].type == "checkbox" && theForm[z].name != "checkall"){
									theForm[z].checked = theElement.checked;
								}
							}
						}
						</script>
						<table>
							<tr>
								<td><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);" /></td>
								<td><strong>KeyID</strong></td>
							</tr>
							<tr>
								<td><input type="checkbox" name="del_keyid" value="<?php echo $data[keyid]; ?>" />
								<td><?php echo $data[keyid]; ?></td>
							</tr>
						</table>
						<p>
							<input type="submit" class="submit" value="Delete Key" />
						</p>
				<?php endif; ?>
					</div>
				</div>
				</form>
