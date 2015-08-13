<?php  ?>
<style type="text/css">
    .breadcrumb_div{display:none}
</style>
<div class="get_started_outer">
    <?php if(SES_TYPE<3) { ?>
    <div class="get_hd_bg">Getting Started with Orangescrum</div>
    <div class="get_det">
        <div class="fl get_img get_prj_bg" onclick="newProject();" style="cursor: pointer;">
            <div class="get_prj"></div>
            <div class="get_img_txt">Projects</div>
        </div>
        <div class="get_text fl">
            <div class="get_title"><?php echo $this->Html->link('Create and Assign Project','javascript:void(0);',array('onclick'=>'newProject();','class'=>'get_title'));?> <?php echo !empty($projects)?$this->Html->image('yes.png'):'';?> </div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    Name your project suitably, choose short name accordingly.
                </li>
                <li>
                    Add up your team members/customers to the project while creating.
                </li>
                <li>
                    Alternately, you can add team/customer to a project in ‘Manage Projects’ section.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <div class="get_det">
        <div class="fl get_img get_usr_bg" onclick="newUser();" style="cursor: pointer;">
            <div class="get_user"></div>
            <div class="get_img_txt">User</div>
        </div>
        <div class="get_text fl">
            <div class="get_title"><?php echo $this->Html->link('Invite User','javascript:void(0);',array('onclick'=>'newUser();','class'=>'get_title'));?> <?php echo !empty($invitations)?$this->Html->image('yes.png'):'';?></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    Send invitation to team member (separate by comma for multi Email IDs)
                </li>
                <li>
                    Invitees need to set up their account using link provided in the email.
                </li>
                <li>
                    Assign projects to your team members while inviting them.
                </li>
                <li>
                    You can assign/remove projects from your team members any time on manage users section.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <div class="get_det">
        <div class="fl get_img get_tsk_bg"  onclick="creatask();" style="cursor: pointer;">
            <div class="get_task"></div>
            <div class="get_img_txt">Tasks</div>
        </div>
        <div class="get_text fl">
            <div class="get_title"><?php echo $this->Html->link('Create','javascript:void(0);',array('onclick'=>'creatask();','class'=>'get_title'));?> or <?php echo $this->Html->link('Import Task','/import-export',array('class'=>'get_title'));?> <?php echo !empty($tasks)?$this->Html->image('yes.png'):'';?></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    Enter a title, put due date or set priority and create a task under a project.
                </li>
                <li>
                    Assign task to a team member, attach files from Google Drive or Dropbox while creating a task.
                </li>
                <li>
                    You can also Import a bunch of tasks from a .CSV file under a project.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    
     <div class="get_hd_bg" style="cursor:pointer" onclick="window.location='<?php echo HTTP_ROOT; ?>task-type'">Custom Task Type</div>
    <div class="get_det">
        <div class="fl get_img get_tsk_bg" style="cursor:pointer;background:#FFAAD4" onclick="window.location='<?php echo HTTP_ROOT; ?>task-type'">
            <div class="get_email" style="background:none;color:#FFF;width:auto;font-weight:bold;font-size:15px;height:55px;">
            	Custom<br/>Task Type
            </div>
        </div>
        <div class="get_text fl">
            <div class="get_title"></div>
            <ul style="padding:5px 15px;margin-top:0px;">
            	<li>
                    You can categorize your Task usign the Task Types.
                </li>
                <li>
                    It's doesn't matter whether your business is Education or Health Services or Construction, you can define your own Task Type and add Tasks under them
                </li>
                <li>
                   You can remove the default Task Type by unchecking the checkbox and save the changes.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    
    <?php } ?>
    <div class="get_hd_bg" >Respond via Email</div>
    <div class="get_det">
        <div class="fl get_img get_eml_bg" >
            <div class="get_email"></div>
            <div class="get_img_txt">Email</div>
        </div>
        <div class="get_text fl">
            <div class="get_title"></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    You can respond to the task Email sent from notify@orangescrum.com.
                </li>
                <li>
                    Your Email response will be posted on Orangescrum against that task.
                </li>
                <li>
                    Respond on a task even on-the-go from your mobile via Email.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <?php
	if (defined('NODEJS_HOST') && trim(NODEJS_HOST)) {
	?>
   	<div class="get_hd_bg" style="cursor:pointer" onclick="window.location='<?php echo HTTP_ROOT; ?>email_notifications'">Desktop Notification</div>
    <div class="get_det">
        <div class="fl get_img get_not_bg" style="cursor:pointer" onclick="window.location='<?php echo HTTP_ROOT; ?>email_notifications'">
            <div class="get_not" style="background:url('<?php echo HTTP_ROOT; ?>img/bell.png');height:25px;width:27px;"></div>
            <div class="get_img_txt">Notification</div>
        </div>
        <div class="get_text fl">
            <div class="get_title"></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                   The Desktop Notification works on heigher versions of most of the browsers.
                   Firefox 22 and above, Chrome 32 and above, Safari 6 on Mac OSX 10.8+
                </li>
                <li>
                   You'll see a pop-up when a new task or reply arrives so you can keep track of your Tasks even when you're not looking at Orangescrum.
                </li>
                <li>
                    Turn the desktop notification On or Off in the "<?php echo $this->Html->link('Notifications','/users/email_notifications');?>" page.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <?php
	}
	?>
    <div class="get_hd_bg">Settings</div>
    <div class="get_det">
		<a class="get_title" href="<?php echo HTTP_ROOT; ?>users/profile" style="font-size: 14px;">
        <div class="fl get_img get_tm_bg">
            <div class="get_time"></div>
            <div class="get_img_txt">Setting</div>
        </div>
	</a>
        <div class="get_text fl">
            <div class="get_title"><?php echo $this->Html->link('Timezone & Profile','/users/profile',array('class'=>'get_title'));?></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    Personalize your Orangescrum account by setting up your Profile details and Timezone.
                </li>
                <li>
                    The Timezone settings help you to keep stay up-to-date while working with a virtual or remote team.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <div class="get_det">
	<a class="get_title" href="<?php echo HTTP_ROOT; ?>users/email_notifications" style="font-size: 14px;">
        <div class="fl get_img get_not_bg">
            <div class="get_not"></div>
            <div class="get_img_txt">Notification</div>
        </div>
	</a>
        <div class="get_text fl">
            <div class="get_title"><?php echo $this->Html->link('Notifications','/users/email_notifications',array('class'=>'get_title'));?></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    The Email Notification is set to "No" by default, to get email those email only when selected when task is posted.
                </li>
                <li>
                    Set to "Yes", to get all the Task related Emails from all your assigned projects from Orangescrum.
                </li>
                <li>
                    You can enable or disable Google Chrome Desktop Notification on the "Notification" page.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <div class="get_det" style="border-bottom:none">
	<a class="get_title" href="<?php echo HTTP_ROOT; ?>users/email_reports" style="font-size: 14px;">
        <div class="fl get_img get_emlrpt_bg">
            <div class="get_emlrpt"></div>
            <div class="get_img_txt">Reports</div>
        </div>
	</a>
        <div class="get_text fl">
            <div class="get_title"><?php echo $this->Html->link('Email Reports','/users/email_reports',array('class'=>'get_title'));?></div>
            <ul style="padding:5px 15px;margin-top:0px;">
                <li>
                    Customize your Email report settings.
                </li>
                <li>
                    Select projects and set your daily update settings.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
</div>