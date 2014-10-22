
var time;
function showTopErrSucc(type,msg) {
	$("#topmostdiv").show();
	$("#btnDiv").show();
	$("#upperDiv").show();
	if(type == 'error') {
		$("#upperDiv").find(".msg_span").removeClass('success');
	}
	else {
		$("#upperDiv").find(".msg_span").removeClass('error');
	}
	$("#upperDiv").find(".msg_span").addClass(type);
	$("#upperDiv").find(".msg_span").html(msg);
	clearTimeout(time);
	time = setTimeout(removeMsg,6000);
}
function removePubnubMsg(){
     $('#punnubdiv').fadeOut(300);
     $("#pub_counter").val(0);
	 $("#hid_casenum").val(0);
     ajaxCaseView();
     //pubnubsection()
	//$("#btnDiv").hide();
}

function removeMsg() {
	$('#upperDiv').fadeOut(300);
	$("#btnDiv").hide();
}
function removeMsg_err() {
	$('#upperDiv_err').fadeOut(300);
	$("#btnDiv").hide();
}
function removeMsg_alert() {
	$('#upperDiv_alert').fadeOut(300);
	$("#btnDiv").hide();
}
function removeMsg_not() {
	$('#upperDiv_not').fadeOut(300);
	$("#btnDiv").hide();
}
function hideShowMilestoneCases(id) {
	var ids = $("#milestonecases"+id).val();
	var splt = ids.split(",");
	
	if($("#colsp"+id).is(":visible")) {
		$("#colsp"+id).hide(); $("#exp"+id).show();
		for(var i=0;i<splt.length;i++) {
			$("#curRow"+splt[i]).fadeOut();
		}
	}
	else {
		$("#colsp"+id).show(); $("#exp"+id).hide();
		for(var i=0;i<splt.length;i++) {
			$("#curRow"+splt[i]).fadeIn();
		}
	}
}
function numericDecimal(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if( unicode != 8 )
    {
        if(unicode < 9 || unicode > 9 && unicode < 46 || unicode > 57 || unicode == 47) {
			if(unicode == 37 || unicode == 38) {
            	return true;
			}
			else {
				return false;
			}
        }
        else {
            return true;
        }
    }
    else
    {
        return true;
    }
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
	$("#topmostdiv").hide();
	document.getElementById(a).style.height=hg;
	$("#"+a).fadeIn(); 
	$("#"+b).slideDown('fast'); 
	//document.getElementById(b).style.display='block'; 
	
}
function cover_close(a,b)
{
	document.body.style.overflow = "visible";
	$("#"+a).fadeOut(); 
	$("#"+b).slideUp('fast'); 
}
function pageFadeIn(divid)
{  
	var hg = getHeight();
	document.getElementById(divid).style.height=hg;
	$("#"+divid).fadeIn(1000);
}
function pageFadeOut(divid)
{  
	var hg = getHeight();
	$("#"+divid).fadeOut(500); 
}
function submitCompany() {
	var name = document.getElementById('name');
	var website = $("#website").val();
	var phone = $("#phone").val();
	var errMsg;
	var done = 1;
	var regUrl = "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)";
	var rxAlphaNum = /^([0-9\(\)-]+)$/;
	
	if(name.value.trim() == "") {

		errMsg = "Name cannot be left blank!";
		name.focus();
		done = 0;
	}
	
	if(website.trim().length != 0 && !website.trim().match(regUrl)) {
		errMsg = 'Please enter valid website url.';
		$("#website").focus();
		done = 0;
	}
	
	if(phone.trim().length != 0 && !phone.trim().match(rxAlphaNum)) {
		errMsg = 'Please enter valid phone number.';
		$("#phone").focus();
		done = 0;
	}
	
	
	if(done == 0) {
		var op = 100;
		showTopErrSucc('error',errMsg);
		return false;
	}
	else {
		document.getElementById('subprof1').style.display='none';
		document.getElementById('subprof2').style.display='block';
	}
}
function memberCustomer(txtEmailid,selprj,loader,btn)
{
	var email_id = document.getElementById(txtEmailid).value;
	var email_arr=email_id.split(',');
	//var time = document.getElementById('txt_loc').value;
	var prjid = $('#'+selprj).val();
	var done = 1;
	if(email_id == ""){
		done = 0;
		msg = "Email cannot be left blank!";
		document.getElementById('err_email_new').innerHTML = "";
		document.getElementById('err_email_new').style.display = 'block';
		document.getElementById('err_email_new').innerHTML = msg;
		document.getElementById(txtEmailid).focus();
		return false;
	}else{
		var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!email_id.match(emailRegEx)){  
			if(email_id.indexOf(',') != -1){
				var totlalemails=0;
				for(var i=0;i<email_arr.length;i++){
					if(email_arr[i].trim() != ""){
						if((!email_arr[i].trim().match(emailRegEx))){
							done = 0;
							msg = "Invalid Email: '"+email_arr[i]+"'";
							document.getElementById('err_email_new').innerHTML = "";
							document.getElementById('err_email_new').style.display = 'block';
							document.getElementById('err_email_new').innerHTML = msg;
							document.getElementById(txtEmailid).focus();
							return false;
						}
					}else{
						totlalemails++;
					}
				}
				if(totlalemails==email_arr.length){
					msg = "Entered stirng is not a valid email";
					$('#err_email_new').html("");
					$('#err_email_new').show();
					$('#err_email_new').html(msg);
					$('#'+txtEmailid).focus();
					return false;
				}
			}else{
				done = 0;
				msg = "Invalid E-Mail!";
				document.getElementById('err_email_new').innerHTML = "";
				document.getElementById('err_email_new').style.display = 'block';
				document.getElementById('err_email_new').innerHTML = msg;
				document.getElementById(txtEmailid).focus();
				return false;
			}	
		}
		if(done != 0)
		{
			var type = document.getElementById('sel_Typ').value;
			if(type == 2)
			{
				var usertype = "Admin";
			}
			else if(type == 3)
			{
				var usertype = "Member";
			}
			//var conf = confirm("Are you sure you want to add '"+email_id+"' as '"+usertype+"'?");
			//var conf = confirm("Are you sure you want to add '"+email_id+"' ?");
			//if(conf == true)
			//{
				document.getElementById('err_email_new').style.display='none';
				
				$("#ldr").show();
				$("#btn_addmem").hide();
				
				var uniq_id = $("#uniq_id").val();
				var strURL = document.getElementById('pageurl').value;
				if(email_id.indexOf(',') != -1){
					$.post(strURL+"users/ajax_check_user_exists",{"email":escape(email_id),"uniq_id":escape(uniq_id)},function(data) {
						 if(data == "success") {
							document.myform.submit();
							return true;
						 } else {
								if(data=='errorlimit'){
									$("#ldr").hide();
									$("#btn_addmem").show();
									$("#err_email_new").show();
									$("#err_email_new").html("Sorry! You are exceeding your user limit.");
								}else{
									$("#ldr").hide();
									$("#btn_addmem").show();
									$("#err_email_new").show();
									$("#err_email_new").html("Oops! Invitation already sent to '"+data+"'!");
								}
							 	
							return false;
						 }
					});
				}else{
					$.post(strURL+"users/ajax_check_user_exists",{"email":escape(email_id),"uniq_id":escape(uniq_id)},function(data) {
						 if(data == "invited" || data == "exists" || data == "owner" || data == "account") {
							$("#ldr").hide();
							$("#btn_addmem").show();
							$("#err_email_new").show();
							if(data == "owner") {
								$("#err_email_new").html("Ah... you are inviting the company Owner!");
							}else if(data == "account") {
								$("#err_email_new").html("Ah... you are inviting yourself!");
							}else {
								$("#err_email_new").html("Oops! Invitation already sent to '"+email_id+"'!");
							}
							return false;
						 }
						 else {
							document.myform.submit();
							return true;
						 }
					});
				}
				
			/*}
			else {
				return false;
			}*/
		}
	}
	return false;
}
function projectAdd(txtProj,shortname,loader,btn){
	document.getElementById('err_msg').innerHTML = "";
	document.getElementById('validate').value='1'
	var proj1 = "";
	proj1 = document.getElementById(txtProj).value;
	shortname1 = document.getElementById(shortname).value;
	var strURL = document.getElementById('pageurl').value;
	proj1 = proj1.trim();
	if(proj1 == ""){
		msg = "'Project Name' cannot be left blank!";
		document.getElementById('err_msg').style.display = 'block';
		document.getElementById('err_msg').innerHTML = msg;
		document.getElementById(txtProj).focus();
		return false;
	}else{
		if(!proj1.match(/^[A-Za-z0-9]/g)){
			msg = "'Project Name' must starts with an Alphabate or Number!";
			$('#err_msg').show();
			$('#err_msg').html(msg);
			$('#'+txtProj).focus();
			return false;
		}
	}
	if(shortname1.trim() == ""){
		msg = "'Project Short Name' cannot be left blank!";
		document.getElementById('err_msg').style.display = 'block';
		document.getElementById('err_msg').innerHTML = msg;
		document.getElementById(shortname).focus();
		return false;
	}else{
		var x = shortname1.substr(-1);
		if(!isNaN(x)){
			msg = "'Project Short Name' cannot have numbers at the end!";
			document.getElementById('err_msg').style.display = 'block';
			document.getElementById('err_msg').innerHTML = msg;
			document.getElementById(shortname).focus();
			
			return false;
		}
		var email_id = $('#members_list').val();
		var done = 1;
		if(email_id){
			var email_arr=email_id.split(',');
			var totlalemails=0;
			var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(!email_id.match(emailRegEx)){  
				if(email_id.indexOf(',') != -1){
					for(var i=0;i<email_arr.length;i++){
						if(email_arr[i].trim() != ""){
							if((!email_arr[i].trim().match(emailRegEx))){
								done = 0;
								msg = "Invalid Email: '"+email_arr[i]+"'";
								$('#err_mem_email').show();
								$('#err_mem_email').html(msg);
								$('#members_list').focus();
								return false;
							}
						}else{
							totlalemails++;
						}
					}
					if(totlalemails==email_arr.length){
						msg = "Entered stirng is not a valid email";
						$('#err_mem_email').show();
						$('#err_mem_email').html(msg);
						$('#members_list').focus();
						return false;
					}
				}else{
					msg = "Invalid E-Mail!";
					$('#err_mem_email').show();
					$('#err_mem_email').html(msg);
					$('#members_list').focus();
					return false;
				}	
			}
		}else{
			$('#err_mem_email').html();
		}
		document.getElementById('err_msg').style.display='none';
		document.getElementById(loader).style.display='block';
		document.getElementById(btn).style.display='none';
		
		$.post(strURL+"projects/ajax_check_project_exists",{"name":escape(proj1),"shortname":escape(shortname1)},function(data) {

			 if(data == "Project") {
				 document.getElementById(loader).style.display='none';
			 	document.getElementById(btn).style.display='block';
				msg = "'Project Name' is already exists!";
				document.getElementById('err_msg').style.display = 'block';
				document.getElementById('err_msg').innerHTML = msg;
				document.getElementById(shortname).focus();
				return false;
			 }else if(data == "ShortName") {
				 document.getElementById(loader).style.display='none';
			 	document.getElementById(btn).style.display='block';
				msg = "'Project Short Name' is already exists!";
				document.getElementById('err_msg').style.display = 'block';
				document.getElementById('err_msg').innerHTML = msg;
				document.getElementById(shortname).focus();
				return false;
			 } else {
				 if(email_id){
					 $.post(strURL+'users/check_fordisabled_user',{'email':email_id},function(res){
						if(res!='1'){
							$('#'+loader).hide();
							$('#'+btn).show();
							if(res.indexOf(',') != -1){
								var msg= "'"+res+"' Users are disabled users.They are not allowed to add into a project.";
							}else{
								msg = "'"+res+"' is a disabled user, So cann't be added to a project";
			 }

							$('#err_mem_email').show();
							$('#err_mem_email').html(msg);
							$('#members_list').focus();
							return false;
						}else{
							$('#err_mem_email').html('');
							$('#err_mem_email').hide();
				 document.projectadd.submit();
				 return true;
			 }
		});
				 }else{
					document.projectadd.submit();
					return true; 
				 }
				 
			 }
		});
		return false;
	}
	return false;
}
function milestoneadd()
{
	var title = document.getElementById('txt_milestone').value;
	var start_date = document.getElementById('start_date1').value;
	var end_date = document.getElementById('end_date1').value;
	var uid = document.getElementById('CS_project_id').value;
		
	if(title.trim() == "")
	{
		msg = "'Milestone Title' cannot be left blank!";
		document.getElementById('err_msg1').style.display = 'block';
		document.getElementById('err_msg1').innerHTML = msg;
		document.getElementById('txt_milestone').focus();
		return false;
	}else if(start_date.trim() == "")
	{
		msg = "'Start date' cannot be left blank!";
		document.getElementById('err_msg1').style.display = 'block';
		document.getElementById('err_msg1').innerHTML = msg;
		document.getElementById('start_date').focus();
		return false;
	}else if(end_date.trim() == "")
	{
		msg = "'End date' cannot be left blank!";
		document.getElementById('err_msg1').style.display = 'block';
		document.getElementById('err_msg1').innerHTML = msg;
		document.getElementById('end_date').focus();
		return false;
	}else if(Date.parse(start_date) > Date.parse(end_date)) {
		msg = "Start Date cannot exceed End Date!";
		document.getElementById('err_msg1').style.display = 'block';
		document.getElementById('err_msg1').innerHTML = msg;
		document.getElementById('end_date').focus();
		return false;
	}else{
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";
	
		$.post(strURL+"add_milestone",{"title":title,"start_date":start_date,"end_date":end_date,"uid":uid}, function(data){
			  if(data) {
				var n=data.split("-");
				var mlstn = n[0];
				if(mlstn.length >= 15) {
					var mlstn = mlstn.substr(0,15)+"...";
				}
				$('#milestone_dd').html(mlstn);
				$("#CS_milestone").val(n[1]);
				$('#txt_milestone').val('');
				$('#start_date1').val('');
				$('#end_date1').val('');
				document.getElementById('err_msg1').style.display='none';
			  }
		});
		cover_close('cover','add_milestone');
	}
}
function ajaxChkShortName(strURL,id,sp1,sp2,btn) 
{
	var shortname = document.getElementById(id).value;
	if(shortname.trim() == "")
	{
		document.getElementById(sp1).innerHTML = '';
		return false;
	}
	document.getElementById(sp2).style.display='block'; 
	
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"projects/";
	
	$.post(strURL+"check_proj_short_name",{"shortname":shortname}, function(data){
		  if(data) {
			$('#ajxShortPage').html(data);
			document.getElementById(sp2).style.display='none'; 
		  }
	});
}
function checkUserShortName(id,ldr,msg)
{
	var shortname = document.getElementById(id).value;
	if(shortname.trim() == "")
	{
		document.getElementById(msg).innerHTML = '';
		return false;
	}
	document.getElementById(ldr).style.display ="block";
	
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	
	$.post(strURL+"check_short_name",{"shortname":shortname}, function(data){
		  if(data) {
			$('#'+msg).html(data);
			
			document.getElementById(ldr).style.display ="none";
		  }
	});
}
function ajaxShortName(strURL,id,sp1,sp2,btn) 
{
	var shortname1 = document.getElementById(id).value;
	if(shortname1.trim() == "")
	{
		document.getElementById(sp1).innerHTML = '';
		return false;
	}
	
	document.getElementById(sp2).style.display='block'; 
	
    var xmlHttpReq = false;
    var self = this;
    if (window.XMLHttpRequest) 
	{
        self.xmlHttpReq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) 
	{
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() 
	{
        if (self.xmlHttpReq.readyState == 4)
		{
            updFinishShortnm(self.xmlHttpReq.responseText,sp1);
			document.getElementById(sp2).style.display='none'; 
		}
	 }
    self.xmlHttpReq.send(qstrFinishShortnm(id));
}
function qstrFinishShortnm(id)
{	
	var qstr;
	var shortname = document.getElementById(id).value;
	qstr ='shortName=' +escape(shortname);
	return qstr;
}
function updFinishShortnm(str,sp1)
{
	document.getElementById(sp1).innerHTML = str;
}
function submitProject(proj,shrt)
{
	var done=1;

	if(document.getElementById(proj).value.trim() == "")
	{
		var msg="'Project Name' cannot be left blank!";
		document.getElementById(proj).focus();
		done=0;
	}
	if(document.getElementById(shrt).value.trim() == "")
	{
		var msg="'Project ShortName' cannot be left blank!";
		document.getElementById(shrt).focus();
		done=0;
	}
	if(done == 0)
	{
		showTopErrSucc('error',msg);
		return false;
	}
	var uniqid = $("#uniqid").val();
	var strURL = document.getElementById('pageurl').value;
	
	$("#savebtn").hide(); $("#settingldr").show();
	
	$.post(strURL+"projects/ajax_check_project_exists",{"uniqid":uniqid,"name":escape(document.getElementById(proj).value.trim()),"shortname":escape(document.getElementById(shrt).value.trim())},function(data) {

		 if(data == "Project") {
			$("#savebtn").show(); $("#settingldr").hide();
			msg = "'Project Name' is already exists!";

			showTopErrSucc('error',msg);
			
			document.getElementById(proj).focus();
			return false;
		 }
		 else if(data == "ShortName") {
			 $("#savebtn").show(); $("#settingldr").hide();
			msg = "'Project Short Name' is already exists!";
			showTopErrSucc('error',msg);
			document.getElementById(shrt).focus();
			return false;
		 }
		 else {
			 document.projsettings.submit();
			 return true;
		 }
	});
	return false;
}
<!------------------------------------------------->
/****************** FB Popup *******************/
<!------------------------------------------------->
function openFbPopup(page,param1,param2,param3)
{
	centerPopup(page,param1,param2,param3);
	loadPopup();
}
function loadPopup()
{
	$("#backgroundPopup").css({"opacity": "0.1"});
	$("#cover").fadeIn("fast");
	$("#popupContact").slideDown("fast");
}
function disablePopup()
{
	$("#cover").fadeOut("slow");
	$("#popupContact").slideUp("fast");
}
function centerPopup(page,param1,param2,param3)
{
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//$("#popupContact").css({"position": "fixed","top": windowHeight/2-popupHeight/2,"left": windowWidth/2-popupWidth/2});
	$("#popupContact").css({"left": windowWidth/2-popupWidth/2});
	$("#backgroundPopup").css({"height": windowHeight});

	var strURL = document.getElementById('pageurl').value;
	var strURL = strURL+'milestones/'+page;
	$("#popupload").show();
	
	$.post(strURL,{"mstid":param1,"projid":param2,"countmanage":param3},function(data) {
	 if(data) {
			$('#loadcontent').html(data);
			
			var str = decodeURIComponent($('#addcsmlstn').val());
			$('#mstonename').html(decodeURIComponent((str + '').replace(/\+/g, '%20')));
			
			$('#proj_name').html($('#cur_proj_name').val());
			$("#popupload").hide();
			$("#popupContactClose, .c_btn").click(function() {
				disablePopup();
			});
			if(page == "add_case") {
				document.getElementById('confirmbtn').style.display = 'block';
				document.getElementById('casesrch').style.display = 'block';
				document.getElementById('title').value = '';
				document.getElementById('confirmMilestone').disabled = true;
				
			}
	  }
	});
}
function fbPopupClose() {
	disablePopup();
}
/*********************** USpopup******************/
function openUsPopup(page,pjid,pjname,count)
{
	centerusPopup(page,pjid,pjname,count);
	loadusPopup();
}
function loadusPopup()
{
	//$("#backgroundPopup").css({"opacity": "0.5"});
	$("#cover").fadeIn("fast");
	$("#popupContact").slideDown("fast");
}
function disablePopup()
{
	$("#cover").fadeOut("slow");
	$("#popupContact").slideUp("fast");
}
function centerusPopup(page,pjid,pjname,count)
{
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//$("#popupContact").css({"position": "fixed","top": windowHeight/2-popupHeight/2,"left": windowWidth/2-popupWidth/2});
	$("#popupContact").css({"left": windowWidth/2-popupWidth/2});
	$("#backgroundPopup").css({"height": windowHeight});

	var strURL = document.getElementById('pageurl').value;
	var strURL = strURL+'projects/'+page;
	$("#popupload").show();
	
	$.post(strURL,{"pjid":pjid,"pjname":pjname,"cntmng":count},function(data) {
	 if(data) {
			$('#loadcontent').html(data);
			if($( "tr[id*='listing']" ).length == 1){
				document.getElementById('confirmbtn').style.display = 'none';
				$("#excptAddContinue").show();
			}
			$('#projectname').html($('#adusrprojnm').val());
			$("#popupload").hide();
			$("#popupContactClose, .c_btn").click(function() {
				disablePopup();
			});
			if(page == "add_user") {
				document.getElementById('closebtn').style.display = 'block';
				document.getElementById('usersrch').style.display = 'block';
				document.getElementById('name').value = '';
			}
	  }
	});
}
function usPopupClose() {
	disablePopup();
}
/*********************** PRpopup******************/

function openPrPopup(page,uid,count,name)
{
	centerprPopup(page,uid,count,name);
	loadprPopup();
}

function loadprPopup()
{
	$("#backgroundPopup").css({"opacity": "0.1"});
	$("#cover").fadeIn("fast");
	$("#popupContact").slideDown("fast");
}

function centerprPopup(page,uid,count,name)
{ 
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//$("#popupContact").css({"position": "fixed","top": windowHeight/2-popupHeight/2,"left": windowWidth/2-popupWidth/2});
	$("#popupContact").css({"left": windowWidth/2-popupWidth/2});
	$("#backgroundPopup").css({"height": windowHeight});
	var strURL = document.getElementById('pageurl').value;
	var strURL = strURL+'users/'+page+'/';
	$("#popupload").show();
	$.post(strURL,{"uid":uid,'count':count},function(data) {
	 if(data) {
				$('#loadcontent1').html(data);
				$("#popupload").hide();
				$("#popupContactClose, .c_btn").click(function() {
					disablePopup();
				});
			
				$('#usermanagename').html(decodeURIComponent(name.replace(/\+/g,' ')));
			
			if(page == "add_project") {
				document.getElementById('confirmbtn').style.display = 'block';				
				//document.getElementById('confirmprj').disabled = true;
			}
	}
	});
	
}

function prPopupClose() {
	disablePopup();
}
function openProfilePopup(page,uid,count,name)
{
	//centerprPopup(page,uid,count,name);
	loadprofilePopup();
}

function loadprofilePopup()
{
	$("#backgroundPopup").css({"opacity": "0.1"});
	$("#cover").fadeIn("fast");
	$("#popupContact").slideDown("fast");
	$('#up_files1').html('');
	$("#actConfirmbtn").hide();
	$("#inactConfirmbtn").show();
	
}
/************popup.js end****************************************************/
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
function jsVoid() {
}
function filterUserType(type) { 
	
	var user_srch = $("#user_srch").val();
	var role = $("#role").val();

	var pageurl = document.getElementById('pageurl').value;
	
	var url = pageurl+"users/manage/?role="+role+"&type="+type+"&user_srch="+user_srch;
	window.location=url;
}
function filterUserRole(role) {
	var user_srch = $("#user_srch").val();
	var type = $("#type").val();

	var pageurl = document.getElementById('pageurl').value;
	
	var url = pageurl+"users/manage/?role="+role+"&type="+type+"&user_srch="+user_srch;
	window.location=url;
}
function filterUserSearch(id) {
	var user_srch = $("#"+id).val();
	var role = $("#role").val();
	var type = $("#type").val();

	if(user_srch.trim() == "") {
		alert("Please enter any search criteria!");
		return false;
	}
	
	var pageurl = document.getElementById('pageurl').value;
	
	var url = pageurl+"users/manage/?role="+role+"&type="+type+"&user_srch="+user_srch;
	window.location=url;
}
//Code by jyoti start
function filterProjectSearch(id,type){ //alert('hi');alert(type);
     var proj_srch = $("#"+id).val();//alert(proj_srch);
     if(proj_srch.trim() == "") {
		//alert("Please enter any search criteria!");
          $("#txt_res").focus();
		return false;
	}
     var pageurl = document.getElementById('pageurl').value;

	if(type=='disabled'){
          var url = pageurl+"projects/gridview/disabled?proj_srch="+proj_srch;
     }else{
          var url = pageurl+"projects/gridview/?proj_srch="+proj_srch;
     }                    
	//var url = pageurl+"projects/gridview/?proj_srch="+proj_srch;
	window.location=url;
}
//Code by jyoti end
function showProjectName(name,id) {
	document.getElementById('projUpdateTop').innerHTML=decodeURIComponent(name);
	document.getElementById('CS_project_id').value=id;
	document.getElementById('openpopup').style.display='none';
	document.getElementById('projAllmsg').style.display = 'none';
	
	var pageurl = document.getElementById('pageurl').value;
	// Quick case User Listing
	var url = pageurl+"easycases/ajax_quickcase_mem";
	$.post(url,{"projUniq":id,"pageload":0}, function(data){
		  if(data) {
			$('#ajxQuickMem').html(data);
		  }
	});
	
	var pageurl = document.getElementById('pageurl').value;
	// Quick case User Listing
	var url = pageurl+"easycases/ajax_default_email";
	$.post(url,{"projUniq":id,"pageload":0}, function(data){
		  if(data) {
			$('#displayMembers').html(data);
			if($('#totaldefault').val() != 0) {
				$('#defaultmem').show();
			}
			else {
				$('#defaultmem').hide();
			}
		  }
	});
	
	opencase('changeproj');
}
function setMenuClass(value) {
	if(value == "assigntome") {
		$("#assigntomenu").addClass("current");
		$('#casesmenu').addClass('current');
		$('#filesmenu').removeClass('current');
		$("#delegatetomenu").removeClass("current");
		$("#milestonemenu").removeClass("current");
		$("#latest").removeClass("current");
		$("#all_case").removeClass("current");
		$("#closecasemenu").removeClass("current");
		$("#overduemenu").removeClass("current");
	}
	else if(value == "delegateto") {
		$("#delegatetomenu").addClass("current");
		$('#casesmenu').addClass('current');
		$('#filesmenu').removeClass('current');
		$("#assigntomenu").removeClass("current");
		$("#milestonemenu").removeClass("current");
		$("#latest").removeClass("current");
		$("#all_case").removeClass("current");
		$("#closecasemenu").removeClass("current");
		$("#overduemenu").removeClass("current");
	}
	else if(value == "latest") {
		$("#latest").addClass("current");
		$('#casesmenu').addClass('current');
		$('#filesmenu').removeClass('current');
		$("#assigntomenu").removeClass("current");
		$("#milestonemenu").removeClass("current");
		$("#delegatetomenu").removeClass("current");
		$("#all_case").removeClass("current");
		$("#closecasemenu").removeClass("current");
		$("#overduemenu").removeClass("current");
	}
	else if(value == "closecase") {
		$("#closecasemenu").addClass("current");
		$('#casesmenu').addClass('current');
		$('#filesmenu').removeClass('current');
		$("#assigntomenu").removeClass("current");
		$("#milestonemenu").removeClass("current");
		$("#delegatetomenu").removeClass("current");
		$("#all_case").removeClass("current");
		$("#latest").removeClass("current");
		$("#overduemenu").removeClass("current");
	}
	else if(value == "overdue") {
		$("#overduemenu").addClass("current");
		$('#casesmenu').addClass('current');
		$('#filesmenu').removeClass('current');
		$("#assigntomenu").removeClass("current");
		$("#milestonemenu").removeClass("current");
		$("#delegatetomenu").removeClass("current");
		$("#all_case").removeClass("current");
		$("#latest").removeClass("current");
		$("#closecasemenu").removeClass("current");
	}
	else if(value == "files") {
		$('#filesmenu').addClass('current');
		$("#assigntomenu").removeClass("current");
		$('#casesmenu').removeClass('current');
		$('#delegatetomenu').removeClass('current');
		$("#milestonemenu").removeClass("current");
		$("#latest").removeClass("current");
		$("#all_case").removeClass("current");
		$("#closecasemenu").removeClass("current");
		$("#overduemenu").removeClass("current");
	}
	else if(value == "milestone") {
		$("#milestonemenu").addClass("current");
		$("#delegatetomenu").removeClass("current");
		$('#casesmenu').removeClass('current');
		$('#filesmenu').removeClass('current');
		$("#assigntomenu").removeClass("current");
		$("#latest").removeClass("current");
		$("#all_case").removeClass("current");
		$("#closecasemenu").removeClass("current");
		$("#overduemenu").removeClass("current");
		
	}
	else {
		$("#casesmenu").addClass("current");
		$('#assigntomenu').removeClass('current');
		$('#filesmenu').removeClass('current');
		$('#delegatetomenu').removeClass('current');
		$("#milestonemenu").removeClass("current");
		$("#latest").removeClass("current");
		$("#all_case").addClass("current");
		$("#closecasemenu").removeClass("current");
		$("#overduemenu").removeClass("current");
	}
	/*if(value == "milestone") {
		document.getElementById('mView').style.fontWeight='bold';
		document.getElementById('mAdd').style.fontWeight='normal';
		document.getElementById('mManage').style.fontWeight='normal';
	}
	else {
		document.getElementById('mView').style.fontWeight='normal';
		document.getElementById('mAdd').style.fontWeight='normal';
		document.getElementById('mManage').style.fontWeight='normal';
		$("#mstoneul").slideUp();
	}*/
}
function caseMenuFileter(value,page,filters,caseid) {
	setMenuClass(value);
	var url = document.getElementById('pageurl').value;
	var durl = document.URL;
	if(page == "dashboard") {
		document.getElementById('casePage').value=1;
		var img = '&nbsp;&nbsp;<img src="'+url+'img/html5/icons/icon_breadcrumbs.png" />&nbsp;&nbsp;';
		document.getElementById('caseMenuFilters').value=value;
		if(value == "files"){
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxFileView('case_files');
				document.getElementById('pageheading').innerHTML='Files';
			}
			
		}
		else if(value == "assigntome"){
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'Assigned To Me';
			}
		}
		else if(value == "closecase"){
			
			resetAllFilters('filters');
			
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'Closed';
			}
		}
		else if(value == "overdue"){
			
			resetAllFilters('filters');
			
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
				
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'Bug';
			}
		}
		else if(value == "delegateto"){
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'Delegated To Others';
			}	
		}
		else if(value == "latest"){
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'Recent';
			}
			
		}
		else if(value == "highpriority"){
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'High Priority';
			}
			
		}
		else if(value == "milestone"){
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Milestone';
			}
		}
		else{
			if((durl.indexOf('?case=') != -1) && (durl.indexOf('&project=') != -1)){
				window.location=url+"dashboard?filters="+value;
			}else{
				ajaxCaseView('case_project');
				document.getElementById('pageheading').innerHTML='Tasks'+img+'All';
			}
			
		}
		
		strUrl = url+"easycases/";
		var projFil = document.getElementById('projFil').value;
	}
	else {
		if(value){
			window.location=url+"dashboard?filters="+value;
		}
		else{ //alert('dashboard');
			window.location=url+"dashboard";
		}
	}
}
function refreshCaseMessage(page) {
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"easycases/";
	
	$.post(strURL+"case_message",{"page":page}, function(data){
		  if(data) {
			$('#case_message').html(data);
		  }
	});
}
function refreshNotification() {
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	
	$.post(strURL+"notification",{}, function(data){
		  if(data) {
			$('#tips_placeholder').html(data);
		  }
	});
}
function removecaseView(id) {
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	
	$.post(strURL+"caseview_remove",{"id":id}, function(data){
		  if(data) {
			$('#remove_caseview').html(data);
		  }
	});
}

function displayAllProjects(page,type) {
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	
	document.getElementById('all_id_recC').style.display='none';
	document.getElementById('proj_loader_ajxC').style.display='block';
	
	$.post(strURL+"project_all",{"page":page,"type":type}, function(data){
		  if(data) {
			$('#ajx_project').html(data);
			document.getElementById('proj_view').style.display='block';
			document.getElementById('all_id_recC').style.display='block';
			document.getElementById('proj_loader_ajxC').style.display='none';
		  }
	});
}
function newUser(menu,loder,others) 
{
	//$('#'+menu).hide();
	//$('#'+loder).show();
	$('#user_popup_td').html('');
	$('#user_popup_td').html('<center><img src="'+HTTP_ROOT+'img/images/case_loader2.gif" alt="Loading..." title="Loading..." /></center>');
	if(others) {
		$('#inviteusr').hide();
		$('#loadinginvt').show();
	}
	
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	cover_open('cover','user_popup');
	$.post(strURL+"new_user",{}, function(data){
		  if(data) {
			if(others) {
				fbPopupClose();
				$('#inviteusr').show();
				$('#loadinginvt').hide();
			}
			$('#user_popup_td').html(data);
			//document.getElementById(menu).style.display ="block";
			//document.getElementById(loder).style.display ="none";
		  }
	});
}
function showInvitedUserLnk(wtLnk,Lnk){
	$("#"+Lnk).show();
	$("#"+wtLnk).hide();
}
function hideInvitedUserLnk(wtLnk,Lnk){
	$("#"+Lnk).hide();
	$("#"+wtLnk).show();
}
function billing_info_popup(){
	$('#est_billing_info_popup').show();
	var strURL = $('#pageurl').val();
	strURL = strURL+"users/billing_info";
	$.post(strURL,{}, function(data){
		  if(data) {
			$('#est_billing_info_popup').html(data);
		  }
	});
	cover_open('cover','est_billing_info_popup');
}
function close_billing_popup(){
	$('#est_billing_info_popup').html('');
	$('#est_billing_info_popup').hide();
	cover_close('cover','est_billing_info_popup');
	//$('#est_billing_info_popup_content').hide();
}
function hideShowImg(id,shText) {
	if(document.getElementById(id).style.display == 'none'){
		$("#"+id).slideDown(300);
		$("#"+shText).html("Hide Images");
	}
	else{
		$("#"+id).slideUp(300);
		$("#"+shText).html("Show Images");
	}
}
function fade() {
	document.getElementById('upperDiv').style.display='none';
}
function displayOption(b,c,d) {
	document.getElementById(b).style.display='none';
	document.getElementById(c).style.display='block';
}
function dspTaskLnkIcon(e,f,asnId,dueId){
	if($("#"+asnId).hasClass('assMain')){
		document.getElementById(e).style.display='block';
		$("#"+asnId).addClass('assignMain');
	}	
	if($("#"+dueId).hasClass('dueMain')){
		document.getElementById(f).style.display='block';
		$("#"+dueId).addClass('dueDateMain');
	}
}
function hideOption(b,c,d) {
	document.getElementById(b).style.display='block';
	document.getElementById(c).style.display='none';
}
function hidTaskLnkIcon(e,f,asnId,dueId,asgPopId,duPopId){
	if($("#"+asnId).hasClass('assignMain') && !$("#"+asgPopId).is(":visible")){
		document.getElementById(e).style.display='none';
		$("#"+asnId).removeClass('assignMain');
	}
	if($("#"+dueId).hasClass('dueDateMain') && !$("#"+duPopId).is(":visible")){
		document.getElementById(f).style.display='none';
		$("#"+dueId).removeClass('dueDateMain');
	}
}
//task action link drop hide
function hideActionLnkCss(){
	$(".assignMain").removeClass('assignMain');
	$(".dueDateMain").removeClass('dueDateMain');
	$("div[id^='assignDrpdown']").hide();
	$("div[id^='dueDateDrpdown']").hide();
}
function funAddAnotherDisplay(id,aa) {
	fileName = document.getElementById(id).value;
	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
	var value = fileName.lastIndexOf('.') + 1;
	if(value != 0) {
		document.getElementById(aa).style.display='block';
	}
	else {
		alert("Unknown extension name in this file!");
		document.getElementById(id).value = '';
		return false;
	}
}
function noSpace(e) {
    var unicode = e.charCode ? e.charCode : e.keyCode;
    if(unicode != 8 )
    {
        if(unicode == 32){
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
function resizeTextarea(id,length,divide,rows) {
	var len = document.getElementById(id).value.length;
	if(len > length) {
		var y = (document.getElementById(id).value.length / divide);
		document.getElementById(id).rows = y+1;
	}
	else {
		document.getElementById(id).rows = rows;
	}
}
function checkPasswordMatch(a,b,c) {
	var pass = document.getElementById(c).value;
	var errMsg;
	var done = 1;
	if(pass.trim() != "") {
		var pass_new = document.getElementById(a);
		var retypr_pass = document.getElementById(b);
		
		if(pass_new.value.trim() == "")
		{
			errMsg = "Password cannot be  blank!";
			pass_new.focus();
			done = 0;
		}
		else if(pass_new.value.length < 6)
		{
			errMsg = "Password should be between 6-15 characters!";
			pass_new.focus();
			done = 0;
		}
		else if(pass_new.value.length > 15)
		{
			errMsg = "Password should be between 6-15 characters!";
			pass_new.focus();
			done = 0;
		}
		else if(retypr_pass.value.trim() == "")
		{
			errMsg = "Confirm Password cannot be  blank!";
			retypr_pass.focus();
			done = 0;
		}
		else if(retypr_pass.value.trim() != pass_new.value.trim())
		{
			errMsg = "Passwords do not match!";
			retypr_pass.focus();
			done = 0;
		}
		else
		{
			document.getElementById('loader').style.display='block';
		}
	}
	else {
		errMsg = "Old Password cannot be left blank!";
		document.getElementById(c).focus();
		done = 0;
	}
	
	if(done == 0) {
		showTopErrSucc('error',errMsg);
		return false;
	}
}

function openDropdown(divId) {
	if(document.getElementById(divId).style.display=="block") {
		document.getElementById(divId).style.display='none';
	}
	else {
		document.getElementById(divId).style.display='block';
	}
}
function openDropdown1(divId) {
	document.getElementById(divId).style.display='none';
}
function setprojectAction(typ,name,id,page) {
	var conf = confirm("Are you sure you want to "+typ+" '"+name+"' ?");
	if(conf == true) {
		window.location=page+'?id='+id+'&action='+typ;
	}
	else {
		return false;
	}
}
function checkProjectSettings(id) {
	var conf = confirm("Do you want to assign user now?");
	if(conf == true) {
		window.location='case_settings.php?pid='+id;
	}
	else {
		return false;
	}
}
function showHide(a,b,div) {


	if(document.getElementById(b).style.display == 'none') {
		document.getElementById(b).style.display = 'block';
		document.getElementById(a).style.display = 'none';
		document.getElementById(div).style.display = 'block';
	}
	else {
		document.getElementById(b).style.display = 'none';
		document.getElementById(a).style.display = 'block';
		document.getElementById(div).style.display = 'none';
	}
}
function showHideDisplay(a,b) {
	if(document.getElementById(b).style.display == 'none') {
		document.getElementById(b).style.display = 'block';
		document.getElementById(a).style.display = 'none';
	}
	else {
		document.getElementById(b).style.display = 'none';
		document.getElementById(a).style.display = 'block';
	}
}
function openClose(a,b) {

	if(document.getElementById(a).style.display == 'block') {
		document.getElementById(a).style.display = 'none'
		document.getElementById(b).style.display = 'block'
	}
	else {
		document.getElementById(a).style.display = 'block'
		document.getElementById(b).style.display = 'none'
	}
}
function view(id,spn_rply,spn_close,spn_edit) {
	document.getElementById(id).style.background='#EEEEEE';
	document.getElementById(spn_rply).style.display= "block";
	document.getElementById(spn_close).style.display= "block";
	document.getElementById(spn_edit).style.display= "block";
}
function view_normal(id,spn_rply,spn_close,spn_edit) {
	document.getElementById(id).style.background='#ffffff';
	document.getElementById(spn_rply).style.display= "none";
	document.getElementById(spn_close).style.display= "none";
	document.getElementById(spn_edit).style.display= "none";
}
function view_th(id,spn_rply,spn_close,spn_edit) {
	document.getElementById(spn_rply).style.display= "block";
	document.getElementById(spn_close).style.display= "block";
	document.getElementById(spn_edit).style.display= "block";
}
function view_normal_th(id,spn_rply,spn_close,spn_edit) {
	document.getElementById(spn_rply).style.display= "none";
	document.getElementById(spn_close).style.display= "none";
	document.getElementById(spn_edit).style.display= "none";
}
function showHideDropDown(id) {
	if(document.getElementById(id).style.display == "block") {
		$("#"+id).slideUp(300);
	}
	else {
		$("#"+id).slideDown(300);
	}
	$("#statusTypes").slideUp(300); $("#prioritRefresh").slideUp(300); $("#csMemAjx").slideUp(300); $("#statusRefresh").slideUp(300); $("#ajaxViewProjects").slideUp(300);
	if(id == "statusRefresh") {
		$("#statusTypes").slideUp(300); $("#prioritRefresh").slideUp(300); $("#csMemAjx").slideUp(300);
	}
	if(id == "statusTypes") {
		$("#statusRefresh").slideUp(300); $("#prioritRefresh").slideUp(300); $("#csMemAjx").slideUp(300);
	}
	if(id == "prioritRefresh") {
		$("#statusTypes").slideUp(300); $("#statusRefresh").slideUp(300); $("#csMemAjx").slideUp(300);
	}
	if(id == "csMemAjx") {
		$("#statusTypes").slideUp(300); $("#prioritRefresh").slideUp(300); $("#statusRefresh").slideUp(300);
	}
	if(id == "ajaxViewProjects") {
		$("#statusTypes").slideUp(300); $("#prioritRefresh").slideUp(300); $("#statusRefresh").slideUp(300); $("#csMemAjx").slideUp(300);
	}
}
function caseAction(id,totid) {
	var allid = document.getElementById(totid).value;
	var newArr = allid.split("|");
	var inputArray = [];
	for(var i in newArr) {
		if(newArr[i])
		{
			var otherids = "tblswitch"+newArr[i];
			try
			{
				if(otherids == id)
				{
					if(document.getElementById(id).style.display == 'block')
					{
						$("#"+id).slideUp(300);
					}
					else
					{
						$("#"+id).slideDown(300);
					}
				}
				else
				{
					document.getElementById(otherids).style.display = 'none';
				}
			}
			catch(e)
			{
				
			}
		}
	}
}
function hide_prifield(val,id) {
	if(document.getElementById('CS_type_id').value == 10) {
		document.getElementById('leb_pri').style.display="none";
		document.getElementById(id).value = val;
		document.getElementById(id).style.color = '#000000';
	}
	else {
		var msg = document.getElementById(id).value;
		if(msg == val)
		{
			document.getElementById(id).value='';
		}
		document.getElementById('leb_pri').style.display="block";
	}
}
function search_project_activity(page,val,e){ 
	var key = e.keyCode;
	if(key==13)return;
	var menu_div_id = 'ajaxbeforesrch';	
	if($('#ajaxaftersrch').is(":visible")){
		var menu_div_id = 'ajaxaftersrch';	
		$('#ajaxbeforesrch > a').removeClass('popup_selected');
	}
    if( e.keyCode==40 || e.keyCode==38 ){
		var selected = "$('#"+menu_div_id+" > a')";
		if ( key == 40 ){ // Down key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':last-child')) {
				$current = $('#ajaxViewProject > a').eq(0);
			}else {
				if($('#'+menu_div_id+'> a').hasClass('popup_selected')){
					$current = $('#'+menu_div_id+'> a').filter('.popup_selected').next('hr').next('a');
				}else{
					$current = $('#'+menu_div_id+' > a').eq(0);
				}
        	}
    	}else if ( key == 38 ){// Up key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':first-child') ) {
				$current = $('#'+menu_div_id+' > a').last('a');
			}else {
				$current = $('#'+menu_div_id+' > a').filter('.popup_selected').prev('hr').prev('a');
			}
		}
		$('#'+menu_div_id+' > a').removeClass('popup_selected');
		$current.addClass('popup_selected');
	}else{
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"users/";
		if(val!=""){
			$('#load_find_act').show();
			$.post(strURL+"search_project_menu",{"page":page,"val":val}, function(data){
				  if(data) {
					$('#ajaxaftersrch').show();
					$('#ajaxbeforesrch').hide();
					$('#ajaxaftersrch').html(data);
					$('#load_find_act').hide();
				  }
			});
		}else{
			$('#ajaxaftersrch').hide();
			$('#ajaxbeforesrch').show();
			$('#load_find_act').hide();
			$('#search_project_menu_act').val('');
		}
	}
}
//Switch Project menu  --- Starts
function view_project_menu(){
	$('#ajaxViewProjects > a').removeClass('popup_selected');
	var checkload = $('#checkload').val();//alert(checkload);
	if($('#ajaxViewProjects').html() == ""){
		var caseMenuFilters=$('#caseMenuFilters').val();
		var usrUrl=document.getElementById('pageurl').value;
		if($('#ajaxViewProjects').is(':visible')){
			$('#loader_prmenu').hide();
		}else{
			$('#ajaxViewProjects').html('');
			$('#loader_prmenu').show();
			$.post(usrUrl+"users/project_menu",{"page":"dashboard","limit":6,"filter":caseMenuFilters}, function(data)	{
				if(data) {
					$('#ajaxViewProjects').html(data);
					$('#checkload').val('1');
					$('#loader_prmenu').hide();
				}
			});
		}
	}else{
		$('#search_project_menu_txt').val('');
		$('#ajaxViewProject').html('');
		$('#ajaxViewProject').hide();
		$('#ajaxViewProjects').show();
	}  
	$('#search_project_menu_txt').focus();
}
//Switch Project menu  --- Starts
function view_import_project_menu(){
	$('#ajaxViewProjects > a').removeClass('popup_selected');
	var checkload = $('#checkload').val();//alert(checkload);
	if($('#ajaxViewProjects').html() == ""){
		var caseMenuFilters=$('#caseMenuFilters').val();
		var usrUrl=document.getElementById('pageurl').value;
		if($('#ajaxViewProjects').is(':visible')){
			$('#loader_prmenu').hide();
			$('#projpopup').hide();
		}else{
			$('#ajaxViewProjects').html('');
			$('#loader_prmenu').show();
			$.post(usrUrl+"users/project_menu",{"page":"import","limit":6,"filter":caseMenuFilters}, function(data)	{
				if(data) {
					$('#ajaxViewProjects').html(data);
					$('#checkload').val('1');
					$('#loader_prmenu').hide();
					$('#projpopup').show();
				}
			});
		}
	}else{
		$('#search_project_menu_txt').val('');
		$('#ajaxViewProject').html('');
		$('#ajaxViewProject').hide();
		$('#ajaxViewProjects').show();
		$('#projpopup').toggle();
	}  
	$('#search_project_menu_txt').focus();
}
// 
function search_project_menu(page,val,e){
	var key = e.keyCode;
	if(key==13)return;
	var menu_div_id = 'ajaxViewProjects';	
	if($('#ajaxViewProject').is(":visible")){
		var menu_div_id = 'ajaxViewProject';	
		$('#ajaxViewProjects > a').removeClass('popup_selected');
	}
    if( e.keyCode==40 || e.keyCode==38 ){
		var selected = "$('#"+menu_div_id+" > a')";
		if ( key == 40 ){ // Down key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':last-child')) {
				$current = $('#ajaxViewProject > a').eq(0);
				//$current.addClass('popup_selected');	
			}else {
				if($('#'+menu_div_id+'> a').hasClass('popup_selected')){
					$current = $('#'+menu_div_id+'> a').filter('.popup_selected').next('hr').next('a');
				}else{
					$current = $('#'+menu_div_id+' > a').eq(0);
				}
        	}
    	}else if ( key == 38 ){// Up key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':first-child') ) {
				$current = $('#'+menu_div_id+' > a').last('a');
			}else {
				$current = $('#'+menu_div_id+' > a').filter('.popup_selected').prev('hr').prev('a');
			}
		}
		$('#'+menu_div_id+' > a').removeClass('popup_selected');
		$current.addClass('popup_selected');
	}else{
		var caseMenuFilters=$('#caseMenuFilters').val();//alert(caseMenuFilters);
		var strURL = $('#pageurl').val();
		strURL = strURL+"users/";
		if(val!=""){
			$('#load_find_dashboard').show();
			$.post(strURL+"search_project_menu",{"page":page,"val":val,"filter":caseMenuFilters,"page_name":pgname}, function(data){
				  if(data) { 
					$('#ajaxViewProject').show();
					$('#ajaxViewProjects').hide();
					$('#ajaxViewProject').html(data);
					$('#load_find_dashboard').hide();
				  }
			});
		}else{
			$('#ajaxViewProject').hide();
			$('#ajaxViewProjects').show();
			$('#load_find_dashboard').hide();
		}
	}
}
function updateAllProj(radio,projId,page,all,pname,srch) {
   // Code added for reset filteration during switch project---- Start
	var strurl = $('#pageurl').val();
	if($('#reset_btn').is(":visible") && !$('#customFIlterId').val()){
		if(confirm('Do you want to reset the filters already active ?')){
			if($('#search_txt_spn').text()){
				$('#clearCaseSearch').val(1);
			}
			$('#caseStatus').val("all"); // Filter by Status(legend)
			$('#priFil').val("all"); // Filter by Priority
			$('#caseTypes').val("all"); // Filter by case Types
			$('#caseMember').val("all");  // Filter by Member
			$('#caseAssignTo').val("all");  // Filter by AssignTo
			$('#casePage').val("1"); // Pagination
			$('#case_srch').val("");
			$('#caseDateFil').val("");
			$('#status_all').attr('checked','checked');
			$('#status_new').removeAttr('checked');
			$('#status_open').removeAttr('checked');
			$('#status_close').removeAttr('checked');
			$('#status_resolve').removeAttr('checked');
			$('#status_file').removeAttr('checked');
			$('#status_upd').removeAttr('checked');
			var totid = $('#totMemId').val();
			for(var i=1;i<=totid;i++) {
				var checkboxid = "mems_"+i;
				$('#'+checkboxid).removeAttr('checked');
			}
			
			var totasnid = $('#totAsnId').val();
			for(var i=1;i<=totasnid;i++) {
				var checkboxid = "Asns_"+i;
				$('#'+checkboxid).removeAttr('checked');
			}
			$('#priority_all').checked = true;
			$('#priority_High').removeAttr('checked');
			$('#priority_Medium').removeAttr('checked');
			$('#priority_Low').removeAttr('checked');
			
			var totid = $('#totType').val();
			for(var i=1;i<=totid;i++) {
				var checkboxid = "types_"+i;
				$('#'+checkboxid).removeAttr('checked');
			}
			$('#case_search').val(''); // Search text
			$('#closesrch').hide();
			$('#srch_load2').show();
			$('#case_search').val('');
			$('#caseDateFil').val('');
			$('#milestoneIds').val('');
			var url = $('#pageurl').val();
			$('#remember_filter').load(url+"easycases/remember_filters?reset=all");
		}	
	}
// Code added for reset filteration during switch project---- End
	if(all == '0'){ 
		$("#projUpdateTop").html(decodeURIComponent(pname));
		$('#projpopup').hide();
		var strurl = $('#pageurl').val();
		strURL = strurl+"easycases/";
		if(pname && (page!= "import")) {
			$('#pname_dashboard').html(decodeURIComponent(pname));
			$('.pname_dashboard').html(ucfirst(decodeURIComponent(pname)));
		}
		if(page == "dashboard"){
			var caseUrl = $("#caseUrl").val();
			if(caseUrl) {
				var pageUrl = document.getElementById('pageurl').value;
				window.location=pageUrl+"dashboard/?project="+projId;
				return false;
			}
			updateProj(radio,projId);
			if(document.getElementById('caseMenuFilters').value == "files"){
				ajaxFileView('case_files');
			}else {
				ajaxCaseView('case_project');
			}
		}else if(page == "milestone"){
			window.location=strurl+'milestones/manage/?pj='+projId;
		}else if(page == "import"){
			window.location=strurl+'projects/import_data/'+projId;
		} else {
			window.location=strURL+'dashboard/?project='+projId;
		}
	} else { 
		document.getElementById('projpopup').style.display = 'none';
		if(pname) {
          $('#pname_dashboard').html(decodeURIComponent(pname));
		  $('.pname_dashboard').html(ucfirst(decodeURIComponent(pname)));
		}
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";
		if(page == "dashboard")	{
			updateProj1('all');
			if(document.getElementById('caseMenuFilters').value == "files")	{
				ajaxFileView('case_files');
			} else {
				ajaxCaseView('case_project');
			}	
		} else {
			window.location=strURL+'dashboard/?project=all';
		}
	}
}
// Chart switch project Filteration 
function search_project_chart(page,val,e){ 
	var key = e.keyCode;
	if(key==13)return;
	var menu_div_id = 'ajaxbeforesrch';	
	if($('#ajaxaftersrch').is(":visible")){
		var menu_div_id = 'ajaxaftersrch';	
		$('#ajaxbeforesrch > a').removeClass('popup_selected');
	}
    if( e.keyCode==40 || e.keyCode==38 ){
		var selected = "$('#"+menu_div_id+" > a')";
		if ( key == 40 ){ // Down key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':last-child')) {
				$current = $('#ajaxViewProject > a').eq(0);
			}else {
				if($('#'+menu_div_id+'> a').hasClass('popup_selected')){
					$current = $('#'+menu_div_id+'> a').filter('.popup_selected').next('hr').next('a');
				}else{
					$current = $('#'+menu_div_id+' > a').eq(0);
				}
        	}
    	}else if ( key == 38 ){// Up key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':first-child') ) {
				$current = $('#'+menu_div_id+' > a').last('a');
			}else {
				$current = $('#'+menu_div_id+' > a').filter('.popup_selected').prev('hr').prev('a');
			}
		}
		$('#'+menu_div_id+' > a').removeClass('popup_selected');
		$current.addClass('popup_selected');
	}else{
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"users/";
		if(val!=""){
			$('#load_find').show();
			$.post(strURL+"search_project_menu",{"page":page,"val":val}, function(data){
			  if(data) { 
				$('#ajaxaftersrch').show();
				$('#ajaxbeforesrch').hide();
				$('#ajaxaftersrch').html(data);
				$('#load_find').hide();
			  }
			});
		}else{
			$('#ajaxaftersrch').hide();
			$('#ajaxbeforesrch').show();
			$('#load_find').hide();
		}
	}
}		

function search_project_archive(page,val,e){  
	var key = e.keyCode;
	if(key==13)return;
	var menu_div_id = 'ajaxbeforesrch';	
	if($('#ajaxaftersrch').is(":visible")){
		var menu_div_id = 'ajaxaftersrch';	
		$('#ajaxbeforesrch > a').removeClass('popup_selected');
	}
    if( e.keyCode==40 || e.keyCode==38 ){
		var selected = "$('#"+menu_div_id+" > a')";
		if ( key == 40 ){ // Down key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':last-child')) {
				$current = $('#ajaxViewProject > a').eq(0);
			}else {
				if($('#'+menu_div_id+'> a').hasClass('popup_selected')){
					$current = $('#'+menu_div_id+'> a').filter('.popup_selected').next('hr').next('a');
				}else{
					$current = $('#'+menu_div_id+' > a').eq(0);
				}
        	}
    	}else if ( key == 38 ){// Up key
			if ( ! $('#'+menu_div_id+' > a').length || $('#'+menu_div_id+'> a').filter('.popup_selected').is(':first-child') ) {
				$current = $('#'+menu_div_id+' > a').last('a');
			}else {
				$current = $('#'+menu_div_id+' > a').filter('.popup_selected').prev('hr').prev('a');
			}
		}
		$('#'+menu_div_id+' > a').removeClass('popup_selected');
		$current.addClass('popup_selected');
	}else{
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"users/";
		if(val!=""){
			$('#load_find_arc').show();
			$.post(strURL+"search_project_menu",{"page":page,"val":val}, function(data){
			  if(data) {
				$('#ajaxaftersrch').show();
				$('#ajaxbeforesrch').hide();
				$('#ajaxaftersrch').html(data);
				$('#load_find_arc').hide();
			  }
			});
		}else{
			$('#ajaxaftersrch').hide();
			$('#ajaxbeforesrch').show();
			$('#load_find_arc').hide();
		}
	}
}
function updateProj1(all) {
	document.getElementById('projFil').value='all';
	//document.getElementById('CS_project_id').value='all';
	$("#ajaxViewProjects").slideUp(300);
	//resetAllFilters('filters');
	//setAllpjFilter();
}
function setAllpjFilter(){
	var url = document.getElementById('pageurl').value;
	$('#remember_filter').load(url+"easycases/remember_filters?allpj=all");
}
function unsetAllpjFilter(){
	var url = document.getElementById('pageurl').value;
	$('#remember_filter').load(url+"easycases/remember_filters?allpj=0");
}
function display_setting(id) {
	document.getElementById(id).style.display = "block";
}
function remove_setting(id) {
	document.getElementById(id).style.display = "none";
}
function checkDate() {
	var d1 = document.getElementById('due_date').value;
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1
	var day = currentTime.getDate()
	var year = currentTime.getFullYear()
	var d2 = month+"/"+day+"/"+year;
	var x1 = d1.split("/");
	var x2 = d2.split("/");
	var date1 = new Date(x1[2], x1[0], x1[1]);
	var date2 = new Date(x2[2], x2[0], x2[1]);
	if(date2 > date1) {
		document.getElementById(id).value = "";
		alert("Invalid Due Date!");
	}
}
function validatemilestone(){
	var title = document.getElementById('title');
	var start_date = document.getElementById('start_date');
	var end_date = document.getElementById('end_date');
	var project_id = document.getElementById('project_id');
	var errMsg;
	var done = 1;
	
	if(project_id.value.trim() == ""){
		errMsg = "Project cannot be left blank!";
		project_id.focus();
		done = 0;

	}
	else if(title.value.trim() == "") {
		errMsg = "Title cannot be left blank!";
		title.focus();
		done = 0;
	}
	else if(start_date.value.trim() == ""){
		errMsg = "Start Date cannot be left blank!";
		start_date.focus();
		done = 0;

		}
	else if(end_date.value.trim() == ""){
		errMsg = "End Date cannot be left blank!";
		end_date.focus();
		done = 0;

		}
	else if(Date.parse(start_date.value) > Date.parse(end_date.value)) {
		errMsg = "Start Date cannot exceed End Date!";
		end_date.focus();
		done = 0;
	}
	if(done == 0) {
          //alert('no');
		var op = 100;
		document.getElementById('subprof1').style.display='block';
		document.getElementById('subprof2').style.display='none';
		showTopErrSucc('error',errMsg);
		return false;
	}
	else {
        /*  //alert('yes');
          var mdata = $('#addmilestone').serialize();//alert(mdata);
          var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"milestones/add?"+mdata;
          $.post(strURL,{}, function(data){
			  if(data) {
                    //alert(data);
				//$('#inner_milestone').html(data);
			
				//document.getElementById(menu).style.display ="block";
				//document.getElementById(loder).style.display ="none";
				//cover_close('cover','inner_milestone');
			  }
		});*/

		document.getElementById('subprof1').style.display='none';
		document.getElementById('subprof2').style.display='block';
		document.getElementById('vid').value='mileadding';
          
	}

}
function submitProfile() { 
	var name1 = $('#profile_name').val().trim();//alert(name1);
	var last_name = $('#profile_last_name').val().trim();//alert(last_name);
	var short_name = $('#short_name').val().trim();
	var errMsg;
	var done = 1;
	if(name1== "") {

		errMsg = "First Name cannot be left blank!";
		$('#profile_name').focus();
		done = 0;
	}
	else if(last_name == "") {

		errMsg = "Last Name cannot be left blank!";
		$('#profile_last_name').focus();
		done = 0;
	}
	else if(short_name == "") {

		errMsg = "Short Name cannot be left blank!";
		$('#short_name').focus();
		done = 0;
	}
	if(done == 0) {
		var op = 100;
		showTopErrSucc('error',errMsg);
		return false;
	}
	else {
		document.getElementById('subprof1').style.display='none';
		document.getElementById('subprof2').style.display='block';
	}
}
function onEnterPostCase(e)
{
	var unicode = e.charCode ? e.charCode : e.keyCode;
	if(unicode == 13) {
		submitAddNewCase('Post',0);
	}
}
function checkAllProj(){
	var projFil = document.getElementById('CS_project_id').value; // Project Uniq ID
	if(projFil == 'all'){
		//alert('Oops! you have not selected any project.'); 
		document.getElementById('projAllmsg').style.display = 'block';
		return false;
	}else{
		document.getElementById('projAllmsg').style.display = 'none';
		return true;
	}
}
function ajaxCaseSearch(e,strURL,ajx,id,type,page) 
{
	var unicode = e.charCode ? e.charCode : e.keyCode;
	if(unicode != 13) {
		var srch = document.getElementById(id).value;
		if(srch.trim() == "") {
			document.getElementById('ajax_search').style.display='block';
			return false;
		}
		else {
			document.getElementById('ajax_search').style.display='block';
		}
		document.getElementById('casePage').value = 1;
		document.getElementById('closesrch').style.display = 'none';
		document.getElementById('srch_load1').style.display='block';
		document.getElementById('srch_load2').style.display='none';
		var pjuniq=document.getElementById('projFil').value;
		var srch = document.getElementById(id).value;
		var srch = srch.trim();
		var url = document.getElementById('pageurl').value;
		url = url+"easycases/";
		
		
		var caseMenuFilters = $("#caseMenuFilters").val();
		var checktype = $("#checktype").val();
		
		$.post(url+"ajax_search",{srch:srch,type:type,page:page,pjuniq:pjuniq,'caseMenuFilters':caseMenuFilters,'checktype':checktype}, function(data){
		  if(data) {
			$('#'+ajx).html(data);
			document.getElementById('srch_load2').style.display='block';
			document.getElementById('srch_load1').style.display='none';
			document.getElementById('closesrch').style.display = 'none';
		  }
		});
	}
}
function caseDetailsSearch(pid,cid,page) {
	var strURL = document.getElementById('pageurl').value;
	if(page == "dashboard") {
		window.location = strURL+'dashboard/?case='+cid+"&project="+pid;
	}
	else {
		window.location = strURL+'dashboard/?case='+cid+"&project="+pid;
	}
}
function updateProj(id,uniq) {
	document.getElementById('projFil').value=uniq;
	document.getElementById('CS_project_id').value=uniq;
	$("#ajaxViewProjects").slideUp(300);
	//resetAllFilters('filters');
	//unsetAllpjFilter();
}
function updateProjSearch(id,uniq) {
	document.getElementById('projFil').value=uniq;
	document.getElementById('CS_project_id').value=uniq;
	$("#ajaxViewProjects").slideUp(300);
	resetAllFilters('filters');
}
function onKeyPress(e,id) {
	var unicode = e.charCode ? e.charCode : e.keyCode;
	if(unicode != 13) {
		var srch = document.getElementById(id).value;
		if(srch.trim() == "") {
			document.getElementById('ajax_search').style.display='none';
		}
		else {
			document.getElementById('ajax_search').style.display='block';
		}
	}
}
/************* case Search *************/
function validateSearch(id,page) {
	var url = document.getElementById('pageurl').value;
	document.getElementById('ajax_search').style.display='none';
	var srch = document.getElementById('case_search').value;
	if(srch.trim() != "") {
		if(page == "dashboard")
		{
			ajaxCaseView('case_project');
			$('#remember_filter').load(url+"easycases/remember_filters?search="+escape(srch));
		}
		else
		{
			window.location = url+'dashboard/?search='+srch;
		}
	}
	else {
		document.getElementById('case_search').focus();
	}
}
function validateSearchview(id,page,caseno,uniq_id) {
	var url_string=window.location.href;
	var url = document.getElementById('pageurl').value;
	document.getElementById('projFil').value=uniq_id;
	document.getElementById('ajax_search').style.display='none';
	
	$("#case_search").val("#"+caseno);
	var case_srch=document.getElementById('case_search').value;
	document.getElementById('case_srch').value="";
	document.getElementById('case_srch').value=caseno;
	if(caseno.trim() != "") {
		if(url_string.search("dashboard") != -1)
		{
			var caseMenuFilters = $("#caseMenuFilters").val();
			if(caseMenuFilters != 'milestone') {
				caseMenuFileter('cases','dashboard','cases','');
			}
			else {
				ajaxCaseView('case_project');
			}
			$('#remember_filter').load(url+"easycases/remember_filters?search="+escape(case_srch));
		}
		else
		{
			window.location = url+'dashboard/?filters=cases&search='+escape(case_srch)+'&case_no='+caseno;
		}
	}
	else {
		document.getElementById('case_search').focus();
	}
}
function goForSearch(e,id,page)
{
    var unicode = e.charCode ? e.charCode : e.keyCode;
	var done = 0;
	if(unicode == 13) {
	    done = 1;
	}
	if(done == 1) {
		validateSearch(id,page);
		return false;
	}
}
// Quick case
function opencase(type) {
	if(document.getElementById('new_case_more_div').innerHTML == "" || type == "changeproj") {
		document.getElementById('loadquick').style.display = 'block';
		
		sel_myproj = $("#CS_project_id").val();
		
		var url = document.getElementById('pageurl').value;
			
		casequick = url+"easycases/";
		$.post(casequick+"case_quick",{newcase:1,sel_myproj:sel_myproj},function(res)
		{
			$("#new_case_more_div").html(res);
			$("#new_case_more_div").slideDown(300);
			$("#new_case_more").hide();
			$("#new_case_hide").show();
			$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
			document.getElementById('loadquick').style.display = 'none';
			
			$("#usedstorage").val($("#storageusedqc").val());
			
		});
	}
	else {
		if(document.getElementById('new_case_more_div').style.display == 'block')
		{
			$("#new_case_more_div").slideUp();
			$("#new_case_more").show();
			$("#new_case_hide").hide();
			$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
		}
		else
		{
			$("#new_case_more_div").slideDown();
			$("#new_case_more").hide();
			$("#new_case_hide").show();
		}
	}
}
$("#close_popup").click(function(){
	$("#new_case_more_div").hide(200);
	$("#add_new_popup").animate({opacity: "hide"}, 200);	
	pageFadeOut('pagefade');
	return false;
});

function show_popoup() {
	if(!is_open_popup){
		return false;
	}
	document.getElementById('ajax_search').style.display='none';
	$("#add_new_popup").animate({opacity: "show"}, 100);
	$("#new_case_hide").hide();
	$("#new_case_more").show();
	$("#new_case_more_div").hide();
	$("#CS_title").focus();
	var txt = $("#hidtotproj").val();
	try {
		document.getElementById('chk_all').checked = false;
		for(var i=0;i < txt;i++){
			var chkids = "chk_"+i;
				document.getElementById(chkids).checked = false;	
		}
	}
	catch(e) {
	}
	//document.getElementById('CS_title').value='';
	pageFadeIn('pagefade');
}
function hide_popoup(resetFlds) {
	
	if(resetFlds ==1 ) {
		/*  Code for reseting the NEW TASK form after clicking the cancel button starts here */
		
		$("#CS_title").val('');
		$("#CS_title").val('Add a task here and hit enter...');
		$("#opt3").children("a").children("span").html('No Due Date');
		$("#opt5").children("a").children("span").html('me');
		var http_images = $("#hid_http_images").val();
		$("#opt1").children("a").children("span").html('<img class="flag" src="'+http_images+'images/types/dev.png" alt="type" style="padding-top:3px;"/>&nbsp;Development');
		$("#opt2").children("a").children("span").html('<font style="color:#28AF51;font-size:12px;">&nbsp;MEDIUM</font>');
		$("#divNewCase").html('<textarea name="data[Easycase][message]" id="CS_message" onfocus="openEditor()" rows="2" class="" style="width:100%;color:#666666; max-width:575px;" placeholder="Enter Description..."></textarea>');
		$("#up_files").html('');
		
		/*  Code for reseting the NEW TASK form after clicking the cancel button ends here  */
	}
	
	$("#new_case_more_div").hide(200);
	$("#add_new_popup").animate({opacity: "hide"}, 200);
	$("#CS_title").blur();
	pageFadeOut('pagefade');
	$(".newcase_bg").removeClass("active_case");
}

function closecase() {
	$("#new_case_more_div").slideUp(200);
	$("#new_case_more").show();
	$("#new_case_hide").hide();
}
$(".bar_con_head"). click(function(){
	$(".bar_con").toggle();
});
$(".pie_con_head"). click(function(){
	$(".pie_con").toggle();
});
function focus_txt() {
	$("#CS_title").css({color:"#000"});
	if($("#CS_title").val()=="Add a task here and hit enter...") {
		$("#CS_title").val("");
	}
}
function blur_txt(){
	$("#CS_title").css({color:"#666666"});
	if($("#CS_title").val()=="") {
		//$("#CS_title").val("Add a task here and hit enter...");
	}
	if($("#CS_title").val()!="Add a task here and hit enter...") {
		$("#CS_title").css({color:"#000000"});
	}
}
function focus_txt_area(){
	$("#CS_message").css({color:"#000"});
	if($("#CS_message").html("Enter Description")) {
		$("#CS_message").html("");	
		$("#CS_message").animate({height:"60px"},300);
	}
}

$("tr.change_color:odd").hover(function(){
	$(this).children().css({background:'#fff',cursor:"pointer"});
},function(){
	$(this).children().css('background','#D8EAF3');
	});

$("tr.change_color:even").hover(function(){
	$(this).children().css({background:'#fff',cursor:"pointer"});
},function(){
	$(this).children().css('background','#F2F2F2');
	});

$("#attach").focus(function(){
	$("#fake_txt").val("");
});
$("#attach").blur(function(){
	if($("#attach").val()=="")
	$("#fake_txt").val("Attach File");
});
 
function assignval(){ 
	$("#fake_txt").val($("#attach").val());
}
$("#srch_close").click(function(){
	$(this).next(".srchbox").val("");
});
function openEditor() {
		
	//$("#CS_message").val("");
	
	$("#divNewCase").hide();
	$("#divNewCaseLoader").show();
	
	(function($) {
	var pageurl = document.getElementById('pageurl').value;
	$('#CS_message').tinymce({
			// Location of TinyMCE script
			script_url : pageurl+'js/tinymce/tiny_mce.js',
			theme : "advanced",
			plugins : "paste",
			theme_advanced_buttons1 : "bold,italic,strikethrough,underline,|,numlist,bullist,|,indent,outdent",
			theme_advanced_resizing : false,
			theme_advanced_statusbar_location : "",
			paste_text_sticky : true,
			gecko_spellcheck : true,
			paste_text_sticky_default : true,
			forced_root_block : false,
			width : "600px",
			height : "200px",
			oninit : function() {
						$("#divNewCaseLoader").hide();
						$("#divNewCase").show();
						$('#CS_message').tinymce().focus();
						$("#tmpl_open").show();
					 }
		});
		
	})($);	
}
$(".more_link a").click(function(){
	if($(this).html("More")){							   
		$("#more").toggle(500);
		$(this).html("");
	}

});
/*setting popup*/
function open_pop(obj) {
	if($(obj).parent("div").next(".popup_option").is(":visible")){ 
		$(".popup_option").hide();
		$(obj).parent("div").next(".popup_option").hide();
	}else{ 
		$(".popup_option").hide();
		$(obj).parent("div").next(".popup_option").show();
	}
}
function open_popAsn(id) {
	hideActionLnkCss();
	
	if($("#"+id).is(":visible")){ 
		$(".popup_option").hide();
		$("#"+id).hide();
	}else{
		$(".popup_option").hide();
		$("#"+id).show();
	}
}

$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if (! $clicked.parents().hasClass("popup_link_case_proj_parent")){
		$(".popup_option").hide();
		hideActionLnkCss();
	}
});

$(document).bind('click', function(e) {
	var $clicked = $(e.target);
	if (!$clicked.hasClass("popup_link_tmpl") || $clicked.attr("class")=="")
		$(".openpopup_tmpl").hide();
});
$(".popup_link_status a").click(function(){
	if($(this).parent("div").next(".popup").is(":visible")){
		$(".popup_option").hide();
		$(".popup").hide();
		$(this).parent("div").next(".popup").hide();
	}
	else{
		$(".popup_option").hide();
		$(".popup").hide();
		$(this).parent("div").next(".popup").show();
	}
});
$("#csAssignAjx-root").click(function(e){
    e.stopPropagation();
});
$(".popup_link_status_all a").click(function(){
	if(document.getElementById('projFil').value == 'all'){
		return false;
	}else {

		if($(this).parent("div").next(".popup").is(":visible")){
			$(".popup_option").hide();
			$(".popup").hide();
			$(this).parent("div").next(".popup").hide();
		}
		else{
			$(".popup_option").hide();
			$(".popup").hide();
			$(this).parent("div").next(".popup").show();
		}
	}
});

$(document).click(function(){
    $(".popup").hide();
});
$(".popup").click(function(e){
    e.stopPropagation();
});
$(".popup_link").click(function(e){
	$(".popup_link_status a").parent("div").next(".popup").hide();
    e.stopPropagation();
});
/*End popup*/
/*Left Panel*/
$("#hide_lpanel").click(function(){	
	$("#left_panel_td").animate({marginLeft:"-240px"},"slow");
	$("#show_lpanel").css({display:"block"},"slow");
	$("#left_panel").animate({marginLeft:"-240px",opacity:"hide"},"slow");
	$("#main_con").animate({marginLeft:"0px"});
	$("#rht_td").css({paddingLeft:"0px"});
	$(".fixed-wraper").css({paddingLeft:"20px"});
	$("#footer_wht").css({background:"#fff",paddingLeft:"0px"});
	$("#hide_lpanel").css({display:"none"});
	$("#show_lpanel").stop().animate({opacity:"show"});
	$("#csTotalSize_div").css({width: "100%", marginLeft:"0px"});
	$(".lt_content").css({width: "0%"});
	//$("#left_panel td").css({background:"#fff"});
	//$("body").css({background:"#fff"});
});
$("#show_lpanel").click(function(){	
	$("#left_panel").animate({marginLeft:"0px",opacity:"show"},"slow",function(){//$(".i_ln").css({background:"#B1C5DB"});
																								  
																								  });
	//$("body").css({background:"url('img/html5/bgs/bg_aside_main_old.png') repeat-y 0px 0"});
	$("#left_panel_td").animate({marginLeft:"0px"});
	//$("#main_con").animate({marginLeft:"240px"},"slow",function(){});
	$("#rht_td").animate({paddingLeft:"240px"},"slow");
	$(".fixed-wraper").css({paddingLeft:"261px"});
	$("#footer_wht").css({background:"none",paddingLeft:"240px"});
	$("#show_lpanel").css({display:"none"});
	$("#hide_lpanel").stop().animate({opacity:"show"},3000);
	$("#csTotalSize_div").css({width: "82%", marginLeft:"240px"});
	$(".lt_content").css({width: "17.9%"});
	
});

/*End Left Panel*/
function showStatus()
{
	document.getElementById('hidpriquick').value=2;
}
function removeFile(id,div,storage)
{
	var x = document.getElementById(id).value;
	//$('#'+div).fadeOut(200);
	document.getElementById(id).value='';
	
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"easycases/";
	
	if(storage) {
		var usedstorage = $("#usedstorage").val();
		var newstorage = usedstorage-storage;
		$("#usedstorage").val(newstorage);
	}	
	$.post(strURL+"fileremove",{"filename":x},function(data) {
		if(data) {
		}
	});
	$('#'+div).parent().parent().remove();
	
}
function checkedAllRes() {	
	//var txt = $("#hidtotproj").val();
	if($('#chked_all').is(":checked")) {
		$('.viewmemdtls_cls').show();
		$('.notify_cls').attr("checked","checked");
	}else {
		$('.notify_cls').removeAttr("checked");
	}
	/*for(var i=0;i < txt;i++) {
		var chkids = "chk_"+i;
		if(document.getElementById('chk_all').checked == true) {
			document.getElementById(chkids).checked = true;
		}else {
			document.getElementById(chkids).checked = false;	
		}
	}*/
}
function checkedAllResReply(CS_id) {
	var allchk = CS_id+'chkAllRep';
	var allid = 'hidtotresreply'+CS_id;
	var res = document.getElementById(allid).value;
	var chkid = CS_id+"chk_";
	if($('#'+allchk).is(":checked")){
		$('input[id^="'+chkid+'"]').each(function(i){
			$(this).attr("checked",'checked');
		});
	}else{
		$('input[id^="'+chkid+'"]').each(function(i){
			$(this).removeAttr("checked");
		});
	}
	/*for(var i=0;i < res;i++) {
		var chkids = CS_id+"chk_"+i;
		if(document.getElementById(allchk).checked == true) {
			document.getElementById(chkids).checked = true;
		}
		else {
			document.getElementById(chkids).checked = false;	
		}
	}*/
}
function removeAll() {
	if(!$('input.notify_cls[type=checkbox]:not(:checked)').length){
		$('#chked_all').attr("checked","checked");
	}else{
		$('#chked_all').removeAttr("checked");
	}
	//document.getElementById('chk_all').checked = false;
}
function removeAllReply(CS_id) {
	var allchk = CS_id+'chkAllRep';
	document.getElementById(allchk).checked = false;
}
function showHideMemDtls(cls) {
	if($('.'+cls).css('display') == 'none') {
		$('.'+cls).slideDown(200);
	}
	else {
		$('.'+cls).slideUp(200);
	}
	$('#defaultmem').slideUp();
}
function validateFileExt(fname) {
	var ext = fname.substring(fname.lastIndexOf('.') + 1);
	if(ext == "txt" || ext == "doc" || ext == "docx" || ext == "xls" || ext == "xlsx" || ext == "pdf" || ext == "odt" || ext == "ppt" || ext == "jpeg" || ext == "tif" || ext == "gif" || ext == "psd" || ext == "jpg" || ext == "png") {
		return true;
	}
	else {
		alert("Invalid input file format! Should be txt, doc, docx, xls, xlsx, pdf, odt, ppt, jpeg, tif, gif, psd, jpg or png");
		return false;
	}
}
/***************** Milestione ****************/
function searchMileStoneCase()
{
	var project_id = '';
	var milestone_id = '';
	var title = document.getElementById('title').value;
	//if(title.trim()) {
		try{
			var project_id = document.getElementById('project_id').value;
			var milestone_id = document.getElementById('milestone_id').value;
		}
		catch(e) {
		}
		if(project_id && milestone_id) {
			var strURL1 = document.getElementById('pageurl').value;
			var strURL1 = strURL1+'milestones/add_case';
			$("#popupload").show();
			var countmanage = document.getElementById('countmanage').value;
			$.post(strURL1,{"mstid":milestone_id,"projid":project_id,"countmanage":countmanage,"title":title},function(data) {
			 if(data) {
					$('#loadcontent').html(data);
					$("#popupload").hide();
					$("#popupContactClose, .c_btn").click(function() {
						disablePopup();
					});
					document.getElementById('confirmMilestone').disabled = true;
				 }
			});
		}
	//}
}

function loaddiv(id){
		document.getElementById('loader_id1').style.display ="block";
		var id=document.getElementById(id).value;
		var strurl=document.getElementById('pageurl').value;
		strurl = strurl+"case_settings/getdetailscase";
		$.post(strurl,{"pjid":id},function(data) { 
			if(data){ 
			$("#allcasesetting").html(data);
			$("#but").show();
			document.getElementById('loader_id1').style.display ="none";
			}
		});
	}

	function postcasedata(){
		document.getElementById('savspn').style.display ="none";
		document.getElementById('loader_id').style.display ="block";
		var project_id=document.getElementById("project_id").value;
		var project_uniqid=document.getElementById("project_uniqid").value;
		var type_id=document.getElementById("type_id").value;
		var assign_to=document.getElementById("assign_to").value;
		var due_date=document.getElementById("due_date").value;
		var user_id=document.getElementById("case").value;
		var priority=document.getElementById("priority").value;
		var cnt=document.getElementById("chkcnt").value;
			if(document.getElementById("id").value!=""){
				var ids=document.getElementById("id").value;
			}else{
				var ids="";
			}
		var email = new Array();
			for(var i=0;i<cnt;i++){
				if(document.getElementById("chk"+i).checked==true){
					email.push(document.getElementById("chk"+i).value);
				}
			}
		if(email.length=="0"){
			email.push("0");
		}
		var strurl=document.getElementById('pageurl').value;
		strurl = strurl+"case_settings/postdetailscase";
		$.post(strurl,{"pjid":project_id,"priority":priority,"pjuniqid":project_uniqid,"typid":type_id,"asgn":assign_to,"duedt":due_date,"case":user_id,"email":email,"id":ids},function(data) { 
			if(data){ alert(data); 
			document.getElementById('loader_id').style.display ="none";
			document.getElementById('savspn').style.display ="block";
			}
		});
	}

/*  function for action in case details page when a case is recently viewed or searched*/
function srccase(id,typ){
		var url = document.getElementById('pageurl').value;
		if(typ=="start")
		{
			if(confirm("Are you sure to start the task# "+id+"?"))
			{
				
				
				$("#startspan1").hide();
				document.getElementById('spnstatus').innerHTML="<font color=#55A0C7 >Started</font>";
				var url = url+"easycases/startsrccase";
				$.post(url,{"caseauto":id,"startid":4,"resolveid":'',"closeid":''}, function(data){
			  		if(data)
					{
						
			  		}
				});
				showTopErrSucc('success','Task has been started.');
			}
		}
		else if(typ=="resolve")
		{
			if(confirm("Are you sure to Resolve the task# "+id+"?"))
			{	
				
				$("#resolvespan1").hide();
				$("#startspan1").hide();
				document.getElementById('spnstatus').innerHTML="<font color=#EF6807 >Resolved</font>";
				var url = url+"easycases/startsrccase";
				$.post(url,{"caseauto":id,"startid":'',"resolveid":'5',"closeid":''}, function(data){
			  		if(data)
					{
						
						
			  		}
				});
				showTopErrSucc('success','Task has been resolved.');
			}
		
		}
		else if(typ=="close"){
			if(confirm("Are you sure to Close the task# "+id+"?"))
			{
				
				$("#closespan1").hide();
				$("#actspan").hide();
				document.getElementById('spnstatus').innerHTML="<font color=#048404 >Closed</font>";
				var url = url+"easycases/startsrccase";
				$.post(url,{"caseauto":id,"startid":'',"resolveid":'',"closeid":'3'}, function(data){
			  		if(data)
					{
						
			  		}
				});
				showTopErrSucc('success','Task has been closed.');
			}


		}
	}
/*Functions for archive,restore,remove of tasks and files in archive section*/
function caseall(){
		var count=document.getElementById("all").value;
		document.getElementById("allcase").checked="true";
		for(var i=1;i<=count;i++){
			document.getElementById("case"+i).checked="true";
			var listing = "cslisting"+i;
			document.getElementById(listing).style.background = "#FEFEEE";

		}
	}
	function fileall(){
		var count=document.getElementById("all").value;
		document.getElementById("allfile").checked="true";
		for(var i=1;i<=count;i++){
			document.getElementById("file"+i).checked="true";
			var listing = "fllisting"+i;
			document.getElementById(listing).style.background = "#FEFEEE";

		}


	}
	function casenone(){
		var count=document.getElementById("all").value;
		document.getElementById("allcase").checked=false;
		for(var i=1;i<=count;i++)
		{
			document.getElementById("case"+i).checked=false;
			var listing = "cslisting"+i;
			document.getElementById(listing).style.background = "";
		}
	}
	function filenone(){
		var count=document.getElementById("all").value;
		document.getElementById("allfile").checked=false;
		for(var i=1;i<=count;i++)
		{
			document.getElementById("file"+i).checked=false;
			var listing = "fllisting"+i;
			document.getElementById(listing).style.background = "";

		}
	}
function milestonerestoreall(){
          open_pop(this);
		var pjid=document.getElementById('pjid').value;
		var count=document.getElementById("all").value;
		var val = new Array();
		var alt = new Array();
		for(var i=1;i<=count;i++)
		{
			if(document.getElementById("case"+i).checked==true)
			{
				val.push(document.getElementById("case"+i).value);
				alt.push(document.getElementById("csn"+i).value);
			}
			
		}//alert(val);
		if(val.length!='0')
		{
			if(confirm("Are you sure you want to restore milestone?"))
			{
				document.getElementById('caseLoader').style.display="block";
				var pageurl = document.getElementById('pageurl').value;
				var url = pageurl+"archives/milestone_move_list";
				$.post(url,{"val":val}, function(data){
				  if(data)
				  {
					showTopErrSucc('success','Milestone(s) have been restored.');
					
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"archives/milestone_list";
					
					$.post(url,{"pjid":pjid}, function(data){
				  		if(data) {
							$('#milestonelist').html(data);
							document.getElementById('caseLoader').style.display="none";
				  		}
					});
				  }
			  });	
		   }
		}
		else{
			alert("Please select at least one milestone to restore.");

		}
     }
	function restoreall(){
		open_pop(this);
		var pjid=document.getElementById('pjid').value;
		var count=document.getElementById("all").value;
		var val = new Array();
		var alt = new Array();
		for(var i=1;i<=count;i++)
		{
			if(document.getElementById("case"+i).checked==true)
			{
				val.push(document.getElementById("case"+i).value);
				alt.push(document.getElementById("csn"+i).value);
			}
			
		}
		if(val.length!='0')
		{
			if(confirm("Are you sure you want to restore task# "+alt+"?"))
			{
				document.getElementById('caseLoader').style.display="block";
				var pageurl = document.getElementById('pageurl').value;
				var url = pageurl+"archives/move_list";
				$.post(url,{"val":val}, function(data){
				  if(data)
				  {
					showTopErrSucc('success','Task(s) have been restored.');
					
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"archives/case_list";
					
					$.post(url,{"pjid":pjid}, function(data){
				  		if(data) {
							$('#caselistall').html(data);
							document.getElementById('caseLoader').style.display="none";
				  		}
					});
				  }
			  });	
		   }
		}
		else{
			alert("No task selected!");

		}
	}
function removeallmilestone(){
          open_pop(this);
		var pjid=document.getElementById('pjid').value;
		var count=document.getElementById("all").value;
		var val = new Array();
		var alt = new Array();
		for(var i=1;i<=count;i++)
		{
			if(document.getElementById("case"+i).checked==true)
			{
				val.push(document.getElementById("case"+i).value);
				alt.push(document.getElementById("csn"+i).value);
			}
			
		}//alert(val);
		if(val.length!='0')
		{
				if(confirm("Are you sure you want to remove the milestone?"))
				{
					document.getElementById('caseLoader').style.display="block";
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"archives/milestone_remove";
					$.post(url,{"val":val}, function(data){
					  if(data)
					{
						showTopErrSucc('success','Milestone have been removed.');
						var pageurl = document.getElementById('pageurl').value;
						var url = pageurl+"archives/milestone_list";
						$.post(url,{"pjid":pjid}, function(data){
					  		if(data) {
								document.getElementById('caseLoader').style.display="none";
								$('#milestonelist').html(data);
					  		}
						});	
					  }
				});
			}
		}
		else{
			alert("Please select at least one milestone to remove.");
		}
     }
	function removeall(){
		open_pop(this);
		var pjid=document.getElementById('pjid').value;
		var count=document.getElementById("all").value;
		var val = new Array();
		var alt = new Array();
		for(var i=1;i<=count;i++)
		{
			if(document.getElementById("case"+i).checked==true)
			{
				val.push(document.getElementById("case"+i).value);
				alt.push(document.getElementById("csn"+i).value);
			}
			
		}
		if(val.length!='0')
		{
				if(confirm("Are you sure you want to remove task# "+alt +"?"))
				{
					document.getElementById('caseLoader').style.display="block";
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"archives/case_remove";
					$.post(url,{"val":val}, function(data){
					  if(data)
					{
						showTopErrSucc('success','Task(s) have been removed.');
						var pageurl = document.getElementById('pageurl').value;
						var url = pageurl+"archives/case_list";
						$.post(url,{"pjid":pjid}, function(data){
					  		if(data) {
								document.getElementById('caseLoader').style.display="none";
								$('#caselistall').html(data);
					  		}
						});	
					  }
				});
			}
		}
		else{
			alert("No task selected!");
		}
	}
	function restorefile(){
		open_pop(this);
		var pjid=document.getElementById('pjid').value;
		var count=document.getElementById("all").value;
		var val = new Array();
		for(var i=1;i<=count;i++)
		{
			if(document.getElementById("file"+i).checked==true)
			{
				val.push(document.getElementById("file"+i).value);
			}
			
		}
		var pageurl = document.getElementById('pageurl').value;
		var url = pageurl+"archives/move_file";
		if(val.length!='0')
		{
			if(confirm("Are you sure you want to restore?"))
			{
				document.getElementById('caseLoader').style.display="block";
				$.post(url,{"val":val}, function(data){
				  if(data)
				  {
					showTopErrSucc('success','File has been restored.');
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"archives/file_list";
					$.post(url,{"pjid":pjid}, function(data){
				  		if(data) {
							document.getElementById('caseLoader').style.display="none";
							$('#filelistall').html(data);
				  		}
					});	
				  }
			   });
			}	

		}
		else{
			alert("No file selected!");
		}
	}
	function removefile(){
		open_pop(this);
		var pjid=document.getElementById('pjid').value;
		var count=document.getElementById("all").value;
		var val = new Array();
		for(var i=1;i<=count;i++)
		{
			if(document.getElementById("file"+i).checked==true)
			{
				val.push(document.getElementById("file"+i).value);
			}
			
		}
		var pageurl = document.getElementById('pageurl').value;
		var url = pageurl+"archives/file_remove";
		if(val.length!='0')
		{
			if(confirm("Are you sure you want to remove?"))
			{
				document.getElementById('caseLoader').style.display="block";
				$.post(url,{"val":val}, function(data){
				  if(data)
				  {
					showTopErrSucc('success','File has been removed.');
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"archives/file_list";
					$.post(url,{"pjid":pjid}, function(data){
				  		if(data) {
							document.getElementById('caseLoader').style.display="none";
							$('#filelistall').html(data);
				  		}
					});	
				  }
			   });
			}	
		}
		else{
			alert("No file selected!");
		}
	}
		function caseallcheck(){
		var count=document.getElementById("all").value;
		if(document.getElementById("allcase").checked==true){		
			for(var i=1;i<=count;i++){
				document.getElementById("case"+i).checked=true;
				
				var listing = "cslisting"+i;
				document.getElementById(listing).style.background = "#FEFEEE";
				
			}
		}else{

			for(var i=1;i<=count;i++){
				document.getElementById("case"+i).checked=false;
				var listing = "cslisting"+i;
				document.getElementById(listing).style.background = "";
			}
		}
	}
	function fileallcheck(){
		var count=document.getElementById("all").value;
		if(document.getElementById("allfile").checked==true){		
			for(var i=1;i<=count;i++){
				document.getElementById("file"+i).checked=true;
				var listing = "fllisting"+i;
				document.getElementById(listing).style.background = "#FEFEEE";

			}
		}else{

			for(var i=1;i<=count;i++){
				document.getElementById("file"+i).checked=false;
				var listing = "fllisting"+i;
				document.getElementById(listing).style.background = "";

			}
		}
	}
	function onecheckcase(id,j){
		var listing = "cslisting"+j;
		if(document.getElementById(id).checked==true) {
			document.getElementById(listing).style.background = "#FEFEEE";
		}
		else {
			document.getElementById(listing).style.background = "";	
		}
		
		var count = document.getElementById("all").value;
		if(document.getElementById(id).checked==true){
			var cnt=0;
			for(var i=1;i<=count;i++){
				if(document.getElementById("case"+i).checked==true)
				{
					cnt++;
				}
			}
			if(cnt==count){
				document.getElementById('allcase').checked=true;
			}
			else{
				document.getElementById('allcase').checked=false;
			}
		}
		else{
			var cnt=0;
			for(var i=1;i<=count;i++){
				if(document.getElementById("case"+i).checked==true)
				{
					cnt++;
				}
			}
			if(cnt==count){
				document.getElementById('allcase').checked=true;
			}
			else{
				document.getElementById('allcase').checked=false;
			}
		}
	}
	function onecheckfile(id,j){
		var listing = "fllisting"+j;
		if(document.getElementById(id).checked==true) {
			document.getElementById(listing).style.background = "#FEFEEE";
		}
		else {
			document.getElementById(listing).style.background = "";	
		}
		var count=document.getElementById("all").value;
		if(document.getElementById(id).checked==true){
			var cnt=0;
			for(var i=1;i<=count;i++){
				if(document.getElementById("file"+i).checked==true)
				{
					cnt++;
				}
				

			}
			if(cnt==count){
				document.getElementById('allfile').checked=true;
			}
			else{
				document.getElementById('allfile').checked=false;
			}


		}
		else{
			var cnt=0;
			for(var i=1;i<=count;i++){
				if(document.getElementById("file"+i).checked==true)
				{
					cnt++;
				}
				

			}
			if(cnt==count){
				document.getElementById('allfile').checked=true;
			}
			else{
				document.getElementById('allfile').checked=false;
			}
		}
	}
// ######### jyoti Archive section start #########
 function chgmile(pjid,pname){ //alert('hi');
          document.getElementById('popup_option').style.display="none";     
		document.getElementById('caseLoader').style.display="block";
          $('#pname_mile').html(decodeURIComponent(pname));
          
          var pageurl = document.getElementById('pageurl').value;
		var url = pageurl+"archives/milestone_list";
		$.post(url,{"pjid":pjid,"casePage":1}, function(data){ 
	  		if(data) {
                    //alert(data);
				document.getElementById('caseLoader').style.display="none";
				$('#milestonelist').html(data);
	  		}
		});
	}
    function CaseActivity(pjid,pname){ 
	$('#ajaxbeforesrch > a').removeClass('popup_selected');
	$('#ajaxaftersrch > a').removeClass('popup_selected');
	$('#ajaxaftersrch').html('');
	$('#ajaxaftersrch').hide();
	$('#ajaxbeforesrch').show();
	$('#search_project_menu_act').val('');
	document.getElementById('popup_option').style.display="none"; 
	$('#pname_activity').html(decodeURIComponent(pname));
	$('#prjid').val(pjid);
	
	$("#activities").html('');
	$("#moreloader").show();
	loadActivity('');
    loadOverdue('my');
	loadUpcoming('my');
     } 
	function chgcase(pjid,pname){ 
		$('#project_menu_arch').val('');
		$('#ajaxaftersrch').html('');
		$('#ajaxaftersrch').hide();
		$('#ajaxbeforesrch').show();
          document.getElementById('popup_option').style.display="none";     
		document.getElementById('caseLoader').style.display="block";
          $('#pname_case').html(decodeURIComponent(pname));
          
          var pageurl = document.getElementById('pageurl').value;
		var url = pageurl+"archives/case_list";
		$.post(url,{"pjid":pjid,"casePage":1}, function(data){ 
	  		if(data) {
				document.getElementById('caseLoader').style.display="none";
				$('#caselistall').html(data);
	  		}
		});
	}
	function chgfile(pjid,pname){ //alert(pname);
          document.getElementById('popup_option').style.display="none";     
		document.getElementById('caseLoader').style.display="block";
          $('#pname_file').html(decodeURIComponent(pname));
		var pageurl = document.getElementById('pageurl').value;
		var url = pageurl+"archives/file_list";
					$.post(url,{"pjid":pjid,"casePage":1}, function(data){
				  		if(data) {
							document.getElementById('caseLoader').style.display="none";
							$('#filelistall').html(data);
				  		}
					});


	}
// ######### jyoti Archive section end #########
	function casePagingCase(page) {
		document.getElementById('casePage').value = page;
		var pjid = document.getElementById('pjid').value;
		document.getElementById('caseLoader').style.display="block";
		var pageurl = document.getElementById('pageurl').value;
		var casePage = document.getElementById('casePage').value;
		var url = pageurl+"archives/case_list";

		$.post(url,{"pjid":pjid,"casePage":casePage}, function(data){ 
	  		if(data) {
				document.getElementById('caseLoader').style.display="none";
				$('#caselistall').html(data);
	  		}
		});
	}
	function ajaxFileArchive(page){
		document.getElementById('casePage').value = page;
		var pjid = document.getElementById('pjid').value;
		document.getElementById('caseLoader').style.display="block";
		var pageurl = document.getElementById('pageurl').value;
		var casePage = document.getElementById('casePage').value;
					var url = pageurl+"archives/file_list";
					$.post(url,{"pjid":pjid,"casePage":casePage}, function(data){
				  		if(data) {
							document.getElementById('caseLoader').style.display="none";
							$('#filelistall').html(data);
				  		}
					});
	}
    function filterProjTyp(id) {
		var pageurl = document.getElementById('pageurl').value;
		if(id == '1'){
			var url = pageurl+"projects/manage/?disProj="+id;
			window.location=url;
		}else if(id == '2'){
			var url = pageurl+"projects/manage/";
			window.location=url;
		}else if(id == '3'){
			var url = pageurl+"projects/manage/";
			window.location=url;
		}else if(id == '4'){

			var url = pageurl+"projects/gridview/";
			window.location=url;
		}
	}
	function validatechart(type){
	document.getElementById('subprof1').style.display='none';
	document.getElementById('subprof2').style.display='block';
	var start_date = document.getElementById('start_date');
	var end_date = document.getElementById('end_date');
	var errMsg;
	var done = 1;
	if(start_date.value.trim() == ""){
		errMsg = "From Date cannot be left blank!";
		start_date.focus();
		done = 0;

		}
	else if(end_date.value.trim() == ""){
		errMsg = "To Date cannot be left blank!";
		end_date.focus();
		done = 0;

		}
	else if(Date.parse(start_date.value) > Date.parse(end_date.value)) {
		errMsg = "From Date cannot exceed To Date!";
		end_date.focus();
		done = 0;
	}
	if(done == 0) {
		var op = 100;
		showTopErrSucc('error',errMsg);
		document.getElementById('subprof1').style.display='block';
		document.getElementById('subprof2').style.display='none';
		return false;
		
	}
	else {
		
		var pjid = $('#pjid').val();
		var sdate = $('#start_date').val();
		var edate = $('#end_date').val();
		var url = $('#pageurl').val();
		if(type == 'bug'){
			$('#piechart_container').load(url+'reports/bug_pichart',{'type_id':1,'pjid':pjid,'sdate':sdate,'edate':edate,'dtsearch':1});
			$('#statistic_container').load(url+'reports/bug_statistics',{'type_id':1,'pjid':pjid,'sdate':sdate,'edate':edate});
			$('#linechart_container').load(url+'reports/bug_linechart',{'type_id':1,'pjid':pjid,'sdate':sdate,'edate':edate});
			$('#glide_container').load(url+'reports/bug_glide',{'type_id':1,'pjid':pjid,'sdate':sdate,'edate':edate},function(res){
				document.getElementById('subprof1').style.display='block';
				document.getElementById('subprof2').style.display='none';
			});
		}else if(type == "hours"){
			$('#piechart_container').load(url+'reports/hours_piechart',{'pjid':pjid,'sdate':sdate,'edate':edate,'dtsearch':1});
			$('#grid_container').load(url+'reports/hours_gridview',{'pjid':pjid,'sdate':sdate,'edate':edate},function(res){
				if($('#thrs').length > 0){
					$('#hrspent').html("<b>"+$('#thrs').val()+"</b> hours");
				}else{
					$('#hrspent').html("");
				}
				document.getElementById('subprof1').style.display='block';
				document.getElementById('subprof2').style.display='none';
			});
		}else if(type == "task"){
			$('#piechart_container').load(url+'reports/tasks_pichart',{'pjid':pjid,'sdate':sdate,'edate':edate});
			$('#statistic_container').load(url+'reports/tasks_statistics',{'pjid':pjid,'sdate':sdate,'edate':edate});
			$('#container').load(url+'reports/tasks_trend',{'pjid':pjid,'sdate':sdate,'edate':edate},function(res){
				document.getElementById('subprof1').style.display='block';
				document.getElementById('subprof2').style.display='none';
			});

		}
		
	}

}
function ReportMenu(uniq){
	var url = $('#pageurl').val();
	if(!uniq) {
		var uniq = $('#projFil').val();
		if(uniq == 'all') {
			var uniq = $('#last_project_uniqid').val();
		}
		window.location = url+'task-report/';
	}else{
		$('#main_con').load(url+'reports/chart/ajax/'+uniq,function(res){
		    document.getElementById('popup_option').style.display="none";
			$('#prjname').html($('#pjname').val());
			$( "#start_date" ).datepicker({
				changeMonth: false,
				changeYear: false,
				//minDate: 0,
				hideIfNoPrevNext: true
			});
			$( "#end_date" ).datepicker({
				changeMonth: false,
				changeYear: false,
				//minDate: 0,
				hideIfNoPrevNext: true
			});
		});
	}

}
function ReportGlideMenu(uniq){
	var url = $('#pageurl').val();
	if(!uniq) {
		var uniq = $('#projFil').val();
		if(uniq == 'all') {
			var uniq = $('#last_project_uniqid').val();
		}
		window.location = url+'bug-report/';
	}else{
		$('#main_con').load(url+'reports/glide_chart/ajax/'+uniq,function(res){
			$('#prjname').html($('#pjname').val());
			$( "#start_date" ).datepicker({
				changeMonth: false,
				changeYear: false,
				//minDate: 0,
				hideIfNoPrevNext: true
			});
			$( "#end_date" ).datepicker({
				changeMonth: false,
				changeYear: false,
				//minDate: 0,
				hideIfNoPrevNext: true
			});
		});
	}
}

function hoursreport(uniq){
	var url = $('#pageurl').val();
	if(!uniq) {
		var uniq = $('#projFil').val();
		if(uniq == 'all') {
			var uniq = $('#last_project_uniqid').val();
		}
		window.location = url+'hours-report/';
	}else{
		$('#main_con').load(url+'reports/hours_report/ajax/'+uniq,function(res){
			$('#prjname').html($('#pjname').val());
			$( "#start_date" ).datepicker({
				changeMonth: false,
				changeYear: false,
				//minDate: 0,
				hideIfNoPrevNext: true
			});
			$( "#end_date" ).datepicker({
				changeMonth: false,
				changeYear: false,
				//minDate: 0,
				hideIfNoPrevNext: true
			});
		});
	}
}
$(window).scroll(function(){
	if($("#add_new_popup").css("display") == "none"){
		$("#fixed_header").css({position:"fixed"});
	}
	else{
		var new_popup_ht=$("#add_new_popup").height();
		var win_ht=$(window).height();
		if(($("#more_opt4").find("ul").css("display")=="block")){
			var new_popup_ht_opt4 = new_popup_ht + $("#more_opt4").find("ul").height();
		}
		else if(($("#more_opt5").find("ul").css("display")=="block")){
			var new_popup_ht_opt5 = new_popup_ht + parseInt($("#more_opt5").find("ul").height()-300);
		}
		if((win_ht <  parseInt(new_popup_ht+80)) || (parseInt(win_ht-40) <  new_popup_ht_opt4) || (parseInt(win_ht-40) <  new_popup_ht_opt5)){
			$("#fixed_header").css({position:"absolute"});
			$("html").css({overflowX:"hidden"});
			$(".fixed_inner_cont").css({position:"absolute"});
		}
		else{
			$("#fixed_header").css({position:"fixed"});
			$("html").css({overflowX:"auto"});
			$(".fixed_inner_cont").css({position:"fixed"});			
		}	
	}
});

function validateCsvForm() {
    var done = 1;
    if($("#csv_date option:selected").val()=='cst_rng'){
	var start_date = document.getElementById('cst_frm');
	var end_date = document.getElementById('cst_to');
	var errMsg;
	if(Date.parse(start_date.value) > Date.parse(end_date.value)) {
	    errMsg = "From Date cannot exceed To Date!";
	    end_date.focus();
	    done = 0;
	}else if(start_date.value.trim() == ""){
	    errMsg = "From Date cannot be left blank!";
	    start_date.focus();
	    done = 0;
	}else if(end_date.value.trim() == ""){
	    errMsg = "End Date cannot be left blank!";
	    end_date.focus();
	    done = 0;
	}
	
	if(done == 0) {
	    showTopErrSucc('error',errMsg);
	    return false;
	}
    }
    if(done==1){
	cover_close('cover', 'exporttaskcsv_popup');
	return true;
    }else{
	return false;
    }
}
function checkboxrange(){
	var start_date = document.getElementById('frm');
	var end_date = document.getElementById('to');
	var errMsg;
	var done = 1;
	if(start_date.value.trim() == ""){
		errMsg = "From Date cannot be left blank!";
		start_date.focus();
		done = 5;

		}
	else if(end_date.value.trim() == ""){
		errMsg = "To Date cannot be left blank!";
		end_date.focus();
		done = 5;

		}
	else if(Date.parse(start_date.value) > Date.parse(end_date.value)) {
		errMsg = "From Date cannot exceed To Date!";
		end_date.focus();
		done = 0;
	}
	if(done == 0) {
		var op = 100;
		showTopErrSucc('error',errMsg);
		return false;
	}else if(done == 5){
		return false;
	}
	else {
			var from=document.getElementById('frm').value;
			var to=document.getElementById('to').value;
			document.getElementById('date_any').checked = false;
			document.getElementById('date_one').checked = false;
			document.getElementById('date_24').checked = false;
			document.getElementById('date_week').checked = false;
			document.getElementById('date_month').checked = false;
			document.getElementById('date_year').checked = false;
			var x=from+":"+to;
			document.getElementById('caseDateFil').value=x;
			var url = document.getElementById('pageurl').value;
			$('#remember_filter').load(url+"easycases/remember_filters?date="+encodeURIComponent(x));
			ajaxCaseView('case_project.php');
	}
}
	function casequick_milestone(){
		$('#milestone_dd').html('[Select]');
		$('#txt_milestone').val('');
		$('#start_date1').val('');
		$('#end_date1').val('');
		document.getElementById('err_msg1').style.display='none';
	}
	function validateProjTemplate() {
		var temp_mod = document.getElementById('temp_id_sel');
		var title = document.getElementById('title');
		var desc = document.getElementById('description');
		var temp_mod_val = temp_mod.options[temp_mod.selectedIndex].value;
		var errMsg;
		var done = 1;		
		if(temp_mod_val.trim() == 0 || temp_mod_val.trim() == ""){
			errMsg = "Template Name cannot be left blank!";
			done = 0;
		}else if(title.value.trim() == ""){
			errMsg = "Title cannot be left blank!";
			title.focus();
			done = 0;	
		}
		if(done == 0) {
			var op = 100;
			showTopErrSucc('error',errMsg);
			return false;
		}
		else {
			document.getElementById('subprof1').style.display='none';
			document.getElementById('subprof2').style.display='block';

		}
	}
	function template_module_add()
	{
		var title = document.getElementById('txt_template').value;	
		if(title.trim() == "")
		{
			msg = "'Template Name' cannot be left blank!";
			document.getElementById('err_msg2').style.display = 'block';
			document.getElementById('err_msg2').innerHTML = msg;
			document.getElementById('txt_template').focus();
			return false;
		}else{
			document.getElementById('subprof5').style.display='block';
			var strURL = document.getElementById('pageurl').value;
			strURL = strURL+"projects/";
			document.getElementById('txt_template').value="";
			$.post(strURL+"ajax_add_template_module",{"title":title}, function(data){
				  if(data) {
					if(data.trim() != 0){
						var n=data.split("-");
						var tmp_id=n[1]
						$.post(strURL+"ajax_refresh_template_module",{"tmp_id":tmp_id}, function(data){
							if(data){
								document.getElementById('subprof5').style.display='none';
								$('#temp_mod_div').html(data);
							}
						});	
					}else{
						document.getElementById('subprof5').style.display='none';
						document.getElementById('temp_id_sel').selectedIndex=0;
						var op = 100;
						showTopErrSucc('error',"This Template already exist. Please try with new one.");
						return false;
					}
				  }
			});
			cover_close('cover','add_temp_mod');
		}
	}
	function unsel_temp(){
		//document.getElementById('temp_id_sel').selectedIndex=0;
		//document.getElementById('temp_id_sel').options['0'].selected = true;
		$("#temp_id_sel").val('0');
	}
	function add_cases_project(){
		var pj_id=document.getElementById('proj_id').value;
		var temp_mod_id=document.getElementById('temp_mod_id').value;
		var done =1;
		if(temp_mod_id == 0){
			errMsg = "Please select a template.";
			done = 0;
		}else if(pj_id == 0){
			errMsg = "Please select a project to add tasks.";
			done = 0;
		}
		if(done == 0) {
			var op = 100;
			showTopErrSucc('error',errMsg);
			return false;
		}
		else {
			var conf = confirm("Are you sure you want to add tasks to the project?");
			if(conf == true)
			{
			document.getElementById('subprof1').style.display='none';
			document.getElementById('subprof2').style.display='block';
			var strURL = document.getElementById('pageurl').value;
			strURL = strURL+"projects/";
			$.post(strURL+"ajax_add_template_cases",{"pj_id":pj_id,"temp_mod_id":temp_mod_id}, function(data){
				  if(data) {
					if(data == 1){
						document.getElementById('subprof1').style.display='block';
						document.getElementById('subprof2').style.display='none';
						var op = 100;
						showTopErrSucc('success','Tasks has been added.');
						return false;	
					}else{
						document.getElementById('subprof1').style.display='block';
						document.getElementById('subprof2').style.display='none';
						var op = 100;
						showTopErrSucc('error','Template is already added to this project!');
						return false;
					}
				  }
			});
			}else{
				return false;		
			}		
		}
	}
	function show_cases_temp_module(val){
			document.getElementById('subprof2').style.display='block';
			$('#show_temp_cases').hide();
			var strURL = document.getElementById('pageurl').value;
			strURL = strURL+"projects/";
			$.post(strURL+"ajax_view_template_cases",{"temp_id":val}, function(data){
				  if(data) {
					document.getElementById('subprof2').style.display='none';
					$('#show_temp_cases').show();
					$('#show_temp_cases').html(data);
				  }
			});
	}
	function editvalidateProjTemplate() {
		var title = document.getElementById('title');
		var errMsg;
		var done = 1;		
		if(title.value.trim() == ""){
			errMsg = "Title cannot be left blank!";
			title.focus();
			done = 0;	
		}
		if(done == 0) {
			var op = 100;
			showTopErrSucc('error',errMsg);
			return false;
		}
		else {
			document.getElementById('subprof1').style.display='none';
			document.getElementById('subprof2').style.display='block';
		}
	}
	function add_template_cs(){
		var temp_mod_id=document.getElementById('temp_mod_id').value;
		var pageurl=document.getElementById('pageurl').value;
		if(temp_mod_id != 0){
			var url = pageurl+"projects/add_template?id="+temp_mod_id;
			window.location=url;
		}else{
			var url = pageurl+"projects/add_template";
			window.location=url;
		}
	}
	function viewTemplateCases(menu,loder) 
	{
		$('#'+menu).hide();
		$('#'+loder).show();
		var temp_id=document.getElementById('sel_Typ').value;
		if(temp_id != 0){
			var strURL = document.getElementById('pageurl').value;
			strURL = strURL+"projects/";
			$.post(strURL+"ajax_view_temp_cases",{'template_id':escape(temp_id)}, function(data){
			  if(data)
				{
					$('#template_mod_cases').html(data);
					$('#'+menu).show();
					$('#'+loder).hide();
					cover_open('cover','template_mod_cases');
			    }
			});
		}
	}
	function view_btn_case(id){
		if(id != 0){
			$('#btn_cse').show();
		}else{
			$('#btn_cse').hide();
		}
	}
	function cover_close_case(a,b)
	{
		document.body.style.overflow = "visible";
		document.getElementById(b).style.display='none'; 
	}
	function open_new_case(){
		if(document.getElementById('add_new_popup').style.display == 'block'){
			hide_popoup();
			return false;
		}
		else{
			show_popoup();
			$(".newcase_bg").addClass("active_case");
			$("#CS_title").focus();
		}
	}
function newMilestone(menu,loder,mileuniqid){
          //alert(mileuniqid);
          document.getElementById(menu).style.display ="none";
		document.getElementById(loder).style.display ="block";
          var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"milestones/";
		cover_open('cover','inner_milestone');
          $.post(strURL+"ajax_new_milestone",{'mileuniqid':mileuniqid}, function(data){
			  if(data) {
                    //alert(data);
				$('#inner_milestone_td').html(data);
				
				var last_project_id = $('#last_project_id').val();
				//$('#selproject').val(last_project_id);
				$('#project_id').val(last_project_id);
			
				document.getElementById(menu).style.display ="block";
				document.getElementById(loder).style.display ="none";
				
			  }
		});
     }
	 function editMilestone(mileuniqid,mid){
		
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"milestones/";
		$.post(strURL+"ajax_new_milestone",{'mileuniqid':mileuniqid,'mid':mid}, function(data){
		  if(data) {
			$('#inner_milestone').html(data);
			var last_project_id = $('#last_project_id').val();
			//$('#selproject').val(pid);
			//$('#project_id').val(pid);
			//$('#uniq_id').val(mileuniqid);
			cover_open('cover','inner_milestone');
		  }
		});
     }
	function newProject(menu,loder,others) 
	{
		//document.getElementById(menu).style.display ="none";
		//document.getElementById(loder).style.display ="block";
		$('#inner_proj_td').html('');
		$('#inner_proj_td').html('<center><img src="'+HTTP_ROOT+'img/images/case_loader2.gif" alt="Loading..." title="Loading..." /></center>');
		if(others) {
			$('#inviteusr').hide();
			$('#loadinginvt').show();
		}
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"projects/";
		cover_open('cover','inner_proj');
		$.post(strURL+"ajax_new_project",{}, function(data){
			  if(data) {
				$('#inner_proj_td').html(data);
				
				if(others) {
					fbPopupClose();
					$('#inviteusr').show();
					$('#loadinginvt').hide();
				}			
				//document.getElementById(menu).style.display ="block";
				//document.getElementById(loder).style.display ="none";
				
				//var url = strURL+"ajax_json_members";
				//$("#select2").autocomplete({json_url:url});
			  }
		});
	}
		/* Function for savefilter created by jyoti start */
function showSaveFilter(id){
	document.getElementById(id).style.display ="block";
	saveAllFilters('saveflt','loaderpj');
}
function saveAllFilters(menu,loder){ 
          var caseStatus = document.getElementById('caseStatus').value;
          var caseType = document.getElementById('caseTypes').value;
          var caseDate = document.getElementById('caseDateFil').value;
          var caseMemeber = document.getElementById('caseMember').value;
	  var caseAssignTo = document.getElementById('caseAssignTo').value;
          var casePriority = document.getElementById('priFil').value;
          var caseSearch = document.getElementById('case_search').value;
          var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";
		$.post(strURL+"ajax_save_filter",{'caseStatus':caseStatus,'caseType':caseType,'caseDate':caseDate,'caseMemeber':caseMemeber,'caseAssignTo':caseAssignTo,'casePriority':casePriority,'caseSearch':caseSearch}, function(data){
			  if(data) { 
				$('#inner_save_filter_td').html(data);
				document.getElementById(menu).style.display ="block";
				document.getElementById(loder).style.display ="none";
				cover_open('cover','inner_save_filter');
				$("#cover").hide();
			  }
		});
     }
	$(document).keypress(function(evt){
		if (evt.keyCode == 27) {
			$("#inner_save_filter").hide();
		  }
	});
	
	/* $(document).click(function(){
		$("#inner_save_filter").hide();						

	});*/
	 $("#inner_save_filter").click(function(e){
		e.stopPropagation();									
	});
     function submitfilter(){
          var filtername=document.getElementById('txt_Proj').value;
         if(filtername.trim() != ""){
          var filter_case_status = document.getElementById('fstatus').value;
          var filter_date = document.getElementById('fdate').value;
          var filter_type = document.getElementById('ftype').value;
          var filter_priority = document.getElementById('fpriority').value;
          var filter_member = document.getElementById('fmember').value;
		  var filter_assignto = document.getElementById('fassignto').value;
		  var filter_search = document.getElementById('fsearch').value;
          var projuniqid = document.getElementById('projFil').value;
         //alert(filtername);alert(filter_case_status);alert(filter_date);alert(filter_type);alert(filter_priority);alert(filter_member);
		document.getElementById("saveFilBtn").style.display ="none";
     	document.getElementById("svloader").style.display ="block";
        var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";
		$.post(strURL+"ajax_customfilter_save",{'caseStatus':filter_case_status,'caseType':filter_type,'caseDate':filter_date,'caseMemeber':filter_member,'caseAssignTo':filter_assignto,'casePriority':filter_priority,'filterName':filtername,'projuniqid':projuniqid,'caseSearch':filter_search}, function(data){
		if(data) { 
			if(data == 'success'){
				showTopErrSucc('success','Custom filter saved sucessfully..');
			    cover_close('cover','inner_save_filter');
			    resetAllFilters();
			    document.getElementById('filter_parent_div1').style.display = 'none';
			    openAjaxCustomFilter();
			    ajaxCaseView('case_project');
			}else{
				showTopErrSucc('error','Custom filter name already exists..');   
				showSaveFilter('inner_save_filter');
		   	}
		}
		});
          }else{
               $("#err_msg").removeAttr("style"); 
          }
     }
     function showDeleteFilter(imgID,type){
     	if(type == 'show'){
     		$("#deleteImg_"+imgID).show();
     	}else{
     		$("#deleteImg_"+imgID).hide();
     	}
     }
     function deleteCustomFilter(id,name){
     	if(id){
     		var conf = confirm("Are you sure you want to delete custom filter '"+decodeURIComponent(name.replace(/\+/g,' '))+"' ?");
			if(conf == true) {
				var strURL = document.getElementById('pageurl').value;
				strURL = strURL+"easycases/";
				$.post(strURL+"ajax_customfilter_delete",{'id':id}, function(data){
					if(data) {
						if(data == 'success'){					
							$("#customDiv"+id).fadeOut('slow'); 
							document.getElementById('filter_parent_div1').style.display = 'none';
							openAjaxCustomFilter();
						}else{
							return false;
					   	}
					}
				});
			}else{
				return false;
			}
    	}
     }
     function openAjaxCustomFilter(){
          if(document.getElementById('filter_parent_div1').style.display == 'none') {
		$("#filterloader").show();
          $("#filter_less").hide();
          $("#filter_more").show();
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";//alert(strURL);
		$.post(strURL+"ajax_custom_filter_show", function(data){
			  if(data) { //alert(data);
			  	$("#filterloader").hide();
                    $("#filter_less").show();
                    $("#filter_more").hide();
				$('#customFilter1').html(data);
				$("#filter_parent_div1").slideToggle("slow");
				//$(".filter_vw").css({fontWeight:"bold"});
			  }
		});
	}
	else {
          $("#filter_less").hide();
          $("#filter_more").show();
		$("#filter_parent_div1").slideToggle("slow");
		//$(".filter_vw").css({fontWeight:"normal"});
	}
     }
     function filterValue(id,prjuniqid,date,type,status,member,assignto,praiority,searchtxt,pname){//alert(prjuniqid);
          document.getElementById('customFIlterId').value = id;
          //document.getElementById('projFil').value = prjuniqid;
          document.getElementById('caseStatus').value = status;
          document.getElementById('priFil').value = praiority;
          document.getElementById('caseTypes').value = type;
          document.getElementById('caseMember').value = member;
		  document.getElementById('caseAssignTo').value = assignto;
          document.getElementById('caseDateFil').value = date;
          document.getElementById('case_search').value = searchtxt;
          if(prjuniqid == 'all'){
               var prjuid = 0;
               var radio = 0;
               var all = 'all';
               var page ='dashboard';
          }else{
               var prjuid = prjuniqid;
               var radio = "proj_"+prjuniqid;
               var all = 0;
               var page ='dashboard';
          }
          //updateAllProj(radio,prjuid,page,all,pname,searchtxt);
          ajaxCaseView();
          //var url = document.getElementById('pageurl').value;
         // $('#casePage').val(1);
          //$('#remember_filter').load(url+"easycases/remember_filters?status="+status);
          
          //$('#reset_btn').show();
          
          /*$('#viewBreadCrumbsMems').html(member);
          $('#viewBreadCrumbsTypes').html(type);
          $('#viewBreadCrumbsStatus').html(status);
          $('#viewBreadCrumbsDate').html(date);
          $('#viewBreadCrumbsPriority').html(praiority);*/
     }
     function showmoreCustomFilter(limit,type){
     if(type == "more") {
		var limit1 =limit;
		var limit2 = 3;
	}
     var strURL = document.getElementById('pageurl').value;
     strURL = strURL+"easycases/";
	 $("#filterloader").show();
     $.post(strURL+"ajax_custom_filter_show",{"limit1":limit1,'type':type}, function(data){
		  if(data) {
			 $("#filterloader").hide();
			$('#recent_view1').html(data);
               //$("#more_recent").slideDown(0);
		  }
	});
}
function previousCustomFilter(limit,type){
     if(type == "less") {
		var limit1 =parseInt(limit)-6;
		var limit2 = 3;
	}//alert(limit1);
     var strURL = document.getElementById('pageurl').value;
     strURL = strURL+"easycases/";
	 $("#filterloader").show();
     $.post(strURL+"ajax_custom_filter_show",{"limit1":limit1,'type':type}, function(data){
     if(data) {
		 $("#filterloader").hide();
          $('#recent_view1').html(data);
          //$("#more_recent").slideDown(0);
     }
     });
}
/* Function for savefilter created by jyoti end */

		function submitsubscription(){
		var storage = document.getElementById('storage');
		var project_limit = document.getElementById('project_limit');
		var user_limit = document.getElementById('user_limit');
		var milestone_limit = document.getElementById('milestone_limit');
		var errMsg;
		var done = 1;
		var numbers = /^[0-9]+$/; 
		if((!storage.value.match(numbers) && storage.value.trim() != "Unlimited")  || storage.value.trim() == "" || storage.value.trim() == 0) {
			if(storage.value.trim() == ""){
				errMsg = "Storage cannot be left blank!";
				storage.focus();
				done = 0;
			}else if(storage.value.trim() == "Unlimited"){
				done = 1;
			}else if(!storage.value.match(numbers)){
				errMsg = "Storage can be a number or Unlimited ";
				storage.focus();
				done = 0;
			}else if(storage.value.trim() == 0){
				errMsg = "Storage can't be Zero. ";
				storage.focus();
				done = 0;
			}
			
		}else if((!project_limit.value.match(numbers) && project_limit.value.trim() != "Unlimited") || project_limit.value.trim() == "" || project_limit.value.trim() == 0) {
			if(project_limit.value.trim() == ""){
				errMsg = "Project Limit cannot be left blank!";
				project_limit.focus();
				done = 0;
			}else if(project_limit.value.trim() == "Unlimited"){
				done = 1;
			}else if(!project_limit.value.match(numbers)){
				errMsg = "Project Limit can be a number or Unlimited ";
				project_limit.focus();
				done = 0;
			}else if(project_limit.value.trim() == 0){
				errMsg = "Project Limit can't be Zero.";
				project_limit.focus();
				done = 0;
			}
			
		}
		else if((!user_limit.value.match(numbers) && user_limit.value.trim() != "Unlimited") || user_limit.value.trim() == "" || user_limit.value.trim() == 0) {
			if(user_limit.value.trim() == ""){
				errMsg = "User Limit cannot be left blank!";
				user_limit.focus();
				done = 0;
			}else if(user_limit.value.trim() == "Unlimited"){
				done = 1;
			}else if(!user_limit.value.match(numbers)){
				errMsg = "User Limit can be a number or Unlimited ";
				user_limit.focus();
				done = 0;
			}else if(user_limit.value.trim() == 0){
				errMsg = "User Limit can't be Zero. ";
				user_limit.focus();
				done = 0;
			}
			
		}else if((!milestone_limit.value.match(numbers) && milestone_limit.value.trim() != "Unlimited") || milestone_limit.value.trim() == "" || milestone_limit.value.trim() == 0) {
			if(milestone_limit.value.trim() == ""){
				errMsg = "Milestone Limit cannot be left blank!";
				milestone_limit.focus();
				done = 0;
			}else if(milestone_limit.value.trim() == "Unlimited"){
				done = 1;
			}else if(!milestone_limit.value.match(numbers)){
				errMsg = "Milestone Limit can be a number or Unlimited ";
				milestone_limit.focus();
				done = 0;
			}else if(milestone_limit.value.trim() == 0){
				errMsg = "Milestone Limit can't be Zero. ";
				milestone_limit.focus();
				done = 0;
			}
			
		}
		if(done == 0) {
			var op = 100;
			showTopErrSucc('error',errMsg);
			return false;
		}
		else {
			document.getElementById('subprof1').style.display='none';
			document.getElementById('subprof2').style.display='block';
		}
	}

	function validatTemplate() {
		var title = document.getElementById('title');
		var desc = document.getElementById('desc');
		var descrval = tinyMCE.activeEditor.getContent();
		var errMsg;
		var done = 1;		
		 if(title.value.trim() == ""){
			errMsg = "Title cannot be left blank!";
			title.focus();
			done = 0;

			}
		else if(descrval.trim() == "") {
			errMsg = "Description cannot be left blank!";
			desc.focus();
			done = 0;
		}
		if(done == 0) {
			var op = 100;
			showTopErrSucc('error',errMsg);
			return false;
		}
		else {
			document.getElementById('subprof1').style.display='none';
			document.getElementById('subprof2').style.display='block';
		}
	}
	function open_template(id){
		if(id.trim() == ''){
			cover_open('cover','add_temp_mod');
		}
	}
	
	
	
	/* function for switch project */
	function ajax_popup(obj){
		$('#ajaxViewProjects > a').removeClass('popup_selected');
		var checkload = $('#checkload').val();
		if(checkload == '1'){
			$('#popup_option').hide();
			$('#checkload').val('0');
			return;
		}
		if($('#ajaxViewProjects').html() == ""){
			var usrUrl=document.getElementById('pageurl').value;
			if($('#ajaxViewProjects').is(':visible')){
				document.getElementById('loader_prmenu').style.display= "none";
			}else{
				$('#ajaxViewProjects').html('');
				$('#popup_option').show();
				document.getElementById('loader_prmenu').style.display= "block";
				$.post(usrUrl+"users/project_menu",{"page":"reports","limit":6,"page_name":pgname}, function(data){
					if(data) {
						$('#ajaxViewProjects').html(data);
						$('#checkload').val('1');
						document.getElementById('loader_prmenu').style.display= "none";
						$('#ajaxViewProjects').show();
					}
				});
			}
		}else{
			$('#searchproject').val('');
			$('#popup_option').show();
			$('#ajaxViewProject').html('');
			$('#ajaxViewProject').hide();
			$('#ajaxViewProjects').show();
			$('#popup_option').show();
			$('#checkload').val('1');
		}  
		$('#searchproject').focus();
	}
	
		function view_project_milestone(){
		//alert("hello");
		
		if($('#mlstnpopup').is(':visible')){
			$("#mlstnpopup").hide();
		}
		else {
			$("#mlstnpopup").show();
		}
		var project_id=document.getElementById('project_id').value;
		var Url=document.getElementById('pageurl').value;
		
		if($('#ajaxViewMilestonesCP').html()){

			document.getElementById('loader_mlsmenu').style.display= "none";
		}else{
			$('#ajaxViewMilestones').html('');
			document.getElementById('loader_mlsmenu').style.display= "block";
			$.post(Url+"milestones/ajax_milestone_menu",{"project_id":project_id}, function(data)	{
				  if(data) {
					//alert(data);
					$('#ajaxViewMilestonesCP').html(data);
					document.getElementById('loader_mlsmenu').style.display= "none";
				  }
			});
		 }
	}
function displayMilestoneMenuProjects(page,limit,filter)
{
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";

	if(limit == "all")
	{
		document.getElementById('showMenu_case_txt').style.display='none';
		document.getElementById('loaderMenu_case').style.display='block';
	}
	
	$.post(strURL+"ajax_project_list_milestone",{"page":page,"limit":limit}, function(data){
		  if(data) {
			$('#ajaxViewProjects').html(data);
			if(limit == "all")
			{
				//document.getElementById('showMenu_case_txt').style.display='block';
				//document.getElementById('loaderMenu_case').style.display='none';
			}
		  }
	});
}
function search_project_menu_milestone(page,val){  
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	if(val!=""){
	$('#load_find_milestone').show();
	$.post(strURL+"search_project_menu_milestone",{"page":page,"val":val}, function(data){
		  if(data) {
			$('#ajaxViewProject').show();
			$('#ajaxViewProjects').hide();
			$('#ajaxViewProject').html(data);
			$('#load_find_milestone').hide();
		  }
	});
}else{
	$('#ajaxViewProject').hide();
	$('#ajaxViewProjects').show();
	$('#load_find_milestone').hide();
	}
}
function openAjaxRecentCase(type){
	if(document.getElementById('recent_parent_div').style.display == 'none') {
		$("#recentloader").show();
          $("#recenttask_less").hide();
          $("#recenttask_more").show();
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";
		$.post(strURL+"ajax_recent_case",{'caseid':recent_case_id},function(data){
			  if(data) { 
			  	$("#recentloader").hide();
                    $("#recenttask_less").show();
                    $("#recenttask_more").hide();
				$('#recentCase').html(data);
				$("#recent_parent_div").slideToggle("slow");
				//$(".rec_vw").css({fontWeight:"bold"});
				$("#recent_less").css({display:"block"});
				$("#recent_more").css({display:"none"});
			  }
		});
	}
	else {
          $("#recenttask_less").hide();
          $("#recenttask_more").show();
		$("#recent_parent_div").slideToggle("slow");
		//$(".rec_vw").css({fontWeight:"normal"});
		$("#recent_less").css({display:"none"});
		$("#recent_more").css({display:"block"});
	}
}
function showmoreRecentCase(limit,type){
     if(type == "more") {
		var limit1 =limit;
		var limit2 = 3;
	}
     var strURL = document.getElementById('pageurl').value;
     strURL = strURL+"easycases/";
	 $("#recentloader").show();
     $.post(strURL+"ajax_recent_case",{"limit1":limit1,'type':type,'caseid':recent_case_id}, function(data){
		  if(data) {
			 $("#recentloader").hide();
			$('#recent_view').html(data);
               //$("#more_recent").slideDown(0);
		  }
	});
}
function previousRecentCase(limit,type){
     if(type == "less") {
		var limit1 =parseInt(limit)-6;
		var limit2 = 3;
	}//alert(limit1);
     var strURL = document.getElementById('pageurl').value;
     strURL = strURL+"easycases/";
	 $("#recentloader").show();
     $.post(strURL+"ajax_recent_case",{"limit1":limit1,'type':type,'caseid':recent_case_id}, function(data){
     if(data) {
		 $("#recentloader").hide();
          $('#recent_view').html(data);
          //$("#more_recent").slideDown(0);
     }
     });
}
function check_proj_size() {
	if(document.getElementById('add_new_popup').style.display != 'block') {
		var strURL = document.getElementById('pageurl').value;
		sizeUrl = strURL+"easycases/";
		$.post(sizeUrl+"ajax_check_size",{"check":'size'},function(data) {
			if(data) {
				
				$("#ajax_check_size").html(data);
				
				var isExceed = $("#isExceed").val();
				$("#usedstorage").val($("#storageusedqc").val());
				
				/*if(isExceed == 1) {
					$("#postcasecheck").html('<font color="#FF0000">Sorry, storage limit Exceeded!</font><br/><font color="#F05A14"><a href="'+strURL+'users/company">Upgrade</a> your account to create more cases</font>');
				}
				else {
					var postbtn = $("#postcasecheck").html();
					var chk = postbtn.indexOf("submitAddNewCase");
					if(chk == -1 || chk == 0) {
						$("#postcasecheck").html('<span id="quickcase" style="display:block"><button type="submit" value="Post" name="data[Easycase][postdata]" style="margin-left:3px;margin:5px 0px;" class="" onclick="return submitAddNewCase(\'Post\',0,\'\',\'\',\'\',1,\'\')">Post</button>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="javascript:jsVoid();" onclick="hide_popoup()">Cancel</a></span><span id="quickloading" style="display:none;padding-left:10px"><img src="<?php echo HTTP_IMAGES;?>images/del.gif" title="Loading..." alt="Loading..."/></span>');
					}
				}*/
			}
		});
	}
}
function delproject(name,uniqid)
{ 
	var conf = confirm("Are you sure you want to delete milestone '"+name+"' ?");
	if(conf == true) {
		var strURL = document.getElementById('pageurl').value;
		document.getElementById('caseLoader').style.display='block';
		$.post(strURL+"milestones/ajax_delete_milestone",{"uniqid":uniqid},function(data) {
			 //if(data == "success") {
				 showTopErrSucc('success','Milestone has been deleted.');
				 ajaxCaseView();
			 //}
		});
	}
	else {
		return false;
	}
}
function assignCaseToMilestone()
{  
	var caseid = Array();
	var done = 0;
	var x = document.getElementById('hid_css').value;
	
	for(var i=1;i<=x;i++) {
		var id = "actionChks"+i;
		if(document.getElementById(id).checked == true) {
			var val = document.getElementById(id).value;
			caseid.push(val);
			done++;
		}
	}
	
	if(done) {
		var tot = caseid.length;
		var project_id = document.getElementById('project_id').value;
		var milestone_id = document.getElementById('milestone_id').value;
		
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'milestones/assign_case';
		document.getElementById('confirmMilestone').disabled = true;
		document.getElementById('mlstoneloader').style.display='block';
		document.getElementById('confirmmlstone').style.display='none';
		$.post(strURL,{"caseid":caseid,"project_id":project_id,"milestone_id":milestone_id},function(data) {
		 if(data == "success") {
			
			var countmanage = document.getElementById('countmanage').value;
			
			var strURL1 = document.getElementById('pageurl').value;
			var strURL1 = strURL1+'milestones/add_case';
			$("#popupload").show();
			$.post(strURL1,{"mstid":milestone_id,"projid":project_id,"countmanage":countmanage},function(data) {
			 if(data) {
					$('#loadcontent').html(data);
					$("#popupload").hide();
					$("#popupContactClose, .c_btn").click(function() {
						disablePopup();
					});
				 }
			});
			document.getElementById('mlstoneloader').style.display='none';
			document.getElementById('confirmmlstone').style.display='block';
			
			
			showTopErrSucc('success',tot+' Task(s) added successfully');
			ajaxCaseView();
               disablePopup();
			var user_id = document.getElementById('user_id').value;
			//caseListing(countmanage,milestone_id,1,user_id);
               
		  }
		});
	}
}
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
function jsVoid() { }
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
function cover_open_feedback(a,b)
{ 
     
     var url =  'Sent From: '+document.URL;
     var url1 = document.URL;
     $("#urlpage").html(url);
     $("#url_sendding").val(url1);
    	$("#support_err").html('');
	//$("#support_name").val('');
	//$("#support_email").val('');
	$("#support_msg").val('');
	var hg = getHeight();
	document.getElementById(a).style.height=hg;
	$("#"+a).fadeIn(); 
	$("#"+b).slideDown('fast'); 
	
}
function cover_close_feedback(a,b)
{
	document.body.style.overflow = "visible";
	$("#"+a).fadeOut(); 
	$("#"+b).slideUp('fast'); 
}
function postSupport(a,b)
{
	var geturl = $("#url_sendding").val().trim();
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
			$.post(strURL+"users/post_support_inner",{"support_refurl":escape(geturl),"support_email":escape(support_email),"support_msg":escape(support_msg),"support_name":escape(support_name)},function(data) {
				 if(data == "success") {
					$("#btn_submit").show();
					$("#loaderpost").hide();
					
					cover_close_feedback('cover','inner_support');
					//$("#support_name").val('');
					//$("#support_email").val('');
					$("#support_msg").val('');
					
					//cover_open_feedback('cover','inner_success');
                         showTopErrSucc('success','Thanks for your feedback. We will get back to you as soon as possible.'); 
					//$("#successmsg").html("Your message has been sent. Thank you for your Feedback.");
					setTimeout("cover_close_feedback('cover','inner_success')",3000);
				 }
				 else {
					 cover_close_feedback('cover','inner_support');
				 }
			});
		}
	}
	return false;
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

function checkuserlogin() {
	var url =$('#pageurl').val();
	setInterval(
		function(){
			if(!getCookie('USER_UNIQ') || !getCookie('USERTYP') || !getCookie('USERTZ')){
				window.top.location = url + 'users/logout/';
			}
		}
	,1349);
}

//chrome desktop notification function
function notify(title, desc) {
	if(DESK_NOTIFY && window.webkitNotifications) {
		var havePermission = window.webkitNotifications.checkPermission();
		if (havePermission == 0) {
			// 0 is PERMISSION_ALLOWED
			var notification = window.webkitNotifications.createNotification(
				HTTP_IMAGES+'transparent_logo.png',
				title,
				desc
			);
			notification.onclick = function () {
				//location.reload();
				window.focus();
				removePubnubMsg();
				notification.close();
			};
			setTimeout(function(){notification.close();}, 10000);
			notification.show();
		} else {
			window.webkitNotifications.requestPermission();
		}
	}
}
function allowChromeDskNotify(check){
	if ((DESK_NOTIFY || check) && window.webkitNotifications && window.webkitNotifications.checkPermission()!=0) {
		window.webkitNotifications.requestPermission();
	}
}
function getImNotifyMsg(projShName, caseNum, caseTtl, caseTyp){
	var action = '';
	switch(caseTyp) {
		case 'NEW': action = "New Task Created";
					break;
		case 'UPD': action = "Task Updated";
					break;
		case 'ARC': action = "Task Archived";
					break;
		case 'DEL': action = "Task Deleted";
					break;
		default:  	action = "New Notification";
	}
	return action+': '+projShName+'# '+caseNum+' - '+caseTtl;
}
//end chrome desktop notification function

function assignuser(el)
{
	var userid = Array();
	var done = 0;
	var x = document.getElementById('hid_cs').value;
	var cntmng = document.getElementById('cntmng').value;
     var page_name = document.getElementById('pagename').value;
	for(var i=1;i<=x;i++) {
		var id = "actionChk"+i;
		if(document.getElementById(id).checked == true) {
			var val = document.getElementById(id).value;
			userid.push(val);
			done++;
		}
	}
	if(done) {
		var tot = userid.length;
		var pjid = document.getElementById('projectId').value;
		var pjname = document.getElementById('project_name').value;
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'projects/assign_userall';
		//document.getElementById('closebtn').style.display='none';
		document.getElementById('confirmbtn').style.display='none';
		document.getElementById('userloader').style.display='block';
		$.post(strURL,{"userid":userid,"pjid":pjid},function(data) {
		 if(data == "success"){ 
		 		document.getElementById('userloader').style.display='none';
				document.getElementById('confirmuser').style.display='block';
				//document.getElementById('closebtn').style.display='block';
				document.getElementById('confirmbtn').style.display='block';
				
				showTopErrSucc('success',tot+' User(s) added successfully');
			
				if(el && el.id=="confirmuserbut"){
					var strURL1 = document.getElementById('pageurl').value;
					var strURL1 = strURL1+'projects/add_user';
					$("#popupload").show();
					$.post(strURL1,{"pjid":pjid,"pjname":pjname,"cntmng":cntmng},function(data) {
					 if(data) {
							$('#loadcontent').html(data);
							$("#popupload").hide();
							$("#popupContactClose, .c_btn").click(function() {
								disablePopup();
							});
							if(page_name == 'dashboard'){
							   ajaxCaseView();
							}
						 }
					});
				} else {
					usPopupClose();
					//alert(page_name);
					if(page_name == 'onbording_createproject'){
						window.location.reload();
					}
				}
				var userDiv = 'userDiv'+cntmng;
				$('#'+userDiv).html('');
				$('#'+userDiv).hide();
				/*var userDiv = 'userDiv'+cntmng;
				var userImg = 'userImg'+cntmng;
				var strURL = document.getElementById('pageurl').value;
				var strURL = strURL+'projects/user_listing';
				$("#"+userImg).show();
			
				$.post(strURL,{"count":cntmng,"project_id":pjid},function(data) {
					if(data){
						$('#'+userDiv).html(data);
						$("#"+userImg).hide();
					}
				});*/
		
			}
		});
	}
}
