<?php
    
    $HTMLOUT .="<form method='post' action='".$_SERVER['PHP_SELF']."'>
	  <input type='hidden' name='action' value='updatetopic' />
		<input type='hidden' name='topicid' value='{$topicid}' />";
	  
	  $HTMLOUT .= begin_table();
		$HTMLOUT .="
		<tr>
		<td colspan='2' class='colhead'>Staff options</td>
		</tr>
		<tr>
		<td class='rowhead' width='1%'>Sticky</td>
		<td>
		<select name='sticky'>
		<option value='yes'". ($sticky ? " selected='selected'" : '').">Yes</option>
		<option value='no' ". (!$sticky ? " selected='selected'" : '').">No</option>
		</select>
		</td>
		</tr>
		<tr>
		<td class='rowhead'>Locked</td>
		<td>
		<select name='locked'>
		<option value='yes'". ($locked ? " selected='selected'" : '').">Yes</option>
		<option value='no'". (!$locked ? " selected='selected'" : '').">No</option>
		</select>
	  </td>
		</tr>
		<tr>
		<td class='rowhead'>Topic name</td>
		<td>
		<input type='text' name='subject' size='60' maxlength='{$maxsubjectlength}' value='".htmlsafechars($subject)."' />
		</td>
		</tr>
		<tr>
		<td class='rowhead'>Move topic</td>
		<td>
		<select name='new_forumid'>";
		$res = mysql_query("SELECT id, name, minclasswrite FROM forums ORDER BY name") or sqlerr(__FILE__, __LINE__);
		while ($arr = mysql_fetch_assoc($res))
	  if ($CURUSER['class'] >= $arr["minclasswrite"])
		$HTMLOUT .= '<option value="' . (int)$arr["id"] . '"' . ($arr["id"] == $forumid ? ' selected="selected"' : '') . '>' . htmlspecialchars($arr["name"]) . '</option>';
		
		$HTMLOUT .="</select>
		</td></tr>
		<tr>
	  <td class='rowhead' style='white-space:nowrap;'>Delete topic</td>
	  <td>
	  <select name='delete'>
		<option value='no' selected='selected'>No</option>
		<option value='yes'>Yes</option>
		</select>
		<br />
		<b>Note:</b> Any changes made to the topic won't take effect if you select 'yes'
		</td>
		</tr>
		<tr>
		<td colspan='2' align='center'>
		<input type='submit' value='Update Topic' />
		</td>
		</tr>";
		$HTMLOUT .= end_table();
	  $HTMLOUT .="</form>";
	  
?>