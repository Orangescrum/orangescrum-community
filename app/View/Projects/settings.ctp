<div class="row-fluid">
	<article class="span12 data-block nested">
		<div class="data-container">
			<section class="tab-content">
				<div class="tab-pane" id="horizontal">
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<!--<tr><td align="left">
		<h1 class="toplink">
		Project  >  Settings
		</h1>
	</td></tr>-->
	<?php
	if(count($projArr))
	{
	?>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="35px">
					<td align="center" width="100%">
						<?php 
						echo $this->Form->create('Project',array('url'=>'/projects/settings','name'=>'projsettings','onsubmit'=>'return submitProject(\'txt_proj\',\'txt_shortProjEdit\')','enctype'=>'multipart/form-data')); 
						?>
						<table border="0" cellspacing="4" cellpadding="4" align="center" width="100%">
							<tr>
								<td valign="top" align="center" width="100%">
									<table cellpadding="4" cellspacing="4" align="center" width="100%" border="0">
										
                                        <tr height="25px">
											<td align="left" valign="top" style="padding-right:200px;padding-top:20px">
												<table cellpadding="10" cellspacing="10" border="0" align="right"  width="100%"> 
													<tr>
														<td valign="top" class="case_fieldprof" align="right">
												Project Name:</td><td>
												<?php echo $this->Form->text('name',array('value'=>stripslashes($projArr['Project']['name']),'size'=>'30','class'=>'text_field','id'=>'txt_proj','maxlength'=>'35')); ?>
											</td></tr><tr>
														<td valign="top" class="case_fieldprof" align="right">
														Project Short Name:
														</td>
														<td  align="left" >
														<?php 
														if(strtoupper($projArr['Project']['short_name']) == 'WCOS'){
															echo $this->Form->text('short_name',array('value'=>stripslashes($projArr['Project']['short_name']),'size'=>'15','class'=>'text_field','id'=>'txt_shortProjEdit','maxlength'=>'5','style'=>'text-transform:lowercase','readonly'=>'readonly')); 
														}else{
															echo $this->Form->text('short_name',array('value'=>stripslashes($projArr['Project']['short_name']),'size'=>'15','class'=>'text_field','id'=>'txt_shortProjEdit','maxlength'=>'5','style'=>'text-transform:lowercase'));
														}
														?>
		
														</td>
													</tr>
													<tr>
														<td valign="top" class="case_fieldprof" align="right">
														Default Assign To:
														</td>
														<td  align="left" >
														<select name="data[Project][default_assign]" id="sel_Typ" class="text_field" style="color:#000000;width: 137px;">
															<option value="" selected="selected">[Select]</option>
															<?php foreach($quickMem as $asgnMem){ ?>
																<option value="<?php echo $asgnMem['User']['id'];?>" 
																<?php if((isset($defaultAssign) && ($asgnMem['User']['id'] == $defaultAssign) && ($asgnMem['User']['id'] != SES_ID))){
																		echo "selected='selected'";
																	}else if(!$defaultAssign && ($asgnMem['User']['id'] == SES_ID)){

																		echo "selected='selected'";
																	}
																	?>															
																><?php if(($asgnMem['User']['id'] == SES_ID)){ echo 'me';}else{ echo $this->Format->formatText($asgnMem['User']['name']);} ?></option>																
															<?php }  ?>
														</select>
		
														</td>
													</tr>
													<tr>
														<td  class="case_fieldprof" align="right">
															Created by:
														</td>
														<td  align="left" style="color:#000;">
															<?php echo $this->Format->formatText($uname); ?>
														</td>
													</tr>
													<tr>
														<td align="right"></td>
														<td  align="left" style="color:#000;">
															<?php
															//$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$projArr['Project']['dt_created'],"datetime");
															
															$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$projArr['Project']['dt_created'],"datetime");
															$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
															$dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate,'time');
															?>
															<span style="color:#000;"><?php echo $dateTime; ?></span>
														</td>
													</tr>
													
													<tr><td><div id="divide"></div></td></tr>
														<tr>
															<td align="right"></td>
															<td  align="left">
																<input type="hidden" name="data[Project][validateprj]" id="validateprj" readonly="true" value="0"/>
																
																<input type="hidden" value="<?php echo $uniqid; ?>" name="data[Project][uniq]" id="uniqid"/>
																<input type="hidden" value="<?php echo $projArr['Project']['id']?>" name="data[Project][id]"/>
																<button style="margin: 5px 0px;"  value="Save" type="submit" class="pop_btn" onclick="document.getElementById('validateprj').value='1'" id="savebtn">Save</button>
																<img src="<?php echo HTTP_IMAGES;?>images/del.gif" width="16" height="16" id="settingldr" style="display:none"/>
																<span class="fnt_opensans">&nbsp;&nbsp;or&nbsp;&nbsp;
																<a href="<?php echo $referer; ?>">Cancel</a></span>
															</td>
														</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							
						</table>
						</form>
					</td>	
				</tr>
			</table>			
		</td>
	</tr>
	<?php
	}
	?>
</table>
</div>
			</section>
		</div>
	</article>
</div>		
