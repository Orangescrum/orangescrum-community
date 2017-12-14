<?php if(isset($gettodos) && !empty($gettodos)) {
$cnt = 0; $od_label = $td_label = 0;
$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
     foreach ($gettodos as $key => $value) {
	 $cnt++;
	 $due_date = '';
	 
	 $actual_dt_created = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Easycase']['actual_dt_created'], "date");
	 
	if($value['Easycase']['due_date'] != "NULL" && $value['Easycase']['due_date'] != "0000-00-00" && $value['Easycase']['due_date'] != "" && $value['Easycase']['due_date'] != "1970-01-01") {
	    $locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Easycase']['due_date'], "date");

	    $is_overdue = 0;
	    if(strtotime($gmdate) > strtotime($locDT)) {
		$is_overdue = 1;
			$due_date = $this->Datetime->facebook_datestyle($value['Easycase']['due_date']);
	    } else {
			$due_date = $this->Datetime->dateFormatOutputdateTime_day($locDT, $gmdate,"date");
	    }
	}
	$actual_dt_created = $this->Datetime->dateFormatOutputdateTime_day($actual_dt_created, $gmdate,"date");
    ?>
	<?php if($value[0]['todos_type']=='od' && !$od_label){ $od_label = 1; ?>
	<div class="due_task_list">Overdue Tasks</div>
	<?php } ?>
	<?php if($value[0]['todos_type']=='td' && !$td_label){ $td_label = 1; ?>
	<div class="due_task_list up_task_list">Upcoming Tasks</div>
	<?php } ?>
	
	<div class="listdv">
		<div class="fl task_title_db">
		<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>" title="<?php echo ucfirst($value['Project']['name']); ?>" style="color:#5191BD"><?php echo $this->Format->shortLength(strtoupper($value['Project']['short_name']),4); ?></a> - 
		<a href="<?php echo HTTP_ROOT; ?>dashboard#details/<?php echo $value['Easycase']['uniq_id']; ?>" title="<?php echo htmlentities($this->Format->convert_ascii($value['Easycase']['title']),ENT_QUOTES); ?>">#<?php echo $value['Easycase']['case_no'];?>: <?php echo htmlentities($this->Format->shortLength($this->Format->convert_ascii($value['Easycase']['title']),50),ENT_QUOTES); ?></a>
		</div>
	    <div class="cb"></div>
		<div class="fl" style="font-size:12px;">
			<span style="color: #999999;">Created on <?php echo $actual_dt_created; ?></span>
		</div>
	    <?php if($due_date) {?>
		<div class="fr" style="font-size:12px;">
		    <div class="img-cls-dt" style="margin:-1px;"></div>
		    <?php if($is_overdue) {?>
			<span class="over-due" title="<?php echo $due_date;?>">Overdue</span>
		    <?php } else {?>
			<span style="color: #0CAA00;"><?php echo $due_date; ?></span>
		    <?php }?>
		</div>
	    <?php }?>
	    <?php if($project == 'all' && 0){ ?>
		<div class="fr">
		    <div class="fl"><img class="prj-db" src="<?php echo HTTP_IMAGES; ?>images/u_det_proj.png"></div>
		    <div class="fl">
			<a href="<?php echo HTTP_ROOT; ?>dashboard/?project=<?php echo $value['Project']['uniq_id']; ?>">
			    <div class="prj_title_db" title="<?php echo ucfirst($value['Project']['name']); ?>"><?php echo ucfirst($value['Project']['name']); ?></div>
			</a>
		    </div>
		</div>
		<?php } ?>
	    <div class="cb"></div>
	    <?php if(count($gettodos) != $cnt) { ?>
	    <div class="lstbtndv"></div>
	    <?php } ?>
	</div>
	<div class="cb"></div>
     <?php } ?>
	<div id="to_dos_more" data-value="<?php echo $total;?>" style="display: none;"></div>
     <?php } else { ?>
	<div class="mytask"></div>
	<div class="mytask_txt">No tasks assigned to you, Explore cool features!</div>
    <div id="to_dos_more" data-value="0" style="display: none;"></div>
<?php } ?>
