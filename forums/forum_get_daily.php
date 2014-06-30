<?php


	$res = mysql_query('SELECT COUNT(p.id) AS post_count '.
					   'FROM posts AS p '.
					   'LEFT JOIN topics AS t ON t.id = p.topicid '.
					   'LEFT JOIN forums AS f ON f.id = t.forumid '.
					   'WHERE p.added > '.time().' - 86400 AND f.minclassread <= '.$CURUSER['class']) or sqlerr(__FILE__, __LINE__);
	
	$arr = mysql_fetch_assoc($res);
	mysql_free_result($res);


        $count = (int)$arr['post_count'];
        if (empty($count))
        stderr('Sorry', 'No posts in the last 24 hours.');

     
        if ($TBDEV['forums_online'] == 0)
        $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
        $HTMLOUT .= begin_main_frame();
        $perpage = 20;
        $pager = pager($perpage, $count, $_SERVER['PHP_SELF'].'?action='.$action.'&amp;');
	
	$HTMLOUT .= "<h2 align='center'>Today Posts (Last 24 Hours)</h2>";
	$HTMLOUT .= $pager['pagertop'];

    $HTMLOUT .= "<table cellpadding='5' width='{$forum_width}'>
    <tr class='colhead' align='center'>
		<td width='100%' align='left'>Topic Title</td>
		<td>Views</td>
		<td>Author</td>
		<td>Posted At</td>
	  </tr>";

     $res = mysql_query('SELECT p.id AS pid, p.topicid, p.userid AS userpost, p.added, t.id AS tid, t.subject, t.forumid, t.lastpost, t.views, f.name, f.minclassread, f.topiccount, u.username '.
					   'FROM posts AS p '.
					   'LEFT JOIN topics AS t ON t.id = p.topicid '.
					   'LEFT JOIN forums AS f ON f.id = t.forumid '.
					   'LEFT JOIN users AS u ON u.id = p.userid '.
					   'LEFT JOIN users AS topicposter ON topicposter.id = t.userid '.
					   'WHERE p.added > '.time().' - 86400 AND f.minclassread <= '.$CURUSER['class'].' '.
					   'ORDER BY p.added DESC '.$pager["limit"]) or sqlerr(__FILE__, __LINE__);
        
    while ($getdaily = mysql_fetch_assoc($res))
	  {
		$postid = (int)$getdaily['pid'];
		$posterid = (int)$getdaily['userpost'];
		
		$HTMLOUT .= "<tr>
			<td align='left'>
		  <a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=".$getdaily['tid']."&amp;page=".$postid."#".$postid ."'>". htmlspecialchars($getdaily['subject'])."</a><br />
      <b>In</b>&nbsp;<a href='". $_SERVER['PHP_SELF']."?action=viewforum&amp;forumid=". (int)$getdaily['forumid']."'>". htmlspecialchars($getdaily['name'])."</a>
      </td>
      <td align='center'>". number_format($getdaily['views'])."</td>
      <td align='center'>";
				
				if (!empty($getdaily['username']))
				{
				$HTMLOUT .= "<a href='{$TBDEV['baseurl']}/userdetails.php?id=".$posterid."'>".htmlspecialchars($getdaily['username'])."</a>";
				}
				else
				{
				$HTMLOUT .= "<b>unknown[".$posterid."]</b>";
				}
			  $HTMLOUT .= "</td>";
		
	      $HTMLOUT .= "<td style='white-space: nowrap;'>".get_date($getdaily['added'], 'LONG',1,0)."</td></tr>";
	   
	}
	mysql_free_result($res);
	
	$HTMLOUT .= end_table();
	$HTMLOUT .= $pager['pagerbottom'];
	$HTMLOUT .= end_main_frame(); 
	print stdhead('Today Posts (Last 24 Hours)', '', $fcss) . $HTMLOUT . stdfoot();


?>