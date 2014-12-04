<div class="crt_slide task_action_bar">
	<button type="button" class="btn gry_btn task_create_back" onclick="crt_popup_close()"><i class="icon-backto"></i>Go Back</button>
</div>	

<div class="breadcrumb_div">

<ol class="breadcrumb breadcrumb_fixed">
	<li>
		<a href="<?php echo HTTP_ROOT.Configure::read('default_action');?>">	<i class="icon-home"></i></a>
	</li>
<?php if(CONTROLLER == "easycases" && (PAGE_NAME == "mydashboard")) { ?>
	<li>Dashboard</li>
<?php } ?>
<?php if(CONTROLLER == "easycases" && (PAGE_NAME == "files")) { ?>
	<li>Files</li>
<?php } ?>
<?php if(CONTROLLER == "milestones" && (PAGE_NAME == "milestone" || PAGE_NAME=='milestonelist')) { ?>
	<li>Milestone</li>
<?php } ?>
<?php if(CONTROLLER == "archives" && (PAGE_NAME == "listall")) { ?>
	<li>Archive</li>
	<li>Tasks</li>
<?php } ?>
<?php if(CONTROLLER == "projects" && (PAGE_NAME == "manage")) { ?>
	<li>Projects</li>
	<li>Manage</li>
<?php } ?>
<?php if(CONTROLLER == "users" && (PAGE_NAME == "manage")) { ?>
	<li>Users</li>
	<li>Manage</li>
<?php } ?>
<?php if(CONTROLLER == "users" && (PAGE_NAME == "profile")) { ?>
	<li>Personal Settings</li>
	<li>My Profile</li>
<?php }
	if(CONTROLLER == "users" && (PAGE_NAME == "changepassword")) { ?>
	<li>Personal Settings</li>
	<li>Change Password</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "email_notifications")) { ?>
	<li>Personal Settings</li>
	<li>Notifications</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "email_reports")) { ?>
	<li>Personal Settings</li>
	<li>Email Reports</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "mycompany")) { ?>
	<li>Company Settings</li>
	<li>My Company</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "dailyupdatealerts")) { ?>
	<li>Company Settings</li>
	<li>Daily Catch-Up</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "importexport")) { ?>
	<li>Company Settings</li>
	<li>Import & Export</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "cancelact")) { ?>
	<li>Company Settings</li>
	<li>Cancel Account</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "subscription")) { ?>
	<li>Account Settings</li>
	<li>Subscription</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "creditcard")) { ?>
	<li>Account Settings</li>
	<li>Credit Card</li>
<?php }
	if(CONTROLLER == "users" && (PAGE_NAME == "transaction")) { ?>
	<li>Account Settings</li>
	<li>Transactions</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "account_activity")) { ?>
	<li>Account Settings</li>
	<li>Account Activity</li>
<?php } 
if(CONTROLLER == "users" && (PAGE_NAME == "upgrade_member")) { ?>
	<li>Subscription</li>
	<li>Upgrade Subscription</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "downgrade")) { ?>
	<li>Subscription</li>
	<li>Downgrade Subscription</li>
<?php } 
	if(CONTROLLER == "users" && (PAGE_NAME == "edit_creditcard")) { ?>
	<li>Credit Card</li>
	<li>Edit Credit Card</li>
<?php } ?>
<?php if(CONTROLLER == "users" && (PAGE_NAME == "confirmationPage")) { ?>
	<li>Subscription</li>
	<li>Account Limitation</li>
<?php } ?>
<?php if(CONTROLLER == "users" && (PAGE_NAME == "pricing")) { ?>
	<li>Subscription</li>
	<li>Pricing</li>
<?php } ?>
<?php if(CONTROLLER == "users" && (PAGE_NAME == "activity")) { ?>
	<li>Activities</li>
<?php } ?>	
<?php if(CONTROLLER == "projects" && (PAGE_NAME == "importexport" || PAGE_NAME=='csv_dataimport' || PAGE_NAME=='confirm_import') ) { ?>
	<li>Company Settings</li>
	<li>Import & Export</li>
<?php }
    if(CONTROLLER == "projects" && (PAGE_NAME == "task_type")) { ?>
	<li>Company Settings</li>
	<li>Task Type</li>
<?php } ?>	
<?php if(CONTROLLER == "projects" && PAGE_NAME == "groupupdatealerts"){ ?>
	<li>Company Settings</li>
	<li>Daily Progress Reminder</li>
<?php } ?>	
<?php if(CONTROLLER == "easycases" && (PAGE_NAME == "dashboard")) {?>
	<li><span id="brdcrmb-cse-hdr">Tasks</span></li>
<?php } ?>
<?php if(CONTROLLER == "templates" && (PAGE_NAME == "view_templates")) {?>
	<li>Template</li>
<?php } ?>
<?php if(CONTROLLER == "templates" && (PAGE_NAME == "projects")) {?>
	<li>Templates</li>
	<li>Project</li>
<?php } ?>
<?php if(CONTROLLER == "templates" && (PAGE_NAME == "tasks")) {?>
	<li>Templates</li>
	<li>Task</li>
<?php } ?>

<?php if(CONTROLLER == "reports" && (PAGE_NAME == "glide_chart")) {?>
	<li>Analytics</li>
	<li>Bug Reports</li>
<?php } ?>
<?php if(CONTROLLER == "reports" && (PAGE_NAME == "hours_report")) {?>
	<li>Analytics</li>
	<li>Hours Spent Reports</li>
<?php } ?>
<?php if(CONTROLLER == "reports" && (PAGE_NAME == "chart")) {?>
	<li>Analytics</li>
	<li>Task Reports</li>
<?php } ?>
<?php if(CONTROLLER == "users" && (PAGE_NAME == "cancel_account")) {?>
	<li>Account</li>
	<?php if(($user_subscription['subscription_id']>1) && !$user_subscription['is_free']){?>
	<li>Cancel Account</li>
	<?php }else{?>
	<li>Delete Account</li>
	<?php } ?>
<?php } ?>
<?php if(CONTROLLER == "reports" && (PAGE_NAME == "weeklyusage_report")) {?>
	<li>Analytics</li>
	<li>Weekly Usage Report</li>
	<li>Project: <span class="weekly_all">All</span></li>
<?php } ?>
<?php if((CONTROLLER == "easycases" && (PAGE_NAME == "dashboard" || PAGE_NAME == "mydashboard")) || (CONTROLLER == "milestones" && (PAGE_NAME == "milestone" || PAGE_NAME=='milestonelist')) || (CONTROLLER == "users" && (PAGE_NAME == "activity"))) {?>
	<li class="dropdown" id="prj_drpdwn">Project:
	<?php if((count($getallproj) == 0) && (SES_TYPE == 1 || SES_TYPE == 2) ) { ?>
		<a onclick="newProject()" href="javascript:void(0);"> <i style="color: 2D678D; font-weight: bold;"> Create Project</i></a>
	<?php }else{
		 if(count($getallproj)=='0'){ ?>
		    <i style="color:#FF0000">None</i>
	<?php } else {
			if(count($getallproj)=='1'){
				echo "<span style='color:#000;'>".$this->Format->shortLength(ucfirst($getallproj['0']['Project']['name']),20)."</span>";
			    $swPrjVal = $getallproj['0']['Project']['name'];
			}else{
			    $swPrjVal = $this->Format->shortLength($projName,20); ?>
			<a href="javascript:void(0);" onclick="view_project_menu('<?php echo PAGE_NAME;?>');" data-toggle="dropdown" class="option-toggle" id="prj_ahref">
			    <div class="prjnm_ttc"><span id="pname_dashboard" class="ttc "><?php echo ucfirst($swPrjVal); ?></span></div>
			    <i class="caret"></i>
			</a>
			<div class="dropdown-menu lft popup" id="projpopup">
			    <center>
				<div id="loader_prmenu" style="display:none;">
				    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/>
				</div>
			    </center>
			    <?php if(count($getallproj) >= 6) { ?>
			    <div id="find_prj_dv" style="display: none;">
				<input type="text" placeholder="Find a Project" class="form-control pro_srch" onkeyup="search_project_menu('<?php echo PAGE_NAME;?>',this.value,event)" id="search_project_menu_txt">
				<i class="icon-srch-img"></i>
				<div id="load_find_dashboard" style="display:none;" class="loading-pro">
				    <img src="<?php echo HTTP_IMAGES;?>images/del.gif"/>
				</div>
			    </div>
			    <?php } ?>
			    <div id="ajaxViewProject" style='display:none;'></div>
				<div id="ajaxViewProjects"></div>
			</div>
	<?php } ?>
	<?php } ?>
	<?php }?>
	</li>
	<?php } ?>

<?php if(CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "chart" || PAGE_NAME == "hours_report")) { ?>
	<li class="dropdown" id="prj_drpdwn">Project:
	<?php if((count($getallproj) == 0) && (SES_TYPE == 1 || SES_TYPE == 2) ) { ?>
		<a onclick="newProject()" href="javascript:void(0);"><i style="color: 2D678D; font-weight: bold;"> Create Project</i></a>
		<!--<button onclick="newProject('menupj','loaderpj');">Create Project</button>-->
	<?php }else{
		 if(count($getallproj)=='0'){ ?>
		    --None--
	<?php } else {
	 if(count($getallproj)=='1'){
				echo $getallproj['0']['Project']['name'];
			    $swPrjVal = $getallproj['0']['Project']['name'];
			}else{
			    $swPrjVal = $this->Format->shortLength($projName,30); ?>
			<a href="javascript:void(0);" onclick="view_project_menu('<?php echo PAGE_NAME;?>');" data-toggle="dropdown" class="option-toggle" id="prj_ahref">
			    <span id="pname_dashboard" class="ttc"><?php echo isset($getallproj['0']['Project']['name'])?$getallproj['0']['Project']['name']:ucfirst($swPrjVal); ?></span>
			    <i class="caret"></i>
			</a>
			<div class="dropdown-menu lft popup" id="projpopup">
			    <center>
				<div id="loader_prmenu" style="display:none;">
				    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/>
				</div>
			    </center>
			    <?php if(count($getallproj) >= 6) { ?>
			    <div id="find_prj_dv" style="display: none;">
				<input type="text" placeholder="Find a Project" class="form-control pro_srch" onkeyup="search_project_menu('<?php echo PAGE_NAME;?>',this.value,event)" id="search_project_menu_txt">
				<i class="icon-srch-img"></i>
				<div id="load_find_dashboard" style="display:none;" class="loading-pro">
				    <img src="<?php echo HTTP_IMAGES;?>images/del.gif"/>
				</div>
			    </div>
			    <?php } ?>
			    <div id="ajaxViewProject" style='display:none;'></div>
				<div id="ajaxViewProjects"></div>
			</div>
	<?php } ?>
	<?php } ?>
	<?php }?>
	</li>
	<?php } ?>
	<?php if(PAGE_NAME=='dashboard'){?>
	<li  class="kanbn dashborad-view-type" id="select_view">
	<a href="<?php echo HTTP_ROOT.'dashboard#tasks';?>" onclick="checkHashLoad('tasks');"><div id="lview_btn" class="btn gry_btn kan30" title="List View"><i class="icon-list-view"></i></div></a>
	<a href="<?php echo HTTP_ROOT.'dashboard#tasks';?>" onclick="checkHashLoad('compactTask');"><div id="cview_btn" class="btn gry_btn kan30" title="Compact View"><i class="icon-compact-view"></i></div></a>
	<a href="<?php echo HTTP_ROOT.'dashboard#kanban';?>" onclick="checkHashLoad('kanban');"><div id="kbview_btn" class="btn gry_btn kan30" style="border-radius:0 3px 3px 0"  title="Kanban View"><i class="icon-kanv-view"></i></div></a>
	<a href="<?php echo HTTP_ROOT.'dashboard#activities';?>" onclick="checkHashLoad('activities');"><div id="actvt_btn" class="btn gry_btn kan30" style="border-radius:0 3px 3px 0"  title="Activities"><i class="icon-actvt-view"></i></div></a>
	<a href="<?php echo HTTP_ROOT.'dashboard#calendar';?>" onclick="calendarView('calendar');"><div id="calendar_btn" class="btn gry_btn kan30" style="border-radius:0 3px 3px 0"  title="Calendar"><img src="<?php echo HTTP_ROOT; ?>img/calendar.png" style="margin-top:-8px;margin-left:-2px"></img></div></a>
	</li>
	<?php } ?>
	<li  class="kanbn dashborad-view-type" id="select_view_mlst" style="display: none;">
		<a href="<?php echo HTTP_ROOT.'dashboard#milestone';?>" onclick="checkHashLoad('milestone');" ><div id="mlview_btn" class="btn gry_btn kan30" title="Manage Milestone"><i class="icon-list-view"></i></div></a>
		<a href="<?php echo HTTP_ROOT.'dashboard#milestonelist';?>" onclick="checkHashLoad('milestonelist');"><div id="mkbview_btn" class="btn gry_btn kan30" style="border-radius:0 3px 3px 0"  title="Milestone Kanban View"><i class="icon-kanv-view"></i></div></a>
		<!--<a href="javascript:void(0);" onclick="addEditMilestone(this);" id="mlist_crt_mlstbtn" class="mlstlink_new" data-name="" data-uid="" data-id="">Create Milestone</a>-->
		<button style="margin-left:25px;" onclick="addEditMilestone(this);" id="mlist_crt_mlstbtn" type="button" value="Create Milestone" class="btn btn_blue">    Create Milestone   </button>
	</li>
	
</ol>
</div>	
<div class="task_action_bar_div task_detail_head">
	<div class="task_action_bar">
		<button class="btn gry_btn task_detail_back" type="button" style="margin-left:18px;">
		<i class="icon-backto"></i>Go Back
		</button>
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
	</div><!-- Case Detail buttons -->
</div>
<div class="task_action_bar_div milestonekb_detail_head">
	<div class="task_action_bar">
		<button class="btn gry_btn task_detail_back" type="button" style="margin-left:18px;">
		<i class="icon-backto"></i>Go Back
		</button>
	</div><!-- Case Detail buttons -->
</div>
