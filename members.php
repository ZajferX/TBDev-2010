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
|   Author: CoLdFuSiOn
|   $URL$
+------------------------------------------------
*/
	define('IN_TBDEV_REG', TRUE);

  require_once "include/bittorrent.php";
  require_once "include/user_functions.php";
  //require_once "include/html_functions.php";
  get_template();
  $TBDEV['register'] = 1; // for config
  
  $params = array_merge($_GET, $_POST);
  $params['action'] = isset($params['action']) ? $params['action'] : '';
  
  switch($params['action'])
  {
    case 'reg':
      require_once "members/signup.php";
      exit();
      break;
      
    case 'reg2':
      require_once "members/takesignup.php";
      exit();
      break;
  
    case 'confirm':
      require_once "members/confirm.php";
      exit();
      break;
      
    case 'ok':
    case 'sysop':
      require_once "members/ok.php";
      exit();
      break;
    
    case 'recover':
      require_once "members/recover.php";
      exit();
      break;
      
    case 'login':
      require_once "members/login.php";
      exit();
      break;
        
    case 'takelogin':
      require_once "members/takelogin.php";
      exit();
      break;
         
    case 'logout':
      require_once "members/logout.php";
      exit();
      break;
        
    case 'confirmemail':
      require_once "members/confirmemail.php";
      exit();
      break;
               
    default:
    			stderr('USER ERROR', 'Dunno what to do');
    			break;
  }
  
  
?>