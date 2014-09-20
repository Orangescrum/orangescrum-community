var OAUTHURL = 'https://accounts.google.com/o/oauth2/auth?';
var SCOPE = 'https://www.google.com/m8/feeds/';
var TYPE = 'code';
var CLIENTID = '1092531224599.apps.googleusercontent.com';
var REDIRECT_URI = 'http://as.smurti.com/users/gmailContacts';
var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT_URI + '&response_type=' + TYPE ;
var gaccess_token;
var pollTimer;
var caseId, caseDpId;
var emailId;
function googleConnect(arg) {
    caseId = arg;
    createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
    window.open(_url, "windowname1", 'scrollbars=1,width=500, height=500');
    if (pollTimer) {
	window.clearInterval(pollTimer);
    }
    pollTimer = window.setInterval(function() {
	try {
	    if (getCookie('google_accessToken')) {
		window.clearInterval(pollTimer);
		try {
		    var google_accessToken = getCookie('google_accessToken');
		    createCookie("google_accessToken", '', -365, DOMAIN_COOKIE);
		    gaccess_token = JSON.parse(google_accessToken).access_token;
		} catch (e) {
		    return;
		}
		gapi.client.setApiKey(API_KEY);
		gapi.client.load('drive', 'v2');
		google.load('picker', '1', {"callback": createPicker});
	    }
	} catch (e) {
	}
    }, 500);
}

function createPicker() {
    var auth_token = gaccess_token;
    var picker = new google.picker.PickerBuilder()
	    .enableFeature(google.picker.Feature.NAV_HIDDEN)
	    .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
	    .setAppId(CLIENT_ID)
	    .setOAuthToken(auth_token)
	    .addViewGroup(
		new google.picker.ViewGroup(google.picker.ViewId.DOCS)
		.addView(google.picker.ViewId.DOCUMENTS)
		.addView(google.picker.ViewId.PRESENTATIONS)
		.addView(new google.picker.DocsUploadView())
	    )
	    .setCallback(pickerCallback)
	    .build();
    picker.setVisible(true);
}

function pickerCallback(data) {
    if (data.action == google.picker.Action.PICKED) {
	$("#gloader").show();
	var fileId, fileName;
	var request;
	var body = {'value': '','type': 'anyone','role': 'reader'};
	for (var i in data.docs) {
	    fileId = data.docs[i].id;
	    fileName = data.docs[i].name;

	    //Set public permission.
	    //Insert permission.
	    request = gapi.client.drive.permissions.insert({
	      'fileId': fileId,
	      'resource': body
	    });
	    request.execute(function(resp) { 
		//console.log(resp);
	    });

	    /*
	    //Getting permission id.
	    request = gapi.client.drive.permissions.list({
		'fileId': fileId
	    });
	    request.execute(function(resp) {
		for(var i in resp){
		    console.log("permissions::: "+i);
		}
	    });

	    //Retrive permission.
	    var request = gapi.client.drive.permissions.get({
		'fileId': fileId,
		'permissionId': permissionId
	    });
	    //Update permission.
	    request.execute(function(resp) {
		resp.role = newRole;
		var updateRequest = gapi.client.drive.permissions.update({
		    'fileId': fileId,
		    'permissionId': permissionId,
		    'resource': resp
		});
		updateRequest.execute(function(resp) { });
	    });*/

	    //Get file details
	    request = gapi.client.drive.files.get({
		fileId: fileId,
		Authorization: 'Bearer ' + gaccess_token
	    });

	    request.execute(fileGetCallback.bind(this, fileName),function(resp) {
		//console.log(resp);
	    });
	}
	$("#gloader").hide();
    }
}

function fileGetCallback(fileName, file){
    console.log("file::: "+file);
    console.log("case::: "+caseId);
    if(file.id != undefined){
	var content = '{"id":"'+file.id+'","title":"'+file.title+'","alternateLink":"'+file.alternateLink+'"';
	if(file.embedLink != undefined)
	    content = content + ',"embedLink":"'+file.embedLink+'"';
	if(file.webContentLink != undefined)
	    content = content + ',"webContentLink":"'+file.webContentLink+'"';
	if(file.parents != undefined)
	    content = content + ',"parent_id":"'+file.parents['0'].id+'"';
	content = content + '}';

	var shortTitle, title;
	title = shortTitle = file.title;
	if(title.length > 70)
	    shortTitle = jQuery.trim(title).substring(0, 67).split(" ").slice(0, -1).join(" ") + "...";

	var str = "<div id='gd_"+caseId+file.id+"'><div style='float:left;margin-top:2px;'><input id='chkbx_"+caseId+file.id+"' type='checkbox' checked='checked' onclick='removeLink(\""+file.id+"\",\""+file.title+"\");' style='cursor: pointer;' /></div><div style='float:left;margin-left: 6px;margin-top:4px;'><a href='"+file.alternateLink+"' target='_blank' title='"+file.title+"'>"+shortTitle+"</a><input type='hidden' name='data[Easycase][cloud_storage_files][]' value='"+content+"' /></div><div style='clear:both;'></div></div>";
	$("#drive_tr_"+caseId).show();

	if($("#gd_"+caseId+file.id).length === 0)
	    $("#cloud_storage_files_"+caseId).append(str);
    } else {
	var message = file.message;
	message = message.split(":");
	if(message['0'] == 'File not found'){
	    alert(fileName+": This file has no read permission.");
	} else {
	    alert(fileName+": "+ message['0']);
	}
    }
}

function removeLink(id, title){
    if(confirm("Are you sure you want to remove '"+title+"' file ?")){
	$("#gd_"+caseId+id).remove();
    } else {
	$("#chkbx_"+caseId+id).attr ("checked" ,"checked");
    }
}

