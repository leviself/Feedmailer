<?php
/**
 * Name:	backup.php
 * Group:	Feedmailer
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Sun May 17 10:26:28 EDT 2009
 **/

require_once "inc/main.php";
require_once "inc/class.backupfeeds.php";

if ($cuser){
	if($cauth == COOKIE_AUTH){
		$export = $_GET[export];
		$import = $_POST[import];

		if (($export) && ($export == "true")) {
			$filename = "feedmailer-backup_".date("m-d-Y").".xml";

			// Output the XML
			if($backup->export($id)){
				// Set the headers
				header("Content-Type: application/xhtml+xml");
				header("Content-Disposition: attachment; filename={$filename}");

				// $backup->export() sets the variable $XML. Return that to
				// get the XML output.
				echo $backup->XML;

				// Exit, else it will write the page output to the XML file too.
				exit;
			} else {
				$template->header();
				$template->error("You have no feeds to backup");
				$template->footer();
				die();
			}
				
		}
		
		if ($import){
			$path = "/tmp/";
			$path = $path.basename($_FILES[xml][name]);

			if (move_uploaded_file($_FILES[xml][tmp_name], $path)){
				$backup->import($id, $path);

				header("Refresh: 3; url=index.php");
				$template->header();
				$template->message(
				"Importing XML file complete!");
				$template->footer();

				// Remove the temporary file
				unlink($path);
			} else {
				$template->header();
				$template->error(
				"Feedmailer did not receive the upload request. Probably
				a browser error.");
				$template->footer();
			}
		} else {
			$template->header();
			$template->backup();
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

