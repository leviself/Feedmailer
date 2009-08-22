<?php
/**
 * Name:	install.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 16:10:27 EDT 2009
 **/

// !!!! Before EVER running this file, _PLEASE_ edit inc/config.php !!!! //

/**	*	*	*	*	*	*	*	*
 *	Admin User Settings					*
 *	PLEASE CHANGE THESE VALUES				*/
	$username = "drsmall";
	$password = md5("password");
	$email    = "drsmall@mycroftserver.homelinux.org";
/*	*	*	*	*	*	*	*	*/


require_once dirname(__FILE__)."/class.db.php";
$mysql->connect();

// Create the tables/rows in the database.
mysql_query(
"CREATE TABLE `{$mysql->prefix}users`
(id INT, username TEXT, password TEXT, email TEXT, last_login INT,
last_ip TEXT, cookie_auth TEXT, cookie_expire TEXT, reg_ip TEXT, 
mail_type TEXT, email_count INT, vacation VARCHAR(3), activation_key TEXT,
email_key TEXT, is_admin TEXT)");

mysql_query(
"CREATE TABLE `{$mysql->prefix}feeds`
(id INT, uid INT, url TEXT, title TEXT)");

mysql_query(
"CREATE TABLE `{$mysql->prefix}cache`
(id INT, fid INT, pubdate TEXT)");

mysql_query(
"CREATE TABLE `{$mysql->prefix}settings`
(id TEXT, value TEXT)");


// Add user to database.
// User is_admin = true
$mysql->addUser($username, $password, $email, $key=null, $admin=true);

// Default settings that get inserted into the settings table at
// installation. These settings can be changed, but it is recommended
// that they are not.

// Site settings.
$mysql->addSetting("site_title", "Feedmailer");
$mysql->addSetting("site_url", "http://192.168.0.70/~drsmall/testing/feedmailer2.0/"); // With trailing slash.
$mysql->addSetting("site_template", "default");
$mysql->addSetting("register_enable", "true");
$mysql->addSetting("cookie_name", "feedmailer");
$mysql->addSetting("email_count", "0");

// Captcha Settings
$mysql->addSetting("captcha_font", "FreeMono.ttf");
$mysql->addSetting("captcha_fontsize", "26");
$mysql->addSetting("captcha_color_background", "255, 255, 255");
$mysql->addSetting("captcha_color_text", "120, 183, 236");
$mysql->addSetting("captcha_color_noise", "50, 255, 50");
$mysql->addSetting("captcha_lines", "50");
$mysql->addSetting("captcha_dots", "100");
$mysql->addSetting("captcha_characters", "7");
$mysql->addSetting("captcha_image_height", "40");
$mysql->addSetting("captcha_image_width", "275");

// Popular Feeds Settings
$mysql->addSetting("pop_enable", "false");
$mysql->addSetting("pop_limit", "5");
$mysql->addSetting("pop_orderby", "DESC");
$mysql->addSetting("pop_howpopular", "5");
$mysql->addSetting("pop_display", "extended");

// Contact Settings
$mysql->addSetting("contact_enable", "true");
$mysql->addSetting("contact_who", "alladmins");

// Debug Settings
$mysql->addSetting("debug_enable", "false");
$mysql->addSetting("debug_max_ttl", "86400");
$mysql->addSetting("debug_limit", "50");
$mysql->addSetting("debug_orderby", "DESC");

// GPG Settings (all GPG settings are defaulted to off,
// because it may take some configuration to get it to work.)
$mysql->addSetting("gpg_enable", "false");
$mysql->addSetting("gpg_homedir", dirname(__FILE__)."/.gnupg");
$mysql->addSetting("gpg_execpath", "/usr/bin/gpg");


// Create the gpg home directory.
mkdir(dirname(__FILE__)."/.gnupg");


$mysql->disconnect();

?>
