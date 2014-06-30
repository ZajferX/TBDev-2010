<?php

// -------- Action: Edit post
  $postid = (int)$_GET["postid"];
  if (!is_valid_id($postid))
      stderr('Error', 'Invalid ID!');

  $res = mysql_query("SELECT p.userid, p.topicid, p.posticon, p.body, t.locked,t.forumid  FROM posts AS p LEFT JOIN topics AS t ON t.id = p.topicid WHERE p.id = " . sqlesc($postid)) or sqlerr(__FILE__, __LINE__);

  if (mysql_num_rows($res) == 0)
      stderr("Error", "No post with that ID!");

  $arr = mysql_fetch_assoc($res);

  if (($CURUSER["id"] != $arr["userid"] || $arr["locked"] == 'yes') && $CURUSER['class'] < UC_MODERATOR && !isMod($arr["forumid"]))
      stderr("Error", "Access Denied!");

  if ($_SERVER['REQUEST_METHOD'] == 'POST') 
  {
      $body = trim($_POST['body']);
      $posticon = (isset($_POST["iconid"]) ? 0 + $_POST["iconid"] : 0);
      if (empty($body))
          stderr("Error", "Body cannot be empty!");

      if(!isset($_POST['lasteditedby']))
      mysql_query("UPDATE posts SET body = " . sqlesc($body) . ", editedat = " . time() . ", editedby = {$CURUSER['id']}, posticon = $posticon WHERE id = $postid") or sqlerr(__FILE__, __LINE__);
      else
      mysql_query("UPDATE posts SET body = " . sqlesc($body) . ", posticon = $posticon WHERE id = $postid") or sqlerr(__FILE__, __LINE__);

      header("Location: {$_SERVER['PHP_SELF']}?action=viewtopic&topicid={$arr['topicid']}&page=p$postid#p$postid");
      exit();
  }
  
  $ebody = unesc($arr["body"]);
  
  if ($TBDEV['forums_online'] == 0)
  $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
  
  $HTMLOUT .= begin_main_frame();
  
  $HTMLOUT .="<h3>Edit Post</h3>";
  
  $HTMLOUT .= begin_frame("Compose", true);
  //<form name='bbcode2text' method='post' action='{$_SERVER['PHP_SELF']}?action=editpost&amp;postid=$postid'>
  $HTMLOUT .= "<form name='bbcode2text' method='post' action='{$_SERVER['PHP_SELF']}?postid=$postid'>
  
  <input type='hidden' name='action' value='editpost' />
  <input type='hidden' name='postid' value='{$postid}' />";
  
  $HTMLOUT .= bbcode2textarea( 'body', $ebody);
  
  if ($CURUSER["class"] >= UC_MODERATOR)
  {
    $HTMLOUT.="<input type='checkbox' name='lasteditedby' />Don't show the Last edited by <font class='small'>(Staff Only)</font>";
  }
  
  
  $HTMLOUT.="<div>".(post_icons())."</div>
  <div align='center'>
                <input type='submit' name='editpost' value='{$lang['forum_functions_submit']}' class='' />
  </div>
  </form>";

  $HTMLOUT .= end_frame();
  $HTMLOUT .= end_main_frame();
  
  $js = "<script type='text/javascript' src='scripts/bbcode2text.js'></script>";
  
  print stdhead("Edit Post", $js, $fcss) . $HTMLOUT . stdfoot();
  exit();


?>