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
ob_start("ob_gzhandler");

require_once "include/bittorrent.php";
require_once "include/user_functions.php";

dbconn(true);

loggedinorreturn();

    $lang = array_merge( load_language('global'), load_language('index') );
    //$lang = ;
    
    $HTMLOUT = '';

    /*
    $a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1")) or die(mysql_error());
    if ($CURUSER)
                  $latestuser = "<a href='userdetails.php?id=" . $a["id"] . "'>" . $a["username"] . "</a>";
                  else
                  $latestuser = $a['username'];
    */

  if( file_exists( ROOT_PATH.'/cache/stats.php' ) )
  {
    require ROOT_PATH.'/cache/stats.php';
    
    $stats = unserialize( stripslashes($stats) );
  }
  else
  {
    $stats = array ( 'seeders' => 0, 'leechers' => 0, 'usercnt' => 0, 'torrentcnt' => 0, 'peers' => 0, 'perc' => 0.00 );
  }


    $adminbutton = '';
    
    if (get_user_class() >= UC_ADMINISTRATOR)
          $adminbutton = "&nbsp;<span style='float:left;'><a href='admin.php?action=news'>News page</a></span>\n";

    $HTMLOUT .= "
                 <div class='cblock'>
                 <div class='cblock-header'>{$lang['news_title']}</div>
                 <div class='cblock-lb'>{$adminbutton}</div>
                 <div class='cblock-content'>";

    $res = mysql_query("SELECT * FROM news WHERE added + ( 3600 *24 *45 ) >
					".TIME_NOW." ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
					
    if (mysql_num_rows($res) > 0)
    {
      require_once "include/bbcode_functions.php";

      $button = "";



      while($array = mysql_fetch_assoc($res))
      {

        $HTMLOUT .= "<div class='newsblock'>";

        if (get_user_class() >= UC_ADMINISTRATOR)
        {
          $button = "<div class='fright'><a href='admin.php?action=news&amp;mode=edit&amp;newsid={$array['id']}'>{$lang['news_edit']}</a>&nbsp;/&nbsp;<a href='admin.php?action=news&amp;mode=delete&amp;newsid={$array['id']}'>{$lang['news_delete']}</a></div>";
        }

        $HTMLOUT .= "<div class='newsheader'><span>".htmlsafechars($array['headline'])."</span></div>\n";

        $HTMLOUT .= "<div class='newscont'>";

        $HTMLOUT .= "<span class='dateadded'>".get_date( $array['added'],'DATE') . "</span>{$button}\n";

        $HTMLOUT .= "<div class='newsbody'>".format_comment($array['body'])."</div>\n";

        $HTMLOUT .= "</div>";

        $HTMLOUT .= "</div>";

      }



    }

    $HTMLOUT .= "</div></div>\n";


    $HTMLOUT .= "
                 <div class='cblock'>
                     <div class='cblock-header'>{$lang['stats_title']}</div>
                     <div class='cblock-lb'></div>
                     <div class='cblock-content'>
                         <table class='main' border='1' cellspacing='0' cellpadding='10'>
                               <tr><td class='rowhead'>{$lang['stats_regusers']}</td><td align='right'>{$stats['usercnt']}</td></tr>
                               <tr><td class='rowhead'>{$lang['stats_torrents']}</td><td align='right'>{$stats['torrentcnt']}</td></tr>";

    if (isset($stats['peers']))
    {
      $HTMLOUT .= "            <tr><td class='rowhead'>{$lang['stats_peers']}</td><td align='right'>{$stats['peers']}</td></tr>
                               <tr><td class='rowhead'>{$lang['stats_seed']}</td><td align='right'>{$stats['seeders']}</td></tr>
                               <tr><td class='rowhead'>{$lang['stats_leech']}</td><td align='right'>{$stats['leechers']}</td></tr>
                               <tr><td class='rowhead'>{$lang['stats_sl_ratio']}</td><td align='right'>{$stats['perc']}</td></tr>";
    }

      $HTMLOUT .= "</table>
      </div></div>";

      $HTMLOUT .= "<div class='clear'>&nbsp;</div>";

/*
<h2>Server load</h2>
<table width='100%' border='1' cellspacing='0' cellpadding='1'0><tr><td align=center>
<table class=main border='0' width=402><tr><td style='padding: 0px; background-image: url("<?php echo $TBDEV['pic_base_url']?>loadbarbg.gif"); background-repeat: repeat-x'>
<?php $percent = min(100, round(exec('ps ax | grep -c apache') / 256 * 100));
if ($percent <= 70) $pic = "loadbargreen.gif";
elseif ($percent <= 90) $pic = "loadbaryellow.gif";
else $pic = "loadbarred.gif";
$width = $percent * 4;
print("<img height='1'5 width=$width src=\"{$TBDEV['pic_base_url']}{$pic}\" alt='$percent%'>"); ?>
</td></tr></table>
</td></tr></table>
*/

    $HTMLOUT .= sprintf("<p class='small'>{$lang['foot_disclaimer']}</p>", $TBDEV['site_name']);


///////////////////////////// FINAL OUTPUT //////////////////////

    print stdhead('Home') . $HTMLOUT . stdfoot();
?>