<?php

// -------- Action: View topic
        $userid = (int)$CURUSER["id"];

    if ($use_poll_mod && $_SERVER['REQUEST_METHOD'] == "POST") 
    {
        $choice = $_POST['choice'];
        $pollid = (int)$_POST["pollid"];
        if (ctype_digit($choice) && $choice < 256 && $choice == floor($choice)) {
            $res = mysql_query("SELECT pa.id " . "FROM forum_polls AS p " . "LEFT JOIN forum_poll_answers AS pa ON pa.pollid = p.id AND pa.userid = " . sqlesc($userid) . " " . "WHERE p.id = " . sqlesc($pollid)) or sqlerr(__FILE__, __LINE__);
            $arr = mysql_fetch_assoc($res) or stderr('Sorry', 'Inexistent poll!');

            if (is_valid_id($arr['id']))
                stderr("Error...", "Dupe vote");

            mysql_query("INSERT INTO forum_poll_answers VALUES(id, " . sqlesc($pollid) . ", " . sqlesc($userid) . ", " . sqlesc($choice) . ")") or sqlerr(__FILE__, __LINE__);

            if (mysql_affected_rows() != 1)
                stderr("Error...", "An error occured. Your vote has not been counted.");
        } else
            stderr("Error..." , "Please select an option.");
    }

    $topicid = (int)$_GET["topicid"];
    if (!is_valid_id($topicid))
        stderr('Error', 'Invalid topic ID!');

    $page = (isset($_GET["page"]) ? $_GET["page"] : 0);
    // ------ Get topic info
    $res = mysql_query("SELECT " . ($use_poll_mod ? 't.pollid, ' : '') . "t.locked, t.subject, t.sticky, t.userid AS t_userid, t.forumid, f.name AS forum_name, f.minclassread, f.minclasswrite, f.minclasscreate, (SELECT COUNT(id)FROM posts WHERE topicid = t.id) AS p_count " . "FROM topics AS t " . "LEFT JOIN forums AS f ON f.id = t.forumid " . "WHERE t.id = " . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
    $arr = mysql_fetch_assoc($res) or stderr("Error", "Topic not found");
    mysql_free_result($res);
    
    if ($CURUSER["class"] < $arr["minclassread"])
        stderr("Error", "You are not permitted to view this topic.");
    
    ($use_poll_mod ? $pollid = (int)$arr["pollid"] : null);
    $t_userid = (int)$arr['t_userid'];
    $locked = ($arr['locked'] == 'yes' ? true : false);
    $subject = $arr['subject'];
    $sticky = ($arr['sticky'] == "yes" ? true : false);
    $forumid = (int)$arr['forumid'];
    $forum = $arr["forum_name"];
    $postcount = (int)$arr['p_count'];
    $minread = $arr['minclassread'];
    $minwrite = $arr['minclasswrite'];
    $mincreate = $arr['minclasscreate'];
    
    // ------ Update hits column
    mysql_query("UPDATE topics SET views = views + 1 WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);

  $perpage = $postsperpage;
  $offset = 0;
  $menu = '';

  if( $postcount > $postsperpage )
  {
    //------ Make page menu
    $pagemenu1 = "<p align='center'>";
    
    $pages = ceil($postcount / $perpage);
    
    if ($page[0] == "p")
    {
      $findpost = substr($page, 1);
      $res = mysql_query("SELECT id FROM posts WHERE topicid=$topicid ORDER BY added") or sqlerr(__FILE__, __LINE__);
      $i = 1;
      while ($arr = mysql_fetch_row($res))
      {
        if ($arr[0] == $findpost)
          break;
        ++$i;
      }
      $page = ceil($i / $perpage);
    }
    
    if ($page == "last")
      $page = $pages;
    else
    {
      if ($page < 1)
        $page = 1;
      else if ($page > $pages)
        $page = $pages;
    }
    
    $offset = ((int)$page * $perpage) - $perpage;
    $offset = ($offset < 0 ? 0 : $offset);
    
    $pagemenu2 = '';
    for ($i = 1; $i <= $pages; ++$i)
      $pagemenu2 .= ($i == $page ? "<b>[<u>$i</u>]</b>" : "<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=$topicid&amp;page=$i'><b>$i</b></a>");
    
    $pagemenu1 .= ($page == 1 ? "<b>&lt;&lt;&nbsp;Prev</b>" : "<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=$topicid&amp;page=".($page - 1)."'><b>&lt;&lt;&nbsp;Prev</b></a>");
    $pmlb = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $pagemenu3 = ($page == $pages ? "<b>Next&nbsp;&gt;&gt;</b></p>" : "<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=$topicid&amp;page=".($page + 1)."'><b>Next&nbsp;&gt;&gt;</b></a></p>");
    
    $menu = $pagemenu1.$pmlb.$pagemenu2.$pmlb.$pagemenu3;
	}
	
	if ($use_poll_mod && is_valid_id($pollid))
	{
    require_once "forum_poll_functions.php";
	}
	
	$fnav = "<a name='top'></a><div class='fnav'>
	<a href='{$_SERVER['PHP_SELF']}'>{$lang['forums_title']}</a> --&gt; 
	<a href='{$_SERVER['PHP_SELF']}?action=viewforum&amp;forumid={$forumid}'>{$forum}</a>
	 --&gt; ".htmlsafechars($subject)."
	<br /><a href='{$TBDEV['baseurl']}/subscriptions.php?topicid=$topicid&amp;subscribe=1'><b><font color='red'>Subscribe to Forum</font></b></a>
	</div>\n";
  
  $buttons = '';
  $fastrepbtn = '';
  $fastreply = '';
  $replybtn = '';
  
  $maypost = ($CURUSER['class'] >= $minwrite && $CURUSER['class'] >= $mincreate);
  
  if ($locked && $CURUSER['class'] < UC_MODERATOR && !isMod($forumid)) 
  {
    $buttons .= "<span class='fbtn nocreate'>{$lang['forum_topic_view_locked']}</span>\n";
  } 
  else 
  {
        //$arr = get_forum_access_levels($forumid);

    if ($CURUSER['class'] < $minwrite) 
    {
      $buttons .= "<span class='fbtn nocreate'>{$lang['forum_topic_view_permission']}</span>\n";
      $maypost = false;
    } 
    else
    {
      $maypost = true;
    }
  }

	
	if ($maypost)
	{
    $replybtn = "&nbsp;<span class='fbtn'><a href='{$TBDEV['baseurl']}/forums.php?action=reply&amp;topicid={$topicid}'>Add Reply</a></span>\n";
    
    $fastrepbtn = "<span class='fbtn'><a href='#' onclick=\"showhide('fastreply'); return(false);\">Fast Reply</a></span>\n";
    
    $fastreply = insert_fastreply(array('forumid' => $forumid, 'topicid' => $topicid));
	}
  
  
  $buttons = "<div style='text-align:right;margin:10px 0px 10px 0px;'>
  <span class='fbtn'><a href='forums.php?action=search'>{$lang['forums_search']}</a></span>
  &nbsp;<span class='fbtn'><a href='forums.php?action=viewunread'>{$lang['forums_view_unread']}</a></span>
  &nbsp;<span class='fbtn'><a href='forums.php?action=catchup'>{$lang['forums_catchup']}</a></span>{$replybtn}
  </div>";
  
  // ------ Forum quick jump drop-down
  $jump = insert_quick_jump_menu($forumid);
  
  $HTMLOUT .="<div class='tb_table_outer_wrap'>{$fnav}$buttons
  <div class='tb_table_inner_wrap'>
    <span style='color:#ffffff;'>{$forum}</span>
  </div>
  
  

  <script  type='text/javascript'>
  /*<![CDATA[*/
  function confirm_att(id)
  {
     if(confirm('Are you sure you want to delete this ?'))
     {
      window.open('".$_SERVER['PHP_SELF']."?action=attachment&amp;subaction=delete&amp;attachmentid='+id,'attachment','toolbar=no, scrollbars=yes, resizable=yes, width=600, height=250, top=50, left=50');
      window.location.reload(true)
     }
  }
      function popitup(url) {
      newwindow=window.open(url,'./usermood.php','height=335,width=735,resizable=no,scrollbars=no,toolbar=no,menubar=no');
      if (window.focus) {newwindow.focus()}
      return false;
      }
  /*]]>*/
  </script>";

    // ------ echo table
    //$HTMLOUT .= begin_frame();
    $res = mysql_query("SELECT p.id, p.added, p.userid, p.added, p.body, p.editedby, p.editedat, p.posticon, p.anonymous as p_anon, u.id as uid, u.username as uusername, u.class, u.avatar, u.donor, u.title, u.username, u.ip, u.reputation, u.mood, u.anonymous, u.country, u.enabled, u.warned, u.uploaded, u.downloaded, u.signature, u.last_access, (SELECT COUNT(id)  FROM posts WHERE userid = u.id) AS posts_count, u2.username as u2_username " . ($use_attachment_mod ? ", at.id as at_id, at.filename as at_filename, at.postid as at_postid, at.size as at_size, at.downloads as at_downloads, at.owner as at_owner " : "") . ", (SELECT lastpostread FROM readposts WHERE userid = " . sqlesc((int)$CURUSER['id']) . " AND topicid = p.topicid LIMIT 1) AS lastpostread " . "FROM posts AS p " . "LEFT JOIN users AS u ON p.userid = u.id " .
        ($use_attachment_mod ? "LEFT JOIN attachments AS at ON at.postid = p.id " : "") . "LEFT JOIN users AS u2 ON u2.id = p.editedby " . "WHERE p.topicid = " . sqlesc($topicid) . " ORDER BY id LIMIT $offset, $perpage") or sqlerr(__FILE__, __LINE__);
    $pc = mysql_num_rows($res);
    $pn = 0;
    $cboxelement = array();

    while ($arr = mysql_fetch_assoc($res)) 
    {
        ++$pn;

        $lpr = $arr['lastpostread'];
        $postid = (int)$arr["id"];
        $postadd = $arr['added'];
        $posterid = (int)$arr['userid'];
        $posticon = ($arr["posticon"] > 0 ? "<img src='{$TBDEV['forum_pic_url']}post_icons/icon{$arr["posticon"]}.gif' style='padding-left:3px;' alt='post icon' title='post icon' />" : "&nbsp;");
        $added = get_date($arr['added'], 'LONG',1,0);
        
 	      $newp = '';
        if (($postid > $lpr) && ($postadd > (time() - $TBDEV['readpost_expiry'])))
        {
            $newp = "&nbsp;&nbsp;<span class='red'>(New)</span>";
        }
        
        
    ////////////// gather user details ///////////////////////
        $ip = $arr['ip'];
        $uploaded = mksize($arr['uploaded']);
        $downloaded = mksize($arr['downloaded']);
        $member_reputation = $arr['uusername'] != '' ? get_reputation($arr, 'posts') : '';
        $last_access = get_date($arr['last_access'],'DATE',1,0);
        if ($arr['downloaded'] > 0) 
        {
          $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
          $ratio = "<font color='" . get_ratio_color($ratio) . "'>$ratio</font>";
       	} 
       	else
       	{ 
          if ($arr['uploaded'] > 0)
          $ratio = "&infin;";
          else
          $ratio = "---";
 	      }
 	      
        foreach($mood as $key => $value)
        {
          $change[$value['id']] = array('id' => $value['id'], 'name' => $value['name'], 'image' => $value['image']);
        }
        
        $mooduname = isset($change[$arr['mood']]['name']) ? $change[$arr['mood']]['name'] : '';
        $moodupic = isset($change[$arr['mood']]['image']) ? $change[$arr['mood']]['image'] : '';
        $title = $arr["title"];
        
        $signature = ($CURUSER['signatures'] == 'yes' ? format_comment($arr['signature']) : '');
        //$signature = ($CURUSER['signature'] !== NULL ? format_comment($arr['signature']) : '');
        $postername = $arr['uusername'];
        
        $avatar = ($CURUSER["avatars"] == 'yes' ? htmlsafechars($arr["avatar"]) : "");
        
        $title = (!empty($postername) ? (empty($arr['title']) ? "(" . get_user_class_name($arr['class']) . ")" : "(" . ($arr['title']) . ")") : '');
        
        $forumposts = (!empty($postername) ? ($arr['posts_count'] != 0 ? $arr['posts_count'] : 'N/A') : 'N/A');
        
        $on_offline = "<img src='{$TBDEV['forum_pic_url']}".($last_access > (time()-360) || $posterid == $CURUSER['id'] ? 'on' : 'off')."line.gif' border='0' alt='on/off' />";
        
        $pm = "<a href='{$TBDEV['baseurl']}/sendmessage.php?receiver={$posterid}'><img src='{$TBDEV['forum_pic_url']}/pm.gif' border='0' alt='Pm ".htmlsafechars($postername)."' /></a>";
        
        
        /////// this section is post head bar /////////
 			  if ($arr["p_anon"] == 'yes') 
 			  {
          if($CURUSER['class'] < UC_MODERATOR && $arr['userid'] != $CURUSER["id"])
          $by = "<i>Anonymous</i>";
          else
          $by = "<i>Anonymous</i>(<a href='{$TBDEV['baseurl']}/userdetails.php?id=$posterid'>{$postername}</a>)".($arr['donor'] == "yes" ? "<img src='{$TBDEV['pic_base_url']}star.gif' alt='Donor' />" : '').($arr['enabled'] == 'no' ? "<img src='{$TBDEV['pic_base_url']}disabled.gif' alt='This account is disabled' style='margin-left: 2px' />" : ($arr['warned'] == 'yes'? "<img src='{$TBDEV['pic_base_url']}warned.gif' alt='Warned' border='0' />" : ''));	
        }
        else 
        {	
          $by = (!empty($postername) ? "<a href='{$TBDEV['baseurl']}/userdetails.php?id=$posterid'><strong>{$postername}</strong></a>&nbsp;$ip&nbsp;".($arr['donor'] == "yes" ? "<img src='{$TBDEV['pic_base_url']}star.gif' alt='Donor' />" : '').($arr['enabled'] == 'no' ? "<img src='{$TBDEV['pic_base_url']}disabled.gif' alt='This account is disabled' style='margin-left: 2px' />" : ($arr['warned'] == 'yes'? "<img src='{$TBDEV['pic_base_url']}warned.gif' alt='Warned' border='0' />" : '')) : "unknown[".$posterid."]");	
        }
        /////////////////////////////////// end
        
        
        if (empty($avatar))
            $avatar = $TBDEV['forum_pic_url'].'default_avatar.gif';
            
        $last ="". ($pn == $pc ? '<a name=\'last\'></a>' : '');
        
        $HTMLOUT .= "
        <div class='post_wrap'>
        <div class='post_head'>$last
        <span style='float:left;'>{$posticon}&nbsp;&nbsp;$by&nbsp</span>
        <span>{$newp}Post&nbsp;<a  id='p".$postid."' name='p{$postid}' href='{$_SERVER['PHP_SELF']}?action=viewtopic&amp;topicid={$topicid}&amp;page=p{$postid}#p{$postid}'>#{$postid}</a>&nbsp;<a href='#top'><img src='{$TBDEV['forum_pic_url']}top.gif' border='0' alt='{$lang['forum_topic_view_top']}' /></a></span>
        </div>\n";


        $highlight = (isset($_GET['highlight']) ? $_GET['highlight'] : '');
        
        $body = (!empty($highlight) ? highlight(htmlsafechars(trim($highlight)), format_comment($arr['body'])) : format_comment($arr['body']));

       if (is_valid_id($arr['editedby']))
       {
        $body .= "<div class='fedited_by'>{$lang['forum_topic_view_edit_by']}<a href='{$TBDEV['baseurl']}/userdetails.php?id={$arr['editedby']}'><strong>{$arr['u2_username']}</strong></a> on ".get_date( $arr['editedat'],'')."</div>\n";
       }
		
/*		   if ($use_attachment_mod && ((!empty($arr['at_filename']) && is_valid_id($arr['at_id'])) && $arr['at_postid'] == $postid))
		   {
         foreach ($allowed_file_extensions as $allowed_file_extension)
         {
          if (substr($arr['at_filename'], -3) == $allowed_file_extension)
            $aimg = $allowed_file_extension;
         }
        
        $body .= "<div style='padding:6px'>
            <fieldset class='fieldset'>
            <legend>Attached Files</legend>
            <table cellpadding='0' cellspacing='3' border='0'>
            <tr>
            <td><img class='inlineimg' src='{$TBDEV['pic_base_url']}$aimg.gif' alt='' width='16' height='16' border='0' style='vertical-align:baseline' />&nbsp;</td>
            <td><a href='".$_SERVER['PHP_SELF']."?action=attachment&amp;attachmentid=".$arr['at_id']."' target='_blank'>".htmlsafechars($arr['at_filename'])."</a> (".mksize($arr['at_size']).", ".$arr['at_downloads']." downloads)</td>
            <td>&nbsp;&nbsp;<input type='button' class='none' value='See who downloaded' tabindex='1' onclick=\"window.open('".$_SERVER['PHP_SELF']."?action=whodownloaded&amp;fileid=".$arr['at_id']."','whodownloaded','toolbar=no, scrollbars=yes, resizable=yes, width=600, height=250, top=50, left=50'); return false;\" />".($CURUSER['class'] >= UC_MODERATOR ? "&nbsp;&nbsp;<input type='button' class='gobutton' value='Delete' tabindex='2' onclick=\"window.open('".$_SERVER['PHP_SELF']."?action=attachment&amp;subaction=delete&amp;attachmentid=".$arr['at_id']."','attachment','toolbar=no, scrollbars=yes, resizable=yes, width=600, height=250, top=50, left=50'); return false;\" />" : "")."</td>
            </tr>
            </table>
            </fieldset>
            </div>";
      } */
					
		  if (!empty($signature) && $arr["p_anon"] == "no")
		  {
        $body .= "<div style='margin-top:10px;'><hr /><p style='margin-top:10px; vertical-align:bottom'>{$signature}</p></div>";
      }
      
      
      /////////// this part is the left user details section ///////////////
      $author_info = '';
      
      if ($arr["p_anon"] == "yes") 
      {
        if($CURUSER['class'] < UC_MODERATOR && $posterid != $CURUSER["id"])
        {
        $author_info ="
        <ul>
        <li><img width='150' src='{$TBDEV['forum_pic_url']}default_avatar.gif' alt='Avatar' /></li>
        </ul>";
        }
        else
        {
        $author_info ="
        <ul>
        <li class='avatar'><img width='100' height='100' src='{$avatar}' alt='Avatar' /></li>
        <li class='title'>{$title}</li>
        </ul>
        <ul class='fields'>
        <li><span class='lu'>Posts:</span>&nbsp;<span class='ru'>{$forumposts}</span></li>
        <li><span class='lu'>Ratio:</span>&nbsp;<span class='ru'>{$ratio}</span></li>
        <li><span class='lu'>Uploaded:</span>&nbsp;<span class='ru'>{$uploaded}</span></li>
        <li><span class='lu'>Downloaded:</span>&nbsp;<span class='ru'>{$downloaded}</span></li>
        </ul>";
        }
      }
      else 
      {
        $author_info ="
        <ul style='margin-left:20px;'>
        <li><img width='100' height='100' src='{$avatar}' alt='Avatar' /></li>
        <li class='title'>{$title}</li>
        </ul>
        <ul class='fields'>
        <li><span class='lu'>Posts:</span>&nbsp;<span class='ru'>{$forumposts}</span></li>
        <li><span class='lu'>Ratio:</span>&nbsp;<span class='ru'>{$ratio}</span></li>
        <li><span class='lu'>Uploaded:</span>&nbsp;<span class='ru'>{$uploaded}</span></li>
        <li><span class='lu'>Downloaded:</span>&nbsp;<span class='ru'>{$downloaded}</span></li>
        <li class='center'>{$member_reputation}</li>
        <li><span class='lu'>PM:</span>&nbsp;<span class='ru'>{$pm}</span></li>
        <li class='center'>{$on_offline}&nbsp;";
      }
      //////////////////////////// end      
		  
		  $mooduser = (isset($arr['username']) ? (htmlsafechars($arr['username'])) : "(unknown)");
		  
      $moodanon = ($arr['anonymous'] == 'yes' ? ($CURUSER['class'] < UC_MODERATOR && $arr['userid'] != $CURUSER['id'] ? '' : $mooduser.' - ')."Anonymous" : $mooduser);	
      
      //$author_info .="<a href='{$TBDEV['baseurl']}/forums/usermood.php' onclick=\"return popitup('{$TBDEV['baseurl']}/forums/usermood.php')\">
      $author_info .="<a href='{$TBDEV['baseurl']}/forums/usermood.php?action=usermood' onclick=\"return popitup('{$TBDEV['baseurl']}/forums.php?action=usermood')\">
      <img border='0' src='{$TBDEV['pic_base_url']}smilies/".htmlsafechars($moodupic)."' alt='".htmlsafechars($mooduname)."' title='{$moodanon}&nbsp;".htmlsafechars($mooduname)."!' /></a></li></ul>";
		  
		  
		  $quotebtn = '';
      $editbtn = '';
      $deletebtn = '';
        
        if (!$locked || $CURUSER['class'] >= UC_MODERATOR || isMod($forumid)) 
        {
          if ($arr["p_anon"] == "yes") 
          {
            if($CURUSER['class'] < UC_MODERATOR)
            $quotebtn .="";
          }
          else
          $quotebtn .="<span class='user_control_fbtn'><a href='{$_SERVER['PHP_SELF']}?action=quotepost&amp;topicid={$topicid}&amp;postid=$postid'>{$lang['forum_topic_view_quote']}</a></span>"; 
 			  }
        else 
        {
          $quotebtn .="<span class='user_control_fbtn'><a href='{$_SERVER['PHP_SELF']}?action=quotepost&amp;topicid={$topicid}&amp;postid=$postid'>{$lang['forum_topic_view_quote']}</a></span>";
		    }


        $report ="<span class='user_control_fbtn'><a href='{$TBDEV['baseurl']}/report.php?type=Post&amp;id={$postid}&amp;id_2={$topicid}&amp;id_3={$posterid}'>Report</a></span>";

        if ($CURUSER['class'] >= UC_MODERATOR || isMod($forumid)) {
        $deletebtn .="<span class='user_control_fbtn'><a href='{$_SERVER['PHP_SELF']}?action=deletepost&amp;postid={$postid}'>{$lang['forum_topic_view_delete']}</a></span>";
        }

        if (($CURUSER["id"] == $posterid && !$locked) || $CURUSER['class'] >= UC_MODERATOR || isMod($forumid)) {
        $editbtn .="<span class='user_control_fbtn'><a href='{$_SERVER['PHP_SELF']}?action=editpost&amp;postid={$postid}'>{$lang['forum_topic_view_edit']}</a></span>";
        }

        $HTMLOUT .="<div class='author_info'>
        $author_info
        </div>
        <div class='post_body'>
        <div class='post_time'>Posted $added</div>
        {$body}
        </div>
        <div class='post_footer'>
          <span style='float:left;'>{$report}</span>
          <span>{$quotebtn}{$editbtn}{$deletebtn}</span></div>
        </div>";
    }

    if ($use_poll_mod && (($userid == $t_userid || $CURUSER['class'] >= UC_MODERATOR || isMod($forumid)) && !is_valid_id($pollid))) 
    {

      $HTMLOUT .="<div>
      <form method='post' action='{$_SERVER['PHP_SELF']}'>
      <input type='hidden' name='action' value='makepoll' />
      <input type='hidden' name='topicid' value='{$topicid}' />
      <input type='submit' value='Add a Poll' />
      </form>
      </div>";
   
    }
    
    $HTMLOUT .= "{$menu}<div style='text-align:right;margin:10px 0px 10px 0px;'>{$replybtn}{$fastrepbtn}</div>{$fastreply}</div>";
    
    if (($postid > $lpr) && ($postadd > (time() - $TBDEV['readpost_expiry']))) {
        if ($lpr)
            mysql_query("UPDATE readposts SET lastpostread = $postid WHERE userid = $userid AND topicid = $topicid") or sqlerr(__FILE__, __LINE__);
        else
            mysql_query("INSERT INTO readposts (userid, topicid, lastpostread) VALUES($userid, $topicid, $postid)") or sqlerr(__FILE__, __LINE__);
    }
    
    // ------ Mod options
    if ($CURUSER['class'] >= UC_MODERATOR || isMod($forumid)) 
    {
      require_once "forum_mod_panel.php";
	  }
	  
    //$fcss = "<link rel='stylesheet' type='text/css' href='templates/1/colorbox.css' />";
    
    $cbox = '';
    foreach($cboxelement as $v )
    {
      $cbox .= "$(\"a[rel='{$v}']\").colorbox({transition:'fade'});";
    }
    
    $js = "<script src='./scripts/jquery.colorbox.js' type='text/javascript'></script>
    <script type='text/javascript' src='./scripts/popup.js'></script>
    <script type='text/javascript' src='./scripts/show_hide.js'></script>
    <script type='text/javascript'>
    $(document).ready(function(){
			{$cbox}
		});
  </script>
    ";
    
    print stdhead($lang['forum_topic_view_view_topic'].$subject, $js, $fcss) . $HTMLOUT . stdfoot();

    //$uploaderror = (isset($_GET['uploaderror']) ? htmlsafechars($_GET['uploaderror']) : '');
/*
  if (!empty($uploaderror))
	{
	$HTMLOUT .="<script>alert(\"Upload Failed: {$uploaderror}\nHowever your post was successful saved!\n\nClick 'OK' to continue.\");</script>";
	}
*/	
	exit();
	


?>