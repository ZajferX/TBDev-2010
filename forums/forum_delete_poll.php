<?php


    $pollid = (int)$_GET["pollid"];
    if (!is_valid_id($pollid))
        stderr("Error", "Invalid ID!");

    $res = mysql_query("SELECT pp.id, t.id AS tid FROM forum_polls AS pp LEFT JOIN topics AS t ON t.pollid = pp.id WHERE pp.id = " . sqlesc($pollid));
    if (mysql_num_rows($res) == 0)
        stderr("Error", "No poll found with that ID.");

    $arr = mysql_fetch_array($res);

    $sure = (int)isset($_GET['sure']) && (int) $_GET['sure'];
    if (!$sure || $sure != 1)
        stderr('Sanity check...', 'You are about to delete a poll. Click <a href=' . $_SERVER['PHP_SELF'] . '?action=' . htmlspecialchars($action) . '&amp;pollid=' . $arr['id'] . '&amp;sure=1>here</a> if you are sure.');

    mysql_query("DELETE pp.*, ppa.* FROM forum_polls AS pp LEFT JOIN forum_poll_answers AS ppa ON ppa.pollid = pp.id WHERE pp.id = " . sqlesc($pollid));

    if (mysql_affected_rows() == 0)
        stderr('Sorry...', 'There was an error while deleting the poll, please re-try.');

    mysql_query("UPDATE topics SET pollid = '0' WHERE pollid = " . sqlesc($pollid));

    header('Location: ' . $_SERVER['PHP_SELF'] . '?action=viewtopic&topicid=' . (int)$arr['tid']);
    exit();


?>