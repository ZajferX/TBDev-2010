<?php

// -------- Action: New topic
  $forumid = (int)$_GET["forumid"];

  if (!is_valid_id($forumid))
      stderr('Error', 'Invalid ID!');
      
  $res = mysql_query("SELECT name FROM forums WHERE id = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);
  $arr = mysql_fetch_assoc($res) or die("Bad forum ID!");

  $body = isset($_POST["body"]) ? trim($_POST["body"]) : '';
  $subject = (isset($_POST['subject']) AND $_POST['subject'] != '') ? htmlsafechars($_POST['subject']) : 'Your subject here';
  //print_r($_POST);
  $HTMLOUT .= begin_main_frame();

  if ($TBDEV['forums_online'] == 0)
  $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode'); 
  
  $HTMLOUT .="<h3>New topic in <a href='{$_SERVER['PHP_SELF']}?action=viewforum&amp;forumid=".$forumid."'>".htmlspecialchars($arr["name"])."</a> forum</h3>";
   
  if( $body != '' )
  {
    $HTMLOUT .= begin_frame("Preview Post", true);
    $HTMLOUT .="
    <div style='text-align:left;border: 0;'>
    <div><strong>$subject</strong></div>
    <p>".format_comment($body)."</p>
    </div>";
    $HTMLOUT .= end_frame();
  }

  $HTMLOUT .= "<script  type='text/javascript'>
  /*<![CDATA[*/
  function Preview()
  {
  document.bbcode2text.action = './forums.php?action=newtopic&forumid=$forumid'
  //document.bbcode2text.target = '_blank';
  document.bbcode2text.submit();
  return true;
  }
  /*]]>*/
  </script>";
    
  $HTMLOUT .= begin_frame("Compose", true);
  $HTMLOUT .="<form method='post' name='bbcode2text' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>
  <input type='hidden' name='action' value='post' />
  <input type='hidden' name='forumid' value='$forumid' />
  <div align='center'>
    <input style='width:615px;' type='text' name='subject' size='50' value='{$subject}' />
  </div>";

  $HTMLOUT .= bbcode2textarea( 'body', $body );

  $HTMLOUT .="<div>".(post_icons())."</div>
  <div>
  <input type='button' value='Preview' name='button2' onclick='return Preview();' />
  Anonymous Topic<input type='checkbox' name='anonymous' value='yes'/>
  </div>
  <div align='center'>
  <input type='submit' name='addpost' value='{$lang['forum_functions_submit']}' class='' />
  </div>
  </form>";

  $HTMLOUT .= end_frame();

  $HTMLOUT .= end_main_frame();
  
  $js = "<script type='text/javascript' src='scripts/bbcode2text.js'></script>";
  
  print stdhead($lang['forum_new_topic_newtopic'], $js, $fcss) . $HTMLOUT . stdfoot();
  exit();

?>