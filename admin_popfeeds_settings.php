<?php
/**
 * Name:	admin_popfeeds_settings.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Sat Apr 25 20:36:58 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			
			$enable = $_POST[enable];
			$limit  = $_POST[limit];
			$orderby= $_POST[orderby];
			$howpopular = $_POST[howpopular];
			$display= $_POST[display];

			if (!$enable && !$limit && !$orderby && !$howpopular && !$display){
				$template->header();
				$template->admin_popfeeds_settings();
				$template->footer();
			} else {
				// If we do not meet the above conditions,
				// start applying these conditions.

				if ($enable){
					if ($limit){
						if ($orderby){
							if ($howpopular){
								if ($display){
								
									if ($enable != $mysql->setting(pop_enable)){
										$mysql->setSetting("pop_enable", $enable);
										$message .= "Setting pop_enable.... done<br />";
									}
	
									if ($limit != $mysql->setting(pop_limit)){
										$mysql->setSetting("pop_limit", $limit);
										$message .= "Setting pop_limit.... done<br />";
									}
	
									if ($orderby != $mysql->setting(pop_orderby)){
										$mysql->setSetting("pop_orderby", $orderby);
										$message .= "Setting pop_orderby.... done<br />";
									}

									if ($howpopular != $mysql->setting(pop_howpopular)){
										$mysql->setSetting("pop_howpopular", $howpopular);
										$message .= "Setting pop_howpopular.... done<br />";
									}

									if ($display != $mysql->setting(pop_display)){
										$mysql->setSetting("pop_display", $display);
										$message .= "Setting pop_display.... done<br />";
									}

									header("Refresh: 3; url=admin.php");
									$template->header();
									$template->message($message.
									"Settings have been successfully updated.");
									$template->footer();
								} else {
									$template->header();
									$template->error("pop_display can not be left blank.");
									$template->footer();
								}
							} else {
								$template->header();
								$template->error("pop_howpopular can not be left blank.");
								$template->footer();
							}
						} else {
							$template->header();
							$template->error("pop_orderby can not be left blank.");
							$template->footer();
						}
					} else {
						$template->header();
						$template->error("pop_limit can not be left blank.");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("pop_enable can not be left blank.");
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
