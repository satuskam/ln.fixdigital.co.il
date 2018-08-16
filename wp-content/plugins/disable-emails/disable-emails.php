<?php
/*
Plugin Name: Disable Emails
Plugin URI: https://shop.webaware.com.au/downloads/disable-emails/
Description: Stop WordPress from sending any emails. ANY!
Version: 1.3.0
Author: WebAware
Author URI: https://shop.webaware.com.au/
Text Domain: disable-emails
Domain Path: /languages/
*/

/*
copyright (c) 2014-2016 WebAware Pty Ltd (email : support@webaware.com.au)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (!defined('ABSPATH')) {
	exit;
}

$disable_urls[] = 'www.seaspa.co.il';
$disable_urls[] = 'seaspa.co.il';

$disable_urls[] = 'www.caa.org.il';
$disable_urls[] = 'caa.org.il';
$disable_urls[] = 'caa1.ln.fixdigital.co.il	';

$disable_urls[] = 'www.jump-park.co.il';
$disable_urls[] = 'jump-park.co.il';
$disable_urls[] = 'jump-park.ln.fixdigital.co.il';
$disable_urls[] = 'www.jump-park.ln.fixdigital.co.il';

define('DISABLE_EMAILS_PLUGIN_FILE', __FILE__);
define('DISABLE_EMAILS_PLUGIN_ROOT', dirname(__FILE__) . '/');
define('DISABLE_EMAILS_PLUGIN_NAME', basename(dirname(__FILE__)) . '/' . basename(__FILE__));

// options
define('DISABLE_EMAILS_OPTIONS', 'disable_emails');

include DISABLE_EMAILS_PLUGIN_ROOT . 'includes/class.DisableEmailsPlugin.php';
DisableEmailsPlugin::getInstance();


// replace standard WordPress wp_mail() if nobody else has already done it
if (!function_exists('wp_mail') && !in_array($_SERVER['HTTP_HOST'], $disable_urls)) {

	function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
		// create mock PHPMailer object to handle any filter and action hook listeners
		$mailer = new DisableEmailsPHPMailerMock();
		return $mailer->wpmail($to, $subject, $message, $headers, $attachments);
	}

	DisableEmailsPlugin::setActive();

}
