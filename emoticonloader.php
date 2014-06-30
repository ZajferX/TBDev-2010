<?php
require_once "include/bittorrent.php";
require_once "include/emoticons.php";

dbconn();

loggedinorreturn();

$htmlout ="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		
		<html xmlns='http://www.w3.org/1999/xhtml'>

		<head>

			<meta name='generator' content='TBDev.net' />
			<meta http-equiv='Content-Language' content='{$TBDEV['language']}' />
			<meta http-equiv='Content-Type' content='text/html; charset={$TBDEV['char_set']}' />
			
			<title>Emoticons Extra</title>
			<link rel='stylesheet' href='{$TBDEV['stylesheet']}' type='text/css' />
		</head>
    
    <body>
    <script type='text/javascript'>
<!--
	function add_smilie(code)
	{
		opener.document.forms['bbcode2text'].body.value += ' ' + code + ' ';
		//return true;
	}
//-->
</script>

    <table>";
    


    foreach ($smilies as $k => $v ) {
    
    $htmlout .= "<tr>
	  <td align='center' class='row1' valign='middle'><a href=\"javascript:add_smilie('$k')\">$k</a></td>
	  <td align='center' class='row2' valign='middle'><a href=\"javascript:add_smilie('$k')\"><img src='{$TBDEV['pic_base_url']}smilies/$v' border='0' style='vertical-align:middle;' alt='$v' title='$v' /></a></td>
   </tr>";
    }

$htmlout .="
    </table>
    </body>
    </html>";
    
 print $htmlout;
?>