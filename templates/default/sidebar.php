<?php	// The sidebar is semi-complicated, as it shows different things for
	// different users. Example, Admins see more stats than users.
	if (IS_ADMIN == "true"){
?>

				<div id="stats">
					<div id="boxpadding">
						<p>
							Username:<br />
							<strong><?php echo USER; ?></strong>
						</p>

						<p>
							Last IP:<br />
							<strong><?php echo LAST_IP; ?></strong>
						</p>

						<p>
							Last Visit:<br />
							<strong><abbr title="<?php echo LAST_LOGIN; ?>"><?php echo SMART_TIME; ?></abbr></strong>
						</p>

						<p>
							Subscriptions:<br />
							<strong><?php echo FEED_COUNT; ?></strong>
						</p>

						<p>
							Notifications:<br />
							<strong><?php echo USER_EMAIL_COUNT; ?></strong>
						</p>

						<p>
							Registered Users:<br />
							<strong><?php echo COUNT_REG; ?></strong>
						</p>
					</div>
				</div>
<?php	// Load sidebar for user.
	} elseif ($_COOKIE[COOKIE_NAME."_user"]){
?>
				<div id="stats">
					<div id="boxpadding">
						<p>
							Username:<br />
							<strong><?php echo USER; ?></strong>
						</p>

						<p>
							Last IP:<br />
							<strong><?php echo LAST_IP; ?></strong>
						</p>

						<p>
							Last Visit:<br />
							<strong><abbr title="<?php echo LAST_LOGIN; ?>"><?php echo SMART_TIME; ?></abbr></strong>
						</p>
						<p>
							Subscriptions:<br />
							<strong><?php echo FEED_COUNT; ?></strong>
						</p>
						<p>
							Notifications:<br />
							<strong><?php echo USER_EMAIL_COUNT; ?></strong>
						</p>
					</div>
				</div>
<?php	// Close the statement.
	}
?>
