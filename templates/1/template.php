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
|   Author: CoLdFuSiOn, Dorksville, AronTh
|   Theme Designed by : Dorksville
|   $URL$
+------------------------------------------------
*/

function stdhead( $title = "", $js='', $css='' ) {
    global $CURUSER, $TBDEV, $lang, $msgalert;

    if (!$TBDEV['site_online'])
      die("Site is down for maintenance, please check back again later... thanks<br />");

    //header("Content-Type: text/html; charset=iso-8859-1");
    //header("Pragma: No-cache");
    if ($title == "")
        $title = $TBDEV['site_name'] .(isset($_GET['tbv'])?" (".TBVERSION.")":'');
    else
        $title = $TBDEV['site_name'].(isset($_GET['tbv'])?" (".TBVERSION.")":''). " :: " . htmlsafechars($title);

  /* Deprecate this.
    if ($TBDEV['msg_alert'] && $msgalert && $CURUSER)
    {
      $res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " && unread='yes'") or sqlerr(__FILE__,__LINE__);
      $arr = mysql_fetch_row($res);
      $unread = $arr[0];
    }
  */

    if ($CURUSER)
    {
      $res1 = @mysql_query("SELECT COUNT(*) FROM messages WHERE receiver={$CURUSER["id"]} AND unread='yes' AND location = 1") or sqlerr(__LINE__,__FILE__);
      $arr1 = mysql_fetch_row($res1);

      $unread = ($arr1[0] > 0 ? "<span class='msgalert'><small>{$arr1[0]}</small></span>" : $arr1[0]);
      $msgalert = $arr1[0];
      $inbox = ($unread == 1 ? "$unread" : "$unread");
    }

	$FILE = isset($CURUSER) ? $CURUSER['stylesheet'] : $TBDEV['stylesheet'] ;

    $htmlout = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">

		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>

			<meta name='generator' content='TBDev.net' />
			<meta http-equiv='Content-Language' content='en-us' />
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

      <title>{$title}</title>
      
      <link rel='stylesheet' type='text/css' href='{$TBDEV['baseurl']}/templates/$FILE/{$FILE}.css' />
      {$css}\n
      <!-- move all this stuff to footer asap -->
      <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
      {$js}\n
      <script type='text/javascript'>
        $(document).ready(function(){
        
        $('#ff').click(function (event) { 
        event.preventDefault();
        $('#fastsearch').slideToggle('fast');
        });
        
      });
      </script>

    </head>

    <body>

    <!-- Begin Wrapper -->
    <div id='wrapper'>

        <!-- Begin Header -->
        <div id='header'>
            <div class='statusbar'>";
    $htmlout .= StatusBar();
    $htmlout .= "
            </div>
            <div class='subheader'>
                <div class='logo'>";

    if ($CURUSER)
    {
      $htmlout .= "
                    <div class='profile'>
                        <div class='status_avatar'>";

    if (!empty($CURUSER['avatar']))
    {
      $avatar = "<a href='userdetails.php?id={$CURUSER['id']}'><img src='{$CURUSER['avatar']}' width='50' height='50' alt='' /></a>";
    }
    else
    {
      $avatar = "<a href='userdetails.php?id={$CURUSER['id']}'><img src='{$TBDEV['baseurl']}/templates/1/images/default_thumb.png' alt='' /></a>";
    }

    $htmlout .= $avatar;

    $htmlout .= "
                        </div>
                            <div class='username'>
                                <p><a href='userdetails.php?id={$CURUSER['id']}'>{$CURUSER['username']}</a></p>
                            </div>
                            <div class='messagesbox'>
                                <p><a href='messages.php'>$inbox</a></p>
                            </div>
                            <ul>
                               <li><a class='bold' href='members.php?action=logout'>{$lang['gl_logout']}</a></li>
                            </ul>
                            <div class='rlink'>
                                <a class='bold' href='my.php'>{$lang['gl_profile']}</a>&nbsp;&nbsp;&nbsp;
                                <a class='bold' href='rules.php'>{$lang['gl_rules']}</a>
                            </div>
                    </div>";
    }
    else
    {
      $htmlout .= "
                    <div class='profile'>
                        <div class='sign_in'>
                            <div style='padding:8px 0 0 5px;'>
                            <img src='{$TBDEV['baseurl']}/templates/1/images/key.png' alt='{$lang['gl_login']}' />&nbsp;<a style='color:#fff;' href='members.php?action=login'>Sign In »</a>
                            </div>
                        </div>
	                   <ul>
                          <li>New user?&nbsp;</li>
                          <li><a class='bold' href='members.php?action=reg'>Register Now!</a></li>
                       </ul>
                    </div>";
    }

    $htmlout .= "
                </div>
                <!-- Begin Navigation -->
                <div id='navigation'>
                    <div id='nav'>
                        <ul>";

    $tab = pathinfo( $_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME );
    
    if ($CURUSER)
    {
      $tabarray = array(
				'index'   => "<li class='fleft'><a href='index.php'>{$lang['gl_home']}</a></li>",
				'browse'  => "<li class='fleft'><a href='browse.php'>{$lang['gl_browse']}</a></li>",
				'upload'  => "<li class='fleft'><a href='upload.php'>{$lang['gl_upload']}</a></li>",
				'chat'    => "<li class='fleft'><a href='chat.php'>{$lang['gl_chat']}</a></li>",
				'forums'  => "<li class='fleft'><a href='forums.php'>{$lang['gl_forums']}</a></li>",
				'topten'  => "<li class='fleft'><a href='topten.php'>{$lang['gl_top_10']}</a></li>",
				'links'   => "<li class='fleft'><a href='links.php'>{$lang['gl_links']}</a></li>",
				'faq'     => "<li class='fleft'><a href='faq.php'>{$lang['gl_faq']}</a></li>",
				'staff'   => "<li class='fleft'><a href='staff.php'>{$lang['gl_staff']}</a></li>"
			);
      
      if( $CURUSER['class'] >= UC_MODERATOR )
      {
      $tabarray['admin']= "<li class='fleft'><a href='admin.php'>{$lang['gl_admin']}</a></li>";
      }

      foreach($tabarray as $k => $v)
			{
        if( $tab == $k )
        $htmlout .= str_replace("<li class='fleft'>", "<li class='fleft active'>", $v);
        else
        $htmlout .= $v;
			}

      $htmlout .= "<li class='fright'><a id='ff' href='search.php'>{$lang['gl_search']}</a></li>";
      unset($tabarray);
    }
    else
    {
      $tabarray = array(
       'login'    => "<li class='fleft'><a href='members.php?action=login'>{$lang['gl_login']}</a></li>",
       'signup'   => "<li class='fleft'><a href='members.php?action=reg'>{$lang['gl_signup']}</a></li>",
       'recover'  => "<li class='fleft'><a href='members.php?action=recover'>{$lang['gl_recover']}</a></li>"
       );
    
      foreach($tabarray as $k => $v)
			{
        if( $tab == $k )
        $htmlout .= str_replace("<li class='fleft'>", "<li class='fleft active'>", $v);
        else
        $htmlout .= $v;
			}
			unset($tabarray);
    }

    $htmlout .= "
                        </ul>
                    </div>
                </div>
                <!-- End Navigation -->
            </div>
        </div>
        <!-- End Header -->

        <div class='clear'></div>

        <!-- Start Container -->
        <div id='container'>

            <!-- Start Search Box -->
            <div class='fright' style='height:0px;'>
                <div id='fastsearch' class='search-box' style='display:none;'>
                    <strong>Torrent Search:</strong>
                    <form method='get' action='browse.php'>
                         <input name='search' size='40' value='' type='text' />
                         <input value='Search!' class='btn' type='submit' />
                    </form>
                </div>
            </div>
            <!-- End Search Box -->

            <!-- Start Maincolumn -->
            <div id='maincolumn'>";

            if ( $TBDEV['msg_alert'] && $msgalert )
            {
             $htmlout .= "
                <div class='alert'>
                    <a href='messages.php'><span>".sprintf($lang['gl_msg_alert'], $msgalert) ."&nbsp;". ($msgalert > 1 ? $lang['gl_msg_plural'] : $lang['gl_msg_singular']) . "!</span></a>
                </div>\n";
}


    return $htmlout;

} // stdhead

function stdfoot() {
  global $TBDEV, $lang;

    $htmlout = '';
    $htmlout .= "
            </div>
            <!-- End Maincolumn -->

            <div class='clear'></div>
        </div>
        <!-- End Container -->

        <!-- Begin Footer -->
        <div id='footer'>
            <div class='footerbg'>
                <p>{$lang['gl_copyright']}<br />
                   <a href='http://www.tbdev.net'><img src='{$TBDEV['pic_base_url']}tbdev_btn_red.png' alt='Powered By TBDev &copy;2010' title='Powered By TBDev &copy;2010' /></a>
                   <a href='http://www.tbdev.net'><img src='{$TBDEV['pic_base_url']}dorks_btn_red.png' alt='Dorksville &copy;2010' title='Dorksville &copy;2010' /></a>
                </p>
            </div>
        </div>
        <!-- End Footer -->

    </div>
    <!-- End Wrapper -->

</body>
</html>";

    return $htmlout;
}

function stdmsg($heading, $text)
{
    $htmlout = "<table class='main' width='750' border='0' cellpadding='0' cellspacing='0'>
    <tr><td class='embedded'>\n";
    
    if ($heading)
      $htmlout .= "<h2>$heading</h2>\n";

    $htmlout .= "<table width='100%' border='1' cellspacing='0' cellpadding='10'><tr><td class='text'>\n";
    $htmlout .= "{$text}</td></tr></table></td></tr></table>\n";

    return $htmlout;
}

function StatusBar() {

	global $CURUSER, $TBDEV, $lang, $msgalert;

	if (!$CURUSER)
		return "&nbsp;";


	$upped = mksize($CURUSER['uploaded']);

	$downed = mksize($CURUSER['downloaded']);

	$ratio = $CURUSER['downloaded'] > 0 ? $CURUSER['uploaded']/$CURUSER['downloaded'] : 0;

	$ratio = number_format($ratio, 2);

	$IsDonor = '';
	if ($CURUSER['donor'] == "yes")

	$IsDonor = "<img src='{$TBDEV['pic_base_url']}star.gif' alt='donor' title='donor' />";


	$warn = '';
	if ($CURUSER['warned'] == "yes")

	$warn = "<img src='{$TBDEV['pic_base_url']}warned.gif' alt='warned' title='warned' />";

	$res2 = @mysql_query("SELECT seeder, COUNT(*) AS pCount FROM peers WHERE userid=".$CURUSER['id']." GROUP BY seeder") or sqlerr(__LINE__,__FILE__);

	$seedleech = array('yes' => '0', 'no' => '0');

	while( $row = mysql_fetch_assoc($res2) ) {
		if($row['seeder'] == 'yes')
			$seedleech['yes'] = $row['pCount'];
		else
			$seedleech['no'] = $row['pCount'];

	}

/////////////// REP SYSTEM /////////////
//$CURUSER['reputation'] = 49;

	$member_reputation = get_reputation($CURUSER, 1);
////////////// REP SYSTEM END //////////

	$StatusBar = '';

		$StatusBar .= "
            <div style='float:left;'>
                $IsDonor$warn&nbsp;
                $member_reputation, {$lang['gl_ratio']}:&nbsp;$ratio &nbsp;&nbsp;{$lang['gl_uploaded']}:&nbsp;$upped
		        &nbsp;&nbsp;{$lang['gl_downloaded']}:&nbsp;$downed
                &nbsp;&nbsp;{$lang['gl_act_torrents']}:&nbsp;<img alt='{$lang['gl_seed_torrents']}' title='{$lang['gl_seed_torrents']}' src='{$TBDEV['pic_base_url']}arrowup.gif' />&nbsp;{$seedleech['yes']}
                &nbsp;&nbsp;<img alt='{$lang['gl_leech_torrents']}' title='{$lang['gl_leech_torrents']}' src='{$TBDEV['pic_base_url']}arrowdown.gif' />&nbsp;{$seedleech['no']}
            </div>
                <p style='text-align:right;'>".get_date(TIME_NOW, 'LONG', 1)."</p>";

	return $StatusBar;

}

?>