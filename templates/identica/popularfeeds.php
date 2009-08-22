<?php	// Load the feed class
	$feed = new feeds();

	// Load the MySQL Connection
	$mysql = new db();
	$mysql->connect();

	if ($mysql->setting(pop_enable) == "true"):
		
?>

			<div id="popularfeeds">
				<p>
					<h4>Popular Feeds</h4>
					<?php $feed->showPopularFeeds(); ?>
					<br />
				</p>
			</div>

<?php endif; ?>
