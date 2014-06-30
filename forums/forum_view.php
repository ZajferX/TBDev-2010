<?php

// -------- Action: View forum
        $forumid = (int)$_GET['forumid'];
    if (!is_valid_id($forumid))
        stderr('Error', 'Invalid ID!');
    $page = (isset($_GET["page"]) ? (int)$_GET["page"] : 0);
    $userid = (int)$CURUSER["id"];
    // ------ Get forum details
    $res = mysql_query("SELECT f.name AS forum_name, f.minclassread, (SELECT COUNT(id) FROM topics WHERE forumid = f.id) AS t_count " . "FROM forums AS f " . "WHERE f.id = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);
    $arr = mysql_fetch_assoc($res) or stderr('Error', 'No forum with that ID!');

    if ($CURUSER['class'] < $arr["minclassread"])
        stderr('Error', 'Access Denied!');

    $perpage = (empty($CURUSER['topicsperpage']) ? 20 : (int)$CURUSER['topicsperpage']);
    $num = (int)$arr['t_count'];

    if ($page == 0)
        $page = 1;

    $first = ($page * $perpage) - $perpage + 1;
    $last = $first + $perpage - 1;

    if ($last > $num)
        $last = $num;

    $pages = floor($num / $perpage);

    if ($perpage * $pages < $num)
        ++$pages;
    // ------ Build menu
    $menu1 = "<p class='success' align='center'>";
    $menu2 = '';

    $lastspace = false;
    for ($i = 1; $i <= $pages; ++$i) {
        if ($i == $page)
            $menu2 .= "<b>[<u>$i</u>]</b>\n";

        else if ($i > 3 && ($i < $pages - 2) && ($page - $i > 3 || $i - $page > 3)) {
            if ($lastspace)
                continue;

            $menu2 .= "... \n";

            $lastspace = true;
        } else {
            $menu2 .= "<a href='" . $_SERVER['PHP_SELF'] . "?action=viewforum&amp;forumid=$forumid&amp;page=$i'><b>$i</b></a>\n";

            $lastspace = false;
        }

        if ($i < $pages)
            $menu2 .= "<b>|</b>";
    }

    $menu1 .= ($page == 1 ? "<b>&lt;&lt;&nbsp;Prev</b>" : "<a href='{$_SERVER['PHP_SELF']}?action=viewforum&amp;forumid=$forumid&amp;page=" . ($page - 1) . "'><b>&lt;&lt;&nbsp;Prev</b></a>");
    $mlb = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $menu3 = ($last == $num ? "<b>Next&nbsp;&gt;&gt;</b></p>" : "<a href='{$_SERVER['PHP_SELF']}?action=viewforum&amp;forumid=$forumid&amp;page=" . ($page + 1) . "'><b>Next&nbsp;&gt;&gt;</b></a></p>");

    $offset = $first - 1;

    $topics_res = mysql_query("SELECT t.id, t.userid, t.views, t.locked, t.sticky" . ($use_poll_mod ? ', t.pollid' : '') . ", t.subject, t.anonymous, u1.username, r.lastpostread, p.id AS p_id,p2.posticon, p.userid AS p_userid, p.anonymous as p_anon, p.added AS p_added, (SELECT COUNT(id) FROM posts WHERE topicid=t.id) AS p_count, u2.username AS u2_username " . "FROM topics AS t " . "LEFT JOIN users AS u1 ON u1.id=t.userid " . "LEFT JOIN readposts AS r ON r.userid = " . sqlesc($userid) . " AND r.topicid = t.id " . "LEFT JOIN posts AS p ON p.id = (SELECT MAX(id) FROM posts WHERE topicid = t.id) " . "LEFT JOIN posts AS p2 ON p2.id = (SELECT MIN(id) FROM posts WHERE topicid = t.id) " . "LEFT JOIN users AS u2 ON u2.id = p.userid " . "WHERE t.forumid = " . sqlesc($forumid) . " ORDER BY t.sticky, t.lastpost DESC LIMIT $offset, $perpage") or sqlerr(__FILE__, __LINE__);
    // subforums
    $r_subforums = mysql_query("SELECT id FROM forums where place=" . $forumid);
    $subforums = mysql_num_rows($r_subforums);
    //$HTMLOUT .= begin_main_frame();
    if ($TBDEV['forums_online'] == 0)
    $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
    
    $fnav = "<div class='fnav'><a href='{$_SERVER['PHP_SELF']}'>{$lang['forums_title']}</a></div>\n";
  
    $buttons = "<div style='text-align:right;margin:10px 0px 10px 0px;'>
    <span class='fbtn'><a href='forums.php?action=search'>{$lang['forums_search']}</a></span>
    &nbsp;<span class='fbtn'><a href='forums.php?action=viewunread'>{$lang['forums_view_unread']}</a></span>
    &nbsp;<span class='fbtn'><a href='forums.php?action=catchup'>{$lang['forums_catchup']}</a></span>
    </div>";
  
    $HTMLOUT .="<div class='tb_table_outer_wrap'>{$fnav}$buttons
    <div class='tb_table_inner_wrap'>
      <span style='color:#ffffff;'>".htmlspecialchars($arr["forum_name"])." : Forums</span>
      </div>";
    
    if ($subforums > 0) 
    {
      $HTMLOUT .="
      <table class='tb_table'>
      <tr class='header'>
      <th class='col_c_icon'>&nbsp;</th>
      <th class='col_c_forum left'>{$lang['forums_forum_heading']}</th>
      <th class='col_c_stats right'>{$lang['forums_topic_heading']}</th>
      <th class='col_c_stats right'>{$lang['forums_posts_heading']}</th>
      <th class='col_c_post left'>{$lang['forums_lastpost_heading']}</th>
      </tr>\n";

      $HTMLOUT .= show_forums($forumid, true);
      $HTMLOUT .= "</table>";
    }

    if (mysql_num_rows($topics_res) > 0) 
    {
      $HTMLOUT .="<div class='header'>". htmlspecialchars($arr["forum_name"])." : Forums</div>
      <table class='tb_table'>
      <tr class='header'>
      <th class='col_c_icon'>&nbsp;</th>
      <th class='col_c_icon'>&nbsp;</th>
      <th class='col_c_forum left'>{$lang['forums_forum_heading']}</th>
      <th class='col_c_stats right'>{$lang['forum_view_replies']}</th>
      <th class='col_c_stats right'>{$lang['forum_view_views']}</th>
      <th class='col_c_post left'>{$lang['forums_lastpost_heading']}</th>
      </tr>\n";
		
    while ($topic_arr = mysql_fetch_assoc($topics_res))
		{
			$topicid = (int)$topic_arr['id'];
			$topic_userid = (int)$topic_arr['userid'];
			$sticky = ($topic_arr['sticky'] == "yes");
			$pollim = $topic_arr['pollid'] > "0";
			($use_poll_mod ? $topicpoll = is_valid_id($topic_arr["pollid"]) : NULL);
		
			$tpages = floor($topic_arr['p_count'] / $postsperpage);
			
			if (($tpages * $postsperpage) != $topic_arr['p_count'])
				++$tpages;
			
			if ($tpages > 1)
			{
				$topicpages = "&nbsp;(<img src='".$TBDEV['forum_pic_url']."multipage.gif' alt='Multiple pages' title='Multiple pages' />";
				$split = ($tpages > 10) ? true : false;
				$flag = false;
				
				for ($i = 1; $i <= $tpages; ++$i)
				{
					if ($split && ($i > 4 && $i < ($tpages - 3)))
					{
						if (!$flag)
						{
							$topicpages .= '&nbsp;...';
							$flag = true;
						}
						continue;
					}
					$topicpages .= "&nbsp;<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=$topicid&amp;page=$i'>$i</a>";
				}
				$topicpages .= ")";
			}
			else
				$topicpages = '';
		
			if ($topic_arr["p_anon"] == "yes") {
      if($CURUSER['class'] < UC_MODERATOR && $topic_arr["p_userid"] != $CURUSER["id"])
      $lpusername = "<i>Anonymous</i>";
      else
      $lpusername = "<i>Anonymous</i><br />(<a href='{$TBDEV['baseurl']}/userdetails.php?id=".(int)$topic_arr['p_userid']."'><b>".$topic_arr['u2_username']."</b></a>)";
      }
      else
      $lpusername = (is_valid_id($topic_arr['p_userid']) && !empty($topic_arr['u2_username']) ? "<a href='{$TBDEV['baseurl']}/userdetails.php?id=".(int)$topic_arr['p_userid']."'><b>".$topic_arr['u2_username']."</b></a>" : "unknown[$topic_userid]");
      if ($topic_arr["anonymous"] == "yes") {
      if($CURUSER['class'] < UC_MODERATOR && $topic_arr["userid"] != $CURUSER["id"])
      $lpauthor = "<i>Anonymous</i>";
      else
      $lpauthor = "<i>Anonymous</i><br />(<a href='{$TBDEV['baseurl']}/userdetails.php?id=$topic_userid'><b>".$topic_arr['username']."</b></a>)";
      }
      else
      $lpauthor = (is_valid_id($topic_arr['userid']) && !empty($topic_arr['username']) ? "<a href='{$TBDEV['baseurl']}/userdetails.php?id=$topic_userid'><b>".$topic_arr['username']."</b></a>" : "unknown[$topic_userid]");
			$new = ($topic_arr["p_added"] > (time() - $TBDEV['readpost_expiry'])) ? ((int)$topic_arr['p_id'] > $topic_arr['lastpostread']) : 0;
			$topicpic = ($topic_arr['locked'] == "yes" ? ($new ? "lockednew" : "locked") : ($new ? "unlockednew" : "unlocked"));
			$post_icon = ($sticky ? "<img src='{$TBDEV['forum_pic_url']}f_pinned.gif' alt='Sticky topic' title='Sticky topic' />" : ($topic_arr["posticon"] > 0 ? "<img src='{$TBDEV['forum_pic_url']}post_icons/icon{$topic_arr["posticon"]}.gif' alt='post icon' title='post icon' />" : "&nbsp;"));

      $HTMLOUT .="<tr class='row1'>
      <td class='altrow'><img src='".$TBDEV['forum_pic_url'].$topicpic.".gif' alt='' /></td>
      <td class='altrow'>$post_icon</td>
			<td class='noborder'>". ($pollim ? "<img src='{$TBDEV['forum_pic_url']}poll.gif' alt='Topic Poll' title='Topic Poll' />&nbsp;" : '').
			($sticky ? 'Sticky:&nbsp;' : '')."<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=".$topicid."'>".htmlspecialchars($topic_arr['subject'])."</a>{$topicpages}</td>
      <td class='altrow stats'>". max(0, $topic_arr['p_count'] - 1)."</td>
      <td class='altrow stats'>". number_format($topic_arr['views'])."</td>
      <td class='last_post noborder'>$lpauthor&nbsp;<span style='white-space:nowrap;'>".get_date($topic_arr["p_added"],'DATE',1,0)."</span><br />by&nbsp;$lpusername</td>
      </tr>";
		    }
		
		$HTMLOUT .= "</table>";
  }
  else
  {
  $HTMLOUT .="<p align='center'>No topics found</p>";
  }
	
	$HTMLOUT .= $menu1.$mlb.$menu2.$mlb.$menu3;


	$HTMLOUT .= "$buttons</div><br />\n"; 
	
	
	
	$HTMLOUT .="<table class='main' border='0' cellspacing='0' cellpadding='0' align='center'>
	<tr align='center'>
		<td class='embedded'><img src='{$TBDEV['forum_pic_url']}unlockednew.gif' alt='New Unlocked' style='margin-right: 5px' /></td>
		<td class='embedded'>New posts</td>
		<td class='embedded'><img src='{$TBDEV['forum_pic_url']}locked.gif' alt='Locked' style='margin-left: 10px; margin-right: 5px' /></td>
		<td class='embedded'>Locked topic</td>
	</tr>
	</table>";

	$arr = get_forum_access_levels($forumid) or die();
	
	$maypost = ($CURUSER['class'] >= $arr["write"] && $CURUSER['class'] >= $arr["create"]);
	
	if (!$maypost)
	{
	$HTMLOUT .="<p><i>You are not permitted to start new topics in this forum.</i></p>";
	}
	$HTMLOUT .="<table border='0' class='main' cellspacing='0' cellpadding='0' align='center'>
	<tr>
	<td class='embedded'><form method='get' action='{$_SERVER['PHP_SELF']}'>
	<input type='hidden' name='action' value='viewunread' />
	<input type='submit' value='View unread' class='gobutton' /></form></td>";

	if ($maypost)
	{
	$HTMLOUT .="<td class='embedded'>
	<form method='get' action='{$_SERVER['PHP_SELF']}'>
	<input type='hidden' name='action' value='newtopic' />
	<input type='hidden' name='forumid' value='".$forumid."' />
	<input type='submit' value='New topic' class='gobutton' style='margin-left: 10px' /></form></td>";
	}
	
	$HTMLOUT .="</tr></table>";
	
	$HTMLOUT .= insert_quick_jump_menu($forumid);
	
	print stdhead("New Topic", '', $fcss) . $HTMLOUT . stdfoot();
	
	exit();


?>