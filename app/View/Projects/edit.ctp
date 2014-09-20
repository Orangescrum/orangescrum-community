<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr><td align="left">
		<h1 class="toplink">
		Project  >  Edit
		</h1>
	</td></tr>
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
						echo $this->Form->create('Project',array('url'=>'/projects/edit','onsubmit'=>'return submitProject(\'txt_proj\',\'txt_shortProjEdit\')','enctype'=>'multipart/form-data')); 
						?>
						<table border="0" cellspacing="4" cellpadding="4" align="center" width="100%" style="border:1px solid #5D718A;-moz-box-shadow: 0px 5px 6px #696969;-webkit-box-shadow: 0px 5px 6px #696969;box-shadow: 0px 5px 6px #696969;" bgcolor="#FFFFFF">
							<tr>
								<td valign="top" align="center" width="100%">
									<table cellpadding="4" cellspacing="4" align="center" width="100%" border="0">
										<tr height="25px">
											<td valign="top" style="font-weight:bold;" align="center" colspan="2">
												Project Name:&nbsp;
												<?php echo $this->Form->text('name',array('value'=>stripslashes($projArr['Project']['name']),'size'=>'30','class'=>'text_field','id'=>'txt_proj','maxlength'=>'35')); ?>
											</td>
										</tr>
                                        <tr height="25px">
											<td align="left" valign="top" style="padding-right:200px;padding-top:20px">
												<table cellpadding="6" cellspacing="6" border="0" align="right"  width="100%"> 
													<tr>
														<td valign="top"  style="font-weight:bold;" align="right">
														Project Short Name:
														</td>
														<td  align="left" >
														<?php echo $this->Form->text('short_name',array('value'=>stripslashes($projArr['Project']['short_name']),'size'=>'15','class'=>'text_field','id'=>'txt_shortProjEdit','maxlength'=>'10')); ?>
		
														</td>
													</tr>
													<tr>
														<td  style="font-weight:bold;" align="right">
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
															$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$projArr['Project']['dt_created'],"datetime");
															?>
															<span style="color:#000;"><?php echo date('m/d/y g:i A',strtotime($locDT))?></span>
														</td>
													</tr>
													
												</table>
											</td>
											<td align="left" valign="top">
												<select id="sel_tech" name="data[Project][tech_ids][]" class="text_field" style="width:200px;height:140px" multiple="multiple">
													<?php
													foreach($getTech as $tech)
													{
													?>
														<option
														<?php
														foreach($tech['ProjectTechnology'] as $pjtech)
														{
															if($tech['Technology']['id'] == $pjtech['technology_id'])
															{
																echo "selected";
															}
														}
														?>
														value="<?php echo $tech['Technology']['id']?>"><?php echo $this->Format->formatText($tech['Technology']['name'])?></option>
													<?php
													}
													?>
													<option value="other">Other</option>
												</select>
											</td>
										</tr>
										
									</table>
								</td>
							</tr>
							<tr><td><div id="divide"></div></td></tr>
							<tr>
								<td  align="center">
									<input type="hidden" value="<?php echo $uniqid; ?>" name="data[Project][uniq]"/>
									<input type="hidden" value="<?php echo $projArr['Project']['id']?>" name="data[Project][id]"/>
									
<button class=""  style="margin: 5px 0px;width:60px;"  value="Save" type="submit">Save</button>
			<button class="" name="but" id="but"  style="margin: 5px 0px;width:60px;"  value="Reset" type="reset">Reset</button>
									
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
