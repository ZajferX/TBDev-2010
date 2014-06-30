<?php


	$error = false;
	$found = '';
	$keywords = (isset($_GET['keywords']) ? trim($_GET['keywords']) : '');
	if (!empty($keywords))
	{
		$res = mysql_query("SELECT COUNT(id) AS c FROM posts WHERE body LIKE ".sqlesc("%".sqlwildcardesc($keywords)."%")) or sqlerr(__FILE__, __LINE__);
		$arr = mysql_fetch_assoc($res);
		$count = (int)$arr['c'];
		$keywords = htmlsafechars($keywords);
		
		if ($count == 0)
			$error = true;
		else
		{
			require_once ROOT_PATH."/include/pager_functions.php";
			$perpage = 10;
      $pager = pager($perpage, $count, $_SERVER['PHP_SELF'].'?action='.$action.'&amp;keywords='.$keywords.'&amp;');
			$res = mysql_query(
			"SELECT p.id, p.topicid, p.userid, p.added, t.forumid, t.subject, f.name, f.minclassread, u.username ".
			"FROM posts AS p ".
			"LEFT JOIN topics AS t ON t.id=p.topicid ".
			"LEFT JOIN forums AS f ON f.id=t.forumid ".
			"LEFT JOIN users AS u ON u.id=p.userid ".
			"WHERE p.body LIKE ".sqlesc("%".$keywords."%")." ".$pager['limit']."");
	
			$num = mysql_num_rows($res);
			$HTMLOUT .= $pager['pagertop'];
			$HTMLOUT .= begin_main_frame();
			
		
            $HTMLOUT .="<table border='0' cellspacing='0' cellpadding='5' width='100%'>
			       <tr>
            	<td class='colhead'>Post</td>
                <td class='colhead'>Topic</td>
                <td class='colhead'>Forum</td>
                <td class='colhead'>Posted by</td>
			          </tr>";
      
			          for ($i = 0; $i < $num; ++$i)
			          {
				        $post = mysql_fetch_assoc($res);
	
				        if ($post['minclassread'] > $CURUSER['class'])
				        {
					      --$count;
					      continue;
				        }
	
				$HTMLOUT .="<tr>".
					 	"<td align='center'>".$post['id']."</td>".
						"<td align='left' width='100%'><a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;highlight=$keywords&amp;topicid=".$post['topicid']."&amp;page=p".$post['id']."#".$post['id']."'><b>" . htmlsafechars($post['subject']) . "</b></a></td>".
						"<td align='left' style='white-space: nowrap;'>".(empty($post['name']) ? 'unknown['.$post['forumid'].']' : "<a href='".$_SERVER['PHP_SELF']."?action=viewforum&amp;forumid=".$post['forumid']."'><b>" . htmlsafechars($post['name']) . "</b></a>")."</td>".
						"<td align='left' style='white-space: nowrap;'>".(empty($post['username']) ? 'unknown['.$post['userid'].']' : "<b><a href='{$TBDEV['baseurl']}/userdetails.php?id=".$post['userid']."'>".$post['username']."</a></b>")."<br />at ".get_date($post['added'], 'DATE',1,0)."</td>".
					 "</tr>";
			}
			$HTMLOUT .= end_table();
			
			$HTMLOUT .= end_main_frame();
			$HTMLOUT .= $pager['pagerbottom'];
			$found ="[<b><font color='red'> Found $count post" . ($count != 1 ? "s" : "")." </font></b> ]";
			
		}
	}
	$HTMLOUT .="<div>
	  <div><center><h1>Search on Forums</h1> ". ($error ? "[<b><font color='red'> Nothing Found</font></b> ]" : $found)."</center></div>
	  <div style='margin-left: 53px; margin-top: 13px;'>
	<form method='get' action='".$_SERVER['PHP_SELF']."' id='search_form' style='margin: 0pt; padding: 0pt; font-family: Tahoma,Arial,Helvetica,sans-serif; font-size: 11px;'>
	<input type='hidden' name='action' value='search' />
	<table border='0' cellpadding='0' cellspacing='0' width='50%'>
	<tbody>
	<tr>
	<td valign='top'><b>By keyword:</b></td>
	</tr>
	<tr>
	<td valign='top'>			
  <input name='keywords' type='text' value='".$keywords."' size='65' /><br />
  <font class='small'><b>Note:</b> Searches <u>only</u> in posts.</font></td>
	<td valign='top'>
	<input type='submit' value='search' /></td>
	</tr>
	</tbody>
	</table>
	</form>
 </div>
	</div>";
	print stdhead("Forum Search", '', $fcss) . $HTMLOUT . stdfoot();
	exit();




    
?>