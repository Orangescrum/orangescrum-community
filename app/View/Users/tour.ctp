<style type="text/css">
*,*:before,*:after{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.each_list_head {
	border-bottom:0px solid #FFFFFF;
	margin-bottom:0;
}
.right_panel_list {
	margin-left:25px;
}
.features {
	border:1px solid #B3CFE1;
}
.features:hover {
	box-shadow:0 0 12px #B3CFE1
}
.classli {
	margin-left:25px; line-height:25px; margin-bottom:20px;
}

</style>
<style>
.img_loader { 
left: 60%; 
position: fixed; 
text-align: center; 
top: 50%;
z-index: 99999;}

footer{margin-bottom:-80px}
</style>
<script type="text/javascript">
/*$(document).ready(function(){
$('.tour_section img').each(function(){
$(this).after('<img class="img_loader" src="<?php //echo HTTP_HOME."img/images/case_loader.gif";?>" style="opacity:0.7"/>') // some preloaded "loading" image
                 .animate({  opacity: 0.25})
                 .attr('src',this.src)
                 .one('load', function() {
                    $(this).animate({opacity: 1}).fadeIn().next().remove();
                 });
});
});*/
</script>
<?php
$menuArray = array(
				array("calendar","Calendar"),
				array("dashboard","Dashboard"),
				array("daily-catch-up","Daily Catch-Up"),
				array("task-template","Task Template"),
				array("kanban","Kanban View"),
				array("drive-dropbox","Drive & Dropbox"),
				array("conversation-threads","Conversation Threads"),
				//array("bug-issue-tracker","Bug/Issue Tracker"),
				array("ticketing-work-flow","Ticketing/Work-flow"),
				array("time-tracking","Time Tracking"),
				array("activities","Activities"),
				array("notification","Email Notification"),
				array("respond","Respond via Email"),
				array("desktop-notification","Desktop Notification"),
			);
?>
<?php if($this->Session->read('Auth.User.id')==''){?>
<div class="gray_pattern gray_color tour_section" style="padding-top:80px; background:#ffffff">
<?php }else{?>
<div class="gray_pattern gray_color tour_section" style="padding-top:0px; background:#ffffff">
<?php } ?>
	<div class="wrapper_help">
		<div class="head fl"><h3>How it Works</h3></div>
		<div class="cb"></div>
		<div class="left_panel_list fl">
			<ul>
				<?php 
				foreach ($menuArray as $key => $arr) {
					if($key == (count($menuArray) - 1)){
						$classLast = 'class="last"';
					}else{
						$classLast = '';
					}
					if(($arr[0] == $tour) || (!$tour && $key == 0)){
						$selectColor = 'color:#4F92BF';
					}else{
						$selectColor = '';
					}
				?>
				<li <?php echo $classLast; ?>>
					<a href="<?php echo HTTP_ROOT."tour/".$arr[0];?>" style="outline:none;<?php echo $selectColor; ?>"><?php echo $arr[1];?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<div id="display_div">
			<div class="right_panel_list fl">
				<?php 
				if($tour == "calendar"  || !$tour) {
				?>
					<div class="each_list_head">
						View and schedule task deadlines
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Easy to see what's due and when.</li>
						<li>The task colors helps to track the status.</li>
						<li>You can drag a task to another due date.</li>
						<li>Click on any date to create new task with the due date</li>
					</ul>
					<div class="cb" align="center">
						<a href="<?php echo HTTP_ROOT;?>img/features/calendar.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/calendar.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="calendar.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				if($tour == "dashboard") {
				?>
					<div class="each_list_head">
						Clear and concise graphics of your Projects
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Provides interactive summary of Projects.</li>
						<li>Consolidates, aggregates, and arranges reports in a visual representation.</li>
						<li>Displayed on a single screen so information can be monitored at a glance.</li>
						<li>Demonstrate to project managers where corrective action needs to be taken.</li>
					</ul>
					<div class="cb" align="center">
						<a href="<?php echo HTTP_ROOT;?>img/features/dashboard.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/dashboard.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="dashboard.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "daily-catch-up") {
				?>
					<div class="each_list_head">
						Just schedule it and get Daily Progress Update from your Team
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Get Daily Team updates without nagging them.</li>
						<li>Get Daily Progress from your team mates in a single email.</li>
						<li>You have a distributed team, every tasks can not be captured, you just want your team send daily update, just set it here and sit back.</li>
						<li>Automate daily updates from your team.</li>
					</ul>
					<div class="cb" align="center">
						<a href="<?php echo HTTP_ROOT;?>img/features/catchup.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/catchup.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="catchup.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "task-template") { ?>
					<div class="each_list_head">
						Save time using Task Template
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Create your custom Task Templates, use that while creating Tasks</li>
						<li>Orangescrum provides some default Task Templates.</li>
						<li>No formatting required every time you create Daily Updates, Meeting Minutes, Status updates or Bugs on Orangescrum</li>
					</ul>
					<div class="cb" align="center">
						<a href="<?php echo HTTP_ROOT;?>img/features/task-template.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/task-template.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="Task Template" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				} 
				elseif($tour == "kanban") {
				?>
					<div class="each_list_head">
						Increase productivity using kanban view
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Kanban view you can make out what's in progress, who's doing what, what needs to be done next, and what has been accomplished</li>
						<li>Track your project progress, workflow and view who is doing what. It is light and straightforward being user-friendly.</li>
						<li>You just need to hit a button to switch to Kanban view from classical view</li>
					</ul>
					<div class="cb" align="center">
						<a href="<?php echo HTTP_ROOT;?>img/features/kanban_2.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/kanban_2.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="kanban.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "drive-dropbox") {
				?>
					<div class="each_list_head">
						Share project documents with team using Dropbox, Google Drive
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Attaching a file from Dropbox or Google Drive, it is simply linked to the cloud storage</li>
						<li>Trim down Orangescrum storage usage</li>
						<li>No more managing files from multiple locations</li>
					</ul>
					<div class="cb" align="center">
						<a href="<?php echo HTTP_ROOT;?>img/features/share.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/share.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="Share" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "conversation-threads") {
				?>
					<div class="each_list_head">
						Have all conversation threads at one place
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Easy to track from Task Title</li>
						<li>No conversation is lost</li>
						<li>Open the relevant doc(s) in-line with the thread</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/features/thread.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/thread.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="thread.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "bug-issue-tracker") {
				?>
					<div class="each_list_head">
						Use as Bug/Issue Tracker
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Get an overview of the progress and status from the 'Bug Glide Chart' </li>
						<li>Get a report of # of bugs opened and resolved</li>
						<li>Generate 'Bug Glide Chart' for a period of time by specifying start and end date</li>
						<li>Get complete bug status at a glance</li>
					</ul>
					<div class="cb" align="center">
						<div style="text-decoration:underline;text-align:right;width:100%;font-size:12px;margin-bottom:5px;"><span style="cursor:pointer" onclick="showHideReport()" id="bugtext">See Bug Report</span></div>
						<div class="cb"></div>
						<div style="display:block" id="bug_tracker"><a href="<?php echo HTTP_ROOT;?>img/features/bug.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/bug.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="bug.gif" class="features"/></a></div>
						<div style="display:none" id="bug_report"><a href="<?php echo HTTP_ROOT;?>img/features/bug_reports.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/bug_reports.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="bug_reports.gif" class="features"/></a></div>
					</div>
					<div class="cb"></div>
					<script>
					function showHideReport() {
						if(document.getElementById('bug_report').style.display == 'none') {
							$("#bug_report").slideDown();
							$("#bug_tracker").slideUp();
							$("#bugtext").html("<< Back");
						}
						if(document.getElementById('bug_tracker').style.display == 'none') {
							$("#bug_tracker").slideDown();
							$("#bug_report").slideUp();
							$("#bugtext").html("See Bug Report");
						}
					}
					</script>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "ticketing-work-flow") {
				?>
					<div class="each_list_head">
						Use as Ticketing/Work-flow
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Manage status such as NEW, START, In Progress, RESOLVE, CLOSE</li>
						<li>Set priorities of each ticket such as LOW, MEDIUM, HIGH</li>
						<li>Set SLA by putting 'Due date' for each ticket</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/features/workflow.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/workflow.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="workflow.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "time-tracking") {
				?>
					<div class="each_list_head">
						Time Tracking
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>You can put time spent on Create Task or Reply Task</li>
						<li>Track Total Time spent on your projects</li>
						<li>Compare Actual Time Spent with Time Planned, have full control</li>
						<li>Helps you to take necessary action before it is too late</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/features/hours_analytics.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/hours_analytics.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="hours_analytics.gif" class="features"/></a>
						<?php /*?><a href="<?php echo HTTP_ROOT;?>img/time-tracking.jpg?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/time-tracking.jpg?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="img/time-tracking.jpg" class="features"/></a><?php */?>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "activities") {
				?>
					<div class="each_list_head">
						Observe activities on a single page
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Observe activity log on a single page with status, due date, priority, type and assigned project members</li>
						<li>View Overdue Tasks and Upcoming Tasks on this dashboard</li>
						<li>Find all conversation threads on a single page</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/features/activity.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/activity.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="activity.gif" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "notification") {
				?>
					<div class="each_list_head">
						Get notified via email upon task completion
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Schedule automatic alert emails to the team members reminding to send their daily status updates</li>
						<li>Users can send work updates simply by replying to the email</li>
						<li>Managers find all the work updates under a single thread reply mail</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/wt_u_gt_scrn7.png?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/wt_u_gt_scrn7.png?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="img/wt_u_gt_scrn7.png" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "respond") {
				?>
					<div class="each_list_head">
						Respond to email notification from mobile on-the-go
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>Auto Email notification sent upon task completion</li>
						<li>You can respond even on-the-go from your mobile</li>
						<li>All communication threads displayed chronologically</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/wt_u_gt_scrn8.png?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/wt_u_gt_scrn8.png?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="img/wt_u_gt_scrn8.png" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				elseif($tour == "desktop-notification") {
				?>
					<div class="each_list_head">
						Desktop Notification works with the Chrome browser
					</div>
					<div class="cb"></div>
					<ul class="classli">
						<li>A message with task information pops up on your desktop for any changes done to a task</li>
						<li>Even though you are swamped with activities on your computer you will never miss the Chrome Desktop Notification</li>
					</ul>
					<div class="cb" align="center" >
						<a href="<?php echo HTTP_ROOT;?>img/features/notification.gif?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/features/notification.gif?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="notification.gif" class="features"/></a>
						<div class="cb"></div><br/>
						<a href="<?php echo HTTP_ROOT;?>img/chrome_notify_2.png?v=<?php echo RELEASE; ?>" target="_blank"><img src="<?php echo HTTP_ROOT;?>img/chrome_notify_2.png?v=<?php echo RELEASE; ?>" style="max-width:100%" alt="img/chrome_notify_2.png" class="features"/></a>
					</div>
					<div class="cb"></div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<div class="cb push_down"></div>
<?php if($this->Session->read('Auth.User.id')==''){?>
<div class="sub_form_bg tour_btn" style="margin:70px auto;text-align:center;">
	<a style="text-decoration:none;" href="<?php echo PROTOCOL."www.".DOMAIN; ?>signup/getstarted<?php echo $ablink; ?>" onclick="getstarted_ga(' features');">
	   <span class="tk_tour" style="padding:10px 30px">Get Started for Free</span>
	</a>
</div>
<?php }?>