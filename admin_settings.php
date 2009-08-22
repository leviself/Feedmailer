<?php
/**
 * Name:	admin_settings.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Apr 23 09:21:26 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){

			$sitetitle = $_POST[site_title];
			$siteurl = $_POST[site_url];
			$template_name = $_POST[template];
			$cookie_name = $_POST[cookie_name];
			$register_enable = $_POST[register_enable];

			// Show the settings page.
			if (!$sitetitle && !$siteurl && !$template_name && !$cookie_name && !$register_enable){
				$template->header();
				$template->admin_settings();
				$template->footer();
			} else {
				// If we do not meet the above conditions,
				// start applying these conditions.

				// Start the POST process.
				if ($sitetitle){
					if ($siteurl){
						if ($template_name){
							if ($cookie_name){

								if ($cookie_name != COOKIE_NAME){
									$mysql->setSetting("cookie_name", $cookie_name);
									$message .= "Updating cookie_name.... done<br />";
								}
	
								if ($template_name != $mysql->setting(site_template)){
									$mysql->setSetting("site_template", $template_name);
									$message .= "Updating template.... done<br />";
								}

								if ($siteurl != SITE_URL){
									$mysql->setSetting("site_url", $siteurl);
									$message .= "Updating site_url.... done<br />";
								}

								if ($sitetitle != SITE_TITLE){
									$mysql->setSetting("site_title", $sitetitle);
									$message .= "Updating site_title.... done<br />";
								}

								if ($register_enable != REGISTER_ENABLE){
									$mysql->setSetting("register_enable", $register_enable);
									$message .= "Updating register_enable.... done<br />";
								}

								header("Refresh: 3; url=admin_settings.php");
								$template->header();
								$template->message($message.
								"Settings updated successfuly.");
								$template->footer();

							} else {
								$template->header();
								$template->error("cookie_name can not be left blank.");
								$template->footer();
							}
						} else {
							$template->header();
							$template->error("template can not be left blank.");
							$template->footer();
						}
					} else {
						$template->header();
						$template->error("site_url can not be left blank.");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("site_title can not be left blank.");
					$template->footer();
				}
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
