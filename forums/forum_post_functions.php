<?php

function post_icons($s = 0)
{
    global $TBDEV;
    
    $body = "<table width='100%' cellspacing='0' cellpadding='8' >
				<tr><td width='20%' valign='top' align='right'><strong>Post Icons</strong> <br/>
				<font class='small'>(Optional)</font></td>\n";
    $body .= "<td width='80%' align='left'>\n";

    for($i = 1; $i < 15;$i++) 
    {
        $body .= "<input type='radio' value='" . $i . "' name='iconid' " . ($s == $i ? "checked='checked'" : "") . " />\n<img align='middle' alt='' src='{$TBDEV['forum_pic_url']}post_icons/icon" . $i . ".gif'/>\n";
        if ($i == 7)
            $body .= "<br/>";
    }

    $body .= "<br/><input type='radio' value='0' name='iconid'  " . ($s == 0 ? "checked='checked'" : "") . " />[Use None]\n";
    $body .= "</td></tr></table>\n";

    return $body;
}


// -------- Inserts a quick jump menu
function insert_quick_jump_menu($currentforum = 0){

	global $CURUSER, $TBDEV;
	$htmlout='';
	$htmlout .="
	<form method='get' action='".$_SERVER['PHP_SELF']."' name='jump'>
	<input type='hidden' name='action' value='viewforum' />
	<div align='center'><b>Quick jump:</b>
	<select name='forumid' onchange=\"if(this.options[this.selectedIndex].value != -1){ forms['jump'].submit() }\">";
	$res = mysql_query("SELECT id, name, minclassread FROM forums ORDER BY name") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	if ($CURUSER['class'] >= $arr["minclassread"])
	$htmlout .="<option value='".$arr["id"].($currentforum == $arr["id"] ? " selected" : "")."'>".$arr["name"]."</option>";
  $htmlout .="</select>
	<input type='submit' value='Go!' class='gobutton' />
	</div>
	</form>";
  return $htmlout;
}


//-------- Inserts a compose frame
function insert_compose_frame($id, $newtopic = true, $quote = false, $attachment = false) {

    global $maxsubjectlength, $CURUSER, $TBDEV, $maxfilesize,  $use_attachment_mod, $lang;
    
    $htmlout='';
    $title = '';
    
    if ($newtopic) 
    {
        $res = mysql_query("SELECT name FROM forums WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        $arr = mysql_fetch_assoc($res) or die("Bad forum ID!");

        $forumname = htmlsafechars($arr["name"]);
        
        $htmlout .="<p style='text-align:center;'>{$lang['forum_functions_newtopic']}<a href='forums.php?action=viewforum&amp;forumid=$id'>$forumname</a>{$lang['forum_functions_forum']}</p>\n";
    } 
    else 
    {
        $res = mysql_query("SELECT t.forumid, t.subject, t.locked, f.minclassread FROM topics AS t LEFT JOIN forums AS f ON f.id = t.forumid WHERE t.id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        $arr = mysql_fetch_assoc($res) or stderr($lang['forum_functions_error'], $lang['forum_functions_topic']);
  
        if ($arr['locked'] == 'yes') 
        {
            stderr("Sorry", "The topic is locked.");

            $htmlout .= end_table();
            $htmlout .= end_main_frame();
            print stdhead("Compose", '', $fcss) . $htmlout . stdfoot();
            exit();
        }
        
        if($CURUSER["class"] < $arr["minclassread"])
        {
		    $htmlout .= stdmsg("Sorry", "You are not allowed in here.");
				$htmlout .= end_table(); 
				$htmlout .= end_main_frame(); 
				print stdhead("Compose", '', $fcss) . $htmlout . stdfoot();
		    exit();
		    }
		    
        $subject = htmlsafechars($arr["subject"]);

      $htmlout .= "<p style='text-align:center;'>{$lang['forum_functions_reply']}<a href='forums.php?action=viewtopic&amp;topicid=$id'>$subject</a></p>";

    }
     
    /*$htmlout .="
    <script  type='text/javascript'>
    
    function Preview()
    {
    document.bbcode2text.action = './forums.php?action=reply&topicid=$id'
    //document.bbcode2text.target = '_blank';
    document.bbcode2text.submit();
    return true;
    }
    
    </script>";*/
    
      
    $htmlout .= begin_frame("Compose", true);
    $htmlout .="<form name='bbcode2text' method='post' action='forums.php?action=post' enctype='multipart/form-data'>
	  <input type='hidden' name='action' value='post' />
	  <input type='hidden' name='". ($newtopic ? 'forumid' : 'topicid')."' value='".$id."' />";

    //$htmlout .= begin_table(true);

    if ($newtopic) 
    {
       $htmlout .= "<div align='center'>
       <input style='width:615px;' type='text' name='subject' size='50' value='' />
       </div>";
    }

    if ($quote) 
    {
        $postid = (int)$_GET["postid"];
        if (!is_valid_id($postid)) {
            stderr("Error", "Invalid ID!");

            $htmlout .= end_table();
            $htmlout .= end_main_frame();
            print stdhead("Compose", '', $css) . $htmlout . stdfoot();
            exit();
        }

        $res = mysql_query("SELECT posts.*, users.username FROM posts JOIN users ON posts.userid = users.id WHERE posts.id = $postid") or sqlerr(__FILE__, __LINE__);

        if (mysql_num_rows($res) == 0) {
            stderr("Error", "No post with this ID");

            $htmlout .= end_table();
            $htmlout .= end_main_frame();
            print stdhead("Error - No post with this ID", '', $fcss) . $htmlout . stdfoot();
            exit();
        }

        $arr = mysql_fetch_assoc($res);
    }

    
		$body = ($quote ? "[quote=".htmlsafechars($arr["username"])."]".htmlsafechars(unesc($arr["body"]))."[/quote]" : "");
		
		$htmlout .= bbcode2textarea( 'body', $body );
		
		/*
		if ($use_attachment_mod && $attachment)
		{
		$htmlout .="<tr>
				<td colspan='2'><fieldset class='fieldset'><legend>Add Attachment</legend>
				<input type='checkbox' name='uploadattachment' value='yes' />
				<input type='file' name='file' size='60' />
        <div class='error'>Allowed Files: rar, zip<br />Size Limit ".mksize($maxfilesize)."</div></fieldset>
				</td>
			</tr>";
    }
		 */
		  $htmlout .="<div>".(post_icons())."</div>
 		  <div>
 		  <input type='button' value='Preview' name='preview' />\n";
 	    //<input type='button' value='Preview' name='button2' onclick='return Preview();' />\n";
      
      if ($newtopic)
      {
      $htmlout .= "Anonymous Topic<input type='checkbox' name='anonymous' value='yes'/>\n";
      }
      else
      {
      $htmlout .= "Anonymous Post<input type='checkbox' name='anonymous' value='yes'/>\n";
      }
      $htmlout .= "</div>\n";


    //$htmlout .= end_table();

    $htmlout .="<div align='center'>
                <input type='submit' name='postreply' value='{$lang['forum_functions_submit']}' class='' />
             </div>
             </form>";
    
    $htmlout .= end_frame();
    // ------ Get 10 last posts if this is a reply
    
    if (!$newtopic && $TBDEV['last_10_posts']) 
    {
        $postres = mysql_query("SELECT p.id, p.added, p.body, p.anonymous, u.id AS uid, u.username, u.avatar FROM posts AS p LEFT JOIN users AS u ON u.id = p.userid WHERE p.topicid = " . sqlesc($id) . " " . "ORDER BY p.id DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
        if (mysql_num_rows($postres) > 0) 
        {

            $htmlout .="<br />";
            $htmlout .= begin_frame("10 last posts, in reverse order");

            while ($post = mysql_fetch_assoc($postres)) {
                $avatar = ($CURUSER["avatars"] == "all" ? htmlsafechars($post["avatar"]) : ($CURUSER["avatars"] == "some" && $post["offavatar"] == "no" ? htmlsafechars($post["avatar"]) : ""));
             
             if ($post['anonymous'] == 'yes') {
             $avatar = $TBDEV['forum_pic_url'].'default_avatar.gif';
             }
             else {
             $avatar = ($CURUSER["avatars"] == "yes" ? htmlsafechars($post["avatar"]) : '');
             }

             if (empty($avatar))
             $avatar = $TBDEV['forum_pic_url'].'default_avatar.gif';

             if ($post["anonymous"] == "yes")
             if($CURUSER['class'] < UC_MODERATOR && $post["uid"] != $CURUSER["id"]){	
             $htmlout .= "<p class='sub'>#" . $post["id"] . " by <i>Anonymous</i> at ".get_date($post["added"], 'LONG',1,0)."</p>";
             }
             else{	
             $htmlout .= "<p class='sub'>#" . $post["id"] . " by <i>Anonymous</i> (<b>" . $post["username"] . "</b>) at ".get_date($post["added"], 'LONG',1,0)."</p>"; 
             }
             else
             $htmlout .="<p class='sub'>#".$post["id"]." by ". (!empty($post["username"]) ? $post["username"] : "unknown[{$post['uid']}]")." at ".get_date($post["added"], 'LONG',1,0)."</p>";

                $htmlout .= begin_table(true);

                
					$htmlout .="<tr>
						<td height='100' width='100' align='center' style='padding: 0px' valign='top'><img height='100' width='100' src='".$avatar."' alt='User avvy' /></td>
						<td class='comment' valign='top'>". format_comment($post["body"])."</td>
					</tr>";
           $htmlout .= end_table();
            }

            $htmlout .= end_frame();
        }
    }
    $htmlout .= insert_quick_jump_menu();
    return $htmlout;
}



//-------- Insert A Fast Reply Frame
  
function insert_fastreply($ids, $pkey = '') {
	
    global $TBDEV, $CURUSER;
    
    $htmlout = "<div style='display: none;' id='fastreply'>
    <div class='tb_table_inner_wrap'>
    <span style='color:#ffffff;'>Fast Reply</span>
    </div>

    <form name='bbcode2text' method='post' action='{$TBDEV['baseurl']}/forums.php?action=post'>\n";
    
    if ( !empty($pkey) )
    {
        $htmlout .= "<input type='hidden' name='postkey' value='$pkey' />\n";
    }
    
    $htmlout .= "<input type='hidden' name='fastreply' value='true' />
    
    <input type='hidden' name='topicid' value='{$ids['topicid']}' />
    
    <input type='hidden' name='forumid' value='{$ids['forumid']}' />
    
    <textarea name='body' cols='50' rows='10'></textarea>

    <br /><input type='submit' class='btn' value='Submit' />
    
    Anonymous<input type='checkbox' name='anonymous' value='yes' ".($CURUSER['anonymous'] == 'yes' ? "checked='checked'":'')." />
    
    <input onclick=\"showhide('fastreply'); return(false);\" value='Close Fast Reply' type='button' class='btn' />

    </form>
    </div><br />\n";
    
    return $htmlout;
}



?>