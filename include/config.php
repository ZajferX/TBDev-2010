<?php
/*
+------------------------------------------------
|   TBDev.net BitTorrent Tracker PHP
|   =============================================
|   by CoLdFuSiOn
|   (c) 2003 - 2011 TBDev.Net
|   http://www.tbdev.net
|   =============================================
|   svn: http://sourceforge.net/projects/tbdevnet/
|   Licence Info: GPL
+------------------------------------------------
|   $Date$
|   $Revision$
|   $Author$
|   $URL$
+------------------------------------------------
*/


$TBDEV['time_adjust'] =  0;
$TBDEV['time_offset'] = '0';
$TBDEV['time_use_relative'] = 1;
$TBDEV['time_use_relative_format'] = '{--}, h:i A';
$TBDEV['time_joined'] = 'j-F y';
$TBDEV['time_short'] = 'jS F Y - h:i A';
$TBDEV['time_long'] = 'M j Y, h:i A';
$TBDEV['time_tiny'] = '';
$TBDEV['time_date'] = '';


// DB setup
$TBDEV['mysql_host'] = "localhost";
$TBDEV['mysql_user'] = "root";
$TBDEV['mysql_pass'] = "password";
$TBDEV['mysql_db']   = "tbdev2010";

// Cookie setup
$TBDEV['cookie_prefix']  = '_tbdev'; // This allows you to have multiple trackers, eg for demos, testing etc.
$TBDEV['cookie_path']    = ''; // ATTENTION: You should never need this unless the above applies eg: /tbdev
$TBDEV['cookie_domain']  = ''; // set to eg: .somedomain.com or is subdomain set to: .sub.somedomain.com
$TBDEV['IPcookieCheck'] = 1;

$TBDEV['site_online'] = 1;
$TBDEV['tracker_post_key'] = '<#tracker_post_key#>';
$TBDEV['tracker_cache_key'] = '<#tracker_cache_key#>';
$TBDEV['max_torrent_size'] = 1000000;
$TBDEV['announce_interval'] = 60 * 30;
$TBDEV['signup_timeout'] = 86400 * 3;
$TBDEV['minvotes'] = 1;
$TBDEV['max_dead_torrent_time'] = 6 * 3600;

// Max users on site
$TBDEV['maxusers'] = 5000; // LoL Who we kiddin' here?



$TBDEV['torrent_dir'] = ROOT_PATH . '/torrents'; # must be writable for httpd user

# the first one will be displayed on the pages
$TBDEV['announce_urls'] = array();
$TBDEV['announce_urls'][] = "http://localhost/announce.php";

$TBDEV['baseurl'] = "http://localhost";


// Email for sender/return path.
$TBDEV['site_email'] = "no@email.me";

$TBDEV['site_name'] = "Gabberbite - TBDev 2010 Heavy Beta Test";

$TBDEV['language'] = 'en';
//charset
$TBDEV['char_set'] = 'UTF-8'; //also to be used site wide in meta tags

$TBDEV['msg_alert'] = 0; // saves a query when off
$TBDEV['captcha'] = 0; // turns captcha on/off

$TBDEV['autoclean_interval'] = 900;
$TBDEV['sql_error_log'] = ROOT_PATH.'/logs/sql_err_'.date("M_D_Y").'.log';
$TBDEV['pic_base_url'] = "templates/1/images/";
$TBDEV['stylesheet'] = "1";

$TBDEV['forums_online'] = 1;
$TBDEV['forums_autoshout_on'] = 0;
$TBDEV['forums_seedbonus_on'] = 0;
$TBDEV['readpost_expiry'] = 14*86400; // 14 days
$TBDEV['allow_images'] = 1;
$TBDEV['max_images'] = 3;

//set this to size of user avatars
$TBDEV['av_img_height'] = 100;
$TBDEV['av_img_width'] = 100;
$TBDEV['allowed_ext'] = array('image/gif', 'image/png', 'image/jpeg');

$TBDEV['version'] = 'TBDev.Heavy.beta.v1.0';
?>