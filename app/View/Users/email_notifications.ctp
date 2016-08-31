<div class="user_profile_con thwidth">
<!--Tabs section starts -->
    <?php echo $this->element("personal_settings");?>

<div class="email_hd">
	<h2 style="">Send me Email Notification</h2>
</div>
<?php echo $this->Form->create('UserNotification',array('url'=>'/users/email_notifications','onsubmit'=>"return hideupdatebtn();")); ?>
<input type="hidden" name="data[UserNotification][id]" value="<?php echo @$getAllNot['UserNotification']['id']; ?>"/>
<input type="hidden" name="data[UserNotification][type]" value="1"/>
<input type="hidden" name="data[UserNotification][csrftoken]" class="csrftoken" readonly="true" value="" />
<table cellspacing="0" cellpadding="0" class="col-lg-5 email_mgt">
    <tbody>
        <tr>
            <th>All New Task:</th>
            <td>
		<input type="radio" title="Send me All New Task emails" rel="tooltip" name="data[UserNotification][new_case]" id="newcaseyes" value="1" <?php if(@$getAllNot['UserNotification']['new_case'] == 1) { echo 'checked="checked"'; } ?> />Yes
		<input type="radio" title="Send me New Task email, only when I am selected to get the email" rel="tooltip" name="data[UserNotification][new_case]" id="newcaseno" value="0" <?php if(@$getAllNot['UserNotification']['new_case'] == 0) { echo 'checked="checked"'; } ?> />No
	    </td>
        </tr>
        <tr>
            <th>All Task Reply & Comment:</th>
            <td>
		<input type="radio" title="Send me All Reply emails" rel="tooltip" name="data[UserNotification][reply_case]" id="replycaseyes" value="1" <?php if(@$getAllNot['UserNotification']['reply_case'] == 1) { echo 'checked="checked"'; } ?> />Yes
		<input type="radio" title="Send me Reply email, only when I am selected to get the email" rel="tooltip" name="data[UserNotification][reply_case]" id="replycaseno" value="0" <?php if(@$getAllNot['UserNotification']['reply_case'] == 0) { echo 'checked="checked"'; } ?> />No
	    </td>
        </tr>
        <tr>
            <th class="last">All Task Status Change:</th>
            <td class="last">
		<input type="radio" title="Send me All Status Update emails" rel="tooltip" name="data[UserNotification][case_status]" id="casestatusyes" value="1" <?php if(@$getAllNot['UserNotification']['case_status'] == 1) { echo 'checked="checked"'; } ?> />Yes
		<input type="radio" title="Send me Status Update email, only when I am selected to get the email" rel="tooltip" name="data[UserNotification][case_status]" id="casestatusno" value="0" <?php if(@$getAllNot['UserNotification']['case_status'] == 0) { echo 'checked="checked"'; } ?> />No
	    </td>
        </tr>					
    </tbody>
</table>
<div class="cbt"></div>
<?php
if (defined('NODEJS_HOST') && trim(NODEJS_HOST)) {
?>
<div class="email_hd">
	<h2>Show me Notification</h2>	
</div>

<table cellspacing="0" cellpadding="0" class="col-lg-5 email_mgt">
    <tbody>
        <tr>
            <th class="dsk_not">Enable Desktop Notification:</th>
            <td>
            <input type="radio" name="data[User][desk_notify]"  id="desknotifyyes" value="1" <?php if((int)DESK_NOTIFY == 1) { echo 'checked="checked"'; } ?> onclick="allowChromeDskNotify(this.checked);" />Yes
            <input type="radio" name="data[User][desk_notify]"  id="desknotifyno" value="0" <?php if((int)DESK_NOTIFY == 0) { echo 'checked="checked"'; } ?> />No
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left" style="font-size:11px;font-color:#666;border:1px solid #F0F0F0;">
				<ul style="padding:0;margin:0">Browser version supporting Desktop Notification,
                   <li style="margin-left:20px;"> Firefox 22 and above</li>
                   <li style="margin-left:20px;">Chrome 32 and above</li>
                   <li style="margin-left:20px;">Above Safari 6 on Mac OSX 10.8+</li>
                </ul>
	    	</td>
        </tr>
    </tbody>
</table>
<?php
}
?>
<div class="cbt"></div>
<table cellspacing="0" cellpadding="0" class="col-lg-5 email_tbl">
<tbody>
<tr>
		<th></th>
            <td class="btn_align btn_eml_lt">
            	<span id="subprof1">
		<input type="hidden" name="data[User][changepass]" id="changepass" readonly="true" value="0"/>
		<?php /* <button type="submit" value="Save" name="submit_Pass"  id="submit_Pass" class="btn btn_blue"><i class="icon-big-tick"></i>Update</button> */ ?>
		<button type="button" value="Save" name="submit_Pass"  id="submit_Pass" class="btn btn_blue" onclick="checkCsrfToken('UserNotificationEmailNotificationsForm');"><i class="icon-big-tick"></i>Update</button>
		<!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
         <span class="or_cancel">or
            <a onclick="cancelProfile('<?php echo $referer;?>');">Cancel</a>
        </span>
		</span>
		<span id="subprof2" style="display:none">
		    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
		</span>
            </td>
</tr>
</tbody>
</table>
<?php echo $this->Form->end(); ?>
<div class="cbt"></div>
</div>