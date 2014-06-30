<?php

 // -------- Action: Edit Forum
        $forumid = (int)$_GET["forumid"];
        if ($CURUSER['class'] == MAX_CLASS || isMod($forumid)) {
        if (!is_valid_id($forumid))
            stderr('Error', 'Invalid ID!');

        $res = mysql_query("SELECT name, description, minclassread, minclasswrite, minclasscreate FROM forums WHERE id = $forumid") or sqlerr(__FILE__, __LINE__);
        if (mysql_num_rows($res) == 0)
        stderr('Error', 'No forum found with that ID!');

        $forum = mysql_fetch_assoc($res);

        
        if ($TBDEV['forums_online'] == 0)
        $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
        $HTMLOUT .= begin_main_frame();
        $HTMLOUT .= begin_frame("Edit Forum", "center");
        $HTMLOUT .="<form method='post' action='" . $_SERVER['PHP_SELF'] . "?action=updateforum&amp;forumid=$forumid'>\n";
        $HTMLOUT .= begin_table();
        $HTMLOUT .="<tr><td class='rowhead'>Forum name</td>
        <td align='left' style='padding: 0px'><input type='text' size='60' maxlength='$maxsubjectlength' name='name' style='border: 0px; height: 19px' value=\"" . htmlspecialchars($forum['name']) . "\" /></td></tr>
        <tr><td class='rowhead'>Description</td><td align='left' style='padding: 0px'><textarea name='description' cols='68' rows='3' style='border: 0px'>" . htmlspecialchars($forum['description']) . "</textarea></td></tr>
        <tr><td class='rowhead'></td><td align='left' style='padding: 0px'>&nbsp;Minimum <select name='readclass'>";
        for ($i = 0; $i <= MAX_CLASS; ++$i)
        $HTMLOUT .="<option value='$i' " . ($i == $forum['minclassread'] ? " selected='selected'" : "") . ">" . get_user_class_name($i) . "</option>\n";
        $HTMLOUT .="</select> Class required to View<br />\n&nbsp;Minimum <select name='writeclass'>";
        for ($i = 0; $i <= MAX_CLASS; ++$i)
        $HTMLOUT .="<option value='$i' " . ($i == $forum['minclasswrite'] ? " selected='selected'" : "") . ">" . get_user_class_name($i) . "</option>\n";
        $HTMLOUT .="</select> Class required to Post<br />\n&nbsp;Minimum <select name='createclass'>";
        for ($i = 0; $i <= MAX_CLASS; ++$i)
        $HTMLOUT .="<option value='$i' " . ($i == $forum['minclasscreate'] ? " selected='selected'" : "") . ">" . get_user_class_name($i) . "</option>\n";
        $HTMLOUT .="</select> Class required to Create Topics</td></tr>
        <tr><td colspan='2' align='center'><input type='submit' value='Submit' /></td></tr>\n";
        $HTMLOUT .= end_table();
        $HTMLOUT .="</form>";

        $HTMLOUT .= end_frame();
        $HTMLOUT .= end_main_frame();
        print stdhead($lang['forums_title'], '', $fcss) . $HTMLOUT . stdfoot();
        exit();
    }


?>