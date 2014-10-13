<table width="100%" class="tsk_tbl compactview_tbl">
	<tr style="" class="tab_tr">
		<td width="18%" class="all_td">
			<div class="dropdown fl">
				<input type="checkbox" class="fl chkAllTsk" id="chkAllTsk" />
				<div class="all_chk"></div>
				<ul class="dropdown-menu" id="dropdown_menu_chk">
					<li><a href="javascript:void(0);" onclick="multipleCaseAction(<%= '\'caseStart\'' %>)"><div class="act_icon act_start_task fl" title="Start"></div>Start</a></li>
					<li><a href="javascript:void(0);" onclick="multipleCaseAction(<%= '\'caseResolve\'' %>)"><div class="act_icon act_resolve_task fl" title="Resolve"></div>Resolve</a></li>
					<li><a href="javascript:void(0);" onclick="multipleCaseAction(<%= '\'caseId\'' %>)"><div class="act_icon act_close_task fl" title="Close"></div>Close</a></li>
					<?php if(SES_TYPE == 1 || SES_TYPE == 2){?>
						<li id="mvTaskToProj"><a href="javascript:void(0);" onclick="mvtoProject(<%= '\' \'' %>,<%= '\' \'' %>,<%= '\'movetop\'' %>)"><div class="act_icon pro_mov fl" title="Move to roject"></div>Move to project</a></li>
					<?php } ?>
				</ul>
			</div>
		</td>
		<td class="task_cn">
			<a href="javascript:void(0);" title="Task#" onclick="ajaxSorting(<%= '\'caseno\', ' + caseCount + ', this' %>);">
				<div class="fl">Task#</div><div class="tsk_sort fl <% if(typeof csNum != 'undefined' && csNum != "") { %>tsk_<%= csNum %><% } %>"></div>
			</a>
		</td>
		<td class="task_wd">
			<a href="javascript:void(0);" title="Title" onclick="ajaxSorting(<%= '\'title\', ' + caseCount + ', this' %>);">
				<div class="fl">Title</div><div class="tsk_sort fl <% if(typeof csTtl != 'undefined' && csTtl != "") { %>tsk_<%= csTtl %><% } %>"></div>
			</a>
		</td>
		<td class="assign_wd_td">
			<a href="javascript:void(0);" title="Assigned to" onclick="ajaxSorting(<%= '\'caseAtsort\', ' + caseCount + ', this' %>);">
				<div class="fl">Assigned to</div><div class="tsk_sort fl <% if(typeof csAtSrt != 'undefined' && csAtSrt != "") { %>tsk_<%= csAtSrt %><% } %>"></div>
			</a>
		</td>
		<td class="tsk_due_dt">
			<a href="javascript:void(0);" title="Due Date" onclick="ajaxSorting(<%= '\'duedate\', ' + caseCount + ', this' %>);">
				<div class="fl">Due Date</div><div class="tsk_sort fl <% if(typeof csDuDt != 'undefined' && csDuDt != "") { %>tsk_<%= csDuDt %><% } %>"></div>
			</a>
		</td>
	</tr>
<%
var count = 0;
var totids = "";
var openId = "";

var pgCaseCnt = caseAll?countJS(caseAll):0;
if(caseCount && caseCount != 0){
	var count=0;
	var caseNo = "";
	var chkMstone = "";
	var caseLegend = "";
	var totids = "";
	var projectName ='';var projectUniqid='';
	for(var caseKey in caseAll){
		var getdata = caseAll[caseKey];
		count++;
		var caseAutoId = getdata.Easycase.id;
		var caseUniqId = getdata.Easycase.uniq_id;
		var caseNo = getdata.Easycase.case_no;
		var caseUserId = getdata.Easycase.user_id;
		var caseTypeId = getdata.Easycase.type_id;
		var projId = getdata.Easycase.project_id;
		var caseLegend = getdata.Easycase.legend;
		var casePriority = getdata.Easycase.priority;
		var caseFormat = getdata.Easycase.format;
		var caseTitle = getdata.Easycase.title;
		var caseAssgnUid = getdata.Easycase.assign_to;
		var getTotRep = 0;
		if(getdata.Easycase.case_count && getdata.Easycase.case_count!=0) {		
			getTotRep = getdata.Easycase.case_count;
		}
		
		if(caseUrl == caseUniqId) {
			openId = count;
		}
		
		var chkDat = 0;
		
		if(projUniq=='all' && (typeof getdata.Easycase.pjname !='undefined')){
			projectName = getdata.Easycase.pjname;
			projectUniqid = getdata.Easycase.pjUniqid;
		}else if(projUniq!='all'){
			projectName = getdata.Easycase.pjname;
			projectUniqid = getdata.Easycase.pjUniqid;
		}
		if(projUniq=='all') { %>
	<tr>
		<td colspan="5" align="left" class="tkt_pjname"><div class="<% if(count!=1) {%>y_day<% } %>"><%= getdata.Easycase.pjname %></div></td>
	</tr>
<% 		} if(getdata.Easycase.newActuldt && getdata.Easycase.newActuldt!=0) {%>
	<tr>
		<td colspan="5" align="left" class="curr_day"><div class="<% if(count!=1 && !getdata.Easycase.pjname) {%>y_day<% } %>"><%= getdata.Easycase.newActuldt %></div></td>
	</tr>
<% 		}
		var bgcol = "#F2F2F2";
		if(chkDat == 1) { bgcol = "#FFF"; }
		var borderBottom = "";
		if(pgCaseCnt == count) { borderBottom = "border-bottom:1px solid #F2F2F2;"; } %>
	<tr class="tr_all" id="curRow<%= caseAutoId %>">
		<td class="pr_<%= easycase.getPriority(casePriority) %>" valign="top">
			<% if(caseLegend != 3 && caseTypeId != 10) { %>
			<input type="checkbox" style="cursor:pointer" id="actionChk<%= count %>" value="<%= caseAutoId + '|' + caseNo + '|' + caseUniqId %>" class="fl mglt chkOneTsk">
			<% } else if(caseTypeId != 10) { %>
			<input type="checkbox" id="actionChk<%= count %>" checked="checked" value="<%= caseAutoId + '|' + caseNo + '|closed' %>" disabled="disabled" class="fl mglt chkOneTsk">
			<% } else { %>
			<input type="checkbox" id="actionChk<%= count %>" checked="checked" value="<%= caseAutoId + '|' + caseNo + '|update' %>" disabled="disabled" class="fl mglt chkOneTsk">
			<% } %>
			<input type="hidden" id="actionCls<%= count %>" value="<%= caseLegend %>" disabled="disabled" size="2"/>
			<div class="dropdown fl">
			<div class="sett" data-toggle="dropdown"></div>
			<ul class="dropdown-menu sett_dropdown-caret">
					<li class="pop_arrow_new"></li>
					<% var caseFlag="";
					if(caseLegend == 1 && caseTypeId!= 10) { caseFlag=1; }
					if(getdata.Easycase.isactive == 1) { %>
					<li onclick="startCase(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + caseUniqId + '\'' %>);" id="start<%= caseAutoId %>" style=" <% if(caseFlag == "1"){ %>display:block<% } else { %>display:none<% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_start_task fl" title="Start"></div>Start</a>
					</li>
					<% }
					if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && caseTypeId!= 10) { caseFlag=2; }
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="caseResolve(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + caseUniqId + '\'' %>);" id="resolve<%= caseAutoId %>" style=" <% if(caseFlag == 2){ %> display:block <% } else { %> display:none <% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_resolve_task fl" title="Resolve"></div>Resolve</a>
					</li>
					<% }
					if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4 || caseLegend == 5) && caseTypeId != 10) { caseFlag=5; }
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="setCloseCase(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + caseUniqId + '\'' %>);" id="close<%= caseAutoId %>" style=" <% if(caseFlag == 5) { %>display:block <% } else { %>display:none<% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_close_task fl" title="Close"></div>Close</a>
					</li>
					<% } if(caseFlag == 5 || caseFlag==2) { %>
					<li class="divider"></li>
					<% } %>
					<% if(caseLegend == 3) { caseFlag= 7; } else { caseFlag= 8; }
					if(getdata.Easycase.isactive == 1){ %>
					<li id="act_reply<%= count %>" data-task="<%= caseUniqId %>">
						<a href="javascript:void(0);" id="reopen<%= caseAutoId %>" style="<% if(caseFlag == 7){ %>display:block <% } else { %>display:none<% } %>"><div class="act_icon act_reply_task fl" title="Re-open"></div>Re-open</a>
						<a href="javascript:void(0);" id="reply<%= caseAutoId %>" style="<% if(caseFlag == 8){ %>display:block <% } else { %>display:none<% } %>"><div class="act_icon act_reply_task fl" title="Reply"></div>Reply</a>
					</li>
					<% }
					if( SES_ID == caseUserId) { caseFlag=3; }
					if(getdata.Easycase.isactive == 1 && getdata.Easycase.reply_cnt == 0 && caseLegend == 1){ %>
					<li onclick="editask(<%= '\''+ caseUniqId+'\',\''+projectUniqid+'\',\''+projectName+'\'' %>);" id="edit<%= caseAutoId %>" style=" <% if(caseFlag == 3 || SES_TYPE == 1 || SES_TYPE == 2){ %>display:block <% } else { %>display:none<% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_edit_task fl" title="Edit"></div>Edit</a>
					</li>
					<% }
					if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && caseTypeId!= 10) { caseFlag=2; }
					if((SES_TYPE == 1 || SES_TYPE == 2) || ((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) &&  (SES_ID == caseUserId))){
					%>
					<li data-prjid="<%= projId %>" data-caseid="<%= caseAutoId %>" data-caseno="<%= caseNo %>" onclick="mvtoProject(<%= '\'' + count + '\'' %>,this)" id="mv_prj<%= caseAutoId %>" style=" ">
					    <a href="javascript:void(0);"><div class="act_icon pro_mov fl" title="Move to Project"></div>Move to Project</a>
					</li>
					<% } 
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="moveTask(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'\'' %>,<%= '\'' + projId + '\'' %>);" id="moveTask<%= caseAutoId %>" style=" <% if(caseFlag == 2){ %> display:block <% } else { %> display:none <% } %>">
						<a href="javascript:void(0);"><div class="act_icon task_move_mlst fl" title="Move Task To Milestone"></div>Move to Milestone</a>
					</li>
					<% } %>
					<li class="divider"></li>
					<% if(getdata.Easycase.isactive == 1){
					if(caseMenuFilters == "milestone" && (SES_TYPE == 1 || SES_TYPE == 2 || SES_ID == getdata.Easycase.Em_user_id)) {
					caseFlag = "remove";
					%>
					<li onclick="removeThisCase(<%= '\'' + count + '\'' %>,<%= '\'' + getdata.Easycase.Emid + '\'' %>, <%= '\'' + caseAutoId + '\'' %>, <%= '\'' + getdata.Easycase.Em_milestone_id + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + caseUserId + '\'' %>);" id="rmv<%= caseAutoId %>" style="<% if(caseFlag == "remove"){ %>display:block<% } else { %>display:none<% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_rmv fl" title="Remove Task"></div>Remove Task</a>
					</li>
					<%
					}
					}
					if(SES_TYPE == 1 || SES_TYPE == 2 || ((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && ( SES_ID == caseUserId))) { caseFlag = "archive"; }
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="archiveCase(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + projId + '\'' %>, <%= '\'t_' + caseUniqId + '\'' %>);" id="arch<%= caseAutoId %>" style="<% if(caseFlag == "archive"){ %>display:block<% } else { %>display:none<% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_arcv_task fl" title="Archive"></div>Archive</a>
					</li>
					<% }
					if(SES_TYPE == 1 || SES_TYPE == 2 || (caseLegend == 1  && SES_ID == caseUserId)) { caseFlag = "delete"; }
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="deleteCase(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + projId + '\'' %>, <%= '\'t_' + caseUniqId + '\'' %>);" id="arch<%= caseAutoId %>" style="<% if(caseFlag == "delete"){ %>display:block<% } else { %>display:none<% } %>">
						<a href="javascript:void(0);"><div class="act_icon act_del_task fl" title="Delete"></div>Delete</a>
					</li>
					<% } %>
				</ul>
			</div>
			<div class="dropdown fl">
				<div id="showUpdStatus<%= caseAutoId %>" class="type_<%= getdata.Easycase.csTdTyp[0] %> <% if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && getdata.Easycase.isactive == 1){ %>clsptr<% } %> <% if($.inArray(getdata.Easycase.csTdTyp[0], ['dev', 'bug', 'upd']) == -1) { %>opcty4<% } %>" title="<%= getdata.Easycase.csTdTyp[1] %>" data-toggle="dropdown"></div>
				<span id="typlod<%= caseAutoId %>" class="type_loader">
					<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
				</span>
				 <% if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && getdata.Easycase.isactive == 1){ %>
				<ul class="dropdown-menu type_dropdown-caret" style="width:175px;">
					<li class="pop_arrow_new"></li>
					<%
					for(var k in GLOBALS_TYPE) {
						var v = GLOBALS_TYPE[k];
						var t = v.Type.id;
						var t1 = v.Type.short_name;
						var t2 = v.Type.name;
					%>
						<li onclick="changeCaseType(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>); changestatus(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + t + '\'' %>, <%= '\'' + t1 + '\'' %>, <%= '\'' + t2 + '\'' %>, <%= '\'' + caseUniqId + '\'' %>)">
							<a href="javascript:void(0);"><div class="task_types_<%= t1 %> fl"></div><%= t2 %></a>
						</li>
					<% } %>
				</ul>
				<% } %>
			</div>
			<div id="csStsRep<%= count %>" class="fl"><%= easycase.getColorStatus(getdata.Easycase.type_id, getdata.Easycase.legend) %></div>
		</td>
		<td valign="top" style="padding-right:20px;text-align:right"><%= caseNo %></td>
		<td class="title_det_wd">
			<div class="fl title_wd">
				<div id="titlehtml<%= count %>" data-task="<%= caseUniqId %>" class="fl case-title <% if(getdata.Easycase.type_id!=10 && getdata.Easycase.legend==3) { %>closed_tsk<% } %>"> 
					<div class="case_title wrapword task_title_ipad"><%= shortLength(formatText(ucfirst(caseTitle)),50) %>&nbsp;</div>
				</div>
				<div class="fl fnt999">
					<!--span id="stsdisp<%= caseAutoId %>"><% if(getTotRep && getTotRep!=0) { %>updated<% } else { %>created<% } %></span--> by <span <% if(getdata.Easycase.usrName) { %> original-title="<%= getdata.Easycase.usrName %>" <% } %>><%= getdata.Easycase.usrShortName %></span>
					<% if(getdata.Easycase.updtedCapDt.indexOf('Today')==-1 && getdata.Easycase.updtedCapDt.indexOf('Y\'day')==-1) { %>on<% } %>
					<span id="timedis<%= count %>">
						<%= getdata.Easycase.updtedCapDt %>.
					</span>
				</div>
				<div class="fl fnt999" style="<% if(!getTotRep || getTotRep==0) { %>display:none<% } %>">
					<div id="repno<%= count %>" class="fl bblecnt"></div>
					(<% if(getTotRep && getTotRep!=0) { %><%= getTotRep %><% } %>)
				</div>
			</div>
			<div class="att_fl fr" <% if(getdata.Easycase.format != 1 && getdata.Easycase.format != 3) { %> style="display:none;" id="fileattch<%= count %>"<% } %>></div>
			<div class="cb"></div>
<!--			<div class="fnt999">
				<div class="fl">
					<span id="stsdisp<%= caseAutoId %>"><% if(getTotRep && getTotRep!=0) { %>updated<% } else { %>created<% } %></span> by <span <% if(getdata.Easycase.usrName) { %> original-title="<%= getdata.Easycase.usrName %>" <% } %>><%= getdata.Easycase.usrShortName %></span>
					<% if(getdata.Easycase.updtedCapDt.indexOf('Today')==-1 && getdata.Easycase.updtedCapDt.indexOf('Y\'day')==-1) { %>on<% } %>
					<span id="timedis<%= count %>">
						<%= getdata.Easycase.updtedCapDt %>.
					</span>
				</div>
				<div class="fl" style="<% if(!getTotRep || getTotRep==0) { %>display:none<% } %>">
					<div id="repno<%= count %>" class="fl bblecnt"></div>
					(<% if(getTotRep && getTotRep!=0) { %><%= getTotRep %><% } %>)
				</div>
			</div>-->
		</td>
		<td valign="top">
			<div class="dropdown fl">
				<% if((projUniq != 'all') && (caseLegend == 1 || caseLegend == 2 || caseLegend == 4)){ %>
					<span id="showUpdAssign<%= caseAutoId %>" data-toggle="dropdown" title="edit Assign to" class="clsptr" onclick="displayAssignToMem(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + projUniq + '\'' %>,<%= '\'' + caseAssgnUid + '\'' %>,<%= '\'' + caseUniqId + '\'' %>)"><%= getdata.Easycase.asgnShortName %><span class="due_dt_icn"></span></span>
				<% } else { %>
					<span id="showUpdAssign<%= caseAutoId %>" style="cursor:text;text-decoration:none;color:#666666;"><%= getdata.Easycase.asgnShortName %></span>
				<% } %>
				<% if((projUniq != 'all') && (caseLegend == 1 || caseLegend == 2 || caseLegend == 4)){ %>
				<span id="asgnlod<%= caseAutoId %>" class="asgn_loader">
					<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
				</span>
				<% } %>
				<ul class="dropdown-menu asgn_dropdown-caret" id="showAsgnToMem<%= caseAutoId %>">
					<li class="pop_arrow_new"></li>
					<li class="text-centre"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" id="assgnload<%= caseAutoId %>" /></li>
				</ul>
			</div>
		</td>
		<td class="fnt12" valign="top">
			<div class="dropdown fl">
				
				<% if(getdata.Easycase.isactive == 1 && caseTypeId != 10){ %>
				<div class="fl" <% if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && caseTypeId != 10){ %> data-toggle="dropdown" original-title="edit Due Date" style="cursor:pointer"<% } %>>
					<span id="showUpdDueDate<%= caseAutoId %>" title="<%= getdata.Easycase.csDuDtFmtT %>">
						<%= getdata.Easycase.csDuDtFmt %>
						<% if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && caseTypeId != 10){ %>
						<span class="due_dt_icn"></span>
						<% } %>
					</span>
					<span id="datelod<%= caseAutoId %>" class="asgn_loader">
						<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
					</span>
				</div>
				<% } %>
				<ul class="dropdown-menu dudt_dropdown-caret">
					<li class="pop_arrow_new"></li>
					<li><a href="javascript:void(0);" onclick="changeCaseDuedate(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>);changeDueDate(<%= '\'' + caseAutoId + '\', \'00/00/0000\', \'No Due Date\', \'' + caseUniqId + '\'' %>)">No Due Date</a></li>
					<li><a href="javascript:void(0);" onclick="changeCaseDuedate(<%= '\'' + caseAutoId + '\', \'' + caseNo + '\'' %>); changeDueDate(<%= '\'' + caseAutoId + '\', \'' + mdyCurCrtd + '\', \'Today\', \'' + caseUniqId + '\'' %>)">Today</a></li>
					<li><a href="javascript:void(0);" onclick="changeCaseDuedate(<%= '\'' + caseAutoId + '\', \'' + caseNo + '\'' %>); changeDueDate(<%= '\'' + caseAutoId + '\', \'' + mdyTomorrow + '\', \'Tomorrow\', \'' + caseUniqId + '\'' %>)">Tomorrow</a></li>
					<li><a href="javascript:void(0);" onclick="changeCaseDuedate(<%= '\'' + caseAutoId + '\', \'' + caseNo + '\'' %>); changeDueDate(<%= '\'' + caseAutoId + '\', \'' + mdyMonday + '\', \'Next Monday\', \'' + caseUniqId + '\'' %>)">Next Monday</a></li>
					<li><a href="javascript:void(0);" onclick="changeCaseDuedate(<%= '\'' + caseAutoId + '\', \'' + caseNo + '\'' %>); changeDueDate(<%= '\'' + caseAutoId + '\', \'' + mdyFriday + '\', \'This Friday\', \'' + caseUniqId + '\'' %>)">This Friday</a></li>
					<li>
						<a href="javascript:void(0);" class="cstm-dt-option" data-csatid="<%= caseAutoId %>">
							<input value="" type="hidden" id="set_due_date_<%= caseAutoId %>" class="set_due_date" title="Custom Date" style=""/>
							<span style="position:relative;top:2px;cursor:text;">Custom&nbsp;Date</span>
						</a>
					</li>
				</ul>
			</div>
		</td>
	</tr>
<%
		totids += caseAutoId + "|";
	}
}
if(!caseCount || caseCount==0){
var case_type = $("#caseMenuFilters").val(); %>
	<tr>
		<td colspan="5" align="center" style="padding:10px 0;color:#FF0000">
			<% if(case_type == 'cases' || case_type == ''){ %>
				No tasks
			<% }else if(case_type == 'assigntome'){ %>
				No tasks for me
			<% }else if(case_type == 'overdue'){ %>
				No tasks as overdue
			<% }else if(case_type == 'delegateto'){ %>
				No tasks delegated
			<% }else if(case_type == 'highpriority'){ %>
				No high priority tasks
			<% } %>
		</td>
	</tr>
<% } %>
</table>
<% $("#task_paginate").html('');
if(caseCount && caseCount!=0) {
	var pageVars = {pgShLbl:pgShLbl,csPage:csPage,page_limit:page_limit,caseCount:caseCount};
	$("#task_paginate").html(tmpl("paginate_tmpl", pageVars));
} %>
<input type="hidden" name="hid_cs" id="hid_cs" value="<%= count %>"/>
<input type="hidden" name="totid" id="totid" value="<%= totids %>"/>
<input type="hidden" name="chkID" id="chkID" value=""/>
<input type="hidden" name="slctcaseid" id="slctcaseid" value=""/>
<input type="hidden" id="getcasecount" value="<%= caseCount %>" readonly="true"/>
<input type="hidden" id="openId" value="<%= openId %>" />
<input type="hidden" id="email_arr" value=<%= '\'' + ((typeof email_arr != 'undefined' && email_arr)?email_arr:'') + '\''  %>  />
<input type="hidden" id="curr_sel_project_id" value="<% if(projUniq!='all'){%><%= projId %> <% } %>"  />