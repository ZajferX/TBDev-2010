/*
+------------------------------------------------
|   TBDev.net BitTorrent Tracker PHP
|   =============================================
|   by CoLdFuSiOn
|   (c) 2003 - 2011 TBDev.Net
|   http://www.tbdev.net
|   =============================================
|   svn: http://sourceforge.net/projects/tbdevnet/
|   Licence Info: GPL
+------------------------------------------------
|   $Date$
|   $Revision$
|   $Author$
|   $URL$
+------------------------------------------------
*/

$(document).ready(function() {
  
  var cansubmit = false;
  var tick = "&nbsp;<img src='templates/1/images/aff_tick.gif' />";
  var cross = "&nbsp;<img src='templates/1/images/aff_cross.gif' />";
  var errors=[];
  errors['name'] ='&nbsp;User Name can contain only numeral, character, or _(underscore)';
  errors['nameshort'] ='&nbsp;User Name too short or too long';
  errors['pass1'] ='&nbsp;Password cannot be blank';
  errors['passshort'] ='&nbsp;Password too short or too long';
  errors['pass2'] ='&nbsp;Passwords don\'t match';
  errors['email'] ='&nbsp;Invalid email address';
  errors['agreeerror'] ='&nbsp;You must accept all conditions';
  
   
  
  $('input[name$="wantusername"]').keyup( function () { validate_username(); } );
  $('input[name$="wantpassword"]').keyup( function () { validate_pass(); } );
  $('input[name$="passagain"]').blur( function () { validate_pass(); } );
  $('input[name$="email"]').blur( function () { validate_email(); } );
  
  $('.btn').click(function(event){
   
  validate_username();
 
  validate_pass(); 
 
  validate_email();
 
  var count=0; 
  $('div').find(':checkbox').each(function(){ 
    if($(this).is(':checked')) 
    { 
      count++; 
    } 
  });
  
  if(count < 3) 
  { 
    $('.agreeerror').html(cross+errors['agreeerror']).show();
    cansubmit = false;
  }
  else 
  { 
    $('.agreeerror').html(tick).show();
    cansubmit = true;
  } 

  if(cansubmit === false)
  {
    event.preventDefault();
  }
  
}); 
 
 
 
function validate_email() 
{ 
  email=$('input[name$="email"]').val();
  var pattern= new RegExp(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/); 
   
  if(pattern.test(email) === true) 
  { 
    $('.emailcheck').html(tick).show();
    return cansubmit = true;
  } 
  else 
  { 
    $('.emailcheck').html(cross+errors['email']).show();
    return cansubmit = false;
  }
   
} 
 
function validate_username() 
{ 
  var data=$('input[name$="wantusername"]').val();
  var len=data.length; 
  
  if(len < 4 || len > 16) 
  {
    $('.namecheck').html(cross+errors['nameshort']).show();
    return cansubmit = false;
  }
  var pattern= new RegExp(/^[a-z0-9_]+$/); 
  if(pattern.test(data)===false)
  {
    $('.namecheck').html(cross+errors['name']).show();
    return cansubmit = false;
  }
  else
  {
    $('.namecheck').html(tick).show();
    return cansubmit = true;
  }
}

function validate_pass()
{
  data=$('input[name$="wantpassword"]').val(); 
  var len=data.length; 
  if(len < 6 || len > 16) 
  { 
    $('.pass1check').html(cross+errors['passshort']).show();
    return cansubmit = false;
  }
  else if($('input[name$="wantpassword"]').val() != $('input[name$="passagain"]').val())
  {
    $('.pass2check').html(cross+errors['pass2']).show();
    $('.pass1check').html(tick).show();
    return cansubmit = false;
  }
  else 
  { 
    $('.pass1check').html(tick).show();
    $('.pass2check').html(tick).show();
    return cansubmit = true;
  }
}
});