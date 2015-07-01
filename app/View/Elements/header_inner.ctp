<input type="hidden" name="pageurl" id="pageurl" value="<?php echo HTTP_ROOT; ?>" size="1" readonly="true"/>
<input type="hidden" name="pagename" id="pagename" value="<?php echo PAGE_NAME; ?>" size="1" readonly="true"/>
<input type="hidden" name="fmaxilesize" id="fmaxilesize" value="<?php echo MAX_FILE_SIZE; ?>" size="1" readonly="true"/>
<input type="hidden" name="case_srch" id="case_srch"  size="1" readonly="true" <?php if($case_num) { echo "value='".$case_num."'"; } else {  echo "value=''"; } ?>/>
 
<?php
$projUniq1 = "";
if(count($getallproj) >= 1) {
	$projUniq1 = $getallproj['0']['Project']['uniq_id'];
}
if( $is_active_proj || (SES_TYPE==3)){
	if(!isset($projUniq)) {
		$projUniq = $projUniq1;
	}
	if(CONTROLLER == 'reports' && (PAGE_NAME == 'chart' || PAGE_NAME == 'glide_chart' || PAGE_NAME == 'hours_report')){
		$projUniq = $proj_uniq;
	}?>
	
<input type="hidden" name="projFil" id="projFil" value="<?php echo $projUniq; ?>" size="24" readonly="true"/>
<input type="hidden" name="projIsChange" id="projIsChange" value="<?php echo $projUniq; ?>" size="24" readonly="true"/>

<input type="hidden" name="CS_project_id" id="CS_project_id" value="<?php if(isset($ctProjUniq)) { echo $ctProjUniq; } ?>" size="24" readonly="true"/>
<input type="hidden" id="CS_assign_to" value="<?php echo SES_ID; ?>">
<input type="hidden" id="own_session_id" value="<?php echo SES_ID; ?>">
<?php }?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="<?php echo HTTP_ROOT.Configure::read('default_action');?>"></a>
	</div>
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
	<?php if(PAGE_NAME != "help" && PAGE_NAME != "tour" && PAGE_NAME != "customer_support") { ?>
		  <ul class="nav navbar-nav side-nav">
			<?php if(ACCOUNT_STATUS!=2){ //$is_active_proj && ACCOUNT_STATUS!=2?>
			<?php if($is_active_proj){?>
			<li class="new_task_li">
				<button class="btn new_task" type="button" onclick="creatask();"><i class="icon-new-task"></i>Create Task</button>
			</li>
                        <?php }else {?>
                        <li class="new_task_li">
				<button class="btn new_task" type="button" onclick="alert('Please create a Project to add Task under that Project');" ><i class="icon-new-task"></i>Create Task</button>
			</li>
			<?php } }?>
			
			<li class="allmenutab <?php if(CONTROLLER == "easycases" && (PAGE_NAME == "mydashboard")) { echo 'active'; } ?>"><a href="<?php echo HTTP_ROOT.'mydashboard';?>"><i class="menu_sprite_ico menu_sprite_ico_dashboard"></i> Dashboard</a></li>
			
			
			<li class="menu-cases"><a href="<?php echo HTTP_ROOT.'dashboard#tasks';?>" onclick="checkHashLoad('tasks')"><i class="menu_sprite_ico menu_sprite_ico_task"></i> Tasks<span class="notify" id="taskCnt" style="display: none;" rel="tooltip" title=""></span></a></li>
			<li class="menu-files"><a href="<?php echo HTTP_ROOT.'dashboard#files';?>" onclick="checkHashLoad('files')"><i class="menu_sprite_ico menu_sprite_ico_file"></i> Files<span class="notify" id="fileCnt" style="display: none;" rel="tooltip" title=""></span></a></li>
			
			<li class="menu-milestone <?php if(CONTROLLER == "milestones" && (PAGE_NAME == "milestone")) { echo 'active'; }?>"><a href="<?php echo HTTP_ROOT.'dashboard#milestonelist';?>" onclick="checkHashLoad('milestonelist')"><i class="menu_sprite_ico menu_sprite_ico_milestone"></i> Milestones</a></li>
			
			
			
            <li class="allmenutab <?php if(CONTROLLER == "projects" && (PAGE_NAME == "manage")) { echo 'active'; } ?>"><a href="<?php echo HTTP_ROOT.'projects/manage';?>"><i class="menu_sprite_ico menu_sprite_ico_proj"></i> Projects</a></li>
			<?php
			if(SES_TYPE == 1 || SES_TYPE == 2)
			{
			?>
				<li class="allmenutab <?php if(CONTROLLER == "users" && (PAGE_NAME == "manage")) { echo 'active'; } ?>"><a href="<?php echo HTTP_ROOT.'users/manage';?>"><i class="menu_sprite_ico menu_sprite_ico_usr"></i> Users</a></li>
				<li class="allmenutab <?php if(CONTROLLER == "projects" && (PAGE_NAME == "groupupdatealerts")) { echo 'active'; } ?>"><a href="<?php echo HTTP_ROOT.'reminder-settings';?>"><i class="menu_sprite_ico menu_sprite_ico_gupd"></i> Daily Catch-Up</a></li>  
			<?php
			}
			?>
			

			<li <?php if((CONTROLLER == "archives" && (PAGE_NAME == "listall")) || CONTROLLER == "templates") { echo 'style="display:block;"'; }?><?php if(CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report")) { echo "class='active more_menu_li'"; echo ' style="display:block;"'; } else { if(SES_TYPE != 3) { echo " class='more_menu_li'"; } } ?>><a href="<?php echo HTTP_ROOT.'task-report/';?>"><i class="menu_sprite_ico menu_sprite_ico_anltc"></i> Analytics</a></li>
			
			<li <?php if((CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report")) || CONTROLLER == "templates") { echo 'style="display:block;"'; }?> <?php if(CONTROLLER == "archives" && (PAGE_NAME == "listall")) { echo "class='active more_menu_li'"; echo ' style="display:block;"'; } else { if(SES_TYPE != 3) { echo " class='more_menu_li'"; } } ?>><a href="<?php echo HTTP_ROOT.'archives/listall#caselist';?>"><i class="menu_sprite_ico menu_sprite_ico_arch"></i> Archive</a></li>
			
			
			<?php if(SES_TYPE == 1 || SES_TYPE == 2) { ?>
			<?php /*?><li class="more_menu_li"><a href="javascript:;"><i class="menu_sprite_ico menu_sprite_ico_mlstn"></i> Milestone</a></li><?php */?>
			<li <?php if((CONTROLLER == "archives" && (PAGE_NAME == "listall")) || (CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report"))) { echo 'style="display:block;"'; }?><?php if(CONTROLLER == "templates") { echo "class='active more_menu_li'"; echo ' style="display:block;"'; } else { echo " class='more_menu_li'"; } ?>><a href="<?php echo HTTP_ROOT. 'templates/tasks';?>"><i class="menu_sprite_ico menu_sprite_ico_tmplt"></i> Template</a></li>
			
			<li <?php if((CONTROLLER == "templates") || (CONTROLLER == "archives" && (PAGE_NAME == "listall")) || (CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report"))) { echo "class='close'"; }else{ echo 'class=""'; } ?>>
				<a href="javascript:void(0);" class="more_in_menu">
					<?php if((CONTROLLER == "templates") || (CONTROLLER == "archives" && (PAGE_NAME == "listall")) || (CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report"))) { echo "Less"; }else{echo "More";} ?>
				</a>
				<b class="<?php if((CONTROLLER == "templates") || (CONTROLLER == "archives" && (PAGE_NAME == "listall")) || (CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report"))) { ?>open_analytics_archive<?php }else{ ?>menu_more_arr<?php } ?>"></b>
			</li>
			<?php } ?>
<!--			<li class="dropdown cust_rec" id="customFil" <?php if(((CONTROLLER == "templates") || (CONTROLLER == "archives" && (PAGE_NAME == "listall")) || (CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report"))) && (SES_TYPE == 1 || SES_TYPE == 2)) { echo ' style="display:none;"'; } ?>>
			  <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" onclick="openAjaxCustomFilter('');"><i class="menu_sprite_ico menu_sprite_ico_cust"></i> Custom Filter 
			  <b class="menu_more_arr"></b></a>
			  <div style="float:left;display:none;margin-left:70px;" class="customFilterLoader">
				 <img width="16" height="16" title="loading..." alt="loading..." src="<?php echo HTTP_ROOT;?>img/images/left-panel-loader.gif">
			  </div>
			  <ul class="dropdown-menu customFilter"></ul>
			</li>-->
			<li class="dropdown cust_rec" id="recentCases" <?php if(((CONTROLLER == "templates") || (CONTROLLER == "archives" && (PAGE_NAME == "listall")) || (CONTROLLER == "reports" && (PAGE_NAME == "glide_chart" || PAGE_NAME == "hours_report" || PAGE_NAME == "chart" || PAGE_NAME == "weeklyusage_report"))) && (SES_TYPE == 1 || SES_TYPE == 2)) { echo 'style="display:none;"'; } ?>>
			  <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" onclick="openAjaxRecentCase();"><i class="menu_sprite_ico menu_sprite_ico_rec"></i> Recently viewed 
			  <b class="menu_more_arr"></b></a>
			  <div style="float:left;display:none;margin-left:70px;" class="recentViewLoader">
				 <img width="16" height="16" title="loading..." alt="loading..." src="<?php echo HTTP_ROOT;?>img/images/loading_dark_nested.gif">
			  </div>
			  <ul class="dropdown-menu recentViewed"></ul>
			</li>
		  </ul>
		<?php } ?>
	  <ul class="nav navbar-nav navbar-left navbar-user" <?php if(PAGE_NAME == "help") { ?>style="margin-left:0px;"<?php } ?>>
		<li class="dropdown alerts-dropdown help_a">
		  <a href="https://www.orangescrum.com/help" target="_blank"><i class="menu_sprite_ico menu_sprite_help" title="Help &amp; Support"></i><span class="ipad_txt" >Help &amp; Support</span></a>
		</li>
        <li class="dropdown user-dropdown user_gt">
        	<a href="<?php echo HTTP_ROOT.'getting_started';?>" title="Getting Started">
        	<div class="fl get_icon"></div>
            <span class="ipad_txt">Getting Started</span></a>
        </li>         
	  </ul>
	  
	 <ul class="nav navbar-nav navbar-right navbar-user ie_navbar_top">
		 <?php if($is_active_proj && ACCOUNT_STATUS!=2){?>
		<li style="border-right: 1px solid #1E252B;">
			<form class="navbar-form navbar-left top_search" role="search">
			  <div id="srch_load1" class="fl lod-src-itm"> 
				<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading" title="loading"/> 
				</div>
				<input type="hidden" value="<?php echo $srch_text; ?>" id="hid_srch_text" />
	  			<div class="form-group">
				  <?php if (PAGE_NAME != "dashboard") { ?>
				  <input type="hidden" name="casePage" id="casePage" value="1" size="4" readonly="true"/>
				  <?php } ?>
				  <input type="text" class="form-control search_top" name="case_search" id="case_search" autocomplete="off" onClick="sch_slide();" onkeypress="onKeyPress(event,'case_search');" onkeydown="return goForSearch(event,'');" placeholder="Search Tasks" />
				</div>
				 <button type="button" class="btn btn_sub_mit" onclick="return goForSearch('',1);"></button>
				<div class="cb"></div>
			 </form>
			<div id="ajax_search" class="ajx-srch-dv1"></div>
		</li>
		<?php 
		}
		else {
		?>
			<input type="hidden" id="case_search">
		<?php
		}
		if(SES_TYPE == 1 || SES_TYPE == 2){?>
            <li class="btn-dice user-dropdown" >
            <a href="javascript:void(0);" class="dropdown-toggle profile_name" data-toggle="dropdown">
                <div class="fl plsimg"></div>
                <div class="fl lblnw">New</div>
                <div class="fl dwnArr"></div>
                <div class="cb"></div>
            </a>
            <?php if(ACCOUNT_STATUS!=2){?>
            <ul class="dropdown-menu">
                <li>
                    <div class="sett_div sett_pop_div">
                        <div>
                            <ul class="new_sub_menu">
                                <li><a href="javascript:void(0);" onClick="newProject()"><i class="menu_os_ico menu_os_ico_proj"></i>Project</a></li>
                                <li><a href="javascript:void(0);" onClick="newUser()"><i class="menu_os_ico menu_os_ico_user"></i>User</a></li>
                                <?php if($is_active_proj){?>
                                <li><a href="javascript:void(0);" onClick="creatask()"><i class="menu_os_ico menu_os_ico_task"></i>Task</a></li>
                                <li><a href="javascript:void(0);" onClick="addEditMilestone(this)"><i class="menu_os_ico menu_os_ico_mlst"></i>Milestone</a></li>
                                <?php }?>
                                <!--<li><a href="javascript:void(0);">Milestone</a></li>
                                <li><a href="javascript:void(0);">Project Template</a></li>
                                <li><a href="javascript:void(0);">Task Template</a></li>-->
                            </ul>	
                        </div>
                    </div>
                        
                </li>
              </ul>
            <?php } ?>
            </li>
            <?php 
            
		}
		?>
		
		<li class="dropdown alerts-dropdown alert-compnm" title="<?php echo CMP_SITE; ?>">
		  <!--<a href="#" class="dropdown-toggle comp_nm" data-toggle="dropdown">-->
          <div class="cmp_nm_wrdwrp"><?php echo $this->Format->shortLength(CMP_SITE,10); ?></div>
      <!--</a>-->
		</li>
		<li class="dropdown user-dropdown">
		  <?php 
				$usrArr = $this->Format->getUserDtls(SES_ID);
					if(count($usrArr)) {
						$ses_name = $usrArr['User']['name'];
						$ses_photo = $usrArr['User']['photo'];
						$ses_email = $usrArr['User']['email'];
						$ses_last_name = $usrArr['User']['last_name'];
					}
			?>
		  	<a href="javascript;" class="dropdown-toggle profile_name" data-toggle="dropdown" title="<?php echo trim($ses_name." ".$ses_last_name); ?>"><span class="prof_sett">
		  	<div class="user_ipad"><?php echo $this->Format->shortLength(trim($ses_name),10); ?></div>
			<?php if(trim($ses_photo)) { ?>
			<img data-original="<?php echo HTTP_ROOT;?>users/image_thumb/?type=photos&file=<?php echo trim($ses_photo); ?>&sizex=28&sizey=28&quality=100" class="lazy round_profile_img" height="28" width="28" />
			<?php } else { ?>
			<img data-original="<?php echo HTTP_ROOT;?>users/image_thumb/?type=photos&file=user.png&sizex=28&sizey=28&quality=100" class="lazy round_profile_img" height="28" width="28" />
			<?php } ?>
			</span><span><b class="sett m_top"></b></span></a>
		  <ul class="dropdown-menu">
			<li>
				<ul class="user_sett_info">
					<li class="settings_hd">This Account is managed by <span title="<?php echo CMP_SITE; ?>"><?php echo $this->Format->shortLength(CMP_SITE,25); ?></span></li>
					<li><?php echo $ses_email; ?></li>
					<?php
					if(isset($user_subscription) && $user_subscription['id'] && $is_active_proj){
						if(!$user_subscription['is_free']  && (SES_TYPE==1 || SES_TYPE==2)){ ?>
							<li>
								<div class="pro_dsc" style="color:#F2F47A">
									Projects: <font <?php if((strtolower($user_subscription['project_limit'])!='unlimited') && $used_projects_count>=$user_subscription['project_limit']){?> style="color:#FFD400;"<?php }?> ><b><?php echo $used_projects_count;?></b> / <b id="tot_project_limit"><?php echo $user_subscription['project_limit'];?></b></font>,&nbsp; 
                                    Users: <font <?php if((strtolower($user_subscription['user_limit'])!='unlimited') && $used_projects_count>=$user_subscription['user_limit']){?> style="color:#FFD400;"<?php }?> ><b><?php echo $used_projects_count;?></b> / <b id="tot_project_limit"><?php echo $user_subscription['user_limit'];?></b></font>,&nbsp; 
									Storage: <span id="storage_spn">
									<span <?php if($used_storage >= $user_subscription['storage']){?> style="color:#FFD400" <?php }?>> 
									
									<?php
									if($user_subscription['storage'] < 1024) {
									?>
										<span id="used_storage"><b><?php echo $used_storage;?></b> </span>MB
									<?php
									}
									else {
									?>
										<span id="used_storage"><b><?php echo round($used_storage/1024);?></b> </span>GB
									<?php
									}
									?>
									 / 
									<?php
									if($user_subscription['storage'] < 1024) {
									?>
										<span id="max_storage"><b><?php echo $user_subscription['storage'];?></b></span><span id="storage_met">MB</span>
									<?php
									}
									else {
									?>
										<span id="max_storage"><b><?php echo round($user_subscription['storage']/1024);?></b></span> <span id="storage_met">GB</span>
									<?php
									}
									?>
									
									</span>
									</span>&nbsp;
								</div>
							</li>
					<?php } } ?>
				</ul>
			</li>
			<li>
				<div class="sett_div">
					<div>
						<ul>
							<li class="settings_hd">Personal Settings</li>	
							<li><a href="<?php echo HTTP_ROOT.'users/profile';?>">My Profile</a></li>
							<li><a href="<?php echo HTTP_ROOT.'users/email_notifications';?>">Notifications</a></li>
							<li><a href="<?php echo HTTP_ROOT.'users/email_reports';?>">Email Reports</a></li>
							<li style="margin-bottom:5px;border-bottom:none;box-shadow: none;"><a href="<?php echo HTTP_APP;?>users/logout" class="sign_out">Sign Out</a></li>
						</ul>	
					</div>
					<?php
					if(SES_TYPE == 1 || SES_TYPE == 2)
					{
					?>
					<div>
						<ul>
						    <li class="settings_hd">Company Settings</li>
							<li><a href="<?php echo HTTP_ROOT.'my-company';?>">My Company</a></li>
							<li><a href="<?php echo HTTP_ROOT.'reminder-settings';?>">Daily Catch-Up</a></li>
							<li><a href="<?php echo HTTP_ROOT.'import-export';?>">Import & Export</a></li>
							<li><a href="<?php echo HTTP_ROOT.'task-type';?>">Task Type</a></li>
						</ul>	
					</div>
					<?php
					}
					?>
				</div>
					
			</li>
		  </ul>
		  
		</li>			
	  </ul>
	  
	</div><!-- /.navbar-collapse -->
</nav>
<?php if(PAGE_NAME != "help" && PAGE_NAME != "tour" && PAGE_NAME != "customer_support") { ?>

<input type="hidden" name="pub_counter" id="pub_counter" value="0" />
<input type="hidden" name="hid_casenum" id="hid_casenum" value="0" />
<div style="display:block; position:fixed; width:88%; text-align:center;z-index: 2147483647; position:fixed">
     <div onClick="removePubnubMsg();" id="punnubdiv" align="center" style="display:none;">
          <div class="fls-spn">
              <div id="pubnub_notf" class="topalerts alert_info msg_span" ></div>
			  <div class="fr close_popup" style="margin:-48px 8px 0 0;">X</div>
          </div>
     </div>
</div>
<!-- Flash Success and error msg starts --> 
<div id="topmostdiv">
    <?php if ($success) { ?>
        <div onClick="removeMsg();" id="upperDiv" align="center" style="margin:0px auto;position:relative; text-align:center;">
	    <div class='fls-spn' id='msg-spn'>
		<div class="topalerts success msg_span">
			<?php echo $success; ?>
		</div>
		<div class="fr close_popup" style="margin:-48px 8px 0 0;">X</div>
	    </div>
        </div>
        <script>setTimeout('removeMsg()',6000);</script>
	<?php } elseif ($error) {
	    if (stristr($error, 'Object(CakeResponse)')) {

	    } else { ?>
		<div onClick="removeMsg();" id="upperDiv" align="center" style="margin:0px auto;position:relative; text-align:center;">
		    <div class='fls-spn' id='msg-spn'>
			<div class="topalerts error msg_span">
			    <?php echo $error; ?>	
			</div>
			<div class="fr close_popup" style="margin:-48px 8px 0 0;">X</div>
		    </div>	
		</div>
		<script>setTimeout('removeMsg()',6000);</script>
	    <?php }
	} else { ?>
	    <div onClick="removeMsg();" id="upperDiv" align="center" style="display:none; margin:0px auto;position:relative; text-align:center;">
		<div class='fls-spn' id='msg-spn'>
		    <div class="topalerts success msg_span" >
			    <?php echo $success; ?>
		    </div>
			<div class="fr close_popup" style="margin:-48px 8px 0 0;">X</div>
		</div>
	    </div>
	<?php } ?>
</div>
<!-- Flash Success and error msg ends --> 
<!-- common popups like Create task, Create project, Invite User -->
<?php } ?>
<?php echo $this->element('popup'); ?>
<!--  common popups -->
<?php if(PAGE_NAME != "help" && PAGE_NAME != "tour" && PAGE_NAME != "customer_support" && PAGE_NAME !='onbording'){ ?>
<!-- breadcrumb, project popup -->  
<input type="hidden" id="checkload" value="0">
<?php echo $this->element('breadcrumb');
if(PAGE_NAME=='dashboard'){?>
<div id="widgethideshow" class="fix-status-widget" <?php if(strtotime("+2 months",strtotime(CMP_CREATED))>=time()){?><?php }?>>
	<section id="widgets-container" class="widget_section" style="border-right:none">
		<span id="ajaxCaseStatus"></span>
	</section>
	<section id="widgets-containertype" style="display:none">
		<span id="ajaxCaseType" style="display:none"></span>
	</section>
</div>
<!--<div class="fr task_section case-filter-menu" data-toggle="dropdown" title="Task Filter" onclick="openfilter_popup(0);">
	<button type="button" class="btn tsk-menu-filter-btn" >
		<a href="javascript:void(0);" class="flt-txt">
			<i class="icon_flt_img"></i>
			Filters
			<i class="caret"></i>
		</a>
	</button>
		<ul class="dropdown-menu" id="dropdown_menu_all_filters">
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
<div class="cb"></div>-->
<!--<div class="dashborad-view-type" >
	<a href="<?php echo HTTP_ROOT.'dashboard#tasks';?>" onclick="checkHashLoad('tasks')"><div id="lview_btn" class="fl btn gry_btn kan30" rel="tooltip" title="List View"><i class="icon-list-view"></i></div></a>
	<a href="<?php echo HTTP_ROOT.'dashboard#kanban';?>" onclick="checkHashLoad('kanban')"><div id="kbview_btn" class="fl btn gry_btn kan30" style="border-radius:0 3px 3px 0" rel="tooltip" title="Kanban View"><i class="icon-kanv-view"></i></div></a>
	<div class="cb"></div>
</div>-->
<?php } } ?>
<div class="slide_rht_con">