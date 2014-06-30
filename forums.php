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
|   Author: CoLdFuSiOn Alex2005
|   $URL$
+------------------------------------------------
*/
	define('IN_TBDEV_FORUM', TRUE);

  require_once "include/bittorrent.php";
  require_once "include/user_functions.php";
  require_once "include/html_functions.php";
  //require_once "include/bbcode_functions.php";
  //require_once "forums/forum_functions.php";
  //require_once("forums/mood.php");

  dbconn(false);

  loggedinorreturn();
  
  $lang = array_merge( load_language('global'), load_language('forums') );


  if ($TBDEV['forums_online'] == 0 AND $CURUSER['class'] < UC_MODERATOR)
  stderr('Information', 'The forums are currently offline for maintainance work');
  
  $TBDEV['last_10_posts'] = false; // shows last ten posts on post screens true=on, false=off
  
//if (function_exists('parked'))
//parked();
/**
* Configs Start
*/
/**
* The max class, ie: UC_CODER
*
* Is able to delete, edit the forum etc...
*/
define('MAX_CLASS', UC_SYSOP);
/**
* The max file size allowed to be uploaded
*
* Default: 1024*1024 = 1MB
*/
$maxfilesize = 40096 * 1024;
/**
* Set's the max file size in php.ini, no need to change
*/
ini_set("upload_max_filesize", $maxfilesize);
/**
* Set's the root path, change only if you know what you are doing
*/
// define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');
/**
* The path to the attachment dir, no slahses
*/
$attachment_dir = ROOT_PATH . "forum_attachments";
//$attachment_dir = ROOT_DIR . "forum_attachments";
/**
* The width of the forum, in percent, 100% is the full width
*
* Note: the width is also set in the function begin_main_frame()
*/
$forum_width = '100%';
/**
* The extensions that are allowed to be uploaded by the users
*
* Note: you need to have the pics in the $pic_base_url folder, ie zip.gif, rar.gif
*/
$allowed_file_extensions = array('rar', 'zip');
/**
* The max subject lenght in the topic descriptions, forum name etc...
*/
$maxsubjectlength = 80;
/**
* Get's the users posts per page, no need to change
*/
$postsperpage = (empty($CURUSER['postsperpage']) ? 25 : (int)$CURUSER['postsperpage']);
/**
* Set to true if you want to use the flood mod
*/
$use_flood_mod = true;
/**
* If there are more than $limit(default 10) posts in the last $minutes(default 5) minutes, it will give them a error...
*
* Requires the flood mod set to true
*/
$minutes = 5;
$limit = 10;
/**
* Set to true if you want to use the attachment mod
*
* Requires 2 extra tables(attachments, attachmentdownloads), so efore enabling it, make sure you have them...
*/
$use_attachment_mod = true;
/**
* Set to true if you want to use the forum poll mod
*
* Requires 2 extra tables(postpolls, postpollanswers), so efore enabling it, make sure you have them...
*/
$use_poll_mod = true;
/**
* Set to false to disable the forum stats
*/
$use_forum_stats_mod = true;
/**
* Define htmlout and javascripts
*/
$HTMLOUT='';

/**
* Define image url
*/
$TBDEV['forum_pic_url'] = $CURUSER['stylesheet']? "{$TBDEV['baseurl']}/templates/{$CURUSER['stylesheet']}/images/forums/" :"{$TBDEV['baseurl']}/templates/{$TBDEV['stylesheet']}/images/forums/";


$fcss = "<link rel='stylesheet' type='text/css' href='templates/{$CURUSER['stylesheet']}/forums.css' />";
/**
* Configs End
*/

$action = (isset($_GET["action"]) ? $_GET["action"] : (isset($_POST["action"]) ? $_POST["action"] : ''));


  switch($action) {
  
    case 'updatetopic':
      require_once "forums/forum_functions.php";
      require_once "forums/forum_update_topic.php";
      exit();
      break;
      
    case 'editforum':
      require_once "forums/forum_functions.php";
      require_once "forums/forum_edit.php";
      exit();
      break;
      
    case 'updateforum':
      require_once "forums/forum_update_forum.php";
      exit();
      break;
      
    case 'deleteforum':
      require_once "forums/forum_delete.php";
      exit();
      break;
      
    case 'newtopic':
      require_once "include/bbcode_functions.php";
      require_once "forums/forum_post_functions.php";
      require_once "forums/forum_new_topic.php";
      exit();
      break;
    
    case 'post':
      require_once "forums/forum_functions.php";
      require_once "forums/forum_post.php";
      exit();
      break;
      
    case 'usermood':
      require_once "forums/mood.php";
      require_once "forums/usermood.php";
      exit();
      break;
      
    case 'viewtopic':
      require_once "include/bbcode_functions.php";
      require_once "forums/forum_post_functions.php";
      require_once "forums/forum_functions.php";
      require_once "forums/mood.php";
      require_once "forums/forum_view_topic.php";
      exit();
      break;
      
    case 'quotepost':
      require_once "include/bbcode_functions.php";
      require_once "forums/forum_post_functions.php";
      require_once "forums/forum_quote_post.php";
      exit();
      break;
      
    case 'reply':
      require_once "include/bbcode_functions.php";
      require_once "forums/forum_post_functions.php";
      require_once "forums/forum_reply.php";
      exit();
      break;
    
    case 'editpost':
      require_once "forums/forum_functions.php";
      require_once "forums/forum_post_functions.php";
      require_once "forums/forum_edit_post.php";
      exit();
      break;
          
    case 'deletetopic':
      require_once "forums/forum_delete_topic.php";
      exit();
      break;
          
    case 'deletepost':
      if( $CURUSER['class'] >= UC_MODERATOR )
      {
        require_once "forums/forum_functions.php";
        require_once "forums/forum_delete_post.php";
      }
      exit();
      break;
          
    case 'deletepoll':
      require_once "forums/forum_delete_poll.php";
      exit();
      break;
          
    case 'makepoll':
      require_once "forums/forum_make_poll.php";
      exit();
      break;
          
    case 'attachment':
      if( $use_attachment_mod )
      require_once "forums/forum_attachment.php";
      exit();
      break;
          
    case 'whodownloaded':
      if( $use_attachment_mod )
      require_once "forums/forum_whodownloaded.php";
      exit();
      break;
          
    case 'viewforum':
      require_once "forums/forum_post_functions.php";
      require_once "forums/forum_functions.php";
      require_once "forums/forum_view.php";
      exit();
      break;
          
    case 'viewunread':
      require_once "forums/forum_view_unread.php";
      exit();
      break;
          
    case 'getdaily':
      require_once "forums/forum_get_daily.php";
      exit();
      break;
          
    case 'search':
      require_once "forums/forum_search.php";
      exit();
      break;
          
    case 'forumview':
      require_once "forums/forum_functions.php";
      require_once "forums/forum_parent_view.php";
      exit();
      break;
      
    case 'catchup':
      require_once "forums/forum_functions.php";
      catch_up();
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit();
      break;
     
    case 'preview':
      require_once "include/bbcode_functions.php";
      //require_once "forums/forum_post_functions.php";
      require_once "forums/preview.php";
      exit();
      break;
    
    default:
      require_once "forums/forum_functions.php";
      require_once "forums/forum_view_default.php";
      exit();
      break;
  }
  
exit('Ooops');

?>