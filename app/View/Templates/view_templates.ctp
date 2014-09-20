<div class="proj_grids" style="margin-top:-25px;">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding:0px;margin:0px;">
			<h1 style="padding:0px;margin:0px;"><?php echo $template_name; ?></h1>
		</td>	
	</tr>
</table>
<table width="98%" class="tsk_tbl arc_tabs caselistall" id="caselist" style="margin-top:10px;">
	<tr style="" class="tab_tr">
		<td width="1%">
			<div class="fl">&nbsp;</div>
		</td>  
		<td width="31%">
			<div class="fl">Title</div>
		</td>        
		<td width="66%">
			<div class="fl">Description</div>
		</td>        
	</tr>
	<?php
		//echo "<pre>";print_r($temp_dtls_cases);exit;
		if(count($temp_dtls_cases) > 0){
		$class = "";
		foreach($temp_dtls_cases as $val)
		{
			$counter++;
			if ($counter % 2 == 0) {
			    $class = "row_col";
			} else {
			    $class = "row_col_alt";
			}
	?>
	<tr class="tr_all all_first_rows <?php echo $class; ?>">
		<td>&nbsp;</td>
		<td>
			<?php echo $val['ProjectTemplateCase']['title']; ?>
		</td>
		<td>
			<?php echo $val['ProjectTemplateCase']['description']; ?>
		</td>
	</tr>
	<?php } }else{ ?>
		<tr>
			<td colspan="3" style="color:#FF0000;" align="center">No tasks found</td>
		</tr>
	<?php } ?>
</table>

<?php /*?><!--Tabs section starts -->
    <div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
            <li id="project_li">
				<a href="<?php echo HTTP_ROOT.'templates/projects'; ?>" id="sett_my_profile">
                <!--<div class="fl act_milestone"></div>-->
				<div class="tem_pro fl"></div>
                <div class="fl">Projects</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li class="active" id="task_li">
				<a href="<?php echo HTTP_ROOT.'templates/tasks'; ?>" id="sett_cpw_prof">
                <!--<div class="fl mt_completed"></div>-->
				<div class="tb_tsk fl"></div>
                <div class="fl">Task</div>
                <div class="cbt"></div>
                </a>
            </li>
            <div class="cbt"></div>
        </ul>
    </div>
<!--Tabs section ends -->
	
	<div class="col-lg-12 user_div m-left-20">
		<a href="javascript:void(0);">
		<div class="col-lg-4">
			<div class="col-lg-12 contain new_prjct user_inv text-centre create_task_temp">
			<div class="icon-projct-template"></div>
				Create Task Template
			</div>	
		</div>
		</a>
		
	   <?php if(!empty($TempalteArray) && isset($TempalteArray)){
	   //echo "<pre>";print_r($TempalteArray);exit;
	   		$count = 0;
	   		foreach($TempalteArray as $template) {
			if($count <= 1){
	   ?>
	   
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain usr_mng_div">
				<div class="usr_block">
					<div class="fl">
						<div class="nm"><?php echo $this->Format->shortLength($this->Format->formatText($template['case_templates']['name']), 28); ?></div>
					</div>
					<div class="cbt"></div>
				 </div>
				 <div class="user-details templt-mng">
					<div class="fl">
						<div class="templ-img">
							<img src="../../img/default_template.png" />
						</div>
					</div>
					<div class="fl create_temp">
						<div class="">Created By:<br/>
							<strong>
								<?php 
									$usr_arr=$this->Casequery->getUserDtls($template['case_templates']['user_id']);
									echo $usr_arr['User']['name']." ".$usr_arr['User']['last_name'];
								?>	
							</strong>
						</div>
						<?php
							if($template['case_templates']['is_active'] == 1){
								$classTemp = 'activate_task_temp';
								$nameTitle = 'Enabled';
							}else{
								$classTemp = 'deactivate_task_temp';
								$nameTitle = 'Disabled';
							}
						?>
							<div class="<?php echo $classTemp; ?>" id="actdeact_<?php echo $template['case_templates']['id']; ?>">
								<?php echo $nameTitle; ?>
							</div>
					</div>
					<div class="cbt"></div>
				  </div>
				<div class="crt_temp_date"> <span class="fnt13">Created: 
					<?php
						$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['case_templates']['created'],"datetime");
						$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
						echo $dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate);						
					?>
				</span></div>
				<div class="proj_mng">
					<div class="fl">
						<?php
							if($template['case_templates']['is_active'] == 1){
						?>
							<span class="icon-enable-temp-task fl">Enable</span><br/>
						<?php }else{ ?>
							<a href="javascript:void(0);" class="icon-enable-temp-task fl" onclick="ActivateTaskTemp('<?php echo $template['case_templates']['id']; ?>','<?php echo $casePage; ?>');">Enable</a><br/>
						<?php } ?>
						
						
						<?php
							if($template['case_templates']['is_active'] == 0){
						?>
							<span class="icon-disable-temp-task fl">Disable</span>
						<?php }else{ ?>	
							<a href="javascript:void(0);" class="icon-disable-temp-task fl" onclick="DeactivateTaskTemp('<?php echo $template['case_templates']['id']; ?>','<?php echo $casePage; ?>');">Disable</a>
						<?php } ?>
					</div>
					<div class="fr">
						<a href="javascript:void(0);" class="icon-edit-temp-task fl" onclick="EditTaskTemp('<?php echo $template['case_templates']['id']; ?>', '<?php echo $template['case_templates']['name']; ?>','<?php echo $casePage; ?>')">Edit</a><br/>
						<a href="javascript:void(0);" onclick="deltemplate('<?php echo $template['case_templates']['id']; ?>', '<?php echo $template['case_templates']['name']; ?>');" class="icon-delete-usr fl" >Delete</a>
					</div>
				</div>
			</div>
		</div>
		
		<?php } $count++; } } ?>
	</div>
	
	
	
		<div class="cbt"></div>
		<div class="col-lg-12 user_div m-left-20">
		
		<?php if(!empty($TempalteArray) && isset($TempalteArray)){
	   		$count = 0;
	   		foreach($TempalteArray as $template) {
			if($count > 1){
	   ?>
		
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain usr_mng_div">
				<div class="usr_block">
					<div class="fl">
						<div class="nm"><?php echo $this->Format->shortLength($this->Format->formatText($template['case_templates']['name']), 28); ?></div>
					</div>
					<div class="cbt"></div>
				 </div>
				 <div class="user-details templt-mng">
					<div class="fl">
						<div class="templ-img">
							<img src="../../img/default_template.png" />
						</div>
					</div>
					<div class="fl create_temp">
						<div class="">Created By:<br/>
							<strong>
								<?php 
									$usr_arr=$this->Casequery->getUserDtls($template['case_templates']['user_id']);
									echo $usr_arr['User']['name']." ".$usr_arr['User']['last_name'];
								?>	
							</strong>
						</div>
						<?php
							if($template['case_templates']['is_active'] == 1){
								$classTemp = 'activate_task_temp';
								$nameTitle = 'Enabled';
							}else{
								$classTemp = 'deactivate_task_temp';
								$nameTitle = 'Disabled';
							}
						?>
							<div class="<?php echo $classTemp; ?>" id="actdeact_<?php echo $template['case_templates']['id']; ?>">
								<?php echo $nameTitle; ?>
							</div>
					</div>
					<div class="cbt"></div>
				  </div>
				<div class="crt_temp_date"> <span class="fnt13">Created: 
					<?php
						$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['case_templates']['created'],"datetime");
						$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
						echo $dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate);						
					?>
				</span></div>
				<div class="proj_mng">
					<div class="fl">
						<?php
							if($template['case_templates']['is_active'] == 1){
						?>
							<span class="icon-enable-temp-task fl">Enable</span><br/>
						<?php }else{ ?>
							<a href="javascript:void(0);" class="icon-enable-temp-task fl" onclick="ActivateTaskTemp('<?php echo $template['case_templates']['id']; ?>','<?php echo $casePage; ?>');">Enable</a><br/>
						<?php } ?>
						
						
						<?php
							if($template['case_templates']['is_active'] == 0){
						?>
							<span class="icon-disable-temp-task fl">Disable</span>
						<?php }else{ ?>	
							<a href="javascript:void(0);" class="icon-disable-temp-task fl" onclick="DeactivateTaskTemp('<?php echo $template['case_templates']['id']; ?>','<?php echo $casePage; ?>');">Disable</a>
						<?php } ?>
					</div>
					<div class="fr">
						<a href="javascript:void(0);" class="icon-edit-temp-task fl" onclick="EditTaskTemp('<?php echo $template['case_templates']['id']; ?>', '<?php echo $template['case_templates']['name']; ?>','<?php echo $casePage; ?>')">Edit</a><br/>
						<a href="javascript:void(0);" onclick="deltemplate('<?php echo $template['case_templates']['id']; ?>', '<?php echo $template['case_templates']['name']; ?>');" class="icon-delete-usr fl" >Delete</a>
					</div>
				</div>
			</div>
		</div>
		
		<?php } $count++; } } ?>
		
	</div><?php */?>

</div>
<div class="cbt"></div>
