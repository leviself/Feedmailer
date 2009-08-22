<?php
/**
 * Name: 	class.templates.php
 * Group:	Simple Login
 * Author:	Dr Small <drsmall@mycroftserver.homelinux.org>
 * Date:	Thu Mar 26 12:20:29 EDT 2009
 **/


// This file will include a boatload of defined objects,
// so things can be used in templates, and throughout other files.


// Some settings to load whether or not a user is logged in.
$template	=	$mysql->setting(site_template);
$register_enable=	$mysql->setting(register_enable);
$siteurl 	=	$mysql->setting(site_url);
$cookie_name 	=	$mysql->setting(cookie_name);
$sitetitle 	=	$mysql->setting(site_title);
$gpgenable	=	$mysql->setting(gpg_enable);

define("TEMPLATE_PATH",	"templates/{$template}/");
define("REGISTER_ENABLE",$register_enable);
define("GPG_ENABLE",	$gpgenable);
define("SITE_TITLE",	$sitetitle);
define("SITE_URL",	$siteurl);
define("COOKIE_NAME",	$cookie_name);
define("DB_PREFIX",	$mysql->prefix);


// Some defined objects that we will only be defined if the user is logged in.
if ($_COOKIE[COOKIE_NAME."_user"]){
	// Convert the Username to a userid
	// This varibale can be used anywhere in place of $id.
	$id = $mysql->usernameToID($_COOKIE[COOKIE_NAME."_user"]);

	// Count how many feeds the user has.
	$userfeedcount = mysql_result(mysql_query("SELECT COUNT(id) FROM `{$mysql->prefix}feeds` WHERE uid='$id'"),0);

	// Select the user's data from the cookie.
	// INFO: Cookie data can not be spoofed, as we use cookie_auth to verify.
	//
	// This $user variable can be used globally to referrence $user[data];
	$user = $mysql->selectUser($id);

	define("USERID",	$user[id]);
	define("USER", 		$user[username]);
	define("EMAIL", 	$user[email]);
	define("COOKIE_EXPIRE",	$user[cookie_expire]);
	define("COOKIE_AUTH",	$user[cookie_auth]);
	define("LAST_IP",	$user[last_ip]);
	define("LAST_LOGIN",	date("m/d/Y h:i:s a", $user[last_login]));
	define("MAIL_TYPE",	$user[mail_type]);
	define("USER_EMAIL_COUNT", $user[email_count]);
	define("VACATION",	$user[vacation]);
	define("IS_ADMIN",	$user[is_admin]);
	define("FEED_COUNT",	$userfeedcount);
	define("SMART_TIME",	$auth->smartTime($user[last_login]));


	// Some things to define if the user is an administrator
	if (IS_ADMIN == "true"){

		$email_count 	=	$mysql->setting(email_count);
		$cache_count	=	@mysql_result(mysql_query(
					"SELECT COUNT(*) FROM `{$mysql->prefix}cache`"),0);
		$feed_count	=	@mysql_result(mysql_query(
					"SELECT COUNT(*) FROM `{$mysql->prefix}feeds`"),0);
		$count_registered_users = mysql_result(mysql_query(
					"SELECT COUNT(id) FROM `{$mysql->prefix}users`"),0);

		define("EMAIL_COUNT",	$email_count);
		define("CACHE_COUNT",	$cache_count);
		define("FEED_COUNT",	$feed_count);
		define("COUNT_REG",	$count_registered_users);
		define("DEBUG_ENABLE",	$mysql->setting(debug_enable));
	}

	// Resend Email Activation Link
	define("EMAIL_KEY",	$user[email_key]);
	if (EMAIL_KEY != NULL) {
		define("ACTIVATION_LINK", '<a href="resend.php?email='.EMAIL.'">click here</a>');
	}

		
}

/**
 * Name:	Template Functions
 * Description:	Contains all the template functions.
 * Usage:	$template = new templates();
 **/
class templates{

	/**
	 * Name:	Header Template
	 * Description:	Load the header template.
	 * Usage:	$template->header();
	 **/
	 public function header(){
	 	include TEMPLATE_PATH."header.php";
	}

	/**
	 * Name:	Footer Template
	 * Description:	Load the footer template.
	 * Usage:	$template->footer();
	 **/
	public function footer(){
		include TEMPLATE_PATH."footer.php";
	}

	/**
	 * Name:	Index Template
	 * Description:	Load the index template.
	 * Usage:	$template->index();
	 **/
	public function index(){
		include TEMPLATE_PATH."index.php";
	}

	/**
	 * Name:	Login Template
	 * Description:	Load the login template.
	 * Usage:	$template->login();
	 **/
	public function login(){
		include TEMPLATE_PATH."login.php";
	}

	/**
	 * Name:	OpenID Template
	 * Description:	Load the OpenID template.
	 * Usage:	$template->openid();
	 **/
	public function openid(){
		include TEMPLATE_PATH."openid.php";
	}


	/**
	 * Name:	Sidebar
	 * Description:	Load the dynamic sidebar template.
	 * Usage:	$template->sidebar();
	 **/
	public function sidebar(){
		include TEMPLATE_PATH."sidebar.php";
	}

	/**
	 * Name:	Reset Password Template
	 * Description:	Load the reset password template.
	 * Usage:	$template->resetpassword();
	 **/
	public function  resetpassword(){
		include TEMPLATE_PATH."resetpassword.php";
	}

	/**
	 * Name:	Register Template
	 * Description:	Load the register template.
	 * Usage:	$template->register();
	 **/
	public function register(){
		include TEMPLATE_PATH."register.php";
	}

	/**
	 * Name:	Settings Template
	 * Description:	Load the settings template.
	 * Usage:	$template->settings();
	 **/
	public function settings(){
		include TEMPLATE_PATH."settings.php";
	}

	/**
	 * Name:	Add Feed Template
	 * Description:	Load the Add Feed Template
	 * Usage:	$template->addfeed();
	 **/
	public function addfeed(){
		include TEMPLATE_PATH."addfeed.php";
	}

	/**
	 * Name:	Delete Feeds Template
	 * Description:	Load the Delete Feeds Template
	 * Usage:	$template->delfeed();
	 **/
	public function delfeeds(){
		include TEMPLATE_PATH."delfeeds.php";
	}

	/**
	 * Name:	Edit Feed Template
	 * Description:	Load the Edit Feed Template
	 * Usage:	$template->editfeed();
	 **/
	public function editfeed(){
		include TEMPLATE_PATH."editfeed.php";
	}

	/**
	 * Name:	Backup Template
	 * Description:	Load the Backup Template
	 * Usage:	$template->backup();
	 **/
	public function backup(){
		include TEMPLATE_PATH."backup.php";
	}

	/**
	 * Name:	Mail Type Template
	 * Description:	Load the Mail Type Template
	 * Usage:	$template->mailtype();
	 **/
	public function mailtype(){
		include TEMPLATE_PATH."mailtype.php";
	}

	/**
	 * Name:	Vacation Template
	 * Description:	Load the Vacation Template
	 * Usage:	$template->vacation();
	 **/
	public function vacation(){
		include TEMPLATE_PATH."vacation.php";
	}

	/**
	 * Name:	Contact Template
	 * Description:	Load the contact Template
	 * Usage:	$template->contact();
	 **/
	public function contact(){
		include TEMPLATE_PATH."contact.php";
	}

	/**
	 * Name:	GPG Auth Template
	 * Description:	Load the GPG Auth Template
	 * Usage:	$template->gpgauth();
	 **/
	public function gpgauth(){
		include TEMPLATE_PATH."gpgauth.php";
	}

	/**
	 * Name:	GPG Login Template
	 * Description:	Load the GPG Login Template
	 * Usage:	$template->gpglogin();
	 **/
	public function gpglogin(){
		include TEMPLATE_PATH."gpglogin.php";
	}

	/**
	 * Name:	Admin Template
	 * Description:	Load the admin template.
	 * Usage:	$template->admin_index();
	 **/
	public function admin_index(){
		include TEMPLATE_PATH."admin.php";
	}

	/**
	 * Name:	Admin AddUser Template
	 * Description:	Load the admin_adduser template.
	 * Usage:	$template->admin_adduser();
	 **/
	public function admin_adduser(){
		include TEMPLATE_PATH."admin_adduser.php";
	}

	/**
	 * Name:	Admin EditUser Template
	 * Description:	Load the admin_edituser template.
	 * Usage:	$template->admin_adduser();
	 **/
	public function admin_edituser(){
		include TEMPLATE_PATH."admin_edituser.php";
	}

	/**
	 * Name:	Admin Mass Email Template
	 * Description:	Load the admin_massemail template.
	 * Usage:	$template->admin_massemail();
	 **/
	public function admin_massemail(){
		include TEMPLATE_PATH."admin_massemail.php";
	}

	/**
	 * Name:	Admin Delete Users Template
	 * Description:	Load the admin_delusers template.
	 * Usage:	$template->admin_delusers();
	 **/
	public function admin_delusers(){
		include TEMPLATE_PATH."admin_delusers.php";
	}

	/**
	 * Name:	Admin Settings Template
	 * Description:	Load the admin_settings template.
	 * Usage:	$template->admin_settings();
	 **/
	public function admin_settings(){
		include TEMPLATE_PATH."admin_settings.php";
	}

	/**
	 * Name:	Admin Captcha Template
	 * Description:	Load the admin_captcha template.
	 * Usage:	$template->admin_captcha();
	 **/
	public function admin_captcha(){
		include TEMPLATE_PATH."admin_captcha.php";
	}

	/**
	 * Name:	Admin Add Feed Template
	 * Description:	Load the admin_addfeed template.
	 * Usage:	$template->admin_addfeed();
	 **/
	public function admin_addfeed(){
		include TEMPLATE_PATH."admin_addfeed.php";
	}

	/**
	 * Name:	Admin Delete Feeds Template
	 * Description:	Load the admin_delfeeds template.
	 * Usage:	$template->admin_delfeeds();
	 **/
	public function admin_delfeeds(){
		include TEMPLATE_PATH."admin_delfeeds.php";
	}

	/**
	 * Name:	Admin Popular Feeds Settings Template
	 * Description:	Load the admin_popfeeds_settings template.
	 * Usage:	$template->admin_popfeeds_settings();
	 **/
	public function admin_popfeeds_settings(){
		include TEMPLATE_PATH."admin_popfeeds_settings.php";
	}

	/**
	 * Name:	Admin GPG Auth Settings Template
	 * Description:	Load the admin_gpgauth_settings template.
	 * Usage:	$template->admin_gpgauth_settings();
	 **/
	public function admin_gpgauth_settings(){
		include TEMPLATE_PATH."admin_gpgauth_settings.php";
	}

	/**
	 * Name:	Admin Contact Settings Template
	 * Description:	Load the admin_contact_settings template.
	 * Usage:	$template->admin_contact_settings();
	 **/
	public function admin_contact_settings(){
		include TEMPLATE_PATH."admin_contact_settings.php";
	}

	/**
	 * Name:	Admin Edit Feed Template
	 * Description:	Load the admin_editfeed template.
	 * Usage:	$template->admin_editfeed();
	 **/
	public function admin_editfeed(){
		include TEMPLATE_PATH."admin_editfeed.php";
	}

	/**
	 * Name:	Admin Debug Settings Template
	 * Description:	Load the admin_debug_settings template.
	 * Usage:	$template->admin_debug_settings();
	 **/
	public function admin_debug_settings(){
		include TEMPLATE_PATH."admin_debug_settings.php";
	}

	/**
	 * Name:	Debug Template
	 * Description:	Load the debug template.
	 * Usage:	$template->debug();
	 **/
	public function debug(){
		include TEMPLATE_PATH."debug.php";
	}

	/**
	 * Name:	Erorr Template
	 * Description:	Display error message based on input.
	 * Usage:	$template->error($message);
	 **/
	 public function error($message){
	 	define("ERROR_MSG",	$message);
		include TEMPLATE_PATH."error.php";
	}

	/**
	 * Name:	Message Template
	 * Description:	Display message based on input.
	 * Usage:	$template->message($message);
	 **/
	 public function message($message){
	 	define("MSG",		$message);
		include TEMPLATE_PATH."message.php";
	}

	/**
	 * Name:	Alert Template
	 * Description:	Display an alert messaged based on input.
	 * Usage:	$template->alert($message);
	 **/
	public function alert($message){
	 	define("ALERT",		$message);
		include TEMPLATE_PATH."alert.php";
	}

	/**
	 * Name:	Page Template
	 * Description:	Display page based on input.
	 * Usage:	$template->page($message);
	 **/
	public function page($message){
		define("PAGE",		$message);
		include TEMPLATE_PATH."page.php";
	}

	/**
	 * Name:	Sidebar Page
	 * Description: Displays a page that is compatible with the sidebar
	 		template. If a page has a sidebar, use this template.
	 * Usage:	$template->sidebar_page($message);
	 **/
	public function sidebar_page($message){
		define("SIDEBAR_PAGE",	$message);
		include TEMPLATE_PATH."sidebar_page.php";
	}

	/**
	 * Name:	Easter Egg Template
	 * Usage:	$template->easteregg();
	 **/
	public function easteregg(){
		echo $this->page
		('5. And the <em><strong>fire</strong></em> was great and good to it\'s followers<br />
		in the land. Whatsoever they asked, that it did give them.<br /><br />

		6. Then arose a giant, a man of the mountains, and did take<br />
		the <em><strong>fire</strong></em> to his own good; Some of the followers were wroth<br />
		with the giant and did take tools to build again the <em><strong>fire</strong></em>,<br />
		yet better and <em>open</em>.
		<p style="text-align:right; font-size:xx-large;">&#8212;The Book of Mycroft</p>');
	}

	/**
	 * Name:	Navigation
	 * Description:	Load the navigation template.
	 * Usage:	$template->navigation();
	 **/
	 public function navigation(){
		include TEMPLATE_PATH."navigation.php";
	}

	/**
	 * Name:	Show Popular Feeds Template
	 * Description:	Load the template
	 * Usage:	$template->popularfeeds();
	 **/
	 public function popularfeeds(){
		include TEMPLATE_PATH."popularfeeds.php";
	 }

	/**
	 * Name:	Show Select box Theme Changer.
	 * Usage:	$template->themeswitcher();
	 **/
	public function themeswitcher(){
		$templatedir = dirname(__FILE__)."/../templates/";
		$templates = array_diff(scandir($templatedir), array('.', '..'));

		$mysql = new db();
		$mysql->connect();

		echo '<select name="template" class="inputbox">';
		foreach ($templates as $template){
			echo '<option value="'.$template.'"';
			if ($mysql->setting(site_template) == $template){
				echo 'selected="selected" ';
			}
			echo '>'.$template.'</option>';
		}
		echo '</select>';
	}

}

// While we're here, lets go ahead and set the $template variable.
$template = new templates();

?>
