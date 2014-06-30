<?php

// -------- Action: Quote
        $topicid = (int)$_GET["topicid"];
    if (!is_valid_id($topicid))
        stderr('Error', 'Invalid ID!');

    $HTMLOUT .= begin_main_frame();
    
    if ($TBDEV['forums_online'] == 0)
    $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
    
    // Preview stuff
    $HTMLOUT .= "<div id='prediv' style='margin-bottom:30px;'>" .
    begin_frame('Preview Post', true) .
    "<div id='preshow' style='text-align:left;border: 0;'>
    Preview Here
    </div>" . end_frame() . "</div>";
    // preview end
    
    
    $HTMLOUT .= insert_compose_frame($topicid, false, true);
    $HTMLOUT .= end_main_frame();
    
    $js = "<script type='text/javascript' src='scripts/bbcode2text.js'></script>";
    $js .= "
    <script type='text/javascript'>
    /* <![CDATA[ */
    $(document).ready(function() {
     
    $('#prediv').hide(); 
    $('input[name=\"preview\"]').click(function(){
    var body = $('textarea[name=\"body\"]').val(); 
    var data = 'body=' + encodeURIComponent(body); 
    $('#preshow').html('<span><img src=\'templates/1/images/ajax-loader.gif\' alt=\'\' /></span>');
    $('#prediv').fadeIn('slow'); 
    $.ajax({ 
    type:'POST', 
    data: data,
    url: 'forums.php?action=preview',        
    success: function (html) {                  
      $('#preshow').html(html); 
    }   
    });
    });
     
    });
    /* ]]> */
    </script>";
    
    print stdhead("Post quote", $js, $fcss) . $HTMLOUT . stdfoot();
    exit();

/*$('#submit').click(function () {            
    var name = $('.uname').val(); 
    var data = 'uname=' + name;    
    $.ajax({ 
      type:"GET", 
      url:"info.php",     
      data: data,         
      success: function (html) {                  
        $('#message').html(html); 
      }   
    });    
    return false;    
  });*/
?>