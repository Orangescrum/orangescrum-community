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