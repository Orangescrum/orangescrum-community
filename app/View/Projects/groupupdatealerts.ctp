<div class="user_profile_con">
<!--Tabs section starts -->
    <?php echo $this->element("company_settings");?>
<div class="fl grpalert">
	<?php 
 echo $this->Form->create('Project', array('name' => 'dailyUpdateForm','id' => 'dailyUpdateForm','url'=>"/projects/dailyUpdate")); ?>
    <table cellspacing="0" cellpadding="0" class="col-lg-5 dailyTbl grp_tbl" style="text-align:left; width:100%;">
        <tbody>
        <tr id="tr_project">
                <th style="padding:5px 0 5px; vertical-align:top;">Project:</th>
                <td style="padding:0 0 5px;">
            <div class="fl">
		<select name="data[Project][uniq_id]" class="form-control dailyUpdate_sel" id="project_id" onchange="getProjectMembers(this);">
			<option value="">[Select]</option>
			<?php if(isset($project)){
				foreach($project as $key => $value){ ?>
				<option value="<?php echo $key;?>"><?php echo ucfirst($value);?></option>
				<?php }
			} ?>
		</select>
		</div>
		<div class="fl loaderDiv">
		    <span id="loading_sel" style="display: none;"><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..." /></span>
		</div>
		<div class="cb"></div>
	    </td>
        </tr>
        <tr id="tr_members">
			<td colspan="2" style="padding:0 0 10px;"></td>
		</tr>
    	<tr>
			<th></th>
			<td class="btn_align">
				<div style="display: none;padding: 0 0 25px;" id="cancel_daily_update"><a style="color: red;text-decoration: underline;" onclick="cancel_daily_update();" href="javascript:jsVoid();">Cancel Daily Catch-Up</a></div>
				<div>
				<span id="subprof1">
					<button type="submit" name="submit_Pass"  id="daily_btn_disable" class="btn btn_blue btn_disabled" disabled="true"><i class="icon-big-tick"></i>Save</button>
					<button type="submit" name="submit_Pass"  id="daily_btn" class="btn btn_blue" onclick="return validateDailyMail();" style="display:none"><i class="icon-big-tick"></i>Save</button>
					<!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
					 <span class="or_cancel">or
						<a onclick="cancelProfile('<?php echo $referer;?>');">Cancel</a>
					</span>
				</span>
				<div id="subprof2" style="display:none;padding: 0px 140px;">
					<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
				</div>
				</div>				
			</td>
        </tr>
    </tbody>
</table>
<?php echo $this->Form->end(); 
?>
</div>


<div class="fl import-info-dif dl_updt">
    <div class="chk_content" style="width:100%">
        <h4 class="chk_head">Do you want to get daily progress update from your team member(s)?<br/>Just schedule it and sit back, end of the day you will get the update email in your inbox.</h4>
        <ul class="chk_desc">
            <li>Get Daily Team updates without nagging them.</li>
            <li>Get Daily Progress from your team mates in a single email</li>
            <li>You have a distributed team, every tasks can not be captured, you just want your team send daily update, just set it here and sit back.</li>
            <li>Automate daily updates from your team.</li>
        </ul>
        <div><img src="<?php echo HTTP_IMAGES; ?>os_mail_new1.jpg" style="width:100%;height:100%"/></div>
    </div>
</div>
<div class="cbt"></div>
</div>
