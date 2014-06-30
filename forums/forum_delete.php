<?php

// -------- Action: Delete Forum
        $forumid = (int)$_GET['forumid'];
    if ($CURUSER['class'] == MAX_CLASS || isMod($forumid)) {
        if (!is_valid_id($forumid))
            stderr('Error', 'Invalid ID!');

        $confirmed = (int)isset($_GET['confirmed']) && (int)$_GET['confirmed'];
        if (!$confirmed) 
        {
            $rt = mysql_query("SELECT topics.id, forums.name " . "FROM topics " . "LEFT JOIN forums ON forums.id=topics.forumid WHERE topics.forumid = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);
            $topics = mysql_num_rows($rt);
            $posts = 0;

            if ($topics > 0) 
            {
                while ($topic = mysql_fetch_assoc($rt)) 
                {
                    $ids[] = $topic['id'];
                    $forum = $topic['name'];
                }

                $rp = mysql_query("SELECT COUNT(id) FROM posts WHERE topicid IN (" . join(', ', $ids) . ")");
                foreach ($ids as $id)
                if ($a = mysql_fetch_row($rp))
                    $posts += $a[0];
            }

            if ($use_attachment_mod || $use_poll_mod) 
            {
                $res = mysql_query("SELECT " .
                    ($use_attachment_mod ? "COUNT(attachments.id) AS attachments " : "") .
                    ($use_poll_mod ? ($use_attachment_mod ? ', ' : '') . "COUNT(forum_polls.id) AS polls " : "") . "FROM topics " . "LEFT JOIN posts ON topics.id=posts.topicid " .
                    ($use_attachment_mod ? "LEFT JOIN attachments ON attachments.postid = posts.id " : "") .
                    ($use_poll_mod ? "LEFT JOIN forum_polls ON forum_polls.id=topics.pollid " : "") . "WHERE topics.forumid=" . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);

                ($use_attachment_mod ? $attachments = 0 : null);
                ($use_poll_mod ? $polls = 0 : null);

                if ($arr = mysql_fetch_assoc($res)) 
                {
                    ($use_attachment_mod ? $attachments = $arr['attachments'] : null);
                    ($use_poll_mod ? $polls = $arr['polls'] : null);
                }
            }
            stderr("** WARNING! **", "Deleting forum with id=$forumid (" . $forumid . ") will also delete {$posts} post" . ($posts != 1 ? 's' : '') . ($use_attachment_mod ? ", " . $attachments . " attachment" . ($attachments != 1 ? 's' : '') : "") . ($use_poll_mod ? " and " . ($polls - $attachments) . " poll" . (($polls - $attachments) != 1 ? 's' : '') : "") . " in " . $topics . " topic" . ($topics != 1 ? 's' : '') . ". [<a href='{$_SERVER['PHP_SELF']}?action=deleteforum&amp;forumid=$forumid&amp;confirmed=1'>ACCEPT</a>] [<a href='{$_SERVER['PHP_SELF']}?action=viewforum&amp;forumid=$forumid'>CANCEL</a>]");
        }

        $rt = mysql_query("SELECT topics.id " . ($use_attachment_mod ? ", attachments.filename " : "") . "FROM topics " . "LEFT JOIN posts ON topics.id = posts.topicid " .
            ($use_attachment_mod ? "LEFT JOIN attachments ON attachments.postid = posts.id " : "") . "WHERE topics.forumid = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);

        $topics = mysql_num_rows($rt);
		    if ($topics == 0){
		    mysql_query("DELETE FROM forums WHERE id = ".sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);
			  header("Location: {$_SERVER['PHP_SELF']}");
	      exit();
        } 

        while ($topic = mysql_fetch_assoc($rt)) {
            $tids[] = $topic['id'];

            if ($use_attachment_mod && !empty($topic['filename'])) {
                $filename = $attachment_dir . "/" . $topic['filename'];
                if (is_file($filename))
                    unlink($filename);
            }
        }

        mysql_query("DELETE posts.*, topics.*, forums.* " . ($use_attachment_mod ? ", attachments.*, attachmentdownloads.* " : "") . ($use_poll_mod ? ", forum_polls.*, forum_poll_answers.* " : "") . "FROM posts " .
            ($use_attachment_mod ? "LEFT JOIN attachments ON attachments.postid = posts.id " . "LEFT JOIN attachmentdownloads ON attachmentdownloads.fileid = attachments.id " : "") . "LEFT JOIN topics ON topics.id = posts.topicid " . "LEFT JOIN forums ON forums.id = topics.forumid " .
            ($use_poll_mod ? "LEFT JOIN forum_polls ON forum_polls.id = topics.pollid " . "LEFT JOIN forum_poll_answers ON forum_poll_answers.pollid = forum_polls.id " : "") . "WHERE posts.topicid IN (" . join(', ', $tids) . ")") or sqlerr(__FILE__, __LINE__);

        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }


?>