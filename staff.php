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
require_once "include/bittorrent.php";
require_once "include/html_functions.php";
require_once "include/user_functions.php";


dbconn();

loggedinorreturn();

    $lang = array_merge( load_language('global'), load_language('staff') );

    $HTMLOUT = '';

    $query = mysql_query("SELECT users.id, username, added, last_access, class, avatar, av_w, av_h, country, status, countries.flagpic, countries.name FROM users LEFT  JOIN countries ON countries.id = users.country WHERE class >=4 AND status='confirmed' ORDER BY username") or sqlerr();

    while($arr2 = mysql_fetch_assoc($query)) {

    /*	if($arr2["class"] == UC_VIP)
        $vips[] =  $arr2;
    */
      if($arr2["class"] == UC_MODERATOR)
        $mods[] =  $arr2;

      if($arr2["class"] == UC_ADMINISTRATOR)
        $admins[] =  $arr2;

      if($arr2["class"] == UC_SYSOP)
        $sysops[] =  $arr2;
      }

    function DoStaff($staff, $staffclass, $cols = 2)
    {
      global $TBDEV, $lang;

      $dt = TIME_NOW - 180;
      $htmlout = '';

      if($staff===false)
      {
        $htmlout .= "<br /><table width='75%' border='0' cellpadding='3'>";
        $htmlout .= "<tr><td>{$staffclass}</td></tr>";
        $htmlout .= "<tr><td>{$lang['text_none']}</td></tr></table>";
        return;
      }
      $counter = count($staff);

      $rows = ceil($counter/$cols);
      $cols = ($counter < $cols) ? $counter : $cols;
      //echo "<br />" . $cols . "   " . $rows;
      $r = 0;
      $htmlout .= "<div>
                       <table width='100%' border='0' cellpadding='3'>
                             <tr>
                                <td class='colhead' colspan='{$counter}'>{$staffclass}</td>
                             </tr>
                       </table>
                   </div>";

      $htmlout .= "<div>&nbsp;</div>";

      for($ia = 0; $ia < $rows; $ia++)
      {
            for($i = 0; $i < $cols; $i++)
            {
              if( isset($staff[$r]) )
              {
                $htmlout .= "<div class='staffbox'>
                                 <div class='staff_avatar'>";


              if(!empty($staff[$r]['avatar']) && $staff[$r]['av_w'] > 5 && $staff[$r]['av_h'] > 5)
              {
                $avatar = "<img src='".$staff[$r]["avatar"]."' width='50' height='50' alt='' />";
              }
              else
              {
                $avatar = "<img src='{$TBDEV['baseurl']}/templates/1/images/staff_thumb.gif' alt='' />";
              }

                $htmlout .= $avatar;

                $htmlout .= "</div>";

             $htmlout .= "
                       <div class='staff_info'>
                           <span class='bold'>".$staff[$r]["username"]."</span>&nbsp;<img src='{$TBDEV['baseurl']}/templates/1/images/".($staff[$r]['last_access']>$dt?"/user_green.png":"/user_off.png" )."' alt='' /><br />
                           <a href='userdetails.php?id={$staff[$r]['id']}'>View Profile</a>
                           <div style='float:right; padding-top:5px;'>
                               <a href='sendmessage.php?receiver={$staff[$r]['id']}'><img src='{$TBDEV['pic_base_url']}staff/users.png' border='0' title=\"{$lang['alt_pm']}\" alt='' /></a>
                               <a href='email-gateway.php?id={$staff[$r]['id']}'><img src='{$TBDEV['pic_base_url']}staff/mail.png' border='0' alt='".$staff[$r]["username"]."' title=\"{$lang['alt_sm']}\" /></a>
                           </div>";
                $htmlout .= "</div></div>";

          $r++;
              }
              else
              {
                $htmlout .= "<div>&nbsp;</div>";
              }
            }
      }
      $htmlout .= "<div class='clear'>&nbsp;</div>";

      return $htmlout;
    }

    $HTMLOUT .= "
                     <div class='cblock'>
                         <div class='cblock-header'>{$lang['text_staff']}</div>
                         <div class='cblock-lb'>You will find all the Sysops, Administrators and Moderators here!</div>
                         <div class='cblock-content'>";

    $HTMLOUT .= DoStaff($sysops, "{$lang['header_sysops']}");
    $HTMLOUT .= isset($admins) ? DoStaff($admins, "{$lang['header_admins']}") : DoStaff($admins=false, "{$lang['header_admins']}");
    $HTMLOUT .= isset($mods) ? DoStaff($mods, "{$lang['header_mods']}") : DoStaff($mods=false, "{$lang['header_mods']}");
    //$HTMLOUT .= isset($vips) ? DoStaff($vips, "{$lang['header_vips']}") : DoStaff($vips=false, "{$lang['header_vips']}");


    $HTMLOUT .= "        </div>
                     </div>";

    print stdhead("{$lang['stdhead_staff']}") . $HTMLOUT . stdfoot();

?>