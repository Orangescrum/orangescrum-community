<%
var total_records = sqlcasedata.length;
var totdata = 0;
sqlcasedata = sqlcasedata.reverse();
var edit_cnt;
if(0){
	edit_cnt = 1;
}else{
	edit_cnt = total_records;
}
totdata = total_records+1;
var i=0;
var startSlno = totalReplies-total_records+1;
var sortOrder = getCookie('REPLY_SORT_ORDER');
if(sortOrder == 'ASC') {
	var startSlno = totalReplies;
}
if(typeof thrdStOrd == 'undefined' || !thrdStOrd) {
	if($('#thread_sortorder'+csAtId).length) {
		var thrdStOrd = $('#thread_sortorder'+csAtId).val();
	} else {
		var thrdStOrd = 'DESC';
	}
		
}

for(var repKey in sqlcasedata){
	var getdata = sqlcasedata[repKey];
	totdata--; i++;
	var caseDtId = getdata.Easycase.id;
	var caseDtUniqId = getdata.Easycase.uniq_id;
	var caseDtPid = getdata.Easycase.project_id;
	var caseDtUid = getdata.Easycase.user_id;
	var caseDtMsg = getdata.Easycase.message;
	var wrap_msg = getdata.Easycase.wrap_msg;
	var caseDtFormat = getdata.Easycase.format;
	var taskLegend = getdata.Easycase.legend;
	var userArr = getdata.Easycase.userArr;
	var by_name = userArr.User.name;
	var by_photo = userArr.User.photo;
	var short_name = userArr.User.short_name;
	var photo_exist = userArr.User.photo_exist;
	
	if(!photo_exist){ var by_photo = 'user.png'; }
	
	var shRplyEdt = 0;
	
	if((taskLegend == 1 || taskLegend == 2) && SES_ID == caseDtUid && caseDtMsg) {
		if((thrdStOrd=='ASC' && i==1) || (thrdStOrd=='DESC' && edit_cnt== i)) {
			shRplyEdt = 1;
		}
	} %>
<div class="col-lg-12" id="rep<%= totdata %>"> 
	<div class="details_task_block">
		<div class="details_task_head">
			<div class="fl reply_task_count"><span style="padding:2px 4px;line-height:16px;background:#989898"><%= startSlno %></span></div>
			<div class="fl"><img class="lazy round_profile_img rep_bdr" data-original="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<%= by_photo %>&sizex=35&sizey=35&quality=100" title="<%= by_name %>" width="35" height="35" /></div>
			<div class="fl">
				<span><b class="ttc"><%= formatText(shortLength(getdata.Easycase.usrName,15)) %></b></span>
				<div class="fnt999"><%= getdata.Easycase.rply_dt %></div>
			</div>
			<div class="fr">
			<% if(is_active){ %><a href="javascript:void(0);" class="link_repto_task" data-csatid="<%= csAtId %>"><div class="reply_to_task fr"></div></a><% } %>
			<table cellpadding="0" cellspacing="0" class="fr task_status">
				<tr>
					<% if(getdata.Easycase.sts){ %>
					<td>Status: <%= getdata.Easycase.sts %></td>
					<% } %>
					<% if(getdata.Easycase.asgnTo){ %>
					<td>Assigned To: <b class="ttc"><%= shortLength(getdata.Easycase.asgnTo,10) %></b></td>
					<% } %>
					
					<% if(getdata.Easycase.completed){ %>
					<td>Completed: <b><%= getdata.Easycase.completed %>%</b></td>
					<% } %>
					
					<% if(getdata.Easycase.hourspent){ %>
					<td>Hour(s) Spent: <b><%= getdata.Easycase.hourspent %></b></td>
					<% } %>
				</tr>
			</table>
			</div>
		</div>
		<div class="details_task_desc wrapword">
			<div id="casereplytxt_id_<%= caseDtId %>">
				<span id="replytext_content<%= caseDtId %>"><%= wrap_msg %></span>
				<% if(shRplyEdt==1){ %>
					<div title="Edit" onclick="editmessage(this,<%= caseDtId %>,<%= caseDtPid %>);" id="editpopup<%= caseDtId %>" class="fr rep_edit"><i class="icon-edit fr"></i></div>
				<% } %>
				<%= getdata.Easycase.replyCap %>
			</div>
			<div id="casereplyid_<%= caseDtId %>"></div>
			<% if(caseDtFormat != 2){
			var filesArr = getdata.Easycase.rply_files;
			if(filesArr.length){ %>
			<br/><br/>
			<div class="case_clip"><div></div></div>
				<% var fc = 0;
				var imgaes = ""; var caseFileName = "";
				for(var fkey in filesArr){
					var getFiles = filesArr[fkey]; %>
				<%	caseFileName = getFiles.CaseFile.file;
					downloadurl = getFiles.CaseFile.downloadurl;
					if(getFiles.CaseFile.is_exist) {
					fc++; %>
					<div class="attch_file_bg fl">
						<div class="fl tsk_ficn"><div class="tsk_fl <%= easycase.imageTypeIcon(getFiles.CaseFile.format_file) %>_file"></div></div>
						<div class="fl">
							<% if(downloadurl) { %>
							<span><a href="<%= downloadurl %>" target="_blank" alt="<%= caseFileName %>" title="<%= caseFileName %>"><%= shortLength(caseFileName,37) %></a></span>
							<% } else { %>
							<span><%= shortLength(caseFileName,37) %></span></span>
							<div class="fnt999">
							<% if(getFiles.CaseFile.is_ImgFileExt){ %>
								(<%= getFiles.CaseFile.file_size %>)&nbsp;&nbsp;
								<span class="gallery">
								    <a href="<%= getFiles.CaseFile.fileurl %>" target="_blank" alt="<%= caseFileName %>" title="<%= caseFileName %>" rel="prettyPhoto[]">View</a>
								</span>
								&nbsp;&nbsp;
								<a href="<?php echo HTTP_ROOT; ?>easycases/download/<%= caseFileName %>" alt="<%= caseFileName %>" title="<%= caseFileName %>">Download</a>
							<% } else{ %>
								(<%= getFiles.CaseFile.file_size %>)&nbsp;
								<a href="<?php echo HTTP_ROOT; ?>easycases/download/<%= caseFileName %>" alt="<%= caseFileName %>" title="<%= caseFileName %>">Download</a>
							<% } %>
							</div>
							<% } %>
						</div>
					</div>
					<% if(fc%2==0) { %>
					<div class="cb"></div>
					<% } %>
					<% } %>
				<% } %>
				<div class="cb"></div>
			<% 	}
			} %>
		</div>
	</div>
</div> 	
<div class="cb"></div>
<% if(sortOrder == 'ASC') {
	startSlno--;
} else {
	startSlno++;
}
} %>
<input type="hidden" value="<%= total_records %>" id="totdata<%= csAtId %>"/> 