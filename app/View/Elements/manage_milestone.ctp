<div id="mlstnlistingDv">
    <div class="col-lg-12 m-left-42 prj_div">
	    <div class="col-lg-4">
            <div class="col-lg-12 contain new_prjct text-centre" style="padding:0;">
                <a href="javascript:void(0);" onClick="addEditMilestone(this);" data-id="" data-uid="" data-name="" style="display: block !important;padding:60px 55px;">
                    <div class="icon-projct-gridvw"></div>
                    Create Milestone
                </a>
            </div>
	    </div>
<%
var pgCaseCnt = milestoneAll?countJS(milestoneAll):0;
if(caseCount && caseCount != 0){
	var count=0;
	var caseNo = "";
	var totids = "";
	var mlstline_cnt = 1;
	var projectName ='';var projectUniqid='';
	for(var caseKey in milestoneAll){
		var getdata = milestoneAll[caseKey];
		count++;
		var milestoneAutoId = getdata.Milestone.id;
		var milestoneUniqId = getdata.Milestone.uniq_id;
		var projId = getdata.Milestone.project_id;
		var milestoneTitle = getdata.Milestone.title;
		var closed_cases =  getdata.Milestone.closed;
		var resolved_cases =  getdata.Milestone.resolved;
		var total_cases = getdata.Milestone.totalcases;
		var crtUser = getdata.Milestone.crtUser;
		var total_progress =0;
		if(total_cases>0){
			total_progress = Math.round(((closed_cases/total_cases)*100));
		}
		var hrSpent ='0.0';
		if(getdata.Milestone.hrSpent){
			hrSpent = getdata.Milestone.hrSpent;
		}
		var locDT = getdata.Milestone.locDT;
	%>
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain usr_mng_div milestone_div">
				<h3 class="prj_name"><a href="<?php echo HTTP_ROOT.'dashboard#kanban/';?><%= milestoneUniqId %>"><%= shortLength(formatText(ucfirst(milestoneTitle)),25) %></a></h3>
                <div class="imprv_bar col-lg-12" rel="tooltip" title="<%= closed_cases %> out of <%= total_cases %> task are closed">
                    <div class="cmpl_green" style="width:<%= total_progress %>%"></div>
                </div> 
                <div class="user-details prj_details">
                    <div class="fl usr_lt">
                    <div class="user-image mlstn_user_image">
                        <a href="<?php echo HTTP_ROOT."dashboard/?project=".$prjArr['Project']['uniq_id'];?>">
                        	<div class="icon-crt-mileston"></div>
                        </a>
                    </div>
                    </div>
                    <div class="fl usr_rt">
                    <div class="border_usr"><b><span id="tot_tasks<%= milestoneAutoId %>"><%= total_cases %></span></b> Tasks</div>
                    <div><b><%= closed_cases %></b> Closed</div>
                    <div><b><%= resolved_cases %></b> Resolved</div> 
                    </div>
                    <div class="cb"></div>
					<div class="fl mlst_crt_user">
                        <%= crtUser %> 
                    </div>
					<div class="cb"></div>
                </div>
                <div class="last_updt prj_last_updt">Last activity on <%= locDT %></div>
                
                
<!--				<div class="tsk_updts">
				    <span id="tot_tasks<%= milestoneAutoId %>"><%= total_cases %></span> Tasks&nbsp; . &nbsp;<%= closed_cases %> Closed
				</div>
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green" style="width:<%= total_progress %>%"></div>
				</div>
				<div class="last_updt">Last activity on <%= locDT %></div>
				<div class="cb"></div>
-->			

                <div class="proj_mng">
				    <div class="fl">
                                        <% if(mlsttype!='0'){ %>
					<a href="javascript:void(0);" class="icon-add-task fl" onClick="addTaskToMilestone(this);" data-prj-id="<%= projId %>" data-id="<%= milestoneAutoId %>">Add Task</a>
					<br /> <% } %>
					<% if(mlsttype=='0'){ %>
						<a href="javascript:void(0);" class="icon-restore-mlstn fl" onClick="milestoneRestore(this);" data-uid="<%= milestoneUniqId %>" data-name="<%= milestoneTitle %>">Restore</a>
					<% }else{ %>
						<a href="javascript:void(0);" class="icon-complete-mlstn fl" onClick="milestoneArchive(this);" data-uid="<%= milestoneUniqId %>" data-name="<%= milestoneTitle %>">Complete</a>
					<% } %>
					<% if(total_cases>0 && mlsttype!='0'){ %><br/>
						<a href="javascript:void(0);" class="icon-remov-task fl" onClick="removeTaskFromMilestone(this);" data-prj-id="<%= projId %>" data-id="<%= milestoneAutoId %>">Remove Task</a>
					<% } %>
				    </div>
				    <div class="fr">
                                        <% if(mlsttype!='0'){ %>
					<a href="javascript:void(0);" class="icon-edit-mlstn fl" onClick="addEditMilestone(this);" data-id="<%= milestoneAutoId %>" data-uid="<%= milestoneUniqId %>" data-name="<%= milestoneTitle %>">Edit</a>
					<br /> <% } %>
					<a href="javascript:void(0);" class="icon-delete-mlstn fl" onClick="delMilestone(this);" data-uid="<%= milestoneUniqId %>" data-name="<%= milestoneTitle %>">Delete</a>
				    </div>
				</div>
			</div>
		</div>
	<% if((mlstline_cnt%3)==0){%>
	</div>
	<div class="cb"></div>
	<%
		if(mlstline_cnt != caseCount){%>
			<div class="col-lg-12 m-left-20">
	<% }
	} 
}
if(mlstline_cnt %3 !=0){ %>
	</div>
	<div class="cb"></div>
<% } 
}%>	

<% $("#milestone_paginate").html('');
if(caseCount && caseCount!=0) {
	var pageVars = {pgShLbl:pgShLbl,csPage:csPage,page_limit:page_limit,caseCount:caseCount};
	$("#milestone_paginate").html(tmpl("paginate_tmpl", pageVars));
} %>
</div>