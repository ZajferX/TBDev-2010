<?php

// -------- Action: Update Forum
        $forumid = (int)$_GET["forumid"];
    if ($CURUSER['class'] == MAX_CLASS || isMod($forumid)) {
        if (!is_valid_id($forumid))
            stderr('Error', 'Invalid ID!');

        $res = mysql_query('SELECT id FROM forums WHERE id = ' . sqlesc($forumid));
        if (mysql_num_rows($res) == 0)
            stderr('Error', 'No forum with that ID!');

        $name = $_POST['name'];
        $description = $_POST['description'];

        if (empty($name))
            stderr("Error", "You must specify a name for the forum.");

        if (empty($description))
            stderr("Error", "You must provide a description for the forum.");

        mysql_query("UPDATE forums SET name = " . sqlesc($name) . ", description = " . sqlesc($description) . ", minclassread = " . sqlesc((int)$_POST['readclass']) . ", minclasswrite = " . sqlesc((int)$_POST['writeclass']) . ", minclasscreate = " . sqlesc((int)$_POST['createclass']) . " WHERE id = " . sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);

        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }

?>