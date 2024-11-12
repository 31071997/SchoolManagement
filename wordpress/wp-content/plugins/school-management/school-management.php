<?php
/*
Plugin Name: School Management
Plugin URI: https://mojoomla.com/
Description: School Management System Plugin for wordpress is ideal way to manage complete school operation. 
The system has different access rights for Admin, Teacher, Student and Parent.

Version: 91.5.0 (25-10-2024)	

Author: Mojoomla
Author URI: https://codecanyon.net/search/mojoomla
Text Domain: school-mgt
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2015  Mojoomla  (email : sales@mojoomla.com)
*/
define( 'SMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SMS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'SMS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'SMS_CONTENT_URL',  content_url( ));
require_once SMS_PLUGIN_DIR . '/settings.php';
if (isset($_REQUEST['page']))
{
	if($_REQUEST['page'] == 'callback')
	{
	   require_once SMS_PLUGIN_DIR. '/callback.php';
	}
	if($_REQUEST['page'] == 'notify')
    {
       require_once SMS_PLUGIN_DIR. '/notify.php';
    }
}
?>