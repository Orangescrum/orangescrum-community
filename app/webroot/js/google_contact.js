var OAUTHURL = 'https://accounts.google.com/o/oauth2/auth?';
var SCOPE = 'https://www.google.com/m8/feeds/';
var TYPE = 'code';

var google_contact;
var pollTimer;
var timer;
var google_contact_win;
function contactListGoogle(uid_comp) {
    var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENT_ID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&access_type=offline&state=contact';
    //createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
    timer = setInterval('polling()',100);
    var emails = $('#txt_email').val();
    _url += '-'+encodeURIComponent(emails)+'-'+uid_comp;
    setCookieContact('contact_emails', '',-1);
    google_contact_win = window.open(_url, "windowname11", 'width=500, height=600');
}
function polling(){
    if (google_contact_win && google_contact_win.closed) {
	google_contact_win=null
	clearInterval(timer);
	var emails = getCookieContact('contact_emails');
	$('#txt_email').val(emails);
	var lenEmal = emails.split(',');
	if(emails && lenEmal.length >= 4){
	    $('#txt_email').css('height',eval(parseInt(lenEmal.length)*20)+'px');
	}else{
	    $('#txt_email').css('height','60px');
	}	
    }
}
function getCookieContact(cname) {
    if(cname == 'contact_emails'){
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
	    var c = ca[i];
	    while (c.charAt(0)==' ') c = c.substring(1);
	    if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
	}
    }
    return "";
}
function setCookieContact(cname, cvalue, exdays) {
    var domain_set = '';
	domain_set = '.'+DOMAIN;
    if(cname == 'contact_emails'){
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+d.toGMTString();
	document.cookie = cname + "=" + cvalue + ";domain="+domain_set+";path=/;" + expires;
    }
}