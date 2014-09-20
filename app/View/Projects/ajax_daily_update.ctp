<?php echo $this->Form->create('Project', array('name' => 'dailyUpdateForm','id' => 'dailyUpdateForm','url'=>"/projects/dailyUpdate")); ?>
<table cellspacing="0" cellpadding="0" width="560px" class="div_pop" align="center" style="margin:40px auto;">
    <tr>
	<td style="padding-left:10px;" valign="middle" colspan="2" class="ms_hd">
	    <div style="float:left"><h1 style="margin:0;padding:0;" class="popup_head">Daily Progress Reminder</h1></div>
	    <div style="float:right;padding-right:5px;"><img src="<?php echo HTTP_IMAGES; ?>images/popup_close.png" alt="Close" title="Close" onClick="cover_close('cover', 'dailyupd_popup');" style="cursor:pointer" /></div>
	</td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr id="tr_project">
	<td align="right" valign="top" width="115px;" class="case_fieldprof" style="padding-top:14px;">Project: </td>
	<td align="left" valign="top" style="padding-top:10px;">
	    <select name="data[Project][uniq_id]" class="text_field" id="project_id" style="width:350px;" onchange="getProjectMembers(this);">
		<option value="">--Select--</option>
		<?php if(isset($project)){
		    foreach($project as $key => $value){ ?>
		    <option value="<?php echo $key;?>"><?php echo ucfirst($value);?></option>
		    <?php }
		} ?>
	    </select>
	    <span id="loading_sel" style="display: none;"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." /></span>
	</td>
    </tr>
    <tr id="tr_members">
	<td colspan="2"></td>
    </tr>
    <tr>
	<td>&nbsp;</td>
	<td align="left" style="padding-top:10px">
	    <div style="display: none;padding-bottom: 7px;" id="cancel_daily_update"><a style="color: red;" onclick="cancel_daily_update();" href="javascript:jsVoid();">Cancel Daily Progress Reminder</a></div>
	    <span id="btn_addmem" style="padding-right:10px">
		<span id="loading_btn" style="display: none;"><img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." /></span>
		<span id="spn_btn"><button type="button" id="daily_btn" style="margin-top:5px;width:75px" class="pop_btn" onclick="return validateDailyMail();">Save</button></span>
		<span class="fnt_opensans">&nbsp;&nbsp;or&nbsp;&nbsp;
		<a onclick="cover_close('cover','dailyupd_popup');" href="javascript:jsVoid();">Cancel</a></span>
	    </span>
	    <span id="err_msg_spn" style="color: red;display: none;"></span>
	</td>
    </tr>
    <tr><td colspan="2">&nbsp;</td></tr>
</table>
<?php echo $this->Form->end();?>