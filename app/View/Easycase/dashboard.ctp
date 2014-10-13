<?php ?>
<style type="text/css">
	#show_milestonelist .kanban-main .kanban-child{width:353px}
	#show_milestonelist .kbtask_div{width:95%}
</style>
<div id="detail_section"></div>
<div class="page-wrapper task_section" style="text-align: center;" id="filter_section">
	<div class="row"   id="filter_div_menu">
	<div class="filters">
<!--		<i class="db-filter-icon fl"></i>
		<div class="fl ftext">Filters:&nbsp;</div>-->
		<div class="fl task_section case-filter-menu " data-toggle="dropdown" title="Task Filter" onclick="openfilter_popup(0,'dropdown_menu_all_filters');">
			<button type="button" class="btn tsk-menu-filter-btn flt-txt">
					<i class="icon_flt_img"></i>
					Filters
					<i class="icon-filter-right"></i>
			</button>
			<ul class="dropdown-menu" id="dropdown_menu_all_filters" style="position: absolute;">
				<li class="pop_arrow_new"></li>
				<li>
					<a href="javascript:jsVoid();" title="Time" data-toggle="dropdown" onclick="allfiltervalue('date');"> Time</a>
					<div class="dropdown_status" id="dropdown_menu_date_div">
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_date"></ul>
					</div>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Due Date" data-toggle="dropdown" onclick="allfiltervalue('duedate');"> Due Date</a>
					<div class="dropdown_status" id="dropdown_menu_duedate_div">
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_duedate"></ul>
					</div>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Status" data-toggle="dropdown" onclick="allfiltervalue('status');">Status</a>
					<div class="dropdown_status" id="dropdown_menu_status_div">
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_status"></ul>
					</div>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Types" data-toggle="dropdown" onclick="allfiltervalue('types');">Types</a>
					<div class="dropdown_status" id="dropdown_menu_types_div" >
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_types"></ul>
					</div>

				</li>
				<li>
					<a href="javascript:jsVoid();" title="Priority" data-toggle="dropdown" onclick="allfiltervalue('priority');">Priority</a>
					<div class="dropdown_status" id="dropdown_menu_priority_div" >
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_priority"></ul>
					</div>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Users" data-toggle="dropdown" onclick="allfiltervalue('users');">Created by </a>
					<div class="dropdown_status" id="dropdown_menu_users_div" >
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_users"></ul>
					</div>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Assign To" data-toggle="dropdown" onclick="allfiltervalue('assignto');">Assign To</a>
					<div class="dropdown_status" id="dropdown_menu_assignto_div" >
						<i class="status_arrow_new"></i>
						<ul class="dropdown-menu" id="dropdown_menu_assignto"></ul>
					</div>
				</li>
			</ul>
		</div>
		<div class="fl" id="filtered_items" style="padding-left:10px;" ></div>
		<!-- Filter options ends-->
		<div class="filter_btn_section fl" id="savereset_filter">
<!--				<div style="display:none;" id="savefilter_btn" class="fl"  >
					<div class="db-filter-save-icon fl" onClick="showSaveFilter();" title="Save Filter" rel="tooltip"></div>
					 <div id="inner_save_filter" class="sml_popup_bg">
						<div>
							<div class="popup_title smal">
								<span>Save Custom Filter</span>
							</div>
							<div class="popup_form smal_form">
							    <table cellpadding="0" cellspacing="0" class="col-lg-12" id="inner_save_filter_td">
									<tr>
										<td colspan="2">
											<span id="loaderpj" style="display:block;">
												<center>
												<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." />
												</center>
											</span>
										</td>
									</tr>
							    </table>
							</div>
						</div>
					 </div>
				</div>-->
				<div class="fl db-filter-reset-icon" style="display:none;" id="reset_btn" title="Reset Filters" rel="tooltip" onClick="resetAllFilters('all');"></div>
		   </div>
			<div class="fl task_section case-filter-menu taskgroupby-div" data-toggle="dropdown" title="Task Filter" onclick="openfilter_popup(0,'dropdown_menu_groupby_filters');">
			<button type="button" class="btn tsk-menu-sortgroup-btn flt-txt" >
					<i class="icon_groupby_img"></i>Group by<i class="icon-filter-right"></i>
			</button>
			<ul class="dropdown-menu" id="dropdown_menu_groupby_filters" style="position: absolute;">
				<li class="pop_arrow_new"></li>
<!--				<li>
					<a href="javascript:jsVoid();" title="Time" data-toggle="dropdown" onclick="groupby('crtdate');"> Created Date</a>
				</li>-->
				<li>
					<a href="javascript:jsVoid();" title="Due Date" data-toggle="dropdown" onclick="groupby('duedate');"> Due Date</a>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Status" data-toggle="dropdown" onclick="groupby('status');">Status</a>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Priority" data-toggle="dropdown" onclick="groupby('priority');">Priority</a>
				</li>
				<li>
					<a href="javascript:jsVoid();" title="Priority" data-toggle="dropdown" onclick="groupby('assignto');">Assigned to</a>
				</li>
			</ul>
		</div>
		<div class="fl" id="groupby_items"></div>
		<div class="fl task_section case-filter-menu tasksortby-div " data-toggle="dropdown" >
		<button type="button" class="btn tsk-menu-sortgroup-btn flt-txt sortby_btn <?php if(isset($_COOKIE['TASKGROUPBY']) && ($_COOKIE['TASKGROUPBY']!='date')){?>disable-btn<?php }?> " onclick="openfilter_popup(0,'dropdown_menu_sortby_filters');" <?php if(isset($_COOKIE['TASKGROUPBY']) && ($_COOKIE['TASKGROUPBY']!='date')){?>disabled="disabled"<?php }?>>
				<i class="icon_sortby_img"></i>Sort by<i class="icon-filter-right"></i>
		</button>
		<ul class="dropdown-menu" id="dropdown_menu_sortby_filters" style="position: absolute;">
			<li class="pop_arrow_new"></li>
			<li>
				<a href="javascript:jsVoid();"  data-toggle="dropdown" onclick="ajaxSorting('title');">Title</a>
			</li>
			<li>
				<a href="javascript:jsVoid();"  data-toggle="dropdown" onclick="ajaxSorting('caseno');">Task#</a>
			</li>
			<li>
				<a href="javascript:jsVoid();"  data-toggle="dropdown" onclick="ajaxSorting('duedate');"> Due Date</a>
			</li>
			<li>
				<a href="javascript:jsVoid();"  data-toggle="dropdown" onclick="ajaxSorting('caseAt');">Assigned to</a>
			</li>

		</ul>
	</div>
	<div class="fl" id="sortby_items"></div>
		   <div class="cb"></div>
	  </div>
	</div>
	<div class="cb"></div>
	<!-- /.row --><!-- Task filters -->
</div><!-- /.page-wrapper -->
<table cellpadding="0" cellspacing="0" width="96%" class="task_section dashbod_tbl_m10 fixed_layout">
    <tr>
<td id="topaction" class="">
	    <!--Tabs section starts -->
	    <div style="display:block;border:0px solid #FF0000;" class="tab" id="topactions">
		<ul id="myTab4" class="nav-tabs">

		<?php
		if (ACT_TAB_ID && ACT_TAB_ID > 1) {
		    $tablists = Configure::read('DTAB');
		    foreach ($tablists AS $tabkey => $tabvalue) {
			if ($tabkey & ACT_TAB_ID) {
			    $default_actv = "";
			    if($tabvalue["fkeyword"] == "cases") { $tab_spn_id = "tskTabAllCnt"; $default_actv = "active";}
				elseif($tabvalue["fkeyword"] == "assigntome") { $tab_spn_id = "tskTabMyCnt"; }
				elseif($tabvalue["fkeyword"] == "delegateto") { $tab_spn_id = "tskTabDegCnt"; }
				elseif($tabvalue["fkeyword"] == "highpriority") { $tab_spn_id = "tskTabHPriCnt"; }
				elseif($tabvalue["fkeyword"] == "overdue") { $tab_spn_id = "tskTabOverdueCnt"; }
			    ?>
			<li class="<?php echo $default_actv;?>">
				<a class="cattab"  id="<?php echo $tabvalue["fkeyword"]; ?>_id" onclick="caseMenuFileter('<?php echo $tabvalue["fkeyword"]; ?>', 'dashboard', 'cases', '');" data-toggle="tab">
				<div class="fl <?php echo $tabvalue["fkeyword"];?>"></div>
				<div class="fl"><?php echo $tabvalue["ftext"]; ?><span id="<?php echo $tab_spn_id;?>"></span></div>
				<div class="cbt"></div>
			    </a>
			</li>
			<?php } ?>
		    <?php } ?>
			<li class="pop_li">

			    <a href="javascript:void(0);" class="select_button_ftop" onclick="newcategorytab();" rel="tooltip" title="Tab Settings">
				<div class="tab_pop">+</div>
			    </a>
			</li>
			<div style="clear:both"></div>
		<?php } ?>
		</ul>
	    </div>
	     <!--Tabs section ends -->
	</td>
    </tr>
    <tr>
        <td>
        <!--Task listing section starts here-->
			<div id="caseViewSpanUnclick">
				<div id="caseViewDetails" style="display:none"></div>
				<div id="caseViewSpan" style="display:block"></div>
				<div id="task_paginate" style="display:block"></div>
			</div>
        <!--Task listing section ends here-->
        </td>
    </tr>
</table><!-- Tab and task list -->

<div id="caseFileDv" style="display:block"></div>
<div id="caseKanbanDv"  style="text-align: center;display:block;position: absolute;margin-left: 20px;margin-top: -20px;">
    <div id="show_search_kanban" class="global-srch-res fl"></div><div style="float:left;text-align:center;margin-top:5px;" id="resetting_kanban"></div>
</div>
<div id="kanban_list" class="kanban_section kanban_resp" style="display:block"></div>
<div id="calendar_view" class="calendar_section calendar_resp" style="display:block;margin-top: 12px;"></div>
<div id="caseLoader">
	<div class="loadingdata">Loading...</div>
</div>

<!--Task activities section start here-->
<div class="page-wrapper" id="actvt_section" style="display:none">
    <div class="col-lg-9 fl m-left-20 activity_ipad">
		<div id="activities"></div>
		<div style="display:none;" id="more_loader" class="morebar">
			<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/>
		</div>
    </div>
	<div class="col-lg-3 fl act_rt_div">
	<div class="tab tab_comon tab_task">
	    <ul class="nav-tabs activ_line mod_wide">
		<li class="active">
		    <a href="javascript:void(0);" id="myTab" onclick="myactivities('myTab', 'delegatedTab');">
			<div class="fl" >My</div>
			<div class="cbt"></div>
		    </a>
		</li>
		<li id="file_li">
		    <a href="javascript:void(0);"  id="delegatedTab" onclick="delegateactivities('myTab', 'delegatedTab');">
			<div class="fl">Delegated</div>
			<div class="cbt"></div>
		    </a>
		</li>
		<div class="cbt"></div>
	    </ul>
	</div>
	<div class="cb"></div>

	<div id="Upcoming"></div>
	<div id="moreOverdueloader" class="moreOverdueloader">Loading Tasks...</div>
	<hr/>
	<div id="Overdue"></div>
	<hr/>
	<div id="PieChart" style="display: none;"></div>
    </div>
    <div class="cb"></div>
</div>
<div class="cb"></div>
<input type="hidden" id="displayed" value="30">
<!--Task activities section ends here-->
<!-- Milestone Listing Start -->
<div id="caseMilestoneDv"  style="text-align: center;display:block;position: absolute;margin-left: 20px;margin-top: 0px;">
    <div id="show_search" class="global-srch-res fl"></div><div style="float:left;text-align:center;margin-top:5px;" id="resetting"></div>
</div>
<div class="cb"></div>

<div id="milestone_content" >
    <div id="manage_milestone" style="display: none;">
        <div class="tab tab_comon" id="mlsttab" >
            <ul class="nav-tabs mod_wide">
                <li class="active" id="mlstab_act">
                    <a href="javascript:void(0);" onclick="ManageMilestoneList(1)" >
                        <div class="fl act_milestone"></div>
                        <div class="fl">Active</div>
                        <div class="cbt"></div>
                    </a>
                </li>
                <li id="mlstab_cmpl">
                    <a href="javascript:void(0);" onclick="ManageMilestoneList(0)" >
                        <div class="fl mt_completed"></div>
                        <div class="fl">Completed</div>
                        <div class="cbt"></div>
                    </a>
                </li>
                <div class="cbt"></div>
            </ul>
        </div><br />
        <div id="manage_milestone_list"></div>
        <div id="milestone_paginate" style="margin-right: 3%;"></div>
    </div>


    <div id="milestonelisting">
        <div id="manage_milestonelist" style="display: none;">
            <div class="tab tab_comon" id="mlsttab">
                <ul class="nav-tabs mod_wide">
                    <li class="active" id="mlstab_act_kanban">
                        <a href="javascript:void(0);" onclick="showMilestoneList('',1)" >
                            <div class="fl act_milestone"></div>
                            <div class="fl">Active</div>
                            <div class="cbt"></div>
                        </a>
                    </li>
                    <li id="mlstab_cmpl_kanban">
                        <a href="javascript:void(0);" onclick="showMilestoneList('',0)" id="completed_tab" >
                            <div class="fl mt_completed"></div>
                            <div class="fl">Completed</div>
                            <div class="cbt"></div>
                        </a>
                    </li>
                    <div class="cbt"></div>
                </ul>
            </div>
            <br />
        </div>
        <div id="show_milestonelist"></div>
        <div class="milestonenextprev" style="display: none;" >
            <div class="fr">
                <button class="btn gry_btn next" type="button" title="Next">
                    <i class="icon-next"></i>
                </button>
            </div>
            <div class="fr">
                <button class="btn gry_btn prev" type="button" title="Previous">
                    <i class="icon-prev"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Milestone Listing End -->

<script type="text/template" id="case_project_tmpl">
<?php echo $this->element('case_project'); ?>
</script>
<!--<script type="text/template" id="case_project_tmpl">
<?php //echo $this->element('compact_view'); ?>
</script>-->
<script type="text/template" id="kanban_task_tmpl">
<?php echo $this->element('kanban_task'); ?>
</script>
<script type="text/template" id="paginate_tmpl">
<?php echo $this->element('paginate'); ?>
</script>
<script type="text/template" id="case_details_tmpl">
<?php echo $this->element('case_details'); ?>
</script>
<script type="text/template" id="case_replies_tmpl">
<?php echo $this->element('case_reply'); ?>
</script>
<script type="text/template" id="case_widget_tmpl">
<?php echo $this->element('ajax_case_status'); ?>
</script>

<script type="text/template" id="case_files_tmpl">
<?php echo $this->element('case_files'); ?>
</script>

<script type="text/template" id="date_filter_tmpl">
<?php echo $this->element('date_filter'); ?>
</script>

<script type="text/template" id="duedate_filter_tmpl">
<?php echo $this->element('duedate_filter'); ?>
</script>
<script type="text/template" id="ajax_activity_tmpl">
    <?php echo $this->element("../Users/json_activity");?>
</script>
<script type="text/template" id="manage_milestone_tmpl">
<?php echo $this->element('manage_milestone'); ?>
</script>
<script type="text/template" id="milestonelist_tmpl">
<?php echo $this->element('ajax_milestonelist'); ?>
</script>
<input type="hidden" name="checktype" id="checktype" value="" size="10" readonly="true">
<input type="hidden" name="caseStatus" id="caseStatus" value="<?php echo $caseStatus; ?>" size="10" readonly="true">
<input type="hidden" name="caseStatusprev" id="caseStatusprev" value="" size="10" readonly="true">
<input type="hidden" name="priFil" id="priFil" value="<?php echo $priorityFil; ?>" size="14" readonly="true"/>
<input type="hidden" name="caseTypes" id="caseTypes" value="<?php echo $caseTypes; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseMember" id="caseMember" value="<?php echo $caseUserId; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseAssignTo" id="caseAssignTo" value="<?php echo $caseAssignTo; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseSearch" id="caseSearch" value="<?php echo $caseSearch; ?>" size="4" readonly="true"/>
<input type="hidden" name="mlstPage" id="mlstPage" value="1" size="4" readonly="true"/>
<input type="hidden" name="caseId" id="caseId" value="<?php //echo $caseUniqId; ?>" size="14" readonly="true"/>
<input type="hidden" name="caseDate" id="caseDate" value="<?php echo $caseDate; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseTitle" id="caseTitle" value="<?php echo $caseTitle; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseDueDate" id="caseDueDate" value="<?php echo $caseDueDate; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseCreatedDate" id="caseCreatedDate" value="<?php echo $caseCreatedDate; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseNum" id="caseNum" value="<?php echo $caseNum; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseLegendsort" id="caseLegendsort" value="<?php echo $caseLegendsort; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseAtsort" id="caseAtsort" value="<?php echo $caseAtsort; ?>" size="4" readonly="true"/>
<input type="hidden" name="isSort" id="isSort" value="<?php echo $isSort; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseStart" id="caseStart" value="" size="4" readonly="true"/>
<input type="hidden" name="caseChangeType" id="caseChangeType" value="" size="4" readonly="true"/>
<input type="hidden" name="caseChangePriority" id="caseChangePriority" value="" size="4" readonly="true"/>
<input type="hidden" name="caseChangeAssignto" id="caseChangeAssignto" value="" size="4" readonly="true"/>
<input type="hidden" name="caseChangeDuedate" id="caseChangeDuedate" value="" size="4" readonly="true"/>
<input type="hidden" name="caseResolve" id="caseResolve" value="" size="4" readonly="true"/>
<input type="hidden" name="clearCaseSearch" id="clearCaseSearch" value="" size="4" readonly="true"/>
<input type="hidden" name="caseMenuFilters" id="caseMenuFilters" value="<?php echo $caseMenuFilters?$caseMenuFilters:'cases'; ?>" size="4" readonly="true"/>
<input type="hidden" name="customFIlterId" id="customFIlterId" value="" size="4" readonly="true"/>

<input type="hidden" name="milestoneIds" id="milestoneIds" value="<?php echo $milestoneIds; ?>" size="4" readonly="true"/>

<input type="hidden" name="caseDetailsSorting" id="caseDetailsSorting" value="<?php echo $caseDtlsSort; ?>" size="4" readonly="true"/>
<input type="hidden" name="urllvalueCase" id="urllvalueCase" value="<?php echo $urllvalueCase; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseUrl" id="caseUrl" value="<?php echo $caseUrl; ?>" size="4" readonly="true"/>
<input type="hidden" name="caseDateFil" id="caseDateFil" value="<?php echo $caseDateFil; ?>" size="4" readonly="true"/>
<input type="hidden" name="casedueDateFil" id="casedueDateFil" value="<?php echo $casedueDateFil; ?>" size="4" readonly="true"/>

<input type="hidden" name="prvhash" id="prvhash" value="" readonly="true"/>
<input type="hidden" name="milestoneUid" id="milestoneUid"   readonly="true"  value=''/>
<!-- Used for switching from milestone list to kanban task list and Accordingly counter changed -->
<input type="hidden" name="milestoneUid" id="milestoneId"   readonly="true"  value=''/>
<!-- differentiate between list view and Compact View -->
<input type="hidden" name="lviewtype" id="lviewtype"   readonly="true"  value='<?php echo $_COOKIE['LISTVIEW_TYPE'];?>'/>

<input type="hidden" id="last_project_id" value="">
<input type="hidden" id="last_project_uniqid" value="">
<input type="hidden" value="0" id="totalMlstCnt" readonly="true"/>
<input type="hidden" value="0" id="milestoneLimit" readonly="true"/>
<input type="hidden" value="1" id="mlsttabvalue" readonly="true"/>
<input type="hidden" value="milestone" id="refMilestone" readonly="true"/>
<input type="hidden" id="storeIsActive">
<input type="hidden" id="storeIsActivegrid">
<input type="hidden" id="view_type" value="kanban">
<input type="hidden" id="search_text">
<script type="text/javascript">
	$(document).ready(function(event){
		$(document).click(function(e){
			if($(e.target).is(".filter_opn")){
				e.preventDefault();
				e.stopPropagation();
			}else{
				$('#dropdown_menu_all_filters').hide();
				$('#dropdown_menu_sortby_filters').hide();
				$('#dropdown_menu_groupby_filters').hide();
				$('.dropdown_status').hide();
				//$(".case-filter-menu").css({"position":'fixed'});
			}
		});
	});
	$(".proj_mng_div .contain").hover(function(){
		$(this).find(".proj_mng").stop(true,true).animate({bottom:"0px",opacity:1},400);
	},function(){
		$(this).find(".proj_mng").stop(true,true).animate({bottom:"-42px",opacity:0},400);
	});
	$(document).on('click','.milestonenextprev .prev',function(){
		//$('#milestoneLimit').val(parseInt($('#milestoneLimit').val())-6);
                var isActive=($('#storeIsActive').val()!='')?$('#storeIsActive').val():1;
                var search_key=$('#search_text').val();
		showMilestoneList('prev',isActive,1,search_key);
	});
	$(document).on('click','.milestonenextprev .next',function(){
            var isActive=($('#storeIsActive').val()!='')?$('#storeIsActive').val():1;
                var search_key=$('#search_text').val();
		showMilestoneList('next',isActive,1,search_key);
	});
	
</script>