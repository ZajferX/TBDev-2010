<?php

// -------- Action: Reply
  $topicid = (int)$_GET["topicid"];
  
  if (!is_valid_id($topicid))
      stderr('Error', 'Invalid ID!');

  $res = mysql_query("SELECT t.forumid, t.subject, t.locked, f.minclassread FROM topics AS t LEFT JOIN forums AS f ON f.id = t.forumid WHERE t.id = ".sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
  $arr = mysql_fetch_assoc($res) or stderr($lang['forum_functions_error'], $lang['forum_functions_topic']);
  
  if ($arr['locked'] == 'yes') 
  {
    stderr("Sorry", "The topic is locked.");

    $HTMLOUT .= end_table();
    $HTMLOUT .= end_main_frame();
    print stdhead("Compose", '', $fcss) . $HTMLOUT . stdfoot();
    exit();
  }

  if($CURUSER["class"] < $arr["minclassread"])
  {
    $HTMLOUT .= stdmsg("Sorry", "You are not allowed in here.");
    $HTMLOUT .= end_table(); 
    $HTMLOUT .= end_main_frame(); 
    print stdhead("Compose", '', $fcss) . $HTMLOUT . stdfoot();
    exit();
  }

  $subject = htmlsafechars($arr["subject"]);

  $HTMLOUT .= "<p style='text-align:center;'>{$lang['forum_functions_reply']}<a href='forums.php?action=viewtopic&amp;topicid=$topicid'>$subject</a></p>";
  
  
  //$body = isset($_POST["body"]) ? strip_tags( trim($_POST["body"]) ) : '';
  $title = '';
  
  $HTMLOUT .= begin_main_frame();
  
  if ($TBDEV['forums_online'] == 0)
  $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
  
  // Preview stuff
  $HTMLOUT .= "<div id='prediv' style='margin-bottom:30px;'>" .
  begin_frame('Preview Post', true) .
  "<div id='preshow' style='text-align:left;border: 0;'>
  Preview Here
  </div>" . end_frame() . "</div>";
  // preview end
  
  
  $HTMLOUT .= begin_frame("Compose", true);
  $HTMLOUT .="<form name='bbcode2text' method='post' action='forums.php?action=post' enctype='multipart/form-data'>
  <input type='hidden' name='action' value='post' />
  <input type='hidden' name='topicid' value='{$topicid}' />";
  
  $HTMLOUT .= bbcode2textarea( 'body' );
  
  $HTMLOUT .="<div>".(post_icons())."</div>
  <div>
  <input type='button' value='Preview' name='preview' />
  Anonymous Post<input type='checkbox' name='anonymous' value='yes'/>
  </div>
  <div align='center'>
    <input type='submit' name='postquickreply' value='{$lang['forum_functions_submit']}' class='' />
  </div>
  </form>";

  $HTMLOUT .= end_frame();
  
  $HTMLOUT .= end_main_frame();
  
  $js = "<script type='text/javascript' src='scripts/bbcode2text.js'></script>\n";
  $js .= "
    <script type='text/javascript'>
    /* <![CDATA[ */
    $(document).ready(function() {
     
    $('#prediv').hide(); 
    $('input[name=\"preview\"]').click(function(){
    var body = $('textarea[name=\"body\"]').val(); 
    var data = 'body=' + encodeURIComponent(body); 
    alert(data);
    $('#preshow').html('<span><img src=\'templates/1/images/ajax-loader.gif\' alt=\'\' /></span>');
    $('#prediv').fadeIn('slow'); 
    $.ajax({ 
    type:'POST', 
    data: data,
    url: 'forums.php?action=preview',        
    success: function (html) {                  
      $('#preshow').html(html); 
    }   
    });
    });
     
    });
    /* ]]> */
    </script>";
  
  print stdhead($lang['forum_reply_reply'], $js, $fcss) . $HTMLOUT . stdfoot();
  exit();


?>