<div class="proj_grids">
<?php /*?><!--Tabs section starts -->
    <div class="tab tab_comon">
        <ul class="nav-tabs mod_wide">
            <li id="project_li">
				<a href="<?php echo HTTP_ROOT.'templates/projects'; ?>" id="sett_my_profile">
                <!--<div class="fl act_milestone"></div>-->
				<div class="tem_pro fl"></div>
                <div class="fl">Projects</div>
                <div class="cb"></div>
                </a>
            </li>
            <li class="active" id="task_li">
				<a href="<?php echo HTTP_ROOT.'templates/tasks'; ?>" id="sett_cpw_prof">
                <!--<div class="fl mt_completed"></div>-->
				<div class="tb_tsk fl"></div>
                <div class="fl">Task</div>
                <div class="cb"></div>
                </a>
            </li>
            <div class="cb"></div>
        </ul>
    </div>
<!--Tabs section ends --><?php */?>

	<div class="cb"></div>
	<div class="col-lg-12 user_div m-left-20">
		<a href="javascript:void(0);">
		<div class="col-lg-4">
			<div class="col-lg-12 contain new_prjct user_inv text-centre create_task_temp" style="padding: 55px 50px; display: block ! important;">
			<div class="icon-projct-template"></div>
				Create Task Template
			</div>	
		</div>
		</a>
	<?php if(!empty($TempalteArray) && isset($TempalteArray)){
		$count = 0;
		foreach($TempalteArray as $template) {
		if($count == 2){ ?>
		<div class="cb"></div>
		<?php } ?>
	
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain usr_mng_div">
				<div class="usr_block">
					<div class="fl">
						<div class="nm"><?php echo $this->Format->shortLength($this->Format->formatText($template['case_templates']['name']), 28); ?></div>
					</div>
					<div class="cb"></div>
				 </div>
				 <div class="user-details templt-mng">
					<div class="fl">
						<div class="templ-img">
							<img src="<?php echo HTTP_ROOT; ?>img/default_template.png" />
						</div>
					</div>
					<div class="fl create_temp">
						<?php if($template['case_templates']['user_id']){ ?>
						<div class="">Created By:<br/>
							<strong>
								<?php 
									$usr_arr=$this->Casequery->getUserDtls($template['case_templates']['user_id']);
									echo $this->Format->shortLength($usr_arr['User']['name']." ".$usr_arr['User']['last_name'], 20);
								?>
							</strong>
						</div>
						<?php } else { ?>
						<div class="">
							<span class="no_due_dt">Default Template</span>
						</div>
						<?php } ?>
						<?php
							if($template['case_templates']['is_active'] == 1){
								$classTemp = 'activate_task_temp';
								$nameTitle = '';
							}else{
								$classTemp = 'deactivate_task_temp';
								$nameTitle = 'Disabled';
							}
						?>
							<div class="<?php echo $classTemp; ?>" id="actdeact_<?php echo $template['case_templates']['id']; ?>">
								<?php echo $nameTitle; ?>
							</div>
					</div>
					<div class="cb"></div>
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
						<a href="javascript:void(0);" class="icon-edit-temp-task fl" onclick="EditTaskTemp('<?php echo $template['case_templates']['id']; ?>', '<?php echo $template['case_templates']['name']; ?>','<?php echo $casePage; ?>')">Edit</a><br/>
						<a href="javascript:void(0);" onclick="deltemplate('<?php echo $template['case_templates']['id']; ?>', '<?php echo $template['case_templates']['name']; ?>');" class="icon-delete-usr fl" >Delete</a>
					</div>
					<div class="fr">
						<?php
							if($template['case_templates']['is_active'] == 1){
						?>
							<a href="javascript:void(0);" class="icon-disable-temp-task fl" onclick="DeactivateTaskTemp('<?php echo $template['case_templates']['id']; ?>','<?php echo $casePage; ?>');">Disable</a>
						<?php }else{ ?>
							<a href="javascript:void(0);" class="icon-enable-temp-task fl" onclick="ActivateTaskTemp('<?php echo $template['case_templates']['id']; ?>','<?php echo $casePage; ?>');">Enable</a><br/>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php $count++; } } ?>
	</div>

</div>
<div class="cb"></div>
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
		    echo "<li><a href='" . HTTP_ROOT . "templates/tasks/?page=1' class=\"button_act\" >&laquo; First</a></li>";
		    echo "<li class='hellip'>&hellip;</li>";
		}
		if ($page != 1) {
		    $pageprev = $page - 1;
		    echo "<li><a href='" . HTTP_ROOT . "templates/tasks/?page=" . $pageprev . "' class=\"button_act\">&lt;&nbsp;Prev</a></li>";
		} else {
		    echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
		}
		for ($i = $k; $i <= $numofpages; $i++) {
		    if ($i == $page) {
			echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">" . $i . "</a></li>";
		    } else {
			echo "<li><a href='" . HTTP_ROOT . "templates/tasks/?page=" . $i . "' class=\"button_act\" >" . $i . "</a></li>";
		    }
		}
		if (($caseCount - ($page_limit * $page)) > 0) {
		    $pagenext = $page + 1;
		    echo "<li><a href='" . HTTP_ROOT . "templates/tasks/?page=" . $pagenext . "' class=\"button_act\" >Next&nbsp;&gt;</a></li>";
		} else {
		    echo "<li><a href='" . HTTP_ROOT . "templates/tasks/?page=" . $pagenext . "' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
		}
		if ($data2) {
		    echo "<li class='hellip'>&hellip;</li>";
		    echo "<li><a href='" . HTTP_ROOT . "templates/tasks/?page=" . floor($lastPage) . "' class=\"button_act\" >Last &raquo;</a></li>";
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
		var strURL = HTTP_ROOT+'templates/temptaskdelete/'+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
</script>