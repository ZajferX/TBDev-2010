<?php


    $topicid = (isset($_GET['topicid']) ? (int)$_GET['topicid'] : (isset($_POST['topicid']) ? (int)$_POST['topicid'] : 0));
    if (!is_valid_id($topicid))
        stderr('Error...', 'Invalid topic ID!');

    $topic_res = mysql_query('SELECT t.sticky, t.locked, t.subject, t.forumid, f.minclasswrite, ' . '(SELECT COUNT(id) FROM posts WHERE topicid = t.id) As post_count ' . 'FROM topics AS t ' . 'LEFT JOIN forums AS f ON f.id = t.forumid ' . 'WHERE t.id = ' . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
    if (mysql_num_rows($topic_res) == 0)
        stderr('Error...', 'No topic with that ID!');

    $topic_arr = mysql_fetch_assoc($topic_res);
    if (isMod($topic_arr["forumid"]) || $CURUSER['class'] >= UC_MODERATOR) {
        if (($CURUSER['class'] < (int)$topic_arr['minclasswrite']) && !isMod($topic_arr["forumid"]))
            stderr('Error...', 'You are not allowed to edit this topic.');

        $forumid = (int)$topic_arr['forumid'];
        $subject = $topic_arr['subject'];

        if ((isset($_GET['delete']) ? $_GET['delete'] : (isset($_POST['delete']) ? $_POST['delete'] : '')) == 'yes') {
            if ((isset($_GET['sure']) ? $_GET['sure'] : (isset($_POST['sure']) ? $_POST['sure'] : '')) != 'yes')
                stderr("Sanity check...", "You are about to delete this topic: <b>" . htmlspecialchars($subject) . "</b>. Click <a href='" . $_SERVER['PHP_SELF'] . "?action=$action&amp;topicid=$topicid&amp;delete=yes&amp;sure=yes'>here</a> if you are sure.");

            write_log("topicdelete","Topic <b>" . $subject . "</b> was deleted by <a href='{$TBDEV['baseurl']}/userdetails.php?id=" . $CURUSER['id'] . "'>" . $CURUSER['username'] . "</a>.");

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

            header('Location: ' . $_SERVER['PHP_SELF'] . '?action=viewforum&forumid=' . $forumid);
            exit();
        }

        $returnto = $_SERVER['PHP_SELF'] . '?action=viewtopic&topicid=' . $topicid;

        $updateset = array();

        $locked = ($_POST['locked'] == 'yes' ? 'yes' : 'no');
        if ($locked != $topic_arr['locked'])
            $updateset[] = 'locked = ' . sqlesc($locked);

        $sticky = ($_POST['sticky'] == 'yes' ? 'yes' : 'no');
        if ($sticky != $topic_arr['sticky'])
            $updateset[] = 'sticky = ' . sqlesc($sticky);

        $new_subject = $_POST['subject'];
        if ($new_subject != $subject) {
            if (empty($new_subject))
                stderr('Error...', 'Topic name cannot be empty.');

            $updateset[] = 'subject = ' . sqlesc($new_subject);
        }

        $new_forumid = (int)$_POST['new_forumid'];
        if (!is_valid_id($new_forumid))
            stderr('Error...', 'Invalid forum ID!');

        if ($new_forumid != $forumid) {
            $post_count = (int)$topic_arr['post_count'];

            $res = mysql_query("SELECT minclasswrite FROM forums WHERE id = " . sqlesc($new_forumid)) or sqlerr(__FILE__, __LINE__);

            if (mysql_num_rows($res) != 1)
                stderr("Error...", "Forum not found!");

            $arr = mysql_fetch_assoc($res);
            if ($CURUSER['class'] < (int)$arr['minclasswrite'])
                stderr('Error...', 'You are not allowed to move this topic into the selected forum.');

            $updateset[] = 'forumid = ' . sqlesc($new_forumid);

            mysql_query("UPDATE forums SET topiccount = topiccount - 1, postcount = postcount - " . sqlesc($post_count) . " WHERE id = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);
            mysql_query("UPDATE forums SET topiccount = topiccount + 1, postcount = postcount + " . sqlesc($post_count) . " WHERE id = " . sqlesc($new_forumid)) or sqlerr(__FILE__, __LINE__);

            $returnto = $_SERVER['PHP_SELF'] . '?action=viewforum&forumid=' . $new_forumid;
        }

        if (sizeof($updateset) > 0)
            mysql_query("UPDATE topics SET " . implode(', ', $updateset) . " WHERE id = " . sqlesc($topicid));

        header('Location: ' . $returnto);
        exit();
    }
?>