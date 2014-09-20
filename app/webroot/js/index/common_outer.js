//For google login and signup starts
var OAUTHURL = 'https://accounts.google.com/o/oauth2/auth?';
var SCOPE = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
var TYPE = 'code';

var google_signup;
var google_login;
var pollTimer;

function signinWithGoogle() {
    var uri = (window || this).location.href;
    var index = uri.indexOf(PROTOCOL+"www."+DOMAIN+"signup/");
    var param = "getstarted";
    if (index !== -1) {
	param = uri.replace(PROTOCOL+"www."+DOMAIN+"signup/", "");
	param = (param) ? param : "getstarted";
    }
  
    var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENT_ID_SIGNUP + '&redirect_uri=' + REDIRECT_SIGNUP + '&response_type=' + TYPE + '&access_type=offline&state=signup';
    createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
    window.open(_url, "windowname1", 'width=600, height=600');
    if (pollTimer) {
	window.clearInterval(pollTimer);
    }
    pollTimer = window.setInterval(function() {
	try {
	    if (getCookie('google_accessToken')) {
		window.clearInterval(pollTimer);
		try {
		    google_signup = getCookie('google_signup');
		    var user_info = getCookie('user_info');
                    //alert(user_info)
		    createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
		    createCookie("google_signup", '', -365, DOMAIN_COOKIE);
		    if(user_info){
                        window.location=PROTOCOL+"app."+DOMAIN+"users/login";
                    }
                    else if (parseInt(google_signup)) {
			window.location=PROTOCOL+"www."+DOMAIN+"signup/"+param;
		    }
		} catch (e) {
		    return;
		}
	    }
	} catch (e) {
	}
    }, 500);
}

function loginWithGoogle() {
    var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENT_ID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&access_type=offline&state=login';
    createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
    window.open(_url, "windowname1", 'width=600, height=600');
    if (pollTimer) {
	window.clearInterval(pollTimer);
    }
    pollTimer = window.setInterval(function() {
	try {
	    if (getCookie('google_accessToken')) {
		window.clearInterval(pollTimer);
		try {
		    google_login = getCookie('google_login');
		    createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
		    createCookie("google_login", '', -365, DOMAIN_COOKIE);
		    if (parseInt(google_login)) {
			window.location=HTTP_APP+"users/login";
		    }
		} catch (e) {
		    return;
		}
	    }
	} catch (e) {
	}
    }, 500);
}

//For google login and signup ends


String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
function jsVoid() { }
function removeMsg() {
	$('#upperDiv').fadeOut(300);
	$("#btnDiv").hide();
}
function getHeight()
{
	var height = document.body.offsetHeight;
	if(window.innerHeight) 
	{
		var theHeight=window.innerHeight;
	}
	else if(document.documentElement && document.documentElement.clientHeight) 
	{
		var theHeight=document.documentElement.clientHeight;
	}
	else if(document.body) 
	{
		var theHeight=document.body.clientHeight;
	}
	if(theHeight > height)
	{
		var hg1 = theHeight;
	}
	else
	{
		var hg1 = height;
	}
	var newhg = $(document).height();
	if(newhg > hg1) {
		var hg = newhg+"px";
	}
	else {
		var hg = hg1+"px";
	}
	return hg;
}
function cover_open(a,b)
{
	var hg = getHeight();
	document.getElementById(a).style.height=hg;
	$("#"+a).fadeIn(); 
	$("#"+b).slideDown('fast'); 
	
}
function cover_close(a,b)
{
	document.body.style.overflow = "visible";
	$("#"+a).fadeOut(); 
	$("#"+b).slideUp('fast'); 
}
function postSupport(a,b)
{
	
	var support_name = $("#support_name").val().trim();
	var support_email = $("#support_email").val().trim();
	var support_msg = $("#support_msg").val().trim();
	$("#support_err").hide();
	
	if(support_name == "")
	{
		$("#support_err").show();
		$("#support_err").html("Name cannot be blank!");
		$("#support_name").focus();
		return false;
	}
	else if(support_email == "")
	{
		$("#support_err").show();
		$("#support_err").html("E-mail cannot be blank!");
		$("#support_email").focus();
		return false;
	}
	else
	{
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!support_email.match(emailRegEx))
		{  
			$("#support_err").show();
			$("#support_err").html("Please enter a valid E-mail!");
			$("#support_email").focus();
			return false;
		}
		else if(support_msg == "")
		{
			$("#support_err").show();
			$("#support_err").html("Message cannot be blank!");
			$("#support_msg").focus();
			return false;
		}
		else {
			$("#btn_submit").hide();
			$("#loaderpost").show();
			
			var strURL = $("#pageurl").val();
			$.post(strURL+"users/post_support",{"support_email":escape(support_email),"support_msg":escape(support_msg),"support_name":escape(support_name)},function(data) {
				 if(data == "success") {
					$("#btn_submit").show();
					$("#loaderpost").hide();
					
					cover_close('cover','inner_support');
					$("#support_name").val('');
					$("#support_email").val('');
					$("#support_msg").val('');
					
					cover_open('cover','inner_success');
					$("#successmsg").html("Thanks for your feedback. We will get back to you as soon as possible.");
					setTimeout("cover_close('cover','inner_success')",3000);
				 }
				 else {
					 cover_close('cover','inner_support');
				 }
			});
		}
	}
	return false;
}
function validateSignUp()
{	
	var name = document.getElementById('name');
	var pass_new = document.getElementById('pas_new');
	var pas_retype = document.getElementById('pas_retype');
	var errMsg; var done = 1;
	if(name.value.trim() == ""){
		errMsg = "Name cannot be  blank!";
		name.focus();
		done = 0;
	}
	else if(pass_new.value.trim() == ""){
		errMsg = "Password cannot be  blank!";
		pass_new.focus();
		done = 0;
	}
	else if(pass_new.value.length < 6 || pass_new.value.length > 15){
		errMsg = "Password should be between 6-15 characters!";
		pass_new.focus();
		done = 0;
	}
	else if(pas_retype.value.trim() == ""){
		errMsg = "Confirm Password cannot be  blank!";
		pas_retype.focus();
		done = 0;
	}
	else if(pas_retype.value != pass_new.value){
		errMsg = "Password fields do not match!";
		pas_retype.focus();
		done = 0;
	}
	if(done == 0){
		document.getElementById('err_signup').style.display='block';
		document.getElementById('err_signup').innerHTML=errMsg;
		return false;
	}
	else{
		document.getElementById('err_signup').style.display='none';
		document.getElementById('signupbtn').style.display='none';
		document.getElementById('signupload').style.display='block';
		return true;
	}
}
function validpwd(txtEmail)
{
	var email = document.getElementById(txtEmail).value;
	var done = 1;
	if(email.trim() == ""){
		err = "'E-mail' cannot be left blank !";
		done = 0;
	}
	else{
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email.match(emailRegEx)){
			err = "Invalid 'E-mail' address !";
			done = 0;
		}
	}
	if(done == 0){
		document.getElementById('error').style.display='block';
		document.getElementById('error').innerHTML=err;
		return false;
	}
	else{
		document.getElementById('error').style.display='none';
		document.getElementById('fgpass').style.display='none';
		document.getElementById('fgload').style.display='block';
		return true;
	}
	
}
function noSpace(e)
{
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if(unicode != 8 ) {
        if(unicode == 32) {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return true;
    }
}
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

function vaidateSignIn(txt1,txt2)
{
	var uid = document.getElementById(txt1).value;
	var pass = document.getElementById(txt2).value;
	var done=1;
	if(uid.trim() == ""){
		document.getElementById(txt1).style.background='#FBBBB9';
		document.getElementById(txt1).focus();
		done=0;
	}
	else if(pass.trim() == ""){
		document.getElementById(txt2).style.background='#FBBBB9';
		document.getElementById(txt2).style.background='#FFF';
		document.getElementById(txt2).focus();
		done=0;
	}
	if(done == 0){
		return false;
	}
}
function randomNum()
{
	var x1 = Math.ceil(Math.random() * 12)+ '';
	var x2 = Math.ceil(Math.random() * 20)+ '';
	var tot = parseInt(x1)+parseInt(x2);
	var str = "("+x1+" + "+x2+")";
	document.getElementById('showcaptcha').innerHTML = str;
	document.getElementById('hid_captcha').value = tot;
	document.getElementById('js_captcha').value = "";
	document.getElementById('errorform').innerHTML='';
}
function submitForm() 
{   var url=window.location.href;
	var contactForm = $(this);
	var email = document.getElementById('email').value;
	var message = document.getElementById('message').value;
	var js_captcha = document.getElementById('js_captcha').value;
	var hid_captcha = document.getElementById('hid_captcha').value;
	var sbject = document.getElementById('sbject').value;
	
	var done = 1;
	if(email.trim() == ""){
		msg = "'E-mail' cannot be blank!";
		document.getElementById('email').focus();
		done = 0;
	}
	else if(email.trim() != ""){
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email.match(emailRegEx)){
			msg = "Invalid E-mail!";
			document.getElementById('email').focus();
			done = 0;
		}
		else{
			if(message.trim() == ""){
				msg = "'Comment' cannot be blank!";
				document.getElementById('message').focus();
				done = 0;
			}
			else if(js_captcha.trim() == ""){
				msg = "'Result' cannot be blank!";
				document.getElementById('js_captcha').focus();
				done = 0;
			}
			else if(js_captcha.trim() != hid_captcha.trim()){
				msg = "Incorrect 'Result'!";
				document.getElementById('js_captcha').focus();
				done = 0;
			}
		}
	}
	if(done == 0){
		randomNum();
		document.getElementById('errorform').style.display='block';
		document.getElementById('errorform').innerHTML=msg;
		return false;
	}
	else {
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
	if(response == "success")  {
		$('#successMessage').fadeIn().delay(2000).fadeOut();
		$('#senderEmail').val( "" );
		$('#message').val( "" );
		$('#txt_captcha').val( "" );
		randomNum();
		setTimeout("cover_close('cover','inner_feedback')",2800);
	} 
	else if( response == "email_error" ){
		randomNum();
		$('#emailErrMsg').fadeIn().delay(1000).fadeOut();
		$('#contactForm').delay(1000).fadeIn();
	}
	else {
		randomNum();
		$('#failureMessage').fadeIn().delay(1500).fadeOut();
		$('#contactForm').delay(1500+500).fadeIn();
	}
}
/* Lopa */
function randomNumber()
{
	var x1 = Math.ceil(Math.random() * 12)+ '';
	var x2 = Math.ceil(Math.random() * 20)+ '';
	var tot = parseInt(x1)+parseInt(x2);
	var str = "("+x1+" + "+x2+")";
	document.getElementById('showcaptcha1').innerHTML = str;
	document.getElementById('hid_captcha1').value = tot;
	document.getElementById('js_captcha1').value = "";
	document.getElementById('errorform1').innerHTML='';
}


$( contactnow );
function contactnow() 
{ 
  $('#contact').hide().submit( submitForm1 ).addClass( 'positioned' );
   
  $('a[href="#contact"]').click( function() {
    $('#content1').fadeTo( 'slow', .2 );
    $('#contact').fadeIn( 'slow', function() {
	  
      $('#email1').focus();
    } )

    return false;
  } );
  // When the "Cancel" button is clicked, close the form
  $('#cancel1').click( function() { 
    $('#contact').fadeOut();
    $('#content1').fadeTo( 'slow', 1 );
	setTimeout("cover_close('cover','inner_feedback1')",500);
  } ); 
  $('#close1').click( function()  { 
  
    $('#contact').fadeOut();
    $('#content1').fadeTo( 'slow', 1 );
	setTimeout("cover_close('cover','inner_feedback1')",500);
  } ); 
  
  
  // When the "Escape" key is pressed, close the form
  /*$('#contact').keydown( function( event ) {
    if ( event.which == 27 ) {
      $('#contact').fadeOut();
      $('#content1').fadeTo( 'slow', 1 );
	  setTimeout("cover_close('cover','inner_feedback1')",500);
    }
  } );*/
  
  
}




function submitForm1() 
{
	var contact = $(this);
	var email = document.getElementById('email1').value;
	var js_captcha1 = document.getElementById('js_captcha1').value;
	var hid_captcha1 = document.getElementById('hid_captcha1').value;
	var done = 1;
	if(email.trim() == ""){
		msg = "'E-mail' cannot be blank!";
		document.getElementById('email').focus();
		done = 0;
	}
	else if(email.trim() != ""){
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email.match(emailRegEx)){
			msg = "Invalid E-mail!";
			document.getElementById('email').focus();
			done = 0;
		}
		else{
			
			 if(js_captcha1.trim() == ""){
				msg = "'Result' cannot be blank!";
				document.getElementById('js_captcha1').focus();
				done = 0;
			}
			else if(js_captcha1.trim() != hid_captcha1.trim()){
				msg = "Incorrect 'Result'!";
				document.getElementById('js_captcha1').focus();
				done = 0;
			}
		}
	}
	if(done == 0){
		randomNumber();
		document.getElementById('errorform1').style.display='block';
		document.getElementById('errorform1').innerHTML=msg;
		return false;
	}
	else { 
		$('#sendingMessage1').fadeIn();
		contact.fadeOut();
		
		var pageurlhome1 = document.getElementById('pageurlhome').value;
		pageurlhome1 = pageurlhome1+"users/";
		
		$.post(pageurlhome1+"contactnow",{"email1":email,"js_captcha1":js_captcha1,"hid_captcha1":hid_captcha1}, function(response){
			if(response){
				submitFinishednew(response);
			}
			else {
				submitFinishednew("success");
			}
		});
	}
	return false;
}

function submitFinishednew(response) 
{ 
	response = $.trim(response);
	$('#sendingMessage1').fadeOut();
	if(response == "success")  { 
		$('#successMessage1').fadeIn().delay(2000).fadeOut();
		$('#email1').val( "" );
		
		$('#txt_captcha1').val( "" );
		randomNumber();
		setTimeout("cover_close('cover','inner_feedback1')",2800);
	} 
	else if( response == "email_error" ){
		randomNumber();
		$('#emailErrMsg1').fadeIn().delay(1000).fadeOut();
		$('#contact').delay(1000).fadeIn();
	}
	else {
		randomNumber();
		$('#failureMessage1').fadeIn().delay(1500).fadeOut();
		$('#contact').delay(1500+500).fadeIn();
	}
}


function cover_open1(a,b)
{  
	var height = document.body.offsetHeight;
	if(window.innerHeight) {
		var theHeight=window.innerHeight;
	}
	else if(document.documentElement && document.documentElement.clientHeight) {
		var theHeight=document.documentElement.clientHeight;
	}
	else if(document.body) {
		var theHeight=document.body.clientHeight;
	}
	if(theHeight > height){
		var hg1 = theHeight;
	}
	else{
		var hg1 = height;
	}
	var newhg = $(document).height();
	if(newhg > hg1){
		var hg = newhg+"px";
	}
	else{
		var hg = hg1+"px";
	}
	document.getElementById(a).style.height=hg;
	document.getElementById(a).style.display='block';   
	document.getElementById(b).style.display='block'; 
}

function validate(email,span)
	{
		//var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
          var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var email = document.getElementById(email).value;
		if(email == '')
		{
            $("#"+span).html('Please enter your work email.');
			//alert("Please enter a email to subscribe.");
			return false;
		}
		//else if(!pattern.test(email))
          else if(email.trim().match(mailformat))
		{
               return true;
		}
		else
		{
            $("#"+span).html('Please enter your valid work email.');
			//alert("Please enter a valid email for subscription.");
			return false;
		}
	}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
	c_start = document.cookie.indexOf(c_name + "=");
	if (c_start != -1) {
	    c_start = c_start + c_name.length + 1;
	    c_end = document.cookie.indexOf(";", c_start);
	    if (c_end == -1) {
		c_end = document.cookie.length;
	    }
	    return unescape(document.cookie.substring(c_start, c_end));
	}
    }
    return "";
}
function checkusercuki() {
	setInterval(
		function(){
			if(getCookie('USER_UNIQ') && getCookie('USERTYP') && getCookie('USERTZ')){
				window.top.location.reload();
			}
		}
	,1349);
}

function createCookie(name, value, days, domain) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }else
        expires = "";
    if (domain)
        var domain = " ; domain="+DOMAIN_COOKIE;
    else
        var domain = '';
    document.cookie = name + "=" + value + expires + "; path=/" + domain;
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}