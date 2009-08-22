<?php
/**
 * Name:	debug.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Fri May  1 20:25:04 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			if ($mysql->setting(debug_enable) == "true"){
			
				$term = $_GET[term];
				$orderby = $_GET[orderby];
				$results   = $_GET[results];
				
				$db_orderby = $mysql->setting(debug_orderby);
				$db_results   = $mysql->setting(debug_limit);

				// If no page settings are being used, use defaults.
				if (!$term && !$orderby && !$results){

					$template->header();
					$template->debug();
					$template->footer();

				} else {

					if ($orderby != $db_orderby){
						$mysql->setSetting("debug_orderby", $orderby);
					}

					if ($results != $db_results){
						$mysql->setSetting("debug_limit", $results);
					}

					$template->header();
					$template->debug();
					$template->footer();
				}
			} else {
				$template->header();
				$template->error("Debug mode is not enabled");
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
