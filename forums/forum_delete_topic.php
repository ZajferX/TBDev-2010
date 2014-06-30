<?php


    $topicid = (int)$_GET['topicid'];
    if (!is_valid_id($topicid))
        stderr('Error', 'Invalid ID');

    $r = mysql_query("SELECT t.id,t.subject " . ($use_poll_mod ? ",t.pollid" : "") . ",t.forumid,(SELECT COUNT(p.id) FROM posts as p where p.topicid=" . $topicid . ") AS posts FROM topics as t WHERE t.id=" . $topicid) or sqlerr(__FILE__, __LINE__);
    $a = mysql_fetch_assoc($r) or stderr("Error", "No topic was found");

    if ($CURUSER["class"] >= UC_MODERATOR || isMod($a["forumid"])) {
        $sure = (int)isset($_GET['sure']) && (int) $_GET['sure'];
        if (!$sure)
            stderr("Sanity check...", "You are about to delete topic " . $a["subject"] . ". Click <a href='" . $_SERVER['PHP_SELF'] . "?action=deletetopic&amp;topicid=$topicid&amp;sure=1'>here</a> if you are sure.");
        else {
            write_log("topicdelete","Topic <b>" . $a["subject"] . "</b> was deleted by <a href='{$TBDEV['baseurl']}/userdetails.php?id=" . $CURUSER['id'] . "'>" . $CURUSER['username'] . "</a>.");

            if ($use_attachment_mod) {
                $res = mysql_query("SELECT attachments.filename " . "FROM posts " . "LEFT JOIN attachments ON attachments.postid = posts.id " . "WHERE posts.topicid = " . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);

                while ($arr = mysql_fetch_assoc($res))
                if (!empty($arr['filename']) && is_file($attachment_dir . "/" . $arr['filename']))
                    unlink($attachment_dir . "/" . $arr['filename']);
            }

            mysql_query("DELETE posts, topics " .
                ($use_attachment_mod ? ", attachments, attachmentdownloads " : "") .
                ($use_poll_mod ? ", forum_polls, forum_poll_answers " : "") . "FROM topics " . "LEFT JOIN posts ON posts.topicid = topics.id " .
                ($use_attachment_mod ? "LEFT JOIN attachments ON attachments.postid = posts.id " . "LEFT JOIN attachmentdownloads ON attachmentdownloads.fileid = attachments.id " : "") .
                ($use_poll_mod ? "LEFT JOIN forum_polls ON forum_polls.id = topics.pollid " . "LEFT JOIN forum_poll_answers ON forum_poll_answers.pollid = forum_polls.id " : "") . "WHERE topics.id = " . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);

            header('Location: ' . $_SERVER['PHP_SELF'] . '?action=viewforum&forumid=' . $a["forumid"]);
            exit();
        }
    }


?>