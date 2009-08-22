			<form action="openid.php" method="post" onsubmit="this.login.disabled=true;">
			<div id="login">
				<div id="boxpadding">
					<p>
						OpenID Username:
					</p>
					<p>
						<input type="hidden" name="openid_action" value="login" />
						<input type="text" name="openid_url" id="openid_inputbox" value="" size="16"/>
					</p>
					<table>
						<td>
							<p class="submit">
								<input type="submit" name="clusterauth_submit" value="Authenticate" class="submit"/>
							</p>
						</td>
					</table>
				</div>
			</div>
			</form>


