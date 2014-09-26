var gFileupload = 1;
$(function () {

	$('.close-notification').click(
		function () {
			$(this).parent().fadeTo(350, 0, function () {$(this).slideUp(600);});
			return false;
		}
	);
	$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
	$('.ie7').find(':disabled').addClass('disabled');
	
	$('#main-nav li a.no-submenu, #main-nav li li a').click(
		function () {
			window.location.href=(this.href); 
			return false;
		}
	);

	var pagename = document.getElementById('pagename').value;
	setInterval(trackLogin,900000);
	
	var pageurl = document.getElementById('pageurl').value;
	var projectUniqId = $('#projFil').val();
});
/**************** OnLoad Events **************/
function trackLogin()
{
	var strURL = document.getElementById('pageurl').value;
	$.post("users/session_maintain",{},function(data){
	 if(data) {
		if(data == 1)
		{
			//alert('You have been logged out.You will now be redirected to home page.');
			window.location=strURL+"users/login";
		}
	  }
	});
}
function displayMenuProjects(page,limit,filter)
{
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	var filter = document.getElementById('caseMenuFilters').value;
	
	if(limit == "all")
	{
		document.getElementById('showMenu_case_txt').style.display='none';
		document.getElementById('loaderMenu_case').style.display='block';
	}
	
	$.post(strURL+"project_menu",{"page":page,"limit":limit,"filter":filter}, function(data){
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
function submitAddNewCase(postdata,CS_id,uniqid,cnt,dtls,status,prelegend,pid)
{
	var CS_type_id = 2; var CS_priority = 1; var CS_assign_to = 0; var CS_message = ""; var CS_due_date = "";var CS_legend = status;var CS_milestone = "";
	var cs_hours = 0;var completed =0;
	if(CS_id) {
		var CS_legend = $("#legend"+CS_id).val();
	}
	var done = 1;
	if(CS_id) {
		var project_id = "CS_project_id"+CS_id;
		var istype = "CS_istype"+CS_id;
		var title = "CS_title"+CS_id;
		
		var CS_project_id = document.getElementById(project_id).value;
		var CS_istype = document.getElementById(istype).value;
		var CS_title = document.getElementById(title).value;
		
		var type_id = "CS_type_id"+CS_id;
		var priority = "CS_priority"+CS_id;
		var case_no = "CS_case_no"+CS_id;
		
		var CS_type_id = document.getElementById(type_id).value;
		var CS_priority = document.getElementById(priority).value;
		var CS_case_no = document.getElementById(case_no).value;
		
		var html = "html"+CS_id;
		var plane = "plane"+CS_id;
		if(document.getElementById(html).style.display == 'block') {
			var txa_comments = "txa_comments"+uniqid;
			CS_message = $('#'+txa_comments).html();//document.getElementById(txa_comments).value;
		}
		else {
			var txa_plane = "txa_plane"+CS_id;
			CS_message = document.getElementById(txa_plane).value;
		}
		var editortype = "editortype"+CS_id;
		var datatype = document.getElementById(editortype).value;
		
		var totfiles = "totfiles"+CS_id;
		var hidalluser = "hidtotresreply"+CS_id;
		
		var assign_to="CS_assign_to"+CS_id; 		
		var CS_assign_to=document.getElementById(assign_to).value;
	}
	else {
		var CS_project_id = document.getElementById('CS_project_id').value;
		var CS_istype = document.getElementById('CS_istype').value;
		var CS_title = document.getElementById('CS_title').value;
		var totfiles = "totfiles";
		var hidalluser = "hidtotproj";
		var datatype = 0;
		document.getElementById('projAllmsg').style.display = 'none';
		if(CS_project_id == 'all'){ document.getElementById('projAllmsg').style.display = 'block'; alert('Oops! No project selected.'); return false;}
		
		if(!CS_id) {
			if(CS_project_id == "") {
				done = 0;
			}
			if(CS_title.trim() == "" || CS_title.trim() == "Add a task here and hit enter...") {
				$('#CS_title').css('border-color', '#CE2129');
				$("#CS_title").focus();
				done = 0;
			}else{
				$('#CS_title').css('border-color', '');
			}
		}
	}
		
	var emailUser = Array();
	var allUser = Array();
	var allFiles = Array();
	try{
		if(CS_id) {
				var chk = CS_id+"chk_";
			}else {
				var chk = "chk_";
			}
		$('input[id^="'+chk+'"]').each(function(i){
			if($(this).is(':checked')){
				emailUser.push($(this).val());
			}		
		});
		/*var totuid = document.getElementById(hidalluser).value;
		for(var i=0;i<totuid;i++) {
			if(CS_id) {
				var chk = CS_id+"chk_"+i;
			}else {
				var chk = "chk_"+i;
			}
			if(document.getElementById(chk).checked == true) {
				var x = document.getElementById(chk).value;
				emailUser.push(x);
			}
			var x1 = document.getElementById(chk).value;
			allUser.push(x1);
		}*/
	}
	catch(e) {
	}
	try{
	if(done == 1){
		if(gFileupload == 0){
		    if(confirm("Files upload is in progress... Are you sure you want to post?")){
			done = 1;
			gFileupload = 1;
		    }else{
			done = 0;
		    }
		}else{
		    done = 1;
		}
	    }
	var totfiles = document.getElementById(totfiles).value;

		if(parseInt(totfiles) && done == 1) {
			if(!CS_id){
				$('.ajxfileupload').each(function(i){
					allFiles.push($(this).val());
				});
			}else{
				for(var i=1;i<=totfiles;i++) {
					var fid = CS_id+"jqueryfile"+i;
					var fil = document.getElementById(fid).value;
					if(fil) {
						allFiles.push(fil);
					}
				}
			}
			var file_size = 0;
			var storage_max = $("#max_storage").text();
			if(parseFloat(storage_max)){
				var storage_used = $("#used_storage").text().trim();
				for(var indx in allFiles){
					var first = parseInt(allFiles[indx].indexOf("|"));
					var second = parseInt(allFiles[indx].indexOf("|",first+1));
					file_size = parseFloat(file_size) + parseFloat(allFiles[indx].substring(first+1));
				}
				var total_size = parseFloat(storage_used) + parseFloat(file_size/1024);
				total_size = total_size.toFixed('2');
				done = 1;
				/*if(parseFloat(total_size) <= parseFloat(storage_max)){
					done = 1;
				}else{
				  done = 0;
				  alert("Storage limit exceeded!\nUpgrade your account to get more storage.\n\nOR, remove any of the attached file.");
				}*/
			}
		}
	}
	catch(e) {
	}
	
	//if((done == 1 && emailUser.length != "0") || (done == 1 && confirm("Are you sure you want to proceed without sending any email?"))) {
	if(done == 1) {
		if(!CS_id) {
			if(document.getElementById('new_case_more_div').innerHTML) {
				CS_type_id = document.getElementById('CS_type_id').value;
				CS_priority = document.getElementById('CS_priority').value;
				cs_hours = $("#hours").val();
				//CS_message = document.getElementById('CS_message').value;
				//CS_message = tinyMCE.activeEditor.getContent();
				try {
				CS_message = tinyMCE.activeEditor.getContent();
				}catch(e) {
					}
				
				CS_due_date = document.getElementById('CS_due_date').value;
				CS_milestone = document.getElementById('CS_milestone').value;
			}
			
			
			CS_assign_to = document.getElementById('CS_assign_to').value;		
			var own_session_id = document.getElementById('own_session_id').value;	
			
			if(CS_assign_to == '')
			{
				CS_assign_to = own_session_id;
			}
			else
			{
				CS_assign_to = CS_assign_to;
			}
			document.getElementById('quickcase').style.display='none';
			document.getElementById('quickloading').style.display='block';
		}
		else {
			var postcomments = "postcomments"+CS_id;
			var loadcomments = "loadcomments"+CS_id;
			document.getElementById(postcomments).style.display='none';
			document.getElementById(loadcomments).style.display='block';
			var cs_hours = $("#hours"+CS_id).val();
               var completed = $("#completed"+CS_id).val();
		}
		
		var pagename = document.getElementById('pagename').value;
		var strURL = document.getElementById('pageurl').value;
		strURL = strURL+"easycases/";
		
		/*alert(CS_legend);
		alert(prelegend);*/
		if(!cs_hours) {
			cs_hours = 0;
		}
		
		var cloud_storages;
		if(CS_id) {
		    cloud_storages= $('#cloud_storage_form_'+CS_id).serialize();
		} else {
		    cloud_storages= $('#cloud_storage_form_0').serialize();
		}
		var tskURL = (cloud_storages) ? strURL+"ajaxpostcase?"+cloud_storages : strURL+"ajaxpostcase";
		
		$.post(tskURL,{pid:pid,CS_project_id:CS_project_id,CS_istype:CS_istype,CS_title:CS_title,CS_type_id:CS_type_id,CS_priority:CS_priority,CS_message:CS_message,CS_assign_to:CS_assign_to,CS_due_date:CS_due_date,CS_milestone:CS_milestone,postdata:postdata,pagename:pagename,emailUser:emailUser,allUser:allUser,allFiles:allFiles,CS_id:CS_id,CS_case_no:CS_case_no,datatype:datatype,CS_legend:CS_legend,prelegend:prelegend,'hours':cs_hours,'completed':completed},function(data) {
			if(data) {

				if(!CS_id) {
					try {
						document.getElementById('caseMenuFilters').value = "";
						document.getElementById('CS_assign_to').value = '';
						document.getElementById('pageheading').innerHTML='Tasks';
						tinyMCE.activeEditor.setContent('');
					}
					catch(e) {
					}
					
					showTopErrSucc('success','Your task has been posted.');
					
					if(data.storage_used){
					    var clr = 'red';
					    var max_storage = $("#max_storage").text();
					    if(parseFloat(data.storage_used) < parseFloat(max_storage)){
						clr = '#84d1f2';
					    }
					    var str = "<font style='color:"+clr+"'> <span id='used_storage'>"+data.storage_used+"</span>/<b><span id='max_storage'>"+max_storage+"</span> Mb</b></font>";
					    $("#storage_spn").html(str);
					}
					
					document.getElementById('quickcase').style.display='block';
					document.getElementById('quickloading').style.display='none';
					document.getElementById('new_case_more_div').innerHTML="";
					document.getElementById('CS_title').value="";
					hide_popoup();
					var pageUrl = document.getElementById('pageurl').value;
					
					if(data.pagename == "dashboard") {
						updateAllProj('proj'+data.formdata,data.formdata,data.pagename,'0',data.projName);
					}
					else {
						if(pagename =='onbording_createproject'){
							window.location = pageUrl+"projects/onbording_createproject";
						}else{
							var rqUrl = document.URL;
							var n = rqUrl.indexOf("activities");	
							if(n != -1){
								window.location=pageUrl+"dashboard";
							}else{
								window.location=pageUrl+"easycases/dashboard";
							}
						}
						
					}
					var CS_project_id = document.getElementById('CS_project_id').value;
				}
				else {
					updateCaseListing(CS_id,cnt,postdata,dtls,CS_assign_to,data.format,CS_legend,prelegend);
					ajaxCaseDetails(uniqid,CS_id,cnt,"reply",dtls,pid);
					
					showTopErrSucc('success','Your reply has been posted.');
					
					if(data.storage_used){
					    var clr = 'red';
					    var max_storage = $("#max_storage").text();
					    if(parseFloat(data.storage_used) < parseFloat(max_storage)){
						clr = 'green';
					    }
					    var str = "<font style='color:"+clr+"'> <span id='used_storage'>"+data.storage_used+"</span>/<b><span id='max_storage'>"+max_storage+"</span> Mb</b></font>";
					    $("#storage_spn").html(str);
					}
					
					try {
						if(!CS_legend) {
							document.getElementById('actionCls'+cnt).value = 2;
						}
						else {
							document.getElementById('actionCls'+cnt).value = CS_legend;
						}
						var actionChk = "actionChk"+cnt;
						if(postdata == "Post") {
							var xdata = document.getElementById(actionChk).value;
							var exdt = xdata.split("|");
							if(exdt[2] == "closed") { 
								$("#"+actionChk).removeAttr('disabled');
								$("#"+actionChk).removeAttr('checked');
							}
						}
						else {
							document.getElementById(actionChk).disabled = true;
							document.getElementById(actionChk).checked = true;
						}
					}
					catch(e) {
					}
					
					var project_id = "CS_project_id"+CS_id;
					var CS_project_id = document.getElementById(project_id).value;
					
					var pageUrl = document.getElementById('pageurl').value;
					$.post(pageUrl+"easycases/update_assignto",{"caseId":CS_id}, function(res){
						  if(res) {
							$('#showUpdAssign'+CS_id).html(res);
						  }
					});
					
					var caseMenuFilters = document.getElementById('caseMenuFilters').value;
					var pageurl = document.getElementById('pageurl').value;
					var url = pageurl+"easycases/ajax_case_status";
					
					var case_date = $("#caseDateFil").val();		
					var caseStatus = $("#caseStatus").val();
					var caseTypes = $("#caseTypes").val();
					var caseMember = $("#caseMember").val();
					var caseAssignTo = $("#caseAssignTo").val();
					var caseSearch = $("#case_search").val();
					var priFil = $("#priFil").val();
					var milestoneIds = $("#milestoneIds").val();
					var checktype = $("#checktype").val();
					
					$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters,'case_date':case_date,'caseStatus':caseStatus,'caseTypes':caseTypes,'priFil':priFil,'caseMember':caseMember,'caseAssignTo':caseAssignTo,'caseSearch':caseSearch,'milestoneIds':milestoneIds,'checktype':checktype}, function(data){
					//$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters}, function(data){																						
						  if(data) {
						  
								//$('#ajaxCaseStatus').html(data);
								$('#ajaxCaseStatus').html(tmpl("case_widget_tmpl", data));
								
								$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
								$('.tooltip_widget').tipsy({gravity:'e', fade:true});
								
								$('.close-widget').click(
									function () {
										$(this).parent().fadeTo(350, 0, function () {$(this).slideUp(600);}); // Hide widgets
										return false;
									}
								);
								
								if(document.getElementById('reset_btn').style.display != 'none') {
									$('#upperDiv_alert').fadeIn();
									setTimeout(removeMsg_alert,6000);
									//$('.blinkwidget').css('border','1px solid #9BC0FB');
									//$('.blinkwidget').css('box-shadow','1px 1px 1px #B8D2FC');
									
									/*$('.blinkwidget').animate({opacity:"0.8"},500,function(){
										$(this).animate({opacity:"1"},500,function(){
											blink(this);
										});
									});*/
								}
								else {
									$('#upperDiv_alert').fadeOut();
								}
						  }
					});
				}
				
				if(data.caseNo){
					var url_ajax = strURL+"ajaxemail";
					$.post(url_ajax,{'projId':data.projId,'emailUser':emailUser,"allfiles":data.allfiles,'caseNo':data.caseNo,'emailTitle':data.emailTitle,'emailMsg':data.emailMsg,'casePriority':data.casePriority,'caseTypeId':data.caseTypeId,'msg':data.msg,'emailbody':data.emailbody,'caseIstype':data.caseIstype,'csType':data.csType,'caUid':data.caUid,'caseid':data.caseid,'caseUniqId':data.caseUniqId});
				}
				//check size
				check_proj_size();
				
					/*var pageurl = document.getElementById('pageurl').value;
					$.post(pageurl+"easycases/ajax_case_menu",{"projUniq":CS_project_id,"pageload":1,"page":"dashboard"}, function(res){
						  if(res) {
							$('#ajaxMenucaseNo').html(res);
							$('[rel=tooltip], #main-nav span, .loader').tipsy({gravity:'s', fade:true});
						  }
					});*/
					
					
				}
				
				var pageurl = document.getElementById('pageurl').value;
				loadCaseMenu(pageurl+"easycases/ajax_case_menu",{"projUniq":CS_project_id,"pageload":1,"page":"dashboard"})
				
				/*var caseMenuFilters = document.getElementById('caseMenuFilters').value;
				var pageurl = document.getElementById('pageurl').value;
				var url = pageurl+"easycases/ajax_case_status";
				$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters}, function(data){
					  if(data) {
						$('#ajaxCaseStatus').html(data);
							$('.close-widget').click(
								function () {
									$(this).parent().fadeTo(350, 0, function () {$(this).slideUp(600);}); // Hide widgets
									return false;
								}
							);
					  }
				});*/
				
				/*var url = pageurl+"easycases/ajax_status";
				$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters}, function(res){
					  if(res) {
						$('#statusRefresh').html(res);
					  }
				});
				var url = pageurl+"easycases/ajax_types";
				$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters}, function(data){
					  if(data) {
						$('#statusTypes').html(data);
					  }
				});
				
				var url = pageurl+"easycases/ajax_priority";
				$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters}, function(res){
					  if(res) {
						$('#prioritRefresh').html(res);
					  }
				});
				var url = pageurl+"easycases/ajax_members";
				$.post(url,{"projUniq":CS_project_id,"pageload":1,"caseMenuFilters":caseMenuFilters}, function(res){
					  if(res) {
						$('#csMemAjx').html(res);
					  }
				});*/
				
				var projUpdateTop = $("#projUpdateTop").html();
				$("#pname_dashboard").html(projUpdateTop);
				
				$('#defaultmem').show();
				
		},'json');
		//ajaxCaseView('case_project.php');
	}
	else {
		return false;
	}
}
function updateCaseListing(CS_id,cnt,postdata,dtls,assignto,format,CS_legend,prelegend) {
	var hidtotrp = "hidtotrp"+CS_id;
	var cntnum = document.getElementById(hidtotrp).value;
	var repno = "repno"+cnt;
	var case_cnt = "case_cnt"+cnt;
	
	try
	{
		var fileattch = "fileattch"+cnt;
		if(format == 1 || format == 3)
		{
			document.getElementById(fileattch).style.display = "block";
		}
	}
	catch(e) {
	}
	var newnum = parseInt(cntnum)+1;
	if(newnum) {
		$("#"+case_cnt).show();
		$("#"+repno).html(newnum);
	}
	if(dtls == 0)
	{
		var csStsRep = "csStsRep"+cnt;
		
		if(CS_legend == 2 || CS_legend == 4){
			$("#"+csStsRep).html("<font color='#04407C'>WIP</font>")
		}
		else if(CS_legend == 3) {
			$("#"+csStsRep).html("<font color='#387600'>Closed</font>")
		}
		else if(CS_legend == 5){
			$("#"+csStsRep).html("<font color='#EF6807'>Resolved</font>")
		}
		
		var stsdisp = "stsdisp"+CS_id;
		$("#"+stsdisp).html("updated");
		
		var currentTime = new Date()
		var hours = currentTime.getHours()
		var minutes = currentTime.getMinutes()
		if (minutes < 10){
			minutes = "0" + minutes
		}
		if(hours > 11){
			var ampm = "pm";
		} else {
			var ampm = "am";
		}
		if(hours > 12) {
			hours  = hours - 12;
		}
		var time = "Today at "+ hours + ":" + minutes + " " + ampm;
		var timedis = "timedis"+cnt;
		document.getElementById(timedis).innerHTML = time;
		
		var pageurl = document.getElementById('pageurl').value;		
		var url = pageurl+"easycases/ajax_change_assign";
		$.post(url,{"assignto":assignto}, function(data){
			  if(data) {
				$('#asgnblock'+cnt).html(data);
			  }
		});
	}
}
// ######### Jyoti ##########
function search_project_easypost(val)
{  
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"users/";
	if(val!=""){
	     $('#load_find').show();
	     $.post(strURL+"search_project_menu",{"val":val}, function(data){
		       if(data) {
			     $('#ajaxaftersrchc').show();

			     $('#ajaxbeforesrchc').hide();
			     $('#ajaxaftersrchc').html(data);
			     $('#load_find').hide();
		       }
	     });
     }else{
	     $('#ajaxaftersrchc').hide();
	     $('#ajaxbeforesrchc').show();
	     $('#load_find').hide();
	}
}

/* from case_details.ctp */
function valforlegend(id,leg){
	document.getElementById(leg).value=id;	
}

// Sorting reply text and reply box 
function sortreply(id,uniqid){
	tinymce.execCommand('mceRemoveControl',true,'#txa_comments'+uniqid);
	var reply_text = $('#reply_content'+id).html();
	var reply_box = $('#reply_box'+id).html();
	if($('#thread_sortorder'+id).val()=='ASC'){
		$('#repsort_asc_'+id).show();
		$('#repsort_desc_'+id).hide();
		$('#thread_sortorder'+id).val('DESC');
	}else{
		$('#repsort_asc_'+id).hide();
		$('#repsort_desc_'+id).show();
		$('#thread_sortorder'+id).val('ASC');
	}
	var sortorder = $('#thread_sortorder'+id).val();
	var type = $('#threadview_type'+id).val();
	var strURL = document.getElementById('pageurl').value;
	strURL = strURL+"easycases/case_reply";
	var showhidemorereply = "showhidemorereply"+id;
	var morereply = "morereply"+id;
	var hidereply = "hidereply"+id;
	var loadreply = "loadreply"+id;
	var loadreply = "loadreply"+id;
	if(type=='less'){
		var viewtype ='post';
	}else{
		var viewtype =type;
	}
	if($('#remain_case'+id).val()){
		var rem_cases = $('#remain_case'+id).val();
	}else{
		var rem_cases = 0;
	}
	$('#loadreply_sort_'+id).css('visibility', 'visible');
	$.post(strURL,{"id":id,"type":viewtype,'sortorder':sortorder,'rem_cases':rem_cases},function(data) {			
		if(data){
			$('#loadreply_sort_'+id).css('visibility', 'hidden');
			//$("#"+showhidemorereply).html(data);
			
			var results = document.getElementById(showhidemorereply);
			results.innerHTML = tmpl("case_replies_tmpl", data);
			bindPrettyview("prettyPhoto");
			
			if(type == "more") {
				$('#'+morereply).hide();
				$('#'+hidereply).show();					
			}else if(type == "less") {
				$('#'+morereply).show();
				$('#'+hidereply).hide();
			}
		}
	});
}
// Code for Edit description of a task

var url = '<?php echo HTTP_ROOT;?>';
function showeditdesc(id){
	if(!$('#edit_textmesg'+id).is(":visible")){
		$('#edit_textmesg'+id).show();
	}
}
function hideeditdesc(id){
	if($('#edit_textmesg'+id).is(":visible")){
		$('#edit_textmesg'+id).hide();
	}
}
function showedittextpopup(id,projid){
	var url = document.getElementById('pageurl').value;
	$('#edit_textmesg'+id).css('background-image','url("'+ HTTP_ROOT +'img/images/del.gif")');
	$.post(url+"easycases/edit_reply",{id:id,'projid':projid},function(res){
		$('#desc_'+id).hide();
		$('#edit_textmesg'+id).css('background-image','url("'+ HTTP_ROOT +'img/html5/icons/fb_edit.png")');
		$('#edit_desc_'+id).html(res);
		var tiny_mce_url= url+'js/tinymce/tiny_mce.js';
		$('#edit_reply_txtbox'+id).tinymce({
			// Location of TinyMCE script
			theme : "advanced",
			plugins : "paste",
			theme_advanced_buttons1 : "bold,italic,strikethrough,underline,|,numlist,bullist,|,indent,outdent",
			theme_advanced_resizing : false,
			theme_advanced_statusbar_location : "",
			paste_text_sticky : true,
			gecko_spellcheck : true,
			paste_text_sticky_default : true,
			forced_root_block : false,
			cleanup_on_startup : true,
			width : "595px",
			height : "150px",
			oninit : function() {
				$('#edit_reply_txtbox'+id).tinymce().focus();
			 }
		});
	});
}
function save_editedvalue(caseno,id,proj_id){
	var message = $('#edit_reply_txtbox'+id).val();
	var url = document.getElementById('pageurl').value;
	$.post(url+"easycases/save_editedvalue",{'id':id,'message':message,'caseno':caseno,'proj_id':proj_id},function(res){
		if(res==0){
			showTopErrSucc('error',"Message cann't be left blank"); 
		}else{
			$('#desc_'+id).show();
			$('#desc_text_id'+id).html(message);
			$('#edit_desc_'+id).html('');	
			showTopErrSucc('success','Task description edited successfully.'); 
		}
	});
	
}
function cancel_editor(id){
	$('#desc_'+id).show();
	$('#edit_desc_'+id).html('');
	//$('#casereplyid_'+id).hide();	
}
function select_reply_user(cs_autoid,obj){
	uid =$(obj).val();
	$('#'+cs_autoid+'chk_'+uid).attr('checked','checked');
}

var reply_total_files=new Array();var reply_indx = 0;

function fuploadUI(csAtId) {
	var isExceed = 0;
	$('INPUT[type="file"]').change(function () {
		var isExceed = $("#isExceed").val();
		var ext = this.value.match(/\.(.+)$/)[1].toLowerCase();
		if($.inArray(ext, ["bat","com","cpl","dll","exe","msi","msp","pif","shs","sys","cgi","reg","bin","torrent","yps","mp4","mpeg","mpg","3gp","dat","mod","avi","flv","xvid","scr","com","pif","chm","cmd","cpl","crt","hlp","hta","inf","ins","isp","jse?","lnk","mdb","ms","pcd","pif","scr","sct","shs","vb","ws","vbs"]) >= 1) {
			alert("Sorry, '"+ext+"' file type is not allowed!");
			this.value = '';
		}
		else if(isExceed == 1) {
			//alert("Sorry, Storage Limit Exceeded!");
		}
		reply_total_files = new Array();reply_indx = 0;
	});

	var i = 0;
	$('.upload'+csAtId+':not(.applied'+csAtId+')').addClass('applied'+csAtId+'').fileUploadUI({
		uploadTable: $('#up_files'+csAtId+''),
		downloadTable: $('#up_files'+csAtId+''),
		buildUploadRow: function (files, index)
		 {
			var filename = files[index].name;
			if(filename.length > 35)
			{
				filename = filename.substr(0,35);
			}
			gFileupload = 0;
			reply_total_files.push(filename);
			return $('<tr><td valign="top">' + filename + '</td>' +
					'<td valign="top" width="100px" style="padding-left:10px;" title="Uploading..." rel="tooltip"><div class="progress-bar"><div class="progress-bar blue"><\/div><\/div></td>' +
					'<td valign="top" style="padding-left:10px;"><div class="file_upload_cancel">' +
					'<font id="cancel" title="Cancel" title="Cancel" rel="tooltip">' +
					'<span class="ui-icon-fupload ui-icon-cancel" onclick="cancelReplyFile(\''+filename+'\');">Cancel<\/span>' +
					'<\/font><\/div><\/td><\/tr>');
		},
		buildDownloadRow: function (file)
		{
			var fmaxilesize = document.getElementById('fmaxilesize').value;
			reply_indx++;
			if(file.name != "error")
			{
				if(file.message == "success")
				{
					var allowedSize = parseInt(fmaxilesize)*1024;
					if(parseInt(file.sizeinkb) <= parseInt(allowedSize)) {
						i++;
						/*if($('div [id^="'+csAtId+'jquerydiv"]').is(":visible")){
							i++;
						}else{
							i=1;	
						}*/
						document.getElementById('totfiles'+csAtId+'').value = i;
						var oncheck = "";
						var fname = file.filename.split("|");
						
						var filesize = file.sizeinkb;
						if(filesize >= 1024) {
							filesize = filesize/1024;
							filesize = Math.round(filesize*10)/10;
							filesize = filesize+" Mb";
						}
						else {
							filesize = Math.round(filesize*10)/10;
							filesize = filesize+" Kb";
						}
						
						var pageurl = document.getElementById('pageurl').value;
						
						if(parseInt(reply_total_files.length) == reply_indx){
						   gFileupload = 1;
						}
						
						return $('<tr><td style="color:#0683B8;" valign="top"><div id="'+csAtId+'jquerydiv'+i+'"><input type="checkbox" checked onClick="return removeFile(\''+csAtId+'jqueryfile'+i+'\',\''+csAtId+'jquerydiv'+i+'\');" style="cursor:pointer;"/>&nbsp;&nbsp;<a href="'+pageurl+'easycases/download/'+fname[0]+'" style="text-decoration:underline;position:relative;top:4px;">'+file.name+' ('+filesize+')</a><input type="hidden" name="data[Easycase][name][]" id="'+csAtId+'jqueryfile'+i+'" value="'+file.filename+'"/><\/div><\/td><\/tr>');
					}
					else {
						alert("Error uploading file. File size cannot be more then "+fmaxilesize+" Mb!");
						if(parseInt(reply_total_files.length) == reply_indx){
						    gFileupload = 1;
						 }
					}
				}
				else if(file.message == "exceed") {
					alert("Error uploading file.\nStorage usage exceeds by "+file.storageExceeds+" Mb!");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}
				else if(file.message == "size") {
					alert("Error uploading file. File size cannot be more then "+fmaxilesize+" Mb!");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}
				else if(file.message == "error") {
					alert("Error uploading file. Please try with another file");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}else if(file.message == "s3_error") {
					alert("Due to some network problem your file is not uploaded.Please try again after sometime.");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
				}
				else {
					alert("Sorry, "+file.message+" file type is not allowed!");
					if(parseInt(reply_total_files.length) == reply_indx){
					    gFileupload = 1;
					 }
					//\nAllowed are: txt, doc, docx, xls, xlsx, pdf, odt, ppt, jpeg, tif, gif, psd, jpg or png.
				}
			}
			else {
				alert("Error uploading file. Please try with another file");
				if(parseInt(reply_total_files.length) == reply_indx){
				    gFileupload = 1;
				 }
			}
		}
	});
}

function cancelReplyFile(file_name) {
    if(reply_total_files.length){
	reply_total_files.pop(file_name);
    }
    
    if(reply_total_files.length == 0){
	gFileupload = 1;
    }
}

// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function(){
  var cache = {};
 
  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
      cache[str] = cache[str] ||
        tmpl(document.getElementById(str).innerHTML) :
     
      // Generate a reusable function that will serve as a template
      // generator (and which will be cached).
      new Function("obj",
        "var p=[],print=function(){p.push.apply(p,arguments);};" +
       
        // Introduce the data as local variables using with(){}
        "with(obj){p.push('" +
       
        // Convert the template into pure JavaScript
        str
          .replace(/[\r\t\n]/g, " ")
          .split("<%").join("\t")
          .replace(/((^|%>)[^\t]*)'/g, "$1\r")
          .replace(/\t=(.*?)%>/g, "',$1,'")
          .split("\t").join("');")
          .split("%>").join("p.push('")
          .split("\r").join("\\'")
      + "');}return p.join('');");
   
    // Provide some basic currying to the user
    return data ? fn( data ) : fn;
  };
})();
/* end from case_details.ctp */
/* from case_reply.ctp */
function showeditpopup(id){
	if(!$('#editpopup'+id).is(":visible")){
		$('#editpopup'+id).show();
	}
	//$('#editpopup'+id).fadeIn('slow');
}
function hideeditpopup(id){
	if($('#editpopup'+id).is(":visible")){
		$('#editpopup'+id).hide();
	}
	//$('#editpopup'+id).fadeOut('slow');
}

function editmessage(obj,id,projid){
	var url = document.getElementById('pageurl').value;
	$('#editpopup'+id).css('background-image','url("'+ HTTP_ROOT +'img/images/del.gif")');
	$.post(url+"easycases/edit_reply",{'id':id,'reply_flag':1,projid:projid},function(res){
		$('#casereplytxt_id_'+id).hide();
		$('#editpopup'+id).css('background-image','url("'+ HTTP_ROOT +'img/images/edit_reply.png")');
		$('#casereplyid_'+id).html(res);
		var tiny_mce_url= url+'js/tinymce/tiny_mce.js';
		$('#edit_reply_txtbox'+id).tinymce({
			// Location of TinyMCE script
			theme : "advanced",
			plugins : "paste",
			theme_advanced_buttons1 : "bold,italic,strikethrough,underline,|,numlist,bullist,|,indent,outdent",
			theme_advanced_resizing : false,
			theme_advanced_statusbar_location : "",
			paste_text_sticky : true,
			gecko_spellcheck : true,
			paste_text_sticky_default : true,
			forced_root_block : false,
			cleanup_on_startup : true,
			width : "595px",
			height : "150px",
			oninit : function() {
				$('#edit_reply_txtbox'+id).tinymce().focus();
			 }
		});
		//alert(res);
	});
	/*if($(obj).parent("div").next(".popup_option").is(":visible")){ 
		$(".popup_option").hide();
		$(obj).parent("div").next(".popup_option").hide();
	}else{ 
		$(".popup_option").hide();
		$(obj).parent("div").next(".popup_option").show();
	}*/
}
function save_editedvalue_reply(caseno,id,proj_id){
	var url = document.getElementById('pageurl').value;
	var message = $('#edit_reply_txtbox'+id).val();
	$.post(url+"easycases/save_editedvalue",{'id':id,'message':message,'caseno':caseno,proj_id:proj_id},function(res){
		if(res==0){
			showTopErrSucc('error',"Message cann't be left blank"); 
		}else{
			$('#casereplytxt_id_'+id).show();
			$('#replytext_content'+id).html(message);
			$('#casereplyid_'+id).html('');	
			var msg = updated_subcap;
			$('#post_upd_txt'+id).html(msg);
			showTopErrSucc('success','Your reply edited successfully.'); 
		}
	});
}
function cancel_editor_reply(id){
	$('#casereplytxt_id_'+id).show();
	$('#casereplyid_'+id).html('');
	//$('#casereplyid_'+id).hide();	
}
/* end from case_reply.ctp */

/* Scroll window to the reply box for a case */
function gotoRCBox(id){
	try{
		$('html, body').animate({
			scrollTop: $("#reply_box"+id).offset().top-200
		}, 1000);
	}catch(e){}
}
function gotoCase(id){
	try{
		$('html, body').animate({
			scrollTop: $("#curRow"+id).offset().top-200
		}, 1000);
	}catch(e){}
}
