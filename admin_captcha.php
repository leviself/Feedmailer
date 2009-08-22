<?php
/**
 * Name:	admin_captcha.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Apr 23 18:31:41 EDT 2009
 **/

require_once "inc/main.php";

if ($cuser){
	if ($cauth == COOKIE_AUTH){
		if (IS_ADMIN == "true"){
			
			$font_name = $_POST[font_name];
			$font_size = $_POST[font_size];
			$color_background = $_POST[color_background];
			$color_text 	  = $_POST[color_text];
			$color_noise	  = $_POST[color_noise];
			$lines	= $_POST[lines];
			$dots	= $_POST[dots];
			$characters	= $_POST[characters];
			$height		= $_POST[height];
			$width		= $_POST[width];

			if (!$font_name && !$font_size && !$color_background && !$color_text
				&& !$color_noise && !$lines && !$dots && !$characters
				&& !$height && !$width){

				$template->header();
				$template->admin_captcha();
				$template->footer();
			} else {
				
				// If we do not meet the above conditions,
				// start applying these conditions.

				if ($font_name){
					if ($font_size){
						if ($color_background){
							if ($color_text){
								if ($color_noise){
									if ($lines){
										if ($dots){
											if ($characters){
												if ($height){
													if ($width){
														
														if ($font_name != $mysql->setting(captcha_font)){
															$mysql->setSetting("captcha_font", $font_name);
															$message .= "Setting captcha_font.... done<br />";
														}

														if ($font_size != $mysql->setting(captcha_fontsize)){
															$mysql->setSetting("captcha_fontsize", $font_size);
															$message .= "Setting captcha_fontsize.... done<br />";
														}

														if ($color_background != $mysql->setting(captcha_color_background)){
															$mysql->setSetting("captcha_color_background", $color_background);
															$message .= "Setting captcha_color_background.... done<br />";
														}
														
														if ($color_text != $mysql->setting(captcha_color_text)){
															$mysql->setSetting("captcha_color_text", $color_text);
															$message .= "Setting captcha_color_text.... done<br />";
														}

														if ($color_noise != $mysql->setting(captcha_color_noise)){
															$mysql->setSetting("captcha_color_noise", $color_noise);
															$message .= "Setting captcha_color_noise.... done<br />";
														}

														if ($lines != $mysql->setting(captcha_lines)){
															$mysql->setSetting("captcha_lines", $lines);
															$message .= "Setting captcha_lines.... done<br />";
														}

														if ($dots != $mysql->setting(captcha_dots)){
															$mysql->setSetting("captcha_dots", $dots);
															$message .= "Setting captcha_dots.... done<br />";
														}

														if ($characters != $mysql->setting(captcha_characters)){
															$mysql->setSetting("captcha_characters", $characters);
															$message .= "Setting captcha_characters.... done<br />";
														}

														if ($height != $mysql->setting(captcha_image_height)){
															$mysql->setSetting("captcha_image_height", $height);
															$message .= "Setting captcha_height.... done<br />";
														}

														if ($width != $mysql->setting(captcha_image_width)){
															$mysql->setSetting("captcha_image_width", $width);
															$message .= "Setting captcha_width.... done<br />";
														}

														header("Refresh: 3; url=admin_captcha.php");
														$template->header();
														$template->message($message.
														"Settings successfully updated.");
														$template->footer();

													} else {
														$template->header();
														$template->error("captcha_width can not be left blank.");
														$template->footer();
													}
												} else {
													$template->header();
													$template->error("captcha_height can not be left blank.");
													$template->footer();
												}
											} else {
												$template->header();
												$template->error("captcha_characters can not be left blank.");
												$template->footer();
											}
										} else {
											$template->header();
											$template->error("captcha_dots can not be left blank.");
											$template->footer();
										}
									} else {
										$template->header();
										$template->error("captcha_lines can not be left blank.");
										$template->footer();
									}
								} else {
									$template->header();
									$template->error("captcha_color_noise can not be left blank.");
									$template->footer();
								}
							} else {
								$template->header();
								$template->error("captcha_color_text can not be left blank.");
								$template->footer();
							}
						} else {
							$template->header();
							$template->error("captcha_color_background can not be left blank.");
							$template->footer();
						}
					} else {
						$template->header();
						$template->error("captcha_fontsize can not be left blank.");
						$template->footer();
					}
				} else {
					$template->header();
					$template->error("captcha_font can not be left blank.");
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
