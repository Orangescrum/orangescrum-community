<script>
$(document).ready(function() {
		var strURL = document.getElementById('pageurl').value;
		var id = "<?php echo $getAllDailyupdateNot['DailyupdateNotification']['proj_name']; ?>";
		strURL = strURL+"projects/";
		var url = strURL+"ajax_json_project/"+id;
            $("#proj_name_choose").tokenInput(url, {
                theme: "facebook",
				preventDuplicates: true
            });
        });
</script>
<style>
form ul.holder {
    min-height: 60px;
    width: 227px;
}
ul.proj-list {
  clear: left;
  cursor: text;
  font-family: Verdana;
  font-size: 12px;
  height: auto !important;
  list-style-type: none;
  margin: 0;
  min-height: 1px;
  overflow: hidden;
  padding: 0;
  width: 241px;
  z-index: 999;
}
</style>
<div class="row-fluid">
	<article class="span12 data-block nested">
		<div class="data-container">
			<section class="tab-content">
				<div class="tab-pane" id="horizontal">	
					<table width="100%" border="5" cellspacing="2" cellpadding="2" align="center">
						<tr>
							<td valign="top">
								<table width="100%" border="5" cellspacing="5" cellpadding="5" align="center" >
								<tr height="35px">
								<td valign="top" colspan="2" width="100%">
								<?php echo $this->Form->create('User',array('url'=>'/users/notification')); ?>
								<table cellpadding="6" cellspacing="6" align="center" border="5" width="100%"  >
								<tr><td colspan="2" class="font-headings"  align="left"></td></tr>
								<tr><td colspan="2"><div id="divide"></div></td></tr>
<!--								<tr height="40px">
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Enable/Disable category tab for dashboard
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333">
									<input type="radio" name="category_tab" value="15" style="position:relative;top:-2px;cursor:pointer" <?php if(ACT_TAB_ID>1) { echo 'checked="checked"'; } ?> title="Enable my dashboard tab" rel="tooltip">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="category_tab" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(ACT_TAB_ID<=1) { echo 'checked="checked"'; } ?> title="Disable my Dashboard tab" rel="tooltip"> No
								</td>
								</tr>
								<tr>
									<td colspan="2"  class="case_fieldprof" align="center" width="35%" style="font-weight:normal;padding-top:10px;">
										------- <span style="color:#0B95CD;font-weight:bold;font-size:14px;">Email Notifications</span> ---------
									</td>
								</tr>-->
								<tr height="40px">
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me All <b>New Task</b> Email notification:
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333">
									<input type="radio" name="data[UserNotification][new_case]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['new_case'] == 1) { echo 'checked="checked"'; } ?> title="Send me All New Task emails" rel="tooltip">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][new_case]" value="0" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['new_case'] == 0) { echo 'checked="checked"'; } ?> title="Send me New Task email, only when I am selected to get the email" rel="tooltip"> No
								</td>
								</tr>
								<tr height="30px">
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me All <b>Task Reply & Comment</b> Email notification:
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333;">
									<input type="radio" name="data[UserNotification][reply_case]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['reply_case'] == 1) { echo 'checked="checked"'; } ?> title="Send me All Reply emails" rel="tooltip">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][reply_case]" value="0" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['reply_case'] == 0) { echo 'checked="checked"'; } ?> title="Send me Reply email, only when I am selected to get the email" rel="tooltip"> No
								</td>
								</tr>
								<tr height="30px">
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me All <b>Task Status Change</b> Email notification:
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333;">
									<input type="radio" name="data[UserNotification][case_status]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['case_status'] == 1) { echo 'checked="checked"'; } ?> title="Send me All Status Update emails" rel="tooltip">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][case_status]" value="0" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['case_status'] == 0) { echo 'checked="checked"'; } ?> title="Send me Status Update email, only when I am selected to get the email" rel="tooltip"> No
								</td>
								</tr>
								<?php if(SES_TYPE<3){?>
								<tr height="30px">
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me  <b>Weekly Usage Report</b>: 
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333;">
									<input type="radio" name="data[UserNotification][weekly_usage_alert]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['weekly_usage_alert'] == 1) { echo 'checked="checked"'; } ?> title="Send me All Weekly usage details emails" rel="tooltip">&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][weekly_usage_alert]" value="0" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['weekly_usage_alert'] == 0) { echo 'checked="checked"'; } ?> title="Send me All Weekly usage details emails when i am selected to get the email " rel="tooltip"> No
								</td>
								</tr>
								<?php } ?>
								<tr height="30px">
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Enable Google Chrome <b>Desktop Notification</b>:
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333;">
									<input type="checkbox" name="data[User][desk_notify]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if((int)DESK_NOTIFY == 1) { echo 'checked="checked"'; } ?> title="Show desktop notifications" rel="tooltip" onclick="allowChromeDskNotify(this.checked);">
								</td>
								</tr>
								<tr>
									<td colspan="2"  class="case_fieldprof" align="center" width="35%" style="font-weight:normal;padding-top:10px;">
										------- <span style="color:#0B95CD;font-weight:bold;font-size:14px;">Reports</span> ---------
									</td>
								</tr>
								<tr>
								<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me <b>Task Status</b> Email report:
								</td>
								<td align="left" style="font-weight:normal;font-size:14px;color:#333333;width:33%">
								<input type="radio" name="data[UserNotification][value]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['value'] == 1) { echo 'checked="checked"'; } ?>> Daily&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][value]" value="2" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['value'] == 2) { echo 'checked="checked"'; } ?>> Weekly&nbsp;&nbsp;&nbsp;<input type="radio" value="3" name="data[UserNotification][value]" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['value'] == 3) { echo 'checked="checked"'; } ?>> Monthly&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][value]" style="position:relative;top:-2px;cursor:pointer" value="0" <?php if(@$getAllNot['UserNotification']['value'] == 0) { echo 'checked="checked"'; } ?>> None
								
								<input type="hidden" name="data[UserNotification][id]" value="<?php echo @$getAllNot['UserNotification']['id']; ?>"/>
								<input type="hidden" name="data[UserNotification][type]" value="1"/>
								</td>
								</tr>
								<tr height="40px">
									<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me <b>Task Due</b> daily Email report:
									</td>
									<td align="left" style="font-weight:normal;font-size:14px;color:#333333">
										<input type="radio" name="data[UserNotification][due_val]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['due_val'] == 1) { echo 'checked="checked"'; } ?> >&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="data[UserNotification][due_val]" value="0" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllNot['UserNotification']['due_val'] == 0) { echo 'checked="checked"'; } //id="dly_update_dtls" ?> > No
									</td>
								</tr>
                                <tr height="40px">
									<td  class="case_fieldprof" align="right" width="35%" style="font-size:14px;font-weight:normal;">
									Send me <b>Daily Update</b> Email report:
									</td>
									<td align="left" style="font-weight:normal;font-size:14px;color:#333333">
										<input type="radio" name="data[DailyupdateNotification][dly_update]" value="1" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllDailyupdateNot['DailyupdateNotification']['dly_update'] == 1) { echo 'checked="checked"'; } ?> onclick="show_details()" id="dly_update_yes" >&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="data[DailyupdateNotification][dly_update]" value="0" style="position:relative;top:-2px;cursor:pointer" <?php if(@$getAllDailyupdateNot['DailyupdateNotification']['dly_update'] == 0) { echo 'checked="checked"'; }?> onclick="hide_details()" id="dly_update_no"> No
									</td>
								</tr>
                                <tr>
                          			<td colspan="2" align="center">
                                    <?php
										if(@$getAllDailyupdateNot['DailyupdateNotification']['dly_update'] == 1){
											$style = 'style="display:block"';
											$hr_min = split(':',$getAllDailyupdateNot['DailyupdateNotification']['notification_time']);
										}
										else
											$style = 'style="display:none"';
									?>
                                    <div <?php echo $style ?> id="dly_update_dtls">
                                               <table width="534">
                                                <tr>
                                                    <td  class="case_fieldprof" align="right" width="52%" style="font-size:14px;font-weight:normal;">
                                                    Time:
                                                    </td>
                                                    <td width="48%" align="left" style="font-weight:normal;font-size:14px;color:#333333">
                                                    <div style="float:left">
                                                     <select id="not_hr" class="select" style="width:80px;padding:2px;" name="data[DailyupdateNotification][not_hr]">
                                                     <option selected="" value="Hour">Hour</option>
						<?php 
							for($i = 1;$i<=24;$i++){
							if($i<9){
							$i = '0'.$i;
							}
                        ?>
					 <option value="<?php echo $i; ?>" <?php if($i == $hr_min[0]) echo 'selected'; ?>><?php echo $i; ?></option>
                     <?php
					}
					
				?>
                </select>
                                                        </div>
                                                         <div style="float:left; margin-left:5px;"> <b>:</b></div>
                                                        <div style="float:left; margin-left:5px;">
                                                         <select id="not_mn" class="select" style="width:80px;padding:2px;" name="data[DailyupdateNotification][not_mn]">
                                                        <option selected="" value="Min">Min</option>
                                                        <?php
                                                        for($i =0;$i<=45;$i=$i+15){
                                                            if($i<10)
                                                                $i = '0'.$i;
                                                            ?>
                                                            <option value="<?php echo $i; ?>"<?php if($i == $hr_min[1]) echo 'selected'; ?>><?php echo $i; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                        </select>
                                                        </div>
                                                    </td>
                                                
                                                </tr>
                                                <?php
													if($getAllDailyupdateNot['DailyupdateNotification']['proj_name'] != ''){
												?>
                                                <tr height="33px" id="proj_tr">
                                                <td class="case_fieldprof" align="right" width="52%" style="font-size:14px;font-weight:normal;">Project(s):</td>
                                                <td>
                                                <?php
													if($getAllDailyupdateNot['DailyupdateNotification']['proj_name'])
														$proj_name = split(',',$getAllDailyupdateNot['DailyupdateNotification']['proj_name']);
												?>
                                                <ul class="proj-list" id="ul_proj">
                                                <?php
												for($i= 0;$i<count($proj_name);$i++){
												?>
                                                <input type="hidden" id="hid_proj<?php echo $i;?>" name="data[DailyupdateNotification][proj_name][]" value="<?php echo $proj_name[$i]; ?>" />
                                              	  <li class="token-input-token-facebook" id="prj<?php echo $i;?>"><p><?php echo $this->Casequery->getpjname($proj_name[$i]); ?></p><span class="token-input-delete-token-facebook" onclick="hideProj('prj'+<?php echo $i;?>,'hid_proj'+<?php echo $i;?>)">&times;</span></li>
                                              <?php
												}
												?>
                                                </ul>
                                                </td>
                                                </tr>
                                                
                                                <?php
												}
												?>
                                            <tr>
                                                <td  class="case_fieldprof" align="right" width="52%" style="font-size:14px;font-weight:normal;">
                                                Select Project:
                                                </td>
                                                <td align="left" style="font-weight:normal;font-size:14px;color:#333333">
                                                    <div class="span4">                         
                                                    <?php /*?><input type="text" maxlength="1000" id="proj_name" name="data[DailyupdateNotification][proj_name]" value = <?php echo @$getAllNot['DailyupdateNotification']['proj_name'];?> /><?php */?>
                                                    <div class="holder_ovvd">
                                                    <?php /*?><select multiple="multiple"  id="select_proj"  style="display:block" name="data[DailyupdateNotification][select_proj]"></select><?php */?>
                                                    <input type="text" id="proj_name_choose" name="data[DailyupdateNotification][proj_name][]" />
                                                    </div>
                                                   
                                                    </div>
                                                </td>
                                            </tr>
                                            </table> 
                                           </div>
                               	  </td>
								</tr>
								<tr>
								<td align="center" colspan="2">
								<span id="subprof1" class="fnt_opensans">
								<button type="button" value="Update" name="submit_Profile" id="submit_Profile" class="pop_btn" onclick="chk_validation()">Update</button>
								&nbsp;&nbsp;or&nbsp;&nbsp;
								<a href="<?php echo HTTP_ROOT; ?>dashboard">Cancel</a>
								</span>
								<span id="subprof2" style="display:none">
								Updating... <img src="<?php echo HTTP_IMAGES;?>images/del.gif" alt="Loading..." width="16" height="16"/>
								</span>
								</td>
								</tr>
								</table>
								<?php echo $this->Form->end(); ?>
								</td>
								</tr>
								</table>			
							</td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</article>
</div>	
<script>
$("#select_proj").click(function(){								   					
//alert(9);
var strURL = document.getElementById('pageurl').value;
strURL = strURL+"projects/";
//var url = strURL+"ajax_json_members";
var url = strURL+"ajax_json_project";
$("#select_proj").autocomplete({json_url:url});					   
});
function checkImage(img_id)
{
var fileName = document.getElementById(img_id).value;
var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
if(ext == "jpg" || ext == "JPG" || ext == "gif" || ext == "GIF" || ext == "jpeg" || ext == "JPEG" || ext == "bmp" || ext == "BMP" || ext == "png" || ext == "PNG")
{
document.getElementById('showfile').value=fileName;
return true;
}
else
{
alert("Invalid input file format! Should be an image file.");
document.getElementById(img_id).value="";
return false;
}
}
function show_details(){
	document.getElementById('dly_update_dtls').style.display = 'block';
}
function hide_details(){
	document.getElementById('dly_update_dtls').style.display = 'none';
}
function chk_validation(){
	//var radios = document.getElementsByName("data[DailyupdateNotification][dly_update]").checked;
	//var lenn = document.getElementsByName("data[DailyupdateNotification][dly_update]");
	//alert( document.getElementsByName("data[DailyupdateNotification][dly_update][0]").checked);
	//alert(lenn.length);
	//return false;
	if($("#UserEmailNotificationForm input[name='data[DailyupdateNotification][dly_update]']:checked").val() == 1){
		if($("#not_hr").val() == 'Hour' ||$("#not_mn").val() == 'Min' ){
			alert('Enter valid time.');
			return false;
		}else if($("#proj_name_choose").val() == '' && $("#ul_proj li").size() == 0){
				alert('Select project.');
				$("#proj_name").focus();
				return false;
		}		
	}
	$("#UserEmailNotificationForm").submit();
	
}
function hideProj(proj_name,proj_hid) {
	$("#"+proj_name).fadeOut(350)
	$("#"+proj_name).remove();
	$("#"+proj_hid).val('');
	$("#"+proj_hid).remove();
	  if($("#ul_proj li").size() == 0){
		  $("#proj_tr").remove();
	  }
}
</script>