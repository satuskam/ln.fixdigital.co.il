<?php
define('DB_NAME', $_SERVER['RDS_DB_NAME']);
define('DB_USER', $_SERVER['RDS_USERNAME']);
define('DB_PASSWORD', $_SERVER['RDS_PASSWORD']);
define('DB_HOST', $_SERVER['RDS_HOSTNAME'] . ':' . $_SERVER['RDS_PORT']);
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');    
/*
define('AUTH_KEY',         $_SERVER['AUTH_KEY']);
define('SECURE_AUTH_KEY',  $_SERVER['SECURE_AUTH_KEY']);
define('LOGGED_IN_KEY',    $_SERVER['LOGGED_IN_KEY']);
define('NONCE_KEY',        $_SERVER['NONCE_KEY']);
define('AUTH_SALT',        $_SERVER['AUTH_SALT']);
define('SECURE_AUTH_SALT', $_SERVER['SECURE_AUTH_SALT']);
define('LOGGED_IN_SALT',   $_SERVER['LOGGED_IN_SALT']);
define('NONCE_SALT',       $_SERVER['NONCE_SALT']);
*/

define( 'AUTH_KEY', '|mX_8iu9rB!;IoStV-lEGJ;-5!iPMA(CyqI:#Y3ls%6-VSUxY0h:WG-V]*_{|wYa' );
define( 'SECURE_AUTH_KEY', '5+#U(!t2&uyTx2d1;52bLAuul6c(GRMV&HWP1_> M/}M#_`y/dd7<j4.Sl`n5<9)' );
define( 'LOGGED_IN_KEY', '//3IG4L!v7|`m4j. VTG]j/Q&*?i8C]:,P-l>HXs}w[}^/QT!j/6r2<<$C6Ve]MB' );
define( 'NONCE_KEY', 'AHZ4T[O#y-9wVJD&o!;k|A[2(r)#-KNS|l?>+k+,?^<pi|?9)[:}Y[8u0K,p7QW.' );
define( 'AUTH_SALT', 'iG]n*Bj~eYf{vvf4j$c)TOUA`D8UT-ff3(0Oj^0(JIU&6l^Q]7,W80]Q+XiT>!&?' );
define( 'SECURE_AUTH_SALT', '`Z[PCQdW^i|tIcyIFVnmIkPy1M(>SzV]tL_,*-v:O^+:B5UMB$[f%S2@}X/c1guH' );
define( 'LOGGED_IN_SALT', 'F/wg2W9)Z)y-M2T8o4]t3wM,D:`P/j;<X3p)<MS1r:lwklLPk|u CzWe) %D{?Cm' );
define( 'NONCE_SALT', 'ZiBRn5D&NZ7Se0IPnD^fml%LU1$mCQfY~wnoKC4e_b%%@sq$$VLYT7#-l6s=W)_H' );

$table_prefix  = 'wp_';
define('WP_DEBUG', (bool) $_SERVER['WP_DEBUG']);

define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', $_SERVER['DOMAIN_CURRENT_SITE']);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);


// It's need to enable ssl supposrt for HTTPS requests to the mapped custom domains through CluoudFront
$sslHeaders = [
    'HTTPS' => 'on',
    'HTTP_X_FORWARDED_PROTO' => 'https',
    'HTTP_X_FORWARDED_SSL' => 'on',
    'HTTP_CLOUDFRONT_FORWARDED_PROTO' => 'https',
];
$isSecure = false;
foreach ($sslHeaders as $header => $value) {
    if (!empty($_SERVER[$header]) && $_SERVER[$header] == $value) {
        $_SERVER['HTTPS'] = 'on';
        break;
    }
}


//$REQUEST_PROTOCOL = $isSecure ? 'https://' : 'http://';
//define( 'WP_CONTENT_URL', $REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'] . '/wp-content');
//define( 'WP_HOME', $REQUEST_PROTOCOL.$_SERVER['HTTP_HOST'] );


define( 'SUNRISE', 'on' );

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
