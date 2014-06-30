<?php

// -------- Action: Delete post
        $postid = (int)$_GET['postid'];
    if (!is_valid_id($postid))
        stderr('Error', 'Invalid ID');

    $res = mysql_query("SELECT p.topicid " . ($use_attachment_mod ? ", a.filename" : "") . ", t.forumid, (SELECT COUNT(id) FROM posts WHERE topicid=p.topicid) AS posts_count, " . "(SELECT MAX(id) FROM posts WHERE topicid=p.topicid AND id < p.id) AS p_id " . "FROM posts AS p " . "LEFT JOIN topics as t on t.id=p.topicid " .
        ($use_attachment_mod ? "LEFT JOIN attachments AS a ON a.postid = p.id " : "") . "WHERE p.id=" . sqlesc($postid)) or sqlerr(__FILE__, __LINE__);
    $arr = mysql_fetch_assoc($res) or stderr("Error", "Post not found");

    if (isMod($arr["forumid"]) || $CURUSER['class'] >= UC_MODERATOR) {
        $topicid = (int)$arr['topicid'];

        if ($arr['posts_count'] < 2)
            stderr("Error", "Can't delete post; it is the only post of the topic. You should<br /><a href='" . $_SERVER['PHP_SELF'] . "?action=deletetopic&amp;topicid=$topicid'>delete the topic</a> instead.");

        $redirtopost = (is_valid_id($arr['p_id']) ? "&page=p" . $arr['p_id'] . "#p" . $arr['p_id'] : '');

        $sure = (int)isset($_GET['sure']) && (int) $_GET['sure'];
        if (!$sure)
            stderr("Sanity check...", "You are about to delete a post. Click <a href='" . $_SERVER['PHP_SELF'] . "?action=deletepost&amp;postid=$postid&amp;sure=1'>here</a> if you are sure.");

        mysql_query("DELETE posts.* " . ($use_attachment_mod ? ", attachments.*, attachmentdownloads.* " : "") . "FROM posts " .
            ($use_attachment_mod ? "LEFT JOIN attachments ON attachments.postid = posts.id " . "LEFT JOIN attachmentdownloads ON attachmentdownloads.fileid = attachments.id " : "") . "WHERE posts.id = " . sqlesc($postid)) or sqlerr(__FILE__, __LINE__);

        if ($use_attachment_mod && !empty($arr['filename'])) {
            $filename = $attachment_dir . "/" . $arr['filename'];
            if (is_file($filename))
                unlink($filename);
        }

        update_topic_last_post($topicid);
        header("Location: {$_SERVER['PHP_SELF']}?action=viewtopic&topicid=" . $topicid . $redirtopost);
        exit();
    }


?>