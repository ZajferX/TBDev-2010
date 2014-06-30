<?php
/*
+------------------------------------------------
|   TBDev.net BitTorrent Tracker PHP
|   =============================================
|   by CoLdFuSiOn
|   (c) 2003 - 2011 TBDev.Net
|   http://www.tbdev.net
|   =============================================
|   svn: http://sourceforge.net/projects/tbdevnet/
|   Licence Info: GPL
+------------------------------------------------
|   $Date$
|   $Revision$
|   $Author$
|   $URL$
+------------------------------------------------
*/

if ( ! defined( 'IN_TBDEV_FORUM' ) )
{
	print "{$lang['forum_functions_access']}";
	exit();
}


function catch_up($id = 0)
{
    global $CURUSER, $TBDEV;

    $userid = (int)$CURUSER['id'];

    $res = mysql_query("SELECT t.id, t.lastpost, r.id AS r_id, r.lastpostread " . "FROM topics AS t " . "LEFT JOIN posts AS p ON p.id = t.lastpost " . "LEFT JOIN readposts AS r ON r.userid=" . sqlesc($userid) . " AND r.topicid=t.id " . "WHERE p.added > " . sqlesc(time() - $TBDEV['readpost_expiry']) .
        (!empty($id) ? ' AND t.id ' . (is_array($id) ? 'IN (' . implode(', ', $id) . ')' : '= ' . sqlesc($id)) : '')) or sqlerr(__FILE__, __LINE__);

    while ($arr = mysql_fetch_assoc($res)) {
        $postid = (int)$arr['lastpost'];

        if (!is_valid_id($arr['r_id']))
            mysql_query("INSERT INTO readposts (userid, topicid, lastpostread) VALUES($userid, " . (int)$arr['id'] . ", $postid)") or sqlerr(__FILE__, __LINE__);
        else if ($arr['lastpostread'] < $postid)
            mysql_query("UPDATE readposts SET lastpostread = $postid WHERE id = " . $arr['r_id']) or sqlerr(__FILE__, __LINE__);
    }
    mysql_free_result($res);
}



  //-------- Returns the minimum read/write class levels of a forum
function get_forum_access_levels($forumid)
{
    $res = mysql_query("SELECT minclassread, minclasswrite, minclasscreate FROM forums WHERE id = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);

    if (mysql_num_rows($res) != 1)
        return false;

    $arr = mysql_fetch_assoc($res);

    return array("read" => $arr["minclassread"], "write" => $arr["minclasswrite"], "create" => $arr["minclasscreate"]);
}



// -------- Returns the forum ID of a topic, or false on error
function get_topic_forum($topicid)
{
    $res = mysql_query("SELECT forumid FROM topics WHERE id = " . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);

    if (mysql_num_rows($res) != 1)
        return false;

    $arr = mysql_fetch_assoc($res);

    return (int)$arr['forumid'];
}



// -------- Returns the ID of the last post of a forum
function update_topic_last_post($topicid)
{
    $res = mysql_query("SELECT MAX(id) AS id FROM posts WHERE topicid = " . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_assoc($res) or die("No post found");

    mysql_query("UPDATE topics SET lastpost = {$arr['id']} WHERE id = " . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
}

function get_forum_last_post($forumid)
{
    $res = mysql_query("SELECT MAX(lastpost) AS lastpost FROM topics WHERE forumid = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);

    $arr = mysql_fetch_assoc($res);

    $postid = (int)$arr['lastpost'];

    return (is_valid_id($postid) ? $postid : 0);
}

//==Putyns subforums
function subforums($arr)
{
    global $TBDEV;
    
    $sub = "<font class=\"small\"><b>Subforums:</b>";
    $i = 0;
    foreach($arr as $k) {
        $sub .= "&nbsp;<img src='{$TBDEV['forum_pic_url']}bullet_" . ($k["new"] == 1 ? "green.png" : "white.png") . "' width=\"8\" title=\"" . ($k["new"] == 1 ? "New posts" : "Not new post") . "\" border=\"0\" alt='Subforum' /><a href=\"forums.php?action=viewforum&amp;forumid=" . $k["id"] . "\">" . $k["name"] . "</a>" . ((count($arr)-1) == $i ? "" : ",");
        $i++;
    }
    $sub .= "</font>";
    return $sub;
}


function get_count($arr)
{
    $topics = 0;
    $posts = 0;
    foreach($arr as $k) {
        $topics += $k["topics"];
        $posts += $k["posts"];
    }
    return array($posts, $topics);
}
//== End subforum

//== Forum moderator by putyn
function showMods($ars)
{
    $mods = "<font class=\"small\">Led by:&nbsp;";
    $i = 0;
    $count = count($ars);
    foreach($ars as $a) {
        $mods .= "<a href=\"userdetails.php?id=" . $a["id"] . "\">" . $a["user"] . "</a>" . (($count -1) == $i ? "":" ,");
        $i++;
    }
    $mods .= "</font>";
    return $mods;
}

function isMod($fid)
{
    GLOBAL $CURUSER;
    return (stristr($CURUSER["forums_mod"], "[" . $fid . "]") == true ? true : false) ;
}
//== End forum moderator :)

//==Begin cached online users -- cf edit, what idiot wrote this?
function forum_stats()
 	{
 	//== 09 Active users in forums
 	$htmlout ='';
	global $TBDEV, $forum_width, $lang, $CURUSER;
 	$forum3="";
 	$file = "./cache/forum.txt";
 	$expire = 30; // 30 seconds
 	if (file_exists($file) && filemtime($file) > (time() - $expire)) {
 	$forum3 = unserialize(file_get_contents($file));
 	} else {
 	$dt = sqlesc(time() - 180);
 	$forum1 = mysql_query("SELECT id, username, class, warned, donor, anonymous FROM users WHERE forum_access >= $dt ORDER BY class DESC") or sqlerr(__FILE__, __LINE__);
 	while ($forum2 = mysql_fetch_assoc($forum1)) {
 	$forum3[] = $forum2;
 	}
 	$OUTPUT = serialize($forum3);
 	$fp = fopen($file, "w");
 	fputs($fp, $OUTPUT);
 	fclose($fp);
 	} // end else
 	$forumusers = "";
 	if (is_array($forum3))
 	foreach ($forum3 as $arr) {
	if ($forumusers) $forumusers .= ",\n";
	$forumusers .= "<span style=\"white-space: nowrap;\">"; 
	if ($arr["anonymous"] == "yes")
	if ($CURUSER['class'] < UC_MODERATOR && $arr["id"] != $CURUSER["id"])
	$arr["username"] = "<i>Anonymous</i>";
	else
	$arr["username"] = "<font color='#" . get_user_class_color($arr['class']) . "'> " . htmlspecialchars($arr['username']) . "</font>+";
	else
	$arr["username"] = "<font color='#" . get_user_class_color($arr['class']) . "'> " . htmlspecialchars($arr['username']) . "</font>";
	$donator = $arr["donor"] === "yes";
	$warned = $arr["warned"] === "yes";

	if ($CURUSER)
	$forumusers .= "<a href='{$TBDEV['baseurl']}/userdetails.php?id={$arr["id"]}'><b>{$arr["username"]}</b></a>";
	else
	$forumusers .= "<b>{$arr["username"]}</b>";
	if ($arr["anonymous"] == "yes")
	if ($CURUSER['class'] < UC_MODERATOR && $arr["id"] != $CURUSER["id"])
	$forumusers .= "";
	else
	if ($donator)
	$forumusers .= "<img src='{$TBDEV['pic_base_url']}star.gif' alt='Donated' />";
	if ($arr["anonymous"] == "yes")
	if ($CURUSER['class'] < UC_MODERATOR && $arr["id"] != $CURUSER["id"])
	$forumusers .= "";
	else
	if ($warned)
	$forumusers .= "<img src='{$TBDEV['pic_base_url']}warned.gif' alt='Warned' />";
	$forumusers .= "</span>";
	}
	if (!$forumusers)
 	$forumusers = "Currently No Active users in the Forum";
	
 	$topic_post_res = mysql_query("SELECT SUM(topiccount) AS topics, SUM(postcount) AS posts FROM forums");
	$topic_post_arr = mysql_fetch_assoc($topic_post_res);
	
 	$htmlout .="<br />
	<table width='{$forum_width}' border='0' cellspacing='0' cellpadding='5'>
 	<tr>
 	<td class='colhead' align='center'>Now active in Forums:</td>
 	</tr>
	<tr>
	<td class='text'>";
	if ($CURUSER['anonymous'] == 'yes'){
	$htmlout .="<p align='center'>(+) next to your username indicates you are Anonymous !</p>";
	}
 	$htmlout .="{$forumusers}</td>
 	</tr>
 	<tr>
 	<td class='colhead' align='center'><h2>Our members wrote <b>".number_format($topic_post_arr['posts'])."</b> Posts in <b>".number_format($topic_post_arr['topics'])."</b> Threads</h2></td>
 	</tr>
	</table>";
	return $htmlout;
}


function show_forums($forid, $subforums = false, $sfa = "", $mods_array = "", $show_mods = false)
    {
    global $CURUSER, $TBDEV;
    $htmlout='';
    $forums_res = mysql_query("SELECT f.id, f.name, f.description, f.postcount, f.topiccount, f.minclassread, p.added, p.topicid, p.anonymous, p.userid, p.id AS pid, u.username, t.subject, t.lastpost, r.lastpostread " . "FROM forums AS f " . "LEFT JOIN posts AS p ON p.id = (SELECT MAX(lastpost) FROM topics WHERE forumid = f.id) " . "LEFT JOIN users AS u ON u.id = p.userid " . "LEFT JOIN topics AS t ON t.id = p.topicid " . "LEFT JOIN readposts AS r ON r.userid = " . sqlesc($CURUSER['id']) . " AND r.topicid = p.topicid " . "WHERE " . ($subforums == false ? "f.forid = $forid AND f.place =-1 ORDER BY f.forid ASC" : "f.place=$forid ORDER BY f.id ASC") . "") or sqlerr(__FILE__, __LINE__);

    while ($forums_arr = mysql_fetch_assoc($forums_res)) {
        if ($CURUSER['class'] < $forums_arr["minclassread"])
            continue;

        $forumid = (int)$forums_arr["id"];
        $lastpostid = (int)$forums_arr['lastpost'];

        if ($subforums == false && !empty($sfa[$forumid])) {
        if (($sfa[$forumid]['lastpost']['postid'] > $forums_arr['pid'])) {
        if ($sfa[$forumid]['lastpost']["anonymous"] == "yes") {
        if($CURUSER['class'] < UC_MODERATOR && $sfa[$forumid]['lastpost']['userid'] != $CURUSER['id'])	
        $lastpost1 = "Anonymous<br />";
        else
        $lastpost1 = "Anonymous(<a href='{$TBDEV['baseurl']}/userdetails.php?id=" . (int)$sfa[$forumid]['lastpost']['userid'] . "'><b>" . htmlspecialchars($sfa[$forumid]['lastpost']['user']) . "</b></a>)<br />";
        }
        elseif ($sfa[$forumid]['lastpost']["anonymous"] == "no") { 
        $lastpost1 = "<a href='{$TBDEV['baseurl']}/userdetails.php?id=" . (int)$sfa[$forumid]['lastpost']['userid'] . "'><b>" . htmlspecialchars($sfa[$forumid]['lastpost']['user']) . "</b></a><br />";
        }
        $lastpost = "<span style='white-space:nowrap;'>" . get_date($sfa[$forumid]['lastpost']['added'], 'LONG',1,0) . "</span><br />" . "by $lastpost1" . "in <a href='" . $_SERVER['PHP_SELF'] . "?action=viewtopic&amp;topicid=" . (int)$sfa[$forumid]['lastpost']['topic'] . "&amp;page=p" . $sfa[$forumid]['lastpost']['postid'] . "#p" . $sfa[$forumid]['lastpost']['postid'] . "'><b>" . htmlspecialchars($sfa[$forumid]['lastpost']['tname']) . "</b></a>";
        }
        elseif (($sfa[$forumid]['lastpost']['postid'] < $forums_arr['pid'])) {
        if ($forums_arr["anonymous"] == "yes") {
        if($CURUSER['class'] < UC_MODERATOR && $forums_arr["userid"] != $CURUSER["id"])	
        $lastpost2 = "Anonymous<br />";
        else
        $lastpost2 = "Anonymous(<a href='{$TBDEV['baseurl']}/userdetails.php?id=" . (int)$forums_arr["userid"] . "'><b>" . htmlspecialchars($forums_arr['username']) . "</b></a>)<br />";
        }
        elseif ($forums_arr["anonymous"] == "no") { 
        $lastpost2 = "<a href='{$TBDEV['baseurl']}/userdetails.php?id=" . (int)$forums_arr["userid"] . "'><b>" . htmlspecialchars($forums_arr['username']) . "</b></a><br />";
        }
        $lastpost = "<span style='white-space:nowrap;'>" .get_date($forums_arr["added"], 'LONG',1,0) . "</span><br />" . "by $lastpost2" . "in <a href='" . $_SERVER['PHP_SELF'] . "?action=viewtopic&amp;topicid=" . (int)$forums_arr["topicid"] . "&amp;page=p$lastpostid#p$lastpostid'><b>" . htmlspecialchars($forums_arr['subject']) . "</b></a>";
        } else
        $lastpost = "N/A";
        } else {
        if (is_valid_id($forums_arr['pid']))
        if ($forums_arr["anonymous"] == "yes") {
        if($CURUSER['class'] < UC_MODERATOR && $forums_arr["userid"] != $CURUSER["id"])
        $lastpost ="<span style='white-space:nowrap;'>" .get_date($forums_arr["added"], 'LONG',1,0) . "</span><br />" . "by <i>Anonymous</i><br />" . "in <a href='" . $_SERVER['PHP_SELF'] . "?action=viewtopic&amp;topicid=" . (int)$forums_arr["topicid"] . "&amp;page=p$lastpostid#p$lastpostid'><b>" . htmlspecialchars($forums_arr['subject']) . "</b></a>"; 
        else
        $lastpost ="<span style='white-space:nowrap;'>" .get_date($forums_arr["added"], 'LONG',1,0) . "</span><br />" . "by <i>Anonymous</i>(<a href='{$TBDEV['baseurl']}/userdetails.php?id=" . (int)$forums_arr["userid"] . "'><b>" . htmlspecialchars($forums_arr['username']) . "</b></a>)<br />" . "in <a href='" . $_SERVER['PHP_SELF'] . "?action=viewtopic&amp;topicid=" . (int)$forums_arr["topicid"] . "&amp;page=p$lastpostid#p$lastpostid'><b>" . htmlspecialchars($forums_arr['subject']) . "</b></a>";
        }
        else 
        $lastpost = "<span style='white-space:nowrap;'>" .get_date($forums_arr["added"], 'LONG',1,0) . "</span><br />" . "by <a href='{$TBDEV['baseurl']}/userdetails.php?id=" . (int)$forums_arr["userid"] . "'><b>" . htmlspecialchars($forums_arr['username']) . "</b></a><br />" . "in <a href='" . $_SERVER['PHP_SELF'] . "?action=viewtopic&amp;topicid=" . (int)$forums_arr["topicid"] . "&amp;page=p$lastpostid#p$lastpostid'><b>" . htmlspecialchars($forums_arr['subject']) . "</b></a>";
        else
        $lastpost = "N/A";
        }

        if (is_valid_id($forums_arr['pid']))
            $img = 'unlocked' . ((($forums_arr['added'] > (time() - $TBDEV['readpost_expiry']))?((int)$forums_arr['pid'] > $forums_arr['lastpostread']):0)?'new':'');
        else
            $img = "unlocked";
        if ($subforums == false && !empty($sfa[$forumid])) {
            list($subposts, $subtopics) = get_count($sfa[$forumid]["count"]);
            $topics = $forums_arr["topiccount"] + $subtopics;
            $posts = $forums_arr["postcount"] + $subposts;
        } else {
            $topics = $forums_arr["topiccount"];
            $posts = $forums_arr["postcount"];
        }

      $htmlout.="<tr class='row1'>
			<td class='altrow'><img src='{$TBDEV['forum_pic_url']}{$img}.gif' alt='' /></td>
      <td class='noborder'><a href='{$_SERVER['PHP_SELF']}?action=viewforum&amp;forumid=".$forumid."'><b>". htmlspecialchars($forums_arr["name"])."</b></a>";
      
      if ($CURUSER['class'] >= UC_ADMINISTRATOR || isMod($forumid)) 
      {
        $htmlout.="&nbsp;<font class='small'>[<a class='altlink' href='".$_SERVER['PHP_SELF']."?action=editforum&amp;forumid=".$forumid."'>Edit</a>][<a class='altlink' href='".$_SERVER['PHP_SELF']."?action=deleteforum&amp;forumid=".$forumid."'>Delete</a>]</font>";
      }

      if (!empty($forums_arr["description"])) 
      {
        $htmlout.="<br />". htmlspecialchars($forums_arr["description"]);
      }
      if ($subforums == false && !empty($sfa[$forumid]))
          $htmlout.="<br/>" . subforums($sfa[$forumid]["topics"]);
      if ($show_mods == true && isset($mods_array[$forumid]))
          $htmlout.="<br/>" . showMods($mods_array[$forumid]);

      $htmlout.="</td>
      <td class='altrow stats'>". number_format($topics)."</td>
      <td class='altrow stats'>". number_format($posts)."</td>
      <td class='last_post noborder'>$lastpost</td>
      </tr>";
    }
    
return $htmlout;

}

if (!function_exists('highlight')) {
    function highlight($search, $subject, $hlstart = '<b><font color=\"red\">', $hlend = '</font></b>')
    {
        $srchlen = strlen($search); // length of searched string
        if ($srchlen == 0)
            return $subject;

        $find = $subject;
        while ($find = stristr($find, $search)) { // find $search text in $subject -case insensitiv
            $srchtxt = substr($find, 0, $srchlen); // get new search text
            $find = substr($find, $srchlen);
            $subject = str_replace($srchtxt, $hlstart . $srchtxt . $hlend, $subject); // highlight founded case insensitive search text
        }

        return $subject;
    }
}


?>