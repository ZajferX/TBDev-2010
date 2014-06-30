<?php


// -------- Action: Post
  $forumid = (isset($_POST['forumid']) ? (int)$_POST['forumid'] : null);
  
  if (isset($forumid) && !is_valid_id($forumid))
    stderr('Error', 'Invalid forum ID!');

  $posticon = (isset($_POST["iconid"]) ? 0 + $_POST["iconid"] : 0);
  $topicid = (isset($_POST['topicid']) ? (int)$_POST['topicid'] : null);
  
  if (isset($topicid) && !is_valid_id($topicid))
    stderr('Error', 'Invalid topic ID!');

  $newtopic = is_valid_id($forumid);

  $subject = (isset($_POST["subject"]) ? $_POST["subject"] : '');

  if( $newtopic AND !isset($_POST['fastreply']) )
  {
    $subject = trim($subject);

    if (empty($subject))
        stderr("Error", "You must enter a subject.");

    if (strlen($subject) > $maxsubjectlength)
        stderr("Error", "Subject is limited to " . $maxsubjectlength . " characters.");
  } 
  else
  {
    $forumid = get_topic_forum($topicid) or die("Bad topic ID");
  }

  // ------ Make sure sure user has write access in forum
  $arr = get_forum_access_levels($forumid) or die("Bad forum ID");

  if ($CURUSER['class'] < $arr["write"] || ($newtopic && $CURUSER['class'] < $arr["create"]) && !isMod($forumid))
      stderr("Error", "Permission denied.");

  $body = trim($_POST["body"]);

  if (empty($body))
      stderr("Error", "No body text.");

  $userid = (int)$CURUSER["id"];

  if ($use_flood_mod && $CURUSER['class'] < UC_MODERATOR && !isMod($forumid)) 
  {
    $res = mysql_query("SELECT COUNT(id) AS c FROM posts WHERE userid = " . $CURUSER['id'] . " AND added > '" . (time() - ($minutes * 60)) . "'");
    $arr = mysql_fetch_assoc($res);

    if ($arr['c'] > $limit)
        stderr("Flood", "More than {$limit} posts in the last {$minutes} minutes.");
  }
  if ( $newtopic AND !isset($_POST['fastreply']) )
  {
    $subject = sqlesc($subject);
    $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
    mysql_query("INSERT INTO topics (userid, forumid, subject, anonymous) VALUES($userid, $forumid, $subject, ".sqlesc($anonymous).")") or sqlerr(__FILE__, __LINE__);
    $topicid = mysql_insert_id() or stderr("Error", "No topic ID returned!");

    $added = sqlesc(time());
    $body = sqlesc($body);
    $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
    mysql_query("INSERT INTO posts (topicid, userid, added, body, anonymous, posticon) VALUES($topicid, $userid, $added, $body, ".sqlesc($anonymous).",$posticon)") or sqlerr(__FILE__, __LINE__);
    
    $postid = mysql_insert_id() or stderr("Error", "No post ID returned!");
    
    update_topic_last_post($topicid);
    
    if($TBDEV['forums_autoshout_on'] == 1)
    {
      if ($anonymous == 'yes')
      $message = "(Anonymous) Created a new forum thread [url={$TBDEV['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
      else
      $message = $CURUSER['username'] . " Created a new forum thread [url={$TBDEV['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
      
      //////remember to edit the ids to your staffforum ids :)
      if (!in_array($forumid, array("18","23","24","25"))) 
      {
      autoshout($message);
      }
    }
    
    if($TBDEV['forums_seedbonus_on'] == 1)
    {
      mysql_query("UPDATE users SET seedbonus = seedbonus+3.0 WHERE id =  ". sqlesc($CURUSER['id']."")) or sqlerr(__FILE__, __LINE__);
    }
  }
  else
  {
    //---- Make sure topic exists and is unlocked
    $res = mysql_query("SELECT locked, subject FROM topics WHERE id = ".sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
    
    if (mysql_num_rows($res) == 0)
      stderr('Error', 'Inexistent Topic!');

    $arr = mysql_fetch_assoc($res);
    
    $subject = htmlspecialchars($arr["subject"]);
    
    if ($arr["locked"] == 'yes' && $CURUSER['class'] < UC_MODERATOR)
      stderr("Error", "This topic is locked; No new posts are allowed.");
      
     // === PM subscribed members
    $res_sub = mysql_query("SELECT userid FROM subscriptions  WHERE topicid = ".sqlesc($topicid)."") or sqlerr(__FILE__, __LINE__);
    
    while ($row = mysql_fetch_assoc($res_sub)) 
    {
      $res_yes = mysql_query("SELECT subscription_pm, username FROM users WHERE id = ".sqlesc($row["userid"])."") or sqlerr(__FILE__, __LINE__);
      $arr_yes = mysql_fetch_array($res_yes);
      
      $msg = "Hey there!!! \n a thread you subscribed to: " .htmlspecialchars($arr["subject"]) . " has had a new post!\n click [url=" . $TBDEV['baseurl'] . "/forums.php?action=viewtopic&topicid=" . $topicid . "&page=last][b]HERE[/b][/url] to read it!\n\nTo view your subscriptions, or un-subscribe, click [url=" . $TBDEV['baseurl'] . "/subscriptions.php][b]HERE[/b][/url].\n\ncheers.";
      
      if ($arr_yes["subscription_pm"] == 'yes' && $row["userid"] != $CURUSER["id"])
        mysql_query("INSERT INTO messages (sender, subject, receiver, added, msg) VALUES(".$TBDEV['bot_id'].", 'New post in subscribed thread!', $row[userid], '" . time() . "', " . sqlesc($msg) . ")") or sqlerr(__FILE__, __LINE__);
    }
    // ===end
    
    //------ Check double post     
    $doublepost = mysql_query("SELECT p.id, p.added, p.userid, p.body, t.lastpost, t.id ".
                  "FROM posts AS p ".
                  "INNER JOIN topics AS t ON p.id = t.lastpost ".
                  "WHERE t.id = $topicid AND p.userid = $userid AND p.added > ".(time() - 1*86400)." ".
                  "ORDER BY p.added asc LIMIT 1") or sqlerr(__FILE__, __LINE__);
      if (mysql_num_rows($doublepost) == 0 || $CURUSER['class'] >= UC_MODERATOR)
      {
        $added = sqlesc(time());
        $body = sqlesc($body);
        $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
        mysql_query("INSERT INTO posts (topicid, userid, added, body, anonymous, posticon) VALUES($topicid, $userid, $added, $body, ".sqlesc($anonymous).",$posticon)") or sqlerr(__FILE__, __LINE__);
        
        $postid = mysql_insert_id() or die("Post id n/a");
        
        if($TBDEV['forums_seedbonus_on'] == 1)
        {
        mysql_query("UPDATE users SET seedbonus = seedbonus+2.0 WHERE id = ".sqlesc($userid)."") or sqlerr(__FILE__, __LINE__);
        }
        
        if($TBDEV['forums_autoshout_on'] == 1)
        {
          if ($anonymous == 'yes')
          $message = "(Anonymous) replied to the thread [url={$TBDEV['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]"; 
          else 
          $message = $CURUSER['username'] . " replied to the thread [url={$TBDEV['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]"; 	
          //////remember to edit the ids to your staffforum ids :)
          if (!in_array($forumid, array("18","23","24","25"))) 
          {
            autoshout($message);
          }
        }
        
        $HTMLOUT .= update_topic_last_post($topicid);
      } 
      else 
      {
        $results = mysql_fetch_assoc($doublepost);
        $postid = (int)$results['lastpost'];
        mysql_query("UPDATE posts SET body = " . sqlesc(trim($results['body']) . "\n\n" . $body) . ", editedat = " . time(). ", editedby = $userid, posticon=$posticon WHERE id=$postid") or sqlerr(__FILE__, __LINE__);
      }
  }

/* Off this f#cker!
  if ($use_attachment_mod && ((isset($_POST['uploadattachment']) ? $_POST['uploadattachment'] : '') == 'yes')) 
  {
    $file = $_FILES['file'];

    $fname = trim(stripslashes($file['name']));
    $size = $file['size'];
    $tmpname = $file['tmp_name'];
    $tgtfile = $attachment_dir . "/" . $fname;
    $pp = pathinfo($fname = $file['name']);
    $error = $file['error'];
    $type = $file['type'];

    $uploaderror = '';

    if (empty($fname))
        $uploaderror = "Invalid Filename!";

    if (!validfilename($fname))
        $uploaderror = "Invalid Filename!";

    foreach ($allowed_file_extensions as $allowed_file_extension);
    if (!preg_match('/^(.+)\.[' . join(']|[', $allowed_file_extensions) . ']$/si', $fname, $matches))
        $uploaderror = 'Only files with the following extensions are allowed: ' . join(', ', $allowed_file_extensions) . '.';

    if ($size > $maxfilesize)
        $uploaderror = "Sorry, that file is too large.";

    if ($pp['basename'] != $fname)
        $uploaderror = "Bad file name.";

    if (file_exists($tgtfile))
        $uploaderror = "Sorry, a file with the name already exists.";

    if (!is_uploaded_file($tmpname))
        $uploaderror = "Can't Upload file!";

    if (!filesize($tmpname))
        $uploaderror = "Empty file!";

    if ($error != 0)
        $uploaderror = "There was an error while uploading the file.";

    if (empty($uploaderror)) {
        mysql_query("INSERT INTO attachments (topicid, postid, filename, size, owner, added, type) VALUES ('$topicid','$postid'," . sqlesc($fname) . ", " . sqlesc($size) . ", '$userid', " . time() . ", " . sqlesc($type) . ")") or sqlerr(__FILE__, __LINE__);

        move_uploaded_file($tmpname, $tgtfile);
    }
  }
*/
  $headerstr = "Location: {$_SERVER['PHP_SELF']}?action=viewtopic&topicid=$topicid" . ($use_attachment_mod && !empty($uploaderror) ? "&uploaderror=$uploaderror" : "") . "&page=last";

  header($headerstr . ($newtopic ? '' : "#p$postid"));
  exit();

?>