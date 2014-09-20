
$( init );
function init() 
{
  $('#contactForm').hide().submit( submitForm ).addClass( 'positioned' );
   
  $('a[href="#contactForm"]').click( function() 
  {
    $('#content').fadeTo( 'slow', .2 );
    $('#contactForm').fadeIn( 'slow', function() 
	{
	  
      $('#senderEmail').focus();
    } )

    return false;
  } );
  // When the "Cancel" button is clicked, close the form
  $('#cancel').click( function() 
  { 
    $('#contactForm').fadeOut();
    $('#content').fadeTo( 'slow', 1 );
	setTimeout("cover_close('cover','inner_feedback')",500);
  } ); 
  $('#close').click( function() 
  { 
  
    $('#contactForm').fadeOut();
    $('#content').fadeTo( 'slow', 1 );
	setTimeout("cover_close('cover','inner_feedback')",500);
  } ); 
  // When the "Escape" key is pressed, close the form
  $('#contactForm').keydown( function( event ) 
  {
    if ( event.which == 27 ) 
	{
      $('#contactForm').fadeOut();
      $('#content').fadeTo( 'slow', 1 );
	  setTimeout("cover_close('cover','inner_feedback')",500);
    }
  } );
}
function submitForm() 
{
	var contactForm = $(this);
	var email = document.getElementById('email').value;
	var message = document.getElementById('message').value;
	var js_captcha = document.getElementById('js_captcha').value;
	var hid_captcha = document.getElementById('hid_captcha').value;
	var sbject = document.getElementById('sbject').value;
	
	var done = 1;
	if(email.trim() == "")
	{
		msg = "'E-mail' cannot be blank!";
		document.getElementById('email').focus();
		done = 0;
	}
	else if(email.trim() != "")
	{
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email.match(emailRegEx))
		{
			msg = "Invalid E-mail!";
			document.getElementById('email').focus();
			done = 0;
		}
		else
		{
			if(message.trim() == "")
			{
				msg = "'Comment' cannot be blank!";
				document.getElementById('message').focus();
				done = 0;
			}
			else if(js_captcha.trim() == "")
			{
				msg = "'Result' cannot be blank!";
				document.getElementById('js_captcha').focus();
				done = 0;
			}
			else if(js_captcha.trim() != hid_captcha.trim())
			{
				msg = "Incorrect 'Result'!";
				document.getElementById('js_captcha').focus();
				done = 0;
			}
		}
	}
	if(done == 0)
	{
		randomNum();
		document.getElementById('errorform').style.display='block';
		document.getElementById('errorform').innerHTML=msg;
		return false;
	}
	else 
	{
		$('#sendingMessage').fadeIn();
		contactForm.fadeOut();
		
		var pageurlhome = document.getElementById('pageurlhome').value;
		pageurlhome = pageurlhome+"users/";
		
		$.post(pageurlhome+"feedback",{"email":email,"message":message,"sbject":sbject,"js_captcha":js_captcha,"hid_captcha":hid_captcha}, function(response){
			if(response){
				submitFinished(response);
			}
			else {
				submitFinished("success");
			}
		});
	}
	return false;
}

function submitFinished(response) 
{
	response = $.trim(response);
	$('#sendingMessage').fadeOut();
	if(response == "success") 
	{
		$('#successMessage').fadeIn().delay(2000).fadeOut();
		$('#senderEmail').val( "" );
		$('#message').val( "" );
		$('#txt_captcha').val( "" );
		randomNum();
		setTimeout("cover_close('cover','inner_feedback')",2800);
	} 
	else if( response == "email_error" )
	{
		randomNum();
		$('#emailErrMsg').fadeIn().delay(1000).fadeOut();
		$('#contactForm').delay(1000).fadeIn();
	}
	else 
	{
		randomNum();
		$('#failureMessage').fadeIn().delay(1500).fadeOut();
		$('#contactForm').delay(1500+500).fadeIn();
	}
}


