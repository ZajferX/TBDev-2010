<?php


		$res = mysql_query("SELECT p.*, pa.id AS pa_id, pa.selection FROM forum_polls AS p LEFT JOIN forum_poll_answers AS pa ON pa.pollid = p.id AND pa.userid = ".$CURUSER['id']." WHERE p.id = ".sqlesc($pollid)) or sqlerr(__FILE__, __LINE__);
	
		if (mysql_num_rows($res) > 0)
		{
			$arr1 = mysql_fetch_assoc($res);
			
			$userid = (int)$CURUSER['id'];
			$question = htmlspecialchars($arr1["question"]);
			$o = array($arr1["option0"], $arr1["option1"], $arr1["option2"], $arr1["option3"], $arr1["option4"],
		  $arr1["option5"], $arr1["option6"], $arr1["option7"], $arr1["option8"], $arr1["option9"],
		  $arr1["option10"], $arr1["option11"], $arr1["option12"], $arr1["option13"], $arr1["option14"],
		  $arr1["option15"], $arr1["option16"], $arr1["option17"], $arr1["option18"], $arr1["option19"]);
			
			$HTMLOUT .="<table cellpadding='5' width='{$forum_width}' align='center'>
			<tr><td class='colhead' align='left'><h2>Poll";
			if ($userid == $t_userid || $CURUSER['class'] >= UC_MODERATOR)
			{
			$HTMLOUT .="<font class='small'> - [<a href='".$_SERVER['PHP_SELF']."?action=makepoll&amp;subaction=edit&amp;pollid=".$pollid."'><b>Edit</b></a>]</font>";
			if ($CURUSER['class'] >= UC_MODERATOR)
			{
			$HTMLOUT .="<font class='small'> - [<a href='".$_SERVER['PHP_SELF']."?action=deletepoll&amp;pollid=".$pollid."'><b>Delete</b></a>]</font>";
			}
			}
			$HTMLOUT .="</h2></td></tr>";

			$HTMLOUT .="<tr><td align='center' class='clearalt7'>";
			$HTMLOUT .="
			<table width='55%'>
			<tr><td class='clearalt6'>
			<div align='center'><b>
			{$question}</b></div>";
			
			
			$voted = (is_valid_id($arr1['pa_id']) ? true : false);
			
			if (($locked && $CURUSER['class'] < UC_MODERATOR) ? true : $voted)
			{
				$uservote = ($arr1["selection"] != '' ? (int)$arr1["selection"] : -1);
				
				$res3 = mysql_query("SELECT selection FROM forum_poll_answers WHERE pollid = ".sqlesc($pollid)." AND selection < 20");
				$tvotes = mysql_num_rows($res3);
			   				
			$vs = $os = array();
      for($i=0;$i<20;$i++) $vs[$i]=0;

				
				while ($arr3 = mysql_fetch_row($res3))
					$vs[$arr3[0]] += 1;
				
				reset($o);
				for ($i = 0; $i < count($o); ++$i)
					if ($o[$i])
						$os[$i] = array($vs[$i], $o[$i]);
				
				function srt($a,$b)
				{
					if ($a[0] > $b[0])
						return -1;
						
					if ($a[0] < $b[0])
						return 1;
				
					return 0;
				}

				
				if ($arr1["sort"] == "yes")
					usort($os, "srt");
				
				$HTMLOUT .="<br />
			  <table width='100%' style='border:none;' cellpadding='5'>";
			
         foreach($os as $a) 
				{
					if ($i == $uservote)
						$a[1] .= " *";
					
					$p = ($tvotes == 0 ? 0 : round($a[0] / $tvotes * 100));				
					$c = ($i % 2 ? '' : "poll");
					
					$p = ($tvotes == 0 ? 0 : round($a[0] / $tvotes * 100));				
					$c = ($i % 2 ? '' : "poll");
					$HTMLOUT .="<tr>";
	        $HTMLOUT .="<td width='1%' style='padding:3px;white-space:nowrap;' class='embedded".$c."'>".htmlspecialchars($a[1])."</td>";
					$HTMLOUT .="<td width='99%' class='embedded".$c."' align='center'>";
					$HTMLOUT .="<img src='{$TBDEV['pic_base_url']}bar_left.gif' alt='bar_left.gif' />
					<img src='{$TBDEV['pic_base_url']}bar.gif' alt='bar.gif'  height='9' width='". ($p*3)."' />
					<img src='{$TBDEV['pic_base_url']}bar_right.gif'  alt='bar_right.gif' />&nbsp;".$p."%</td>
					</tr>";
				  }
				  $HTMLOUT .="</table>";
				  $HTMLOUT .="<p align='center'>Votes: <b>".number_format($tvotes)."</b></p>";
			    }
		    	else
			    {
				  $HTMLOUT .="<form method='post' action='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=".$topicid."'>
				  <input type='hidden' name='pollid' value='".$pollid."' />";
				  for ($i=0; $a = $o[$i]; ++$i)
				  $HTMLOUT .="<input type='radio' name='choice' value='$i' />".htmlspecialchars($a)."<br />";
				  $HTMLOUT .="<br />";
				  $HTMLOUT .="<p align='center'><input type='submit' value='Vote!' /></p></form>";
			    }
			    $HTMLOUT .="</td></tr></table>";
			
			    $listvotes = (isset($_GET['listvotes']) ? true : false);
			    if ($CURUSER['class'] >= UC_ADMINISTRATOR)
			    {
			    if (!$listvotes)
			    $HTMLOUT .="<a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=$topicid&amp;listvotes'>List Voters</a>";
				  else
				  {
				  $res4 = mysql_query("SELECT pa.userid, u.username, u.anonymous FROM forum_poll_answers AS pa LEFT JOIN users AS u ON u.id = pa.userid WHERE pa.pollid = ".sqlesc($pollid)) or sqlerr(__FILE__, __LINE__);
				  $voters = '';
				  while ($arr4 = mysql_fetch_assoc($res4))
				  {
				  if (!empty($voters) && !empty($arr4['username']))
          $voters .= ', ';
 	        if ($arr4["anonymous"] == "yes") {
				  if($CURUSER['class'] < UC_MODERATOR && $arr4["userid"] != $CURUSER["id"])
				  $voters = "<i>Anonymous</i>";
         	else
 	        $voters = "<i>Anonymous</i>(<a href='{$TBDEV['baseurl']}/userdetails.php?id=".(int)$arr4['userid']."'><b>".$arr4['username']."</b></a>)";
 	        }
 	        else
				  $voters .= "<a href='{$TBDEV['baseurl']}/userdetails.php?id=".(int)$arr4['userid']."'><b>".htmlspecialchars($arr4['username'])."</b></a>";
				  }
				  $HTMLOUT .= $voters."<br />(<font class='small'><a href='".$_SERVER['PHP_SELF']."?action=viewtopic&amp;topicid=$topicid'>hide</a></font>)";
				  }
			    }
		      $HTMLOUT .="</td></tr></table>";
		    }
		    else
		    {
			  $HTMLOUT .="<br />";
			  stderr('Sorry', "Poll doesn't exist");
		    }
		    $HTMLOUT .="<br />";
?>