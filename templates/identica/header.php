<?php 	// Load the templates class
	$template = new templates(); 
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<title><?php echo SITE_TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL.TEMPLATE_PATH; ?>stylesheet.css" />
	</head>

	<body>
		<?php 
			// Load the navigation...
			$template->navigation(); 
		?>

		<div id="bodywrapper">
			<div id="header">
				<a href="<?php echo SITE_URL;?>"><img class="headerimg" src="<?php echo SITE_URL.TEMPLATE_PATH; ?>images/logo-alt.png" border="0" alt="<?php echo SITE_TITLE; ?> Logo" title="<?php echo SITE_TITLE; ?> Logo"/></a>
			</div>

<?php			// Display the alert for email activation.
			if (defined("ACTIVATION_LINK")):
				$template->alert(
					'Your new email address is still awaiting activation. Please
					check you email in order to activate it. You will not receive
					any Feedmailer Updates until your email address is activated.
					If you do not receive the email, '.ACTIVATION_LINK.' to resend it.');
			endif;
?>
