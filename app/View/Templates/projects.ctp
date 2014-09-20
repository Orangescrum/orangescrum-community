<div class="proj_grids">
<!--Tabs section starts -->
    <div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
            <li class="active" id="project_li">
				<a href="<?php echo HTTP_ROOT.'templates/projects'; ?>" id="sett_my_profile">
                <!--<div class="fl act_milestone"></div>-->
				<div class="tem_pro fl"></div>
                <div class="fl">Project</div>
                <div class="cbt"></div>
                </a>
            </li>
            <li id="task_li">
				<a href="<?php echo HTTP_ROOT.'templates/tasks'; ?>" id="sett_cpw_prof">
               <!-- <div class="fl mt_completed"></div>-->
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
			<div class="col-lg-12 contain new_prjct user_inv text-centre create_project_temp">
			<div class="icon-projct-template"></div>
				Create Project Template
			</div>	
		</div>
		</a>
		
	   <?php if(!empty($proj_temp) && isset($proj_temp)){
	   //echo "<pre>";print_r($proj_temp);exit;
	   		$count = 0;
	   		foreach($proj_temp as $template) {
			if($count <= 1){
	   ?>
	   
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain usr_mng_div">
				<div class="usr_block">
					<div class="fl">
						<div class="nm">
							<div id="tempUpdLoader_<?php echo $template['project_templates']['id']; ?>" class="tsk_files_more">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." />
							</div>
							<div id="textTemp_<?php echo $template['project_templates']['id']; ?>">
								<?php echo $this->Format->shortLength($this->Format->formatText($template['project_templates']['module_name']), 28); ?>
							</div>
						</div>
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
								<?php //if(trim($template['project_templates']['is_default']) == 0){
										//echo "<font style='color:#8C8C8C'>Default</font>";
									//}else if( $template['project_templates']['user_id'] == SES_ID){
										//echo "You";
									//}else{
										$usr_arr=$this->Casequery->getUserDtls($template['project_templates']['user_id']);
										echo $usr_arr['User']['name']." ".$usr_arr['User']['last_name'];
									//}
								?>	
							</strong>
						</div>
						<button class="customfile-button temp-btn" onclick="addToProject('<?php echo $template['project_templates']['id']; ?>', '<?php echo $template['project_templates']['module_name']; ?>');">
							Add to Project
						</button>
					</div>
					<div class="cbt"></div>
				  </div>
				<div class="crt_temp_date"> <span class="fnt13">Created: 
					<?php
						$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['project_templates']['created'],"datetime");
						$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
						echo $dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate);						
					?>
				</span></div>
				<div class="proj_mng">
					<div class="fl">
						<a href="javascript:void(0);" class="icon-add-task fl" onclick="addTempToTask('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>', 0);">Add Task</a><br/>
						<a href="javascript:void(0);" class="icon-remov-task fl" onclick="removeTaskFromTemp('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>');">Manage Task</a>
					</div>
					<div class="fr">
						<a href="javascript:void(0);" class="icon-edit-task fl" onclick="EditTask('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>', '<?php echo $casePage; ?>');">Edit</a><br/>
						<a href="javascript:void(0);" onclick="deltemplate('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>');" class="icon-delete-usr fl" >Delete</a>
					</div>
				</div>
			</div>
		</div>
		
		<?php } $count++; } } ?>
	</div>
	
	
	
		<div class="cbt"></div>
		<div class="col-lg-12 user_div m-left-20">
		
		<?php if(!empty($proj_temp) && isset($proj_temp)){
	   		$count = 0;
	   		foreach($proj_temp as $template) {
			if($count > 1){
	   ?>
		
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain usr_mng_div">
				<div class="usr_block">
					<div class="fl">
						<div class="nm">
							<div id="tempUpdLoader_<?php echo $template['project_templates']['id']; ?>" class="tsk_files_more">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Loading..." title="Loading..." />
							</div>
							<div id="textTemp_<?php echo $template['project_templates']['id']; ?>">
								<?php echo $this->Format->shortLength($this->Format->formatText($template['project_templates']['module_name']), 28); ?>
							</div>
						</div>
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
								<?php //if(trim($template['project_templates']['is_default']) == 0){
										//echo "<font style='color:#8C8C8C'>Default</font>";
									//}else if( $template['project_templates']['user_id'] == SES_ID){
										//echo "You";
									//}else{
										$usr_arr=$this->Casequery->getUserDtls($template['project_templates']['user_id']);
										echo $usr_arr['User']['name']." ".$usr_arr['User']['last_name'];
									//}
								?>	
							</strong>
						</div>
						<button class="customfile-button temp-btn" onclick="addToProject('<?php echo $template['project_templates']['id']; ?>', '<?php echo $template['project_templates']['module_name']; ?>');">
							Add to Project
						</button>
					</div>
					<div class="cbt"></div>
				  </div>
				<div class="crt_temp_date"> <span class="fnt13">Created: 
					<?php
						$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['project_templates']['created'],"datetime");
						$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
						echo $dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate);						
					?>
				</span></div>
				<div class="proj_mng">
					<div class="fl">
						<a href="javascript:void(0);" class="icon-add-task fl" onclick="addTempToTask('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>', 0);">Add Task</a><br/>
						<a href="javascript:void(0);" class="icon-remov-task fl" onclick="removeTaskFromTemp('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>');">Manage Task</a>
					</div>
					<div class="fr">
						<a href="javascript:void(0);" class="icon-edit-task fl" onclick="EditTask('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>', '<?php echo $casePage; ?>');">Edit</a><br/>
						<a href="javascript:void(0);" onclick="deltemplate('<?php echo $template['project_templates']['id']; ?>', '<?php echo addslashes($template['project_templates']['module_name']); ?>');" class="icon-delete-usr fl" >Delete</a>
					</div>
				</div>
			</div>
		</div>
		
		<?php } $count++; } } ?>
		
	</div>

</div>
<div class="cbt"></div>
<input type="hidden" id="getprojectcount" value="<?php echo $caseCount; ?>" readonly="true"/>
<?php if ($caseCount) { ?>
<div class="tot-cs fr">
    <div class="sh-tot-cs">
	<?php echo $this->Format->pagingShowRecords($caseCount, $page_limit, $casePage); ?>
    </div>
    <div class="pg-ntn">
	<ul class="pagination">
	    <?php
	    $page = $casePage;
	    if ($page_limit < $caseCount) {
		$numofpages = $caseCount / $page_limit;
		if (($caseCount % $page_limit) != 0) {
		    $numofpages = $numofpages + 1;
		}
		$lastPage = $numofpages;
		$k = 1;
		$data1 = "";
		$data2 = "";
		if ($numofpages > 5) {
		    $newmaxpage = $page + 2;
		    if ($page >= 3) {
			$k = $page - 2;
			$data1 = "...";
		    }
		    if (($numofpages - $newmaxpage) >= 2) {
			if ($data1) {
			    $data2 = "...";
			    $numofpages = $page + 2;
			} else {
			    if ($numofpages >= 5) {
				$data2 = "...";
				$numofpages = 5;
			    }
			}
		    }
		}
		if ($data1) {
		    echo "<li><a href='" . HTTP_ROOT . "templates/projects/?page=1' class=\"button_act\" >&laquo; First</a></li>";
		    echo "<li class='hellip'>&hellip;</li>";
		}
		if ($page != 1) {
		    $pageprev = $page - 1;
		    echo "<li><a href='" . HTTP_ROOT . "templates/projects/?page=" . $pageprev . "' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
		} else {
		    echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
		}
		for ($i = $k; $i <= $numofpages; $i++) {
		    if ($i == $page) {
			echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">" . $i . "</a></li>";
		    } else {
			echo "<li><a href='" . HTTP_ROOT . "templates/projects/?page=" . $i . "' class=\"button_act\" >" . $i . "</a></li>";
		    }
		}
		if (($caseCount - ($page_limit * $page)) > 0) {
		    $pagenext = $page + 1;
		    echo "<li><a href='" . HTTP_ROOT . "templates/projects/?page=" . $pagenext . "' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
		} else {
		    echo "<li><a href='" . HTTP_ROOT . "templates/projects/?page=" . $pagenext . "' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
		}
		if ($data2) {
		    echo "<li class='hellip'>&hellip;</li>";
		    echo "<li><a href='" . HTTP_ROOT . "templates/projects/?page=" . floor($lastPage) . "' class=\"button_act\" >Last &raquo;</a></li>";
		}
	    }
	    ?>
	    </ul>
	</div>
    </div>
<?php } ?>

<input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/>

<script language="javascript" type="text/javascript">
function deltemplate(id, name)
{
	var conf = confirm("Are you sure to delete the template '"+ name +"'?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT; ?>";
		var strURL = strURL+'templates/projects/?id='+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
</script>