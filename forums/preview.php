<?php

  if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) OR $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' )
  exit('Can\'t process that request!');
  
  if( !isset($_POST['body']) OR strlen($_POST['body']) < 10 )
  exit;

  sleep(2);

  $HTMLOUT ="";

  $body = trim($_POST['body']);
  echo "<p>".format_comment($body)."</p>";
  exit();



/*$HTMLOUT .= begin_main_frame();

$HTMLOUT .= begin_frame("Preview Post", true);

$HTMLOUT .="<form method='post' action='preview.php'>
<div align='center' style='border: 0;'>
<div align='center'>
<p>".format_comment($body)."</p>
</div>
</div>
<div align='center' style='border: 0;'>
<textarea name='body' cols='100' rows='10'>".htmlspecialchars($body)."</textarea><br />
</div>
<div align='center'>
<input type='submit' class='btn' value='Preview' />
</div></form>";

$HTMLOUT .= end_frame();

$HTMLOUT .= end_main_frame();
print stdhead('Preview') . $HTMLOUT . stdfoot();*/
?>