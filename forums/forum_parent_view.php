<?php


        $ovfid = (isset($_GET["forid"]) ? (int)$_GET["forid"] : 0);
        if (!is_valid_id($ovfid))
            stderr('Error', 'Invalid ID!');

        $res = mysql_query("SELECT name FROM forum_parents WHERE id = $ovfid") or sqlerr(__FILE__, __LINE__);
        $arr = mysql_fetch_assoc($res) or stderr('Sorry', 'No forums with that ID!');

        mysql_query("UPDATE users SET forum_access = " . time() . " WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__, __LINE__);

  
        if ($TBDEV['forums_online'] == 0)
        $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
        //$HTMLOUT .= begin_main_frame();

     
	$fnav = "<div class='fnav'><a href='{$_SERVER['PHP_SELF']}'>{$lang['forums_title']}</a></b> -> ". htmlspecialchars($arr["name"])."</div>\n";
	
	$buttons = "<div style='text-align:right;margin:10px 0px 10px 0px;'>
  <span class='fbtn'><a href='forums.php?action=search'>{$lang['forums_search']}</a></span>
  &nbsp;<span class='fbtn'><a href='forums.php?action=viewunread'>{$lang['forums_view_unread']}</a></span>
  &nbsp;<span class='fbtn'><a href='forums.php?action=catchup'>{$lang['forums_catchup']}</a></span>
  </div>";

	$HTMLOUT .="<div class='tb_table_outer_wrap'>{$fnav}$buttons
	<div class='tb_table_inner_wrap'>
    <span style='color:#ffffff;'>". htmlspecialchars($arr["name"])."</span>
  </div>
    
    <table class='tb_table'>
    <tr class='header'>
    <th class='col_c_icon'>&nbsp;</th>
    <th class='col_c_forum left'>{$lang['forums_forum_heading']}</th>
    <th class='col_c_stats right'>{$lang['forums_topic_heading']}</th>
    <th class='col_c_stats right'>{$lang['forums_posts_heading']}</th>
    <th class='col_c_post left'>{$lang['forums_lastpost_heading']}</th>
    </tr>\n";


    $HTMLOUT .= show_forums($ovfid);

    $HTMLOUT .="</table>$buttons</div><br />\n";
    
    print stdhead("Forums", '', $fcss) . $HTMLOUT . stdfoot();
    exit();
    
    
?>