<?php


	$fileid = (int)$_GET['fileid'];
	if (!is_valid_id($fileid))
		die('Invalid ID!');
	
	$res = mysql_query("SELECT fileid, at.filename, userid, username, atdl.downloads, date, at.downloads AS dl ".
					   "FROM attachmentdownloads AS atdl ".
					   "LEFT JOIN attachments AS at ON at.id=atdl.fileid ".
					   "WHERE fileid = ".sqlesc($fileid).($CURUSER['class'] < UC_MODERATOR ? " AND owner=".$CURUSER['id'] : '')) or sqlerr(__FILE__, __LINE__);
	
	if (mysql_num_rows($res) == 0)
	die("<h2 align='center'>Nothing found!</h2>");
	else
	{
	$HTMLOUT = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
    <meta name='generator' content='TBDev.net' />
	  <meta name='MSSmartTagsPreventParsing' content='TRUE' />
		<title>Who Downloaded</title>
    <link rel='stylesheet' href='./1.css' type='text/css' />
    </head>
  <body>
	<table width='100%' cellpadding='5' border='1'>
	<tr align='center'>
	<td>File Name</td>
	<td style='white-space: nowrap;'>Downloaded by</td>
	<td>Downloads</td>
	<td>Date</td>
	</tr>";
  $dls = 0;
	while ($arr = mysql_fetch_assoc($res))
	{
	$HTMLOUT .="<tr align='center'>".
				 "<td>".htmlspecialchars($arr['filename'])."</td>".
				 "<td><a class='pointer' onclick=\"opener.location=('/userdetails.php?id=".(int)$arr['userid']."'); self.close();\">".htmlspecialchars($arr['username'])."</a></td>".
				 "<td>".(int)$arr['downloads']."</td>".
				 "<td>".get_date($arr['date'], 'DATE',1,0)." (".get_date($arr['date'], 'DATE',1,0).")</td>".
				 "</tr>";
	  $dls += (int)$arr['downloads'];
		}
		$HTMLOUT .="<tr><td colspan='4'><b>Total Downloads:</b><b>".number_format($dls)."</b></td></tr></table></body></html>";
	}
	print($HTMLOUT);


?>