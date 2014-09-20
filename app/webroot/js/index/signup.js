function onchecked(id)
{
	if(document.getElementById(id).checked == false)
	{
		 document.getElementById('signUpSubmit').type = 'button';
	}
	else
	{
		document.getElementById('signUpSubmit').type = 'submit';
	}
}

$(init);
function init() 
{
	$('#signup').hide().submit(submitSignUpForm).addClass('positioned');
	$('a[href="#signup"]').click( function() 
	{
		document.getElementById('succMsg').style.display = 'none';
		cover_open('cover','signup_popup');
		
		$('#signup').fadeIn( 'slow', function() 
		{
		$('#signup_name').focus();
		})
		document.getElementById('captcha').src='captcha.php?'+Math.random();
		return false;
	});
	
	$('#close_signup').click( function() 
	{
		document.getElementById('errorsignupform').innerHTML="";
		$('#signup').fadeOut();
		setTimeout("cover_close('cover','signup_popup')",500);
	}); 
	
	$('#signup').keydown( function( event )
	{
		if (event.which == 27)
		{
			document.getElementById('errorsignupform').innerHTML="";
			$('#signup').fadeOut();
			setTimeout("cover_close('cover','signup_popup')",500);
		}
	});
}
// Submit the form via Ajax
function submitSignUpForm() 
{
	var signup = $(this);

	var signup_name = document.getElementById('signup_name').value;
	var signup_email = document.getElementById('signup_email').value;
	var signup_password = document.getElementById('signup_password').value;
	var signup_company = document.getElementById('signup_company').value;
	var signup_word = document.getElementById('signup_word').value;
	
	var done = 1;
	if(signup_name.trim() == "")
	{
		msg = "You must provide your Name.";
		document.getElementById('signup_name').focus();
		done = 0;
	}
	else if(signup_email.trim() == "")
	{
		msg = "You must provide your Email address.";
		document.getElementById('signup_email').focus();
		done = 0;
	}
	else if(signup_email.trim() != "")
	{
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!signup_email.match(emailRegEx))
		{
			msg = "Email address must be valid (format: john@doe.com)";
			document.getElementById('signup_email').focus();
			done = 0;
		}
		else
		{
			if(signup_password == "")
			{
				msg = "You must choose a Password.";
				document.getElementById('signup_password').focus();
				done = 0;
			}
			else if(signup_password.length < 6 && signup_password.length > 15)
			{
				msg = "Password must be between 6-15 characters.";
				document.getElementById('signup_password').focus();
				done = 0;
			}
			else if(signup_word.trim() == "")
			{
				msg = "You must enter Word Verification.";
				document.getElementById('signup_word').focus();
				done = 0;
			}
		}
	}
	if(done == 0)
	{
		document.getElementById('errorsignupform').style.display='block';
		document.getElementById('errorsignupform').innerHTML=msg;
		return false;
	}
	else 
	{
		$('#sendMsg').fadeIn();
		signup.fadeOut();
		
		$.ajax({
		url: signup.attr('action') + "?signup=true",
		type: signup.attr('method'),
		data: signup.serialize(),
		success: finishedSignup
		});
	}
	return false;
}

function finishedSignup(response) 
{
  response = $.trim(response);
  $('#sendMsg').fadeOut();
  if(response == "success") 
  {
    $('#signup_name').val( "" );
    $('#signup_password').val( "" );
	$('#signup_company').val( "" );
	$('#signup_word').val( "" );
	
	$('#succMsg').fadeIn().delay(2000).fadeOut();
	setTimeout("cover_close('cover','signup_popup')",2800);
  }
  else if(response == "error_details")
  {
    $('#failDetails').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
  }
  else if(response == "email_error")
  {
    $('#emailMsg').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
  }
  else if(response == "captcha_error")
  {
    $('#captchaMsg').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
	document.getElementById('captcha').src='captcha.php?'+Math.random();
  }
  else if(response == "password_error")
  {
    $('#paswMsg').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
  } 
  else if(response == "email_exists_error")
  {
    $('#emailEsistsMsg').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
  }
  else if(response == "terms_error")
  {
    $('#termsMsg').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
  }
  else
  {
    $('#failMsg').fadeIn().delay(1500).fadeOut();
    $('#signup').delay(1500).fadeIn();
  }
}
