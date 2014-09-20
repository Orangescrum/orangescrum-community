<div class="col-lg-12 m-left-20">
	    <a href="javascript:void(0);" onClick="addEditMilestone(this);" data-id="" data-uid="" data-name="">
		<div class="col-lg-4">
			<div class="col-lg-12 contain crt_mileston">
			<div class="icon-crt-mileston"></div>
			Create Milestone
			</div>
		</div>
		</a>
		<?php if(!empty($milestones) && isset($milestones)){
		    $count = 1;
		    foreach($milestones as $milestone) {
			if($count <=2) { ?>
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain">
				<h3><a href="<?php echo HTTP_ROOT.'dashboard#kanban/'.$milestone['Milestone']['uniq_id'];?>"><?php echo ucwords($milestone['Milestone']['title']);?></a></h3>
				<div class="tsk_updts">
				    <?php 
				    $total_cases = 0;
				    $total_progress = 0;
				    if($milestone['0']['totalcases']) {
					$total_cases = $milestone['0']['totalcases'];
					$total_progress = round((($milestone['0']['closed']/$total_cases) * 100),2);
				    }
				    ?>
				    <span id="tot_tasks<?php echo $milestone['Milestone']['id'];?>"><?php echo $total_cases;?></span> Tasks&nbsp; . &nbsp;<?php echo $milestone['0']['closed'];?> Closed
				</div>
				<div class="imprv_bar col-lg-12">
					<div class="cmpl_green" style="width:<?php echo $total_progress;?>%"></div>
				</div>
				<?php
				$date = $milestone['Milestone']['created'];
				if($milestone['Milestone']['modified']) {
				    $date = $milestone['Milestone']['modified'];
				}
				$curCreated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
				$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$date,"date");
				$locDT = $this->Datetime->dateFormatOutputdateTime_day($updated, $curCreated,'',1);
				?>
				<div class="last_updt">Last activity on <?php echo $locDT;?></div>
				<div class="cb"></div>
				<div class="proj_mng">
				    <div class="fl">
					<a href="javascript:void(0);" class="icon-add-task fl" onClick="addTaskToMilestone(this);" data-prj-id="<?php echo $milestone['Milestone']['project_id'];?>" data-id="<?php echo $milestone['Milestone']['id'];?>">Add Task</a>
					<br />
					<?php if($type == 'completed') { ?>
					<a href="javascript:void(0);" class="icon-restore-mlstn fl" onClick="milestoneRestore(this);" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Restore</a>
					<?php } else { ?>
					<a href="javascript:void(0);" class="icon-complete-mlstn fl" onClick="milestoneArchive(this);" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Complete</a>
					<?php } ?>
				    </div>
				    <div class="fr">
					<a href="javascript:void(0);" class="icon-edit-mlstn fl" onClick="addEditMilestone(this);" data-id="<?php echo $milestone['Milestone']['id'];?>" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Edit</a>
					<br />
					<a href="javascript:void(0);" class="icon-delete-mlstn fl" onClick="delMilestone(this);" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Delete</a>
				    </div>
				</div>
			</div>
		</div>
	    <?php $count++;
		    }
		}
	    } ?>
	</div>
	<div class="cb"></div>
	<div class="col-lg-12 m-left-20">
	    <?php if(!empty($milestones) && isset($milestones)){
		    $count = 1;
		    foreach($milestones as $milestone) {
			if($count > 2) { ?>
		<div class="col-lg-4 proj_mng_div">
			<div class="col-lg-12 contain">
				<h3><a href="<?php echo HTTP_ROOT.'dashboard#kanban/'.$milestone['Milestone']['uniq_id'];?>"><?php echo ucwords($milestone['Milestone']['title']);?></a></h3>
				<div class="tsk_updts">
				    <?php 
				    $total_cases = 0;
				    $total_progress = 0;
				    if($milestone['0']['totalcases']){
					$total_cases = $milestone['0']['totalcases'];
					$total_progress = round((($milestone['0']['closed']/$total_cases) * 100),2);
				    }
				    ?>
				    <span id="tot_tasks<?php echo $milestone['Milestone']['id'];?>"><?php echo $total_cases;?></span> Tasks&nbsp; . &nbsp;<?php echo $milestone['0']['closed'];?> Closed
				</div>	
				<div class="imprv_bar col-lg-12">
				    <div class="cmpl_green" style="width:<?php echo $total_progress;?>%"></div>
				</div>
				<?php
				$date = $milestone['Milestone']['created'];
				if($milestone['Milestone']['modified']) {
				    $date = $milestone['Milestone']['modified'];
				}
				$curCreated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
				$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$date,"date");
				$locDT = $this->Datetime->dateFormatOutputdateTime_day($updated, $curCreated,'',1);
				?>
				<div class="last_updt">Last activity on <?php echo $locDT;?></div>
				<div class="cb"></div>
				<div class="proj_mng">
				    <div class="fl">
					<a href="javascript:void(0);" class="icon-add-task fl" onClick="addTaskToMilestone(this);" data-prj-id="<?php echo $milestone['Milestone']['project_id'];?>" data-id="<?php echo $milestone['Milestone']['id'];?>">Add Task</a>
					<br />
					<?php if($type == 'completed') { ?>
					<a href="javascript:void(0);" class="icon-restore-mlstn fl" onClick="milestoneRestore(this);" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Restore</a>
					<?php } else { ?>
					<a href="javascript:void(0);" class="icon-complete-mlstn fl" onClick="milestoneArchive(this);" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Complete</a>
					<?php } ?>
				    </div>
				    <div class="fr">
					<a href="javascript:void(0);" class="icon-edit-mlstn fl" onClick="addEditMilestone(this);" data-id="<?php echo $milestone['Milestone']['id'];?>" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Edit</a>
					<br />
					<a href="javascript:void(0);" class="icon-delete-mlstn fl" onClick="delMilestone(this);" data-uid="<?php echo $milestone['Milestone']['uniq_id'];?>" data-name="<?php echo $milestone['Milestone']['title'];?>">Delete</a>
				    </div>
				</div>
			</div>
		</div>
	    <?php 
		    }
		    $count++;
		}
	    } ?>
	</div>
	<div class="cb"></div>
<?php if($caseCount){?>
<table cellpadding="0" cellspacing="0" border="0" align="right">
	<tr>
		<td align="center" style="padding-top:5px;padding-right:35px;">
			<div class="show_total_case" style="font-weight:normal;color:#000;font-size:12px;">
				<?php echo  $this->Format->pagingShowRecords($caseCount,$page_limit,$casePage); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center">
		<ul class="pagination" style="padding-right:35px;">
		<?php $page = $casePage;
			if($page_limit < $caseCount){
				$numofpages = $caseCount / $page_limit;
				if(($caseCount % $page_limit) != 0){
					$numofpages = $numofpages+1;
				}
				$lastPage = $numofpages;
				$k = 1;
				$data1 = "";
				$data2 = "";
				if($numofpages > 5){
					$newmaxpage = $page+2;
					if($page >= 3){
						$k = $page-2;
						$data1 = "...";
					}
					if(($numofpages - $newmaxpage) >= 2){
						if($data1){
							$data2 = "...";
							$numofpages = $page+2;
						}else{
							if($numofpages >= 5){
								$data2 = "...";
								$numofpages = 5;
							}
						}
					}
				}
				if($data1){
				    if($type == 'completed'){
					echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",1);' href='javascript:void(0);' class=\"button_act\">&laquo; First</a></li>";
				   }else{
					echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",1);' href='javascript:void(0);' class=\"button_act\">&laquo; First</a></li>";
				   }
				    echo "<li class='hellip'>&hellip;</li>";
				}
				if($page != 1){
					$pageprev = $page-1;
				    if($type == 'completed'){
					 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$pageprev.");' href='javascript:void(0);'  class=\"button_act\">&lt;&nbsp;Prev</a></li>";
				    }else{
					 echo "<li><a  onclick='caseMilestone(".$projId.",\"".$projName."\",".$pageprev.");' href='javascript:void(0);'  class=\"button_act\">&lt;&nbsp;Prev</a></li>";
				    }
				}else{
					echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
				}
				for($i = $k; $i <= $numofpages; $i++){
					if($i == $page) {
						echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">".$i."</a></li>";
					}else {
					    if($type == 'completed'){
						 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$i.");' href='javascript:void(0);'  class=\"button_act\" >".$i."</a></li>";
					    }else{
						 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$i.");' href='javascript:void(0);' class=\"button_act\" >".$i."</a></li>";
					    }
					}
				}
				if(($caseCount - ($page_limit * $page)) > 0){
				    $pagenext = $page+1;
				    if($type == 'completed'){
						echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$pagenext.");' href='javascript:void(0);'  class=\"button_act\" >Next&nbsp;&gt;</a></li>";
				    }else{
						echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$pagenext.");' href='javascript:void(0);'  class=\"button_act\" >Next&nbsp;&gt;</a></li>";
				    }                                             
				}else{
				    if($type == 'completed'){
					 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$pagenext.");' href='javascript:void(0);' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
				    }else{
					 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".$pagenext.");' href='javascript:void(0);'  class=\"button_prev\">Next&nbsp;&gt;</a></li>";
				    }
				}
				if($data2){
				    echo "<li class='hellip'>&hellip;</li>";
				    if($type == 'completed'){
					 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".floor($lastPage).");' href='javascript:void(0);'  class=\"button_act\" >Last &raquo;</a></li>";
				    }else{
					 echo "<li><a onclick='caseMilestone(".$projId.",\"".$projName."\",".floor($lastPage).");' href='javascript:void(0);'  class=\"button_act\" >Last &raquo;</a></li>";
				    }
				}
			} ?>
		</ul>
	</td>
</tr>
</table>
<?php }	?>