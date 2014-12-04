<style type="text/css">
.pr_low{border: 1px solid #EFF4CC;background:#FCFEE6}
.pr_medium{border: 1px solid #D7E0CF;background:#F9FFF5}
.pr_high{border: 1px solid #EECACC; background:#FEF2F2}
.label{font-weight:normal;}
</style>
<% if(!error){ %>
<% $('#mlist_crt_mlstbtn').show(); %>
<div class="kanban-main">
	<%
        var rec_ids=new Array();
	var clscnt=1;
	var count = 0;
	var totids = "";
	var openId = "";
	var pgCaseCnt = countJS(caseAll);
	var caseCount = countJS(caseAll);
	if(caseCount && caseCount != 0){
		var count=0;
		var caseNo = "";
		var chkMstone = "";var milestonetitle ='';
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
				projectName =	getdata.Easycase.pjname;
				projectUniqid = getdata.Easycase.pjUniqid;
			}else if(projUniq!='all'){
				projectName =	getdata.Easycase.pjname;
				projectUniqid = getdata.Easycase.pjUniqid;
			}
			var chkDat = 0;
			if(chkMstone != getdata.Easycase.Mid){
			milestonetitle = str_replace('"', "", str_replace("'","", formatText(getdata.Easycase.Mtitle)));
                        if(count>1){%>
				</div>	
					</div>
			<%	}
				chkDat = 1;
				var days = getdata.Easycase.days_diff;
			%>
				<div class="fl kanban-child" id="milestone_<%= getdata.Easycase.Mid %>">
                                   
                                    <% rec_ids.push(getdata.Easycase.Mid); %>
				<div class="kbhead kbhead_<%= getdata.Easycase.Mid %>">
					<div id="edit-link_<%= getdata.Easycase.Mid %>">
					<div class=" fl"  id="main-title-holder_<%= getdata.Easycase.Mid %>"><a href="<?php echo HTTP_ROOT.'dashboard#kanban/';?><%= getdata.Easycase.Muinq_id %>"><%= shortLength(ucfirst(getdata.Easycase.Mtitle), 28) %></a></div>	
					<div class="fl">
						<a href="javascript:void(0);" onclick="quickEditMilestone('<%= getdata.Easycase.Mid %>');"><div class="edit_mile"></div></a>						
					</div>
					<div class="dropdown fl">
						<div class="sett" data-toggle="dropdown"></div>
						<ul class="dropdown-menu sett_dropdown-caret">
							<li class="pop_arrow_new"></li>
							 <% if(isActive!=0){ %>
							 <li  onClick="creatask(<%= getdata.Easycase.Mid %>)" >
								<a href="javascript:void(0);" class="mnsm">
									<div class="ct_icon act_create_task fl"></div>
									<div class="fl">Create New Task</div>
									<div class="cb"></div>
								</a>
							</li>
								<li  onClick="addTaskToMilestone(<%= '\'\',\''+ getdata.Easycase.Mid + '\'' %>,<%= '\'' + getdata.Easycase.project_id + '\'' %>,<%= '\'' + count + '\'' %>)" >
								<a href="javascript:void(0);" class="mnsm">
									<div class="ct_icon icon-add-task-milston fl"></div>
									<div class="fl mntxt">Add Task</div>
									<div class="cb"></div>
								</a>
							</li>
							<li onclick="addEditMilestone(<%= '\'\',\'' + getdata.Easycase.Muinq_id + '\'' %>,<%= '\'' + getdata.Easycase.Mid + '\'' %>,<%= '\'' + milestonetitle + '\',1' %>)" class="makeHover">
								<a href="javascript:void(0)">
									<div class="ct_icon act_edit_task fl"></div>
									<div class="fl">Edit</div>
									<div class="cb"></div>
								</a>
							</li>
                                                        <% } %>
							<li onClick="delMilestone(<%= '\'\',\'' + milestonetitle + '\'' %>,<%= '\'' + getdata.Easycase.Muinq_id + '\'' %>);" class="makeHover" >
								<a href="javascript:void(0);" >
									<div class="act_icon act_del_task fl"></div>
									<div class="fl deltmntxt">Delete</div>
									<div class="cb"></div>
								</a>
							</li>
                                                       
							 <% if(isActive!=0){ %>
								<li onclick="milestoneArchive(<%= '\'\',\'' + getdata.Easycase.Muinq_id + '\'' %>, <%= '\'' + milestonetitle + '\'' %>);"  >
									<a href="javascript:jsVoid();">
										<div class="ct_icon mt_completed fl"></div>
										<div class="fl cmplmntxt">Complete</div>
										<div class="cb"></div>
									</a>
								</li>
							<%  }else{ %>
								<li onclick="milestoneRestore(<%= '\'\',\'' + getdata.Easycase.Muinq_id + '\'' %>, <%= '\'' + milestonetitle + '\'' %>);"  >
									<a href="javascript:jsVoid();">
										<div class="ct_icon mt_completed fl"></div>
										<div class="fl cmplmntxt">Restore</div>
										<div class="cb"></div>
									</a>
								</li>
							<%  } %>
                                                        
						</ul>
					</div>
					<div class="fr"><%= getdata.Easycase.totalCs %></div>
					</div>
					<div class="fl" id="edit-save_<%= getdata.Easycase.Mid %>" style="display:none;width:100%;">
					    <input type="text" class="form-control" id="milstone_edit_<%= getdata.Easycase.Mid %>" value="<%= getdata.Easycase.Mtitle %>" onblur="return saveMilesatoneTitle(<%= getdata.Easycase.Mid %>);" />
					</div>
					<div class="cb"></div>
					<div class="fl">
						<span class="mlst-dt">
						<img src="<?php echo HTTP_ROOT."img/images/clock.png"; ?>">&nbsp;&nbsp;
						<% if(days == 0) { %>
							<b style="color:green">Today</b>
						<% } else { %>
							<font color="#565656"><%= getdata.Easycase.mlstDT %></font>&nbsp;
						
                                                <% if(isActive!=0){
                                                if(getdata.Easycase.intEndDate < intCurCreated) { %>
								<font color="red">
						<% 		if(days > 1) { %>
									(Late by <%= days %> days)
						<% 		} else { %>
									(Late by <%= days %> day)
						<% 		} %>
								</font>
						<% } else { %>
								<font color="green">
						<% 		if(days > 1) { %>
									(Coming up in <%= days %> days)
						<% 		} else { %>
									(Coming up in <%= days %> day)
						<% 		} %>
								</font>
						<% }
						} }%>
						</span>
					</div>
					<div class="cb"></div>
					<div class=" fl imprv_bar col-lg-8" title="<%= getdata.Easycase.totalClosedCs + ' out of ' + getdata.Easycase.totalCs + ' tasks are closed' %>" rel="tooltip">
						<div style="width:<%= getdata.Easycase.mlstFill %>%;" class="cmpl_green"></div>
					</div>
					<div class="fl clsd-txt"><%= getdata.Easycase.mlstFill %>% closed</div>
					<div class="cb"></div>
					<!--<% if(projUniq=='all'){ %>
					<div class="fl mlst_prj_nm">&nbsp;Project:&nbsp; </div>
					<div class="pjname-cls fl"><%= projectName %></div>
					<% } %>-->
					<div class="cb"></div>
				</div>
		<div class="kanban_content custom_scroll">
		<% } %>	
		<div class="pr_<%= easycase.getPriority(casePriority) %> kbtask_div" title="<% if(getTotRep && getTotRep!=0) { %>Updated<% } else { %>Created<% } %> by <%= getdata.Easycase.usrShortName %> <% if(getdata.Easycase.updtedCapDt.indexOf('Today')==-1 && getdata.Easycase.updtedCapDt.indexOf('Y\'day')==-1) { %>on<% } %> <%= getdata.Easycase.updtedCapDt %> ">
			<!--<div class="fl" rel="tooltip" title="<% if(getTotRep && getTotRep!=0) { %>Updated<% } else { %>Created<% } %> by <%= getdata.Easycase.usrShortName %> <% if(getdata.Easycase.updtedCapDt.indexOf('Today')==-1 && getdata.Easycase.updtedCapDt.indexOf('Y\'day')==-1) { %>on<% } %> <%= getdata.Easycase.updtedCapDt %> "> <%= getdata.Easycase.proImage %></div> -->	
			<div class="fl title_wd">
				<div id="titlehtml<%= count %>" data-task="<%= caseUniqId %>" class="fl case-title <% if(getdata.Easycase.type_id!=10 && getdata.Easycase.legend==3) { %>closed_tsk<% } %>"> 
                	<span class="case_title wrapword">#<%= caseNo %>: <%= caseTitle %>
                    &nbsp;
                    <% if(getdata.Easycase.csDuDtFmt.search("Set Due Dt") >= 0 || getdata.Easycase.csDuDtFmt=='No Due Date'){%><% } else { %><span class="dt-icon" title="<%= getdata.Easycase.csDuDtFmtT %>"><a href=""></a></span><% } %>
                    <% if(getTotRep && getTotRep!=0) { %><span class="bblecnt2"></span><span class="count_knbn" style="top:-1px;position: relative;font-size: 11px !important;padding-left: 2px;"><%= getTotRep %><% } %></span></span>
                </div>
				<!--<div class="fr" <% if(!getTotRep || getTotRep==0) { %> style="display:none" <% } %>>
				<div id="repno<%= count %>" class="fl bblecnt2"></div>
				<span class="count_knbn"> &nbsp;<% if(getTotRep && getTotRep!=0) { %><%= getTotRep %><% } %></span>
			</div>-->
			</div>
			<div class="cb"></div>

			<div class="cb"></div>
            <div class="fl">
            	<%= easycase.getStatus(getdata.Easycase.type_id, getdata.Easycase.legend) %>
            </div>
            <% if(getdata.Easycase.asgnShortName && getdata.Easycase.asgnShortName.search("me") == -1) { %>
			<div class="fr" title="Assigned to <%= getdata.Easycase.asgnShortName %>" style="float:right">
                
                <div class="fl img-cls-assn1"></div>
				<div class="fnt999 fl">&nbsp;</div>
				<div class="fl" >
					<% if((projUniq != 'all') && (caseLegend == 1 || caseLegend == 2 || caseLegend == 4)){ %>
						<span id="showUpdAssign<%= caseAutoId %>" class="clsptr fnt13" style="font-size:11px;cursor:text;" onclick="displayAssignToMem(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + projUniq + '\'' %>,<%= '\'' + caseAssgnUid + '\'' %>,<%= '\'' + caseUniqId + '\'' %>)"><%= getdata.Easycase.asgnShortName %></span>
					<% } else { %>
						<span id="showUpdAssign<%= caseAutoId %>" style="cursor:text;text-decoration:none;color:#666666;font-size:11px"><%= getdata.Easycase.asgnShortName %></span>
					<% } %>
					<% if((projUniq != 'all') && (caseLegend == 1 || caseLegend == 2 || caseLegend == 4)){ %>
					<span id="asgnlod<%= caseAutoId %>" class="asgn_loader">
						<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..."/>
					</span>
					<% } %>
					<!--<ul class="dropdown-menu asgn_dropdown-caret" id="showAsgnToMem<%= caseAutoId %>">
						<li class="pop_arrow_new"></li>
						<li class="text-centre"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" id="assgnload<%= caseAutoId %>" /></li>
					</ul>-->
				</div>
                
				<div class="dropdown fr m_rht3">
					<!--<div id="showUpdStatus<%= caseAutoId %>" class="type_<%= getdata.Easycase.csTdTyp[0] %> <% if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && getdata.Easycase.isactive == 1){ %>clsptr<% } %> <% if($.inArray(getdata.Easycase.csTdTyp[0], ['dev', 'bug', 'upd']) == -1) { %>opcty4<% } %>" title="<%= getdata.Easycase.csTdTyp[1] %>" data-toggle="dropdown"></div>
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
					<% } %> -->

				</div>
				<div class="cb"></div>
			</div>
            <% } %>
			<div>
                            <div style="float:right">
                            <div class="dropdown fr">
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
					if(caseLegend == 1 && SES_ID == caseUserId) { caseFlag=3; }
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="editask(<%= '\''+ caseUniqId+'\',\''+projectUniqid+'\',\''+projectName+'\'' %>);" id="edit<%= caseAutoId %>" style=" <% if(caseFlag == 3){ %>display:block <% } else { %>display:none<% } %>">
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
					if((caseLegend == 1 || caseLegend == 2 || caseLegend == 4) && caseTypeId!= 10) { caseFlag=2; }
					if(getdata.Easycase.isactive == 1){ %>
					<li onclick="moveTask(<%= '\'' + caseAutoId + '\'' %>, <%= '\'' + caseNo + '\'' %>, <%= '\'' + getdata.Easycase.Mid + '\'' %>,<%= '\'' + projId + '\'' %>);" id="moveTask<%= caseAutoId %>" style=" <% if(caseFlag == 2){ %> display:block <% } else { %> display:none <% } %>">
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
				</div></div>
                                <div class="cb"></div>
            
                               <!-- <div class="dropdown fr"></div>
				<div class="cb"></div>
				<% if(projUniq=='all'){ %>
				<div class="pjname-cls" style="font-weight:normal"><%= projectName %></div>
				<% } %>-->
			</div>
			
			
		</div>
			<%
				totids += caseAutoId + "|";
				chkMstone = getdata.Easycase.Mid;
				
			}%>
		</div>
	</div>
<% } %>
<% if(milestones && countJS(milestones)>0){
		for(var mkey in milestones){
			var mdtls = milestones[mkey];
			if(jQuery.inArray( mdtls['id'],rec_ids )== -1){
			milestonetitle = str_replace('"', "", str_replace("'","", formatText(mdtls.title)));
			var days = mdtls.days_diff;
			%>
			<div class="fl kanban-child" id="milestone_<%= mdtls.id %>">
				<div class="kbhead kbhead_<%= mdtls.id %>">
				       <div id="edit-link_<%= mdtls.id %>">			    
					<div class=" fl" id="main-title-holder_<%= mdtls.id %>"><a href="<?php echo HTTP_ROOT.'dashboard#kanban/';?><%= mdtls.uinq_id %>"><%= shortLength(ucfirst(mdtls.title), 28) %></a></div>	
					<div class="fl">
						<a href="javascript:void(0);" onclick="quickEditMilestone('<%= mdtls.id %>');"><div class="edit_mile"></div></a>						
					</div>
					<div class="dropdown fl">
						<div class="sett" data-toggle="dropdown"></div>
						<ul class="dropdown-menu sett_dropdown-caret" style="min-width:166px;">
							<li class="pop_arrow_new"></li>
							 <% if(isActive!=0){ %>
							<li  onClick="creatask(<%= mdtls.id %>)" >
								<a href="javascript:void(0);">
								<div class="ct_icon act_create_task fl"></div>
									<div class="fl mntxt" style="margin-left:-12px">Create New Task</div>
								</a>                                                        
							</li>
							 <li  onClick="addTaskToMilestone(<%= '\'\',\''+ mdtls.id + '\'' %>,<%= '\'' + mdtls.project_id + '\'' %>,<%= '\'' + count + '\'' %>)" >
								<a href="javascript:void(0);">
									<div class="ct_icon icon-add-task-milston fl"></div>
									<div class="fl mntxt">Add Task</div>
								</a>                                                        
							</li>
							<li onclick="addEditMilestone(<%= '\'\',\'' + mdtls.uinq_id + '\'' %>,<%= '\'' + mdtls.id + '\'' %>,<%= '\'' + milestonetitle + '\',1' %>)" class="makeHover">
								<a href="javascript:void(0)"><div class="ct_icon act_edit_task fl"></div><div class="fl">Edit</div></a>
							</li>
                                                        <%  } %>
							<li onClick="delMilestone(<%= '\'\',\'' + milestonetitle + '\'' %>,<%= '\'' + mdtls.uinq_id + '\'' %>);" class="makeHover" >
								<a href="javascript:void(0);" ><div class="act_icon act_del_task fl"></div><div class="fl mntxt" style="margin-left:-6px">Delete</div></a>
							</li>
                                                         <% if(isActive!=0){ %>
							<li onclick="milestoneArchive(<%= '\'\',\'' + mdtls.uinq_id + '\'' %>, <%= '\'' + milestonetitle + '\'' %>);"  >
								<a href="javascript:jsVoid();"><div class="ct_icon mt_completed fl"></div> <div class="fl mntxt" style="margin-left:-7px">Complete</div></a>
							</li>
							<%  }else{ %>
							<li onclick="milestoneRestore(<%= '\'\',\'' + mdtls.uinq_id + '\'' %>, <%= '\'' + milestonetitle + '\'' %>);"  >
								<a href="javascript:jsVoid();"><div class="ct_icon mt_completed fl"></div><div class="fl mntxt">Restore</div></a>
							</li>
							<%  } %>
						</ul>
					</div>
					<div class="fr"><%= mdtls.totalcases %></div>
					</div>
					<div class="fl" id="edit-save_<%= mdtls.id %>" style="display:none;width:100%;">
					    <input type="text" class="form-control" id="milstone_edit_<%= mdtls.id %>" value="<%= mdtls.title %>" onblur="return saveMilesatoneTitle(<%= mdtls.id %>);"/>
					</div>
					<div class="cb"></div>
					<div class="fl">
						<span class="mlst-dt">
						<img src="<?php echo HTTP_ROOT."img/images/clock.png"; ?>">&nbsp;&nbsp;
						<% if(days == 0) { %>
							<b style="color:green">Today</b>
						<% } else { %>
							<font color="#565656"><%= mdtls.mlstDT %></font>&nbsp;
						<% if(isActive!=0){
                                                    if(mdtls.intEndDate < intCurCreated) { %>
								<font color="red">
						<% 		if(days > 1) { %>
									(Late by <%= days %> days)
						<% 		} else { %>
									(Late by <%= days %> day)
						<% 		} %>
								</font>
						<% } else { %>
								<font color="green">
						<% 		if(days > 1) { %>
									(Coming up in <%= days %> days)
						<% 		} else { %>
									(Coming up in <%= days %> day)
						<% 		} %>
								</font>
						<% }}
						} %>
						</span>
					</div>
					<div class="cb"></div>
				</div>
				<div class="kanban_content custom_scroll"></div>
			</div>
	<% 	}}}  %>
<div class="cb"></div>
</div>
<div class="cb h30"></div>
<% }else{ %>
<div class="fl col-lg-12 not-fonud ml_not_found">
	<div class="icon_con icon-no-milestone"></div>
	<h2>
		<%
			$('#mlist_crt_mlstbtn').hide();
			if(total_exist){
				if(mile_type == '1'){
		%>
				No active Milestone
			<% 	}else{ %>
					No Completed Milestone
			<% 	} %>
		<% }else{ %>
			No milestone
		<% } %>
	</h2>
	<div style="padding-top:10px;">
		<button class="btn btn_blue" value="Add" type="button" onclick="addEditMilestone(this);" style="margin:0;">
			Create Milestone
		</button>
	</div>
</div>
	<?php //echo $this->element('no_data', array('nodata_name' => 'milestonelist','isActive'=>isActive)); ?>
<% } %>
