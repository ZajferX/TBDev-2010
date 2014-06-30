<?php

// -------- Action: View unread posts
        if ((isset($_POST[$action]) ? $_POST[$action] : '') == 'clear') {
            $topic_ids = (isset($_POST['topic_id']) ? $_POST['topic_id'] : array());

            if (empty($topic_ids)) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?action=' . $action);
                exit();
            }

            foreach ($topic_ids as $topic_id)
            if (!is_valid_id($topic_id))
                stderr('Error...', 'Invalid ID!');

            $HTMLOUT .= catch_up($topic_ids);

            header('Location: ' . $_SERVER['PHP_SELF'] . '?action=' . $action);
            exit();
        } else {
            $added = (time() - $TBDEV['readpost_expiry']);
            $res = mysql_query('SELECT t.lastpost, r.lastpostread, f.minclassread ' . 'FROM topics AS t ' . 'LEFT JOIN posts AS p ON t.lastpost=p.id ' . 'LEFT JOIN readposts AS r ON r.userid=' . sqlesc((int)$CURUSER['id']) . ' AND r.topicid=t.id ' . 'LEFT JOIN forums AS f ON f.id=t.forumid ' . 'WHERE p.added > ' . $added) or sqlerr(__FILE__, __LINE__);
            $count = 0;
            while ($arr = mysql_fetch_assoc($res)) {
                if ($arr['lastpostread'] >= $arr['lastpost'] || $CURUSER['class'] < $arr['minclassread'])
                    continue;

                $count++;
            }
            mysql_free_result($res);

            if ($count > 0)
		        {
			      $perpage = 25;
            $pager = pager($perpage, $count, $_SERVER['PHP_SELF'].'?action='.$action.'&amp;');

         
                if ($TBDEV['forums_online'] == 0)
                $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
                $HTMLOUT .= begin_main_frame();
                $HTMLOUT .="<h1 align='center'>Topics with unread posts</h1>";
                $HTMLOUT .= $pager['pagertop'];
	
			$HTMLOUT .= "	<script type='text/javascript'>
			             /*<![CDATA[*/
				var checkflag = 'false';
				function check(a)
				{
					if (checkflag == 'false')
					{
						for(i=0; i < a.length; i++)
							a[i].checked = true;
						checkflag = 'true';
						value = 'Uncheck';
					}
					else
					{
						for(i=0; i < a.length; i++)
							a[i].checked = false;
						checkflag = 'false';
						value = 'Check';
					}
					return value + ' All';
				};
			/*]]>*/
			</script>";
	
			$HTMLOUT .= "<form method='post' action='{$TBDEV['baseurl']}/forums.php?action=viewunread'>
			<input type='hidden' name='viewunread' value='clear' />";
		  $HTMLOUT .= "<table cellpadding='5' width='{$forum_width}'>
			<tr align='left'>
				<td class='colhead' colspan='2'>Topic</td>
				<td class='colhead' width='1%'>Clear</td>
			</tr>";

                $res = mysql_query('SELECT t.id, t.forumid, t.subject, t.lastpost, r.lastpostread, f.name, f.minclassread ' . 'FROM topics AS t ' . 'LEFT JOIN posts AS p ON t.lastpost=p.id ' . 'LEFT JOIN readposts AS r ON r.userid=' . sqlesc((int)$CURUSER['id']) . ' AND r.topicid=t.id ' . 'LEFT JOIN forums AS f ON f.id=t.forumid ' . 'WHERE p.added > ' . $added . ' ' . ' ORDER BY t.forumid '.$pager['limit']) or sqlerr(__FILE__, __LINE__);

                while ($arr = mysql_fetch_assoc($res)) {
                    if ($arr['lastpostread'] >= $arr['lastpost'] || $CURUSER['class'] < $arr['minclassread'])
                        continue;

                    
				$HTMLOUT .= "<tr>
					<td align='center' width='1%'>
						<img src='".$TBDEV['pic_base_url']."unlockednew.gif' alt='New Posts' title='New Posts' />
					</td>
					<td align='left'>
						<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=".(int)$arr['id']."&amp;page=last#last'>".htmlspecialchars($arr['subject'])."</a><br />in&nbsp;<font class='small'><a href='".$_SERVER['PHP_SELF']."?action=viewforum&amp;forumid=".(int)$arr['forumid']."'>". htmlspecialchars($arr['name'])."</a></font>
					 </td>
					<td align='center'>
						<input type='checkbox' name='topic_id[]' value='".(int)$arr['id']."' />
					</td>
				</tr>";
		
                }
                mysql_free_result($res);

                
			$HTMLOUT .= "<tr>
				<td align='center' colspan='3'>
					<input type='button' value='Check All' onclick=\"this.value = check(form);\" />&nbsp;<input type='submit' value='Clear selected' />
				</td>
			</tr>";
			

                $HTMLOUT .= end_table();

               $HTMLOUT .= "</form>";
               $HTMLOUT .= $pager['pagerbottom'];
            

                $HTMLOUT .= "<div align='center'><a href='" . $_SERVER['PHP_SELF'] . "?catchup'>Mark all posts as read</a></div>";

                $HTMLOUT .= end_main_frame();
                print stdhead("Catch Up", '', $fcss) . $HTMLOUT . stdfoot();
                die();
            } else
                stderr("Sorry...", "There are no unread posts.<br /><br />Click <a href='" . $_SERVER['PHP_SELF'] . "?action=getdaily'>here</a> to get today's posts (last 24h).");
        }

    
?>