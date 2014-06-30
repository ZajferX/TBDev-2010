<?php


	@ini_set('zlib.output_compression', 'Off');
	@set_time_limit(0);
	
	if (@ini_get('output_handler') == 'ob_gzhandler' && @ob_get_length() !== false)
	{
		@ob_end_clean();
		header('Content-Encoding:');
	}
	
	$id = (int)$_GET['attachmentid'];
	if (!is_valid_id($id))
		die('Invalid Attachment ID!');
	
	$at = mysql_query("SELECT filename, owner, type FROM attachments WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
	$resat = mysql_fetch_assoc($at) or die('No attachment with that ID!');
	$filename = $attachment_dir.'/'.$resat['filename'];
	
	if (!is_file($filename))
		die('Inexistent atachment.');
		
	if (!is_readable($filename))
		die('Attachment is unreadable.');
	
	if ((isset($_GET['subaction']) ? $_GET['subaction'] : '') == 'delete')
	{
		if ($CURUSER['id'] <> $resat["owner"] && $CURUSER['class'] < UC_MODERATOR)
			die('Not your attachment to delete.');
		
		unlink($filename);
		
		mysql_query("DELETE attachments, attachmentdownloads ".
					"FROM attachments ".
					"LEFT JOIN attachmentdownloads ON attachmentdownloads.fileid = attachments.id ".
					"WHERE attachments.id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
		
		die("<font color='red'>File successfully deleted...</font>");
	}
		
	mysql_query("UPDATE attachments SET downloads = downloads + 1 WHERE id = ".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
	
	$res = mysql_query("SELECT fileid FROM attachmentdownloads WHERE fileid=".sqlesc($id)." AND userid=".sqlesc($CURUSER['id']));
	if (mysql_num_rows($res) == 0)
		mysql_query("INSERT INTO attachmentdownloads (fileid, username, userid, date, downloads) VALUES (".sqlesc($id).", ".sqlesc($CURUSER['username']).", ".sqlesc($CURUSER['id']).", ".time().", 1)") or sqlerr(__FILE__, __LINE__);
	else
		mysql_query("UPDATE attachmentdownloads SET downloads = downloads + 1 WHERE fileid = ".sqlesc($id)." AND userid = ".sqlesc($CURUSER['id']));
	$arr=0;
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false); // required for certain browsers 
	header("Content-Type: ".$arr['type']."");
	header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($filename));
	readfile($filename);
	exit();


?>