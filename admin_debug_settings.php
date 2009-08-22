<?php
/**
 * Name:	admin_debug_settings.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri May  1 17:25:58 EDT 2009
 **/

require_once "inc/main.php";
require_once "inc/class.debug.php";
$debug = new debugger();

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){

			$enable = $_POST[enable];
			$max_ttl= $_POST[max_ttl];

			if (!$enable && !$max_ttl){
				$template->header();
				$template->admin_debug_settings();
				$template->footer();
			} else {
				if ($enable != $mysql->setting(debug_enable)){
					// Set the database setting
					$mysql->setSetting("debug_enable", $enable);
					$message .= "Updating debug_enable.... <br />";

					if ($enable == "true"){
						$debug->buildTable();
						$message .= "Building Table.... <br />";
					}

					if ($enable == "false"){
						$debug->destroyTable();
					$message .= "Destroying Table.... <br />";
					}
				}

				// two conditions. First, that the POST doesn't match the database,
				// and second, that the POST isn't blank. Then proceed.
				if (($max_ttl != $mysql->setting(debug_max_ttl)) && ($max_ttl != NULL)){
					// Set the setting
					$mysql->setSetting("debug_max_ttl", $max_ttl);
					$message .= "Updating debug_max_ttl.... <br />";
				}

				header("Refresh: 3; url=admin_debug_settings.php");
				$template->header();
				$template->message($message.
				"Settings updated successfully");
				$template->footer();
			}
		} else {
			$template->header();
			$template->error($error->accessdenied());
			$template->footer();
		}
	} else {
		$template->header();
		$template->error($error->auth());
		$template->footer();
	}
} else {
	$template->header();
	$template->error($error->snooper());
	$template->footer();
}

?>
