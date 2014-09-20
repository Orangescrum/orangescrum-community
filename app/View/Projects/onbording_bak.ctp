<?php  ?>
<style type="text/css">
    .breadcrumb_div{display:none}
</style>
<div class="get_started_outer">
    <?php if(SES_TYPE<3) { ?>
    <div class="get_hd_bg">Getting Started with Orangescrum</div>
    <div class="get_det">
        <div class="fl get_img get_prj_bg">
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
        <div class="fl get_img get_usr_bg">
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
        <div class="fl get_img get_tsk_bg">
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
        <?php } ?>
    <div class="get_hd_bg">Respond via Email</div>
    <div class="get_det">
        <div class="fl get_img get_eml_bg">
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
    <div class="get_hd_bg">Settings</div>
    <div class="get_det">
        <div class="fl get_img get_tm_bg">
            <div class="get_time"></div>
            <div class="get_img_txt">Setting</div>
        </div>
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
        <div class="fl get_img get_not_bg">
            <div class="get_not"></div>
            <div class="get_img_txt">Notification</div>
        </div>
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
                    You can enable or disable Google Chrome Desktop Notification on the “Notification” page.
                </li>
            </ul>
        </div>
        <div class="cb"></div>
    </div>
    <div class="get_det" style="border-bottom:none">
        <div class="fl get_img get_emlrpt_bg">
            <div class="get_emlrpt"></div>
            <div class="get_img_txt">Reports</div>
        </div>
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