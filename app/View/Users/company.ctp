<div class="row-fluid">
	<article class="span12 data-block nested">
		<div class="data-container">
			<section class="tab-content">
				<div class="tab-pane" id="horizontal">
						<table width="100%" border="5" cellspacing="2" cellpadding="2" align="center">
							<?php
							if(!$user_subscription['is_free']) {
							?>
							<tr>
								<td align="left">
									<div class="notification information" style="margin: -2px -2px;">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left">
													<font style="color:#0576A6;line-height:22px;">
													You are currently using <font style="color:#EB592A;">
													<b>
													 <?php 
														echo "Orangescrum ".$GLOBALS['plan_types'][$user_subscription['subscription_id']]." &nbsp;&nbsp;&nbsp;";
														if($user_subscription['subscription_id']<4){
															echo "<a href='".HTTP_ROOT."pricing'>Upgrade Now!</a>&nbsp;&nbsp;&nbsp;";
														}
														if($user_subscription['subscription_id']>2){
															echo "&nbsp;&nbsp;&nbsp;<a href='".HTTP_ROOT."pricing'>Downgrade Now!</a>";
														}?>
													</b></font>
													<br/>
													<b>Account Status:</b>
													<font style="color:#0576A6;font-size: 14px;"><strong>
													<font style="color:#EB592A;">
													<?php echo $current_active_users;?>
													</font>
													</strong> <font style="font-weight:normal;">User(s)</font>, 
													<strong><font style="color:#EB592A;"><?php echo $used_storage."/".$user_subscription['storage']." Mb";?></font></strong> <font style="font-weight:normal;">Storage</font>, 
													<strong><font style="color:#EB592A;"><?php $totProj = $this->Format->checkProjLimit($user_subscription['project_limit']);
													echo $totProj;
													?></font></strong> <font style="font-weight:normal;">Project(s)</font>, 
													<strong><font style="color:#EB592A;">
													<?php $totMlst = $this->Format->checkCountMilestone($user_subscription['milestone_limit']);
													echo $totMlst;
													?></font></strong> <font style="font-weight:normal;">Milestone(s)</font>
													<?php
													$outPut = $this->Datetime->nextDate($user_subscription['created'],$user_subscription['free_trail_days'],'day'); 
													$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$outPut,"datetime");
													$updatedCur = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"datetime");
													?>
													</font>
													</td>
													<td align="right">
														<div id="csTotalSize" style="text-align:left;color:#666666;font-size:13px;font-weight:normal;"></div>
													</td>
												</tr>
											</table>
									</div>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td valign="top">
									<table width="100%" border="5" cellspacing="5" cellpadding="5" align="center" >
									<tr height="35px">
									<td valign="top" colspan="2" width="100%">
									<?php echo $this->Form->create('Company',array('url'=>'/users/company','onsubmit'=>'return submitCompany()','enctype'=>'multipart/form-data')); ?>
									<table cellpadding="6" cellspacing="6" align="center" border="5" width="100%"  >
									<tr><td colspan="2" class="font-headings" style="font-size:15px" align="left"></td></tr>
									<tr><td colspan="2"><div id="divide"></div></td></tr>
									<tr height="40px">
									<td  class="case_fieldprof" align="right" width="35%">
									Name:
									</td>
									<td align="left">
									<?php echo $this->Form->text('name',array('value'=>$getCompany['Company']['name'],'size'=>'45','class'=>'text_field','style'=>'-moz-border-radius:3px 3px 3px 3px','id'=>'name','maxlength'=>'100')); ?>
									</td>
									</tr>
									<tr height="40px">
									<td  class="case_fieldprof" align="right" width="35%">
									Secure Site:
									</td>
									<td align="left" class="case_fieldprof" style="color: #000000;">
										<?php echo HTTP_ROOT; ?>
									</td>
									</tr>
									<tr height="40px">
									<td class="case_fieldprof" align="right" width="35%">
									Website:
									</td>
									<td align="left" style="color:#000000;">
									<?php echo $this->Form->text('website',array('value'=>$getCompany['Company']['website'],'size'=>'45','class'=>'text_field','style'=>'-moz-border-radius:3px 3px 3px 3px','id'=>'website')); ?>
									</td>
									</tr>
									<tr height="40px">
									<td class="case_fieldprof" align="right" width="35%">
									Contact Phone:
									</td>
									<td align="left" style="color:#000000;">
									<?php echo $this->Form->text('contact_phone',array('value'=>$getCompany['Company']['contact_phone'],'size'=>'45','class'=>'text_field','style'=>'-moz-border-radius:3px 3px 3px 3px','id'=>'phone','maxlength'=>'20')); ?>
									</td>
									</tr>
									<?php
									//if($this->Format->imageExists(DIR_FILES.'company/',$getCompany['Company']['logo']))
									/*	if($this->Format->pub_file_exists(DIR_USER_COMPANY_S3_FOLDER,$getCompany['Company']['logo']))
									{
									?>
									<tr height="40px">
									<td class="case_fieldprof" align="right" width="35%">
									
									</td>
									<td align="left" >
										<?php $fileurl = $this->Format->generateTemporaryURL(DIR_USER_COMPANY_S3.$getCompany['Company']['logo']); ?>
                                                  <a href="<?php echo $fileurl; ?>" target="_blank" style="text-decoration:none;" border="0">
										<!--<a href="<?php echo DIR_USER_COMPANY_S3.$getCompany['Company']['logo']; ?>" target="_blank" style="text-decoration:none;" border="0">-->
											
											
											<!--<img src="<?php echo HTTP_ROOT; ?>files/company/<?php echo $getCompany['Company']['logo']; ?>" border="0" class="profile_img" style="width:100;height:100;"/>-->
												<img src="<?php echo $fileurl; ?>" border="0"  style="width:100;height:100;"/>
										</a>
										
										<a href="<?php echo HTTP_ROOT; ?>users/company/<?php echo urlencode($getCompany['Company']['logo']); ?>"><span onclick="return confirm('Are you sure you want to delete?')"><img src="<?php echo HTTP_IMAGES;?>images/rem.png" border="0"></span></a>
									
										<?php echo $this->Form->hidden('photo_name',array('value'=>$getCompany['Company']['logo'],'class'=>'text_field','id'=>'photo')); ?>
									</td>
									</tr>
									<?php
									}
									else
									{
									?>
									<tr height="40px">
									<td class="case_fieldprof" align="right" width="35%">
									Logo:
									</td>
									<td align="left">
									<?php echo $this->Form->file('photo',array('value'=>$getCompany['Company']['logo'],'onchange'=>'return checkImage(\'photo\')','class'=>'text_field','style'=>'-moz-border-radius:3px 3px 3px 3px','id'=>'photo')); ?>
									</td>
									</tr>
									<?php
									}*/
									?>
									<tr height="40px">
									<td></td>
									<td align="left">
									<span id="subprof1">
									<button type="submit" value="Update" name="submit_Profile" id="submit_Profile" class="pop_btn">Update</button>
									&nbsp;&nbsp;or&nbsp;&nbsp;
									<a href="<?php echo HTTP_ROOT; ?>dashboard" style="color: #3A5280;text-decoration: none;">Cancel</a>
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
function checkImage(img_id)
{
	var fileName = document.getElementById(img_id).value;
	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
	if(ext == "jpg" || ext == "JPG" || ext == "gif" || ext == "GIF" || ext == "jpeg" || ext == "JPEG" || ext == "bmp" || ext == "BMP" || ext == "png" || ext == "PNG")
	{
		$("#showfile").val(fileName);
		return true;
	}
	else
	{
		alert("Invalid input file format! Should be an image file.");
		document.getElementById(img_id).value="";
		return false;
	}
}
</script>
