<?php if(isset($recent_milestones) && !empty($recent_milestones)) {
    $cnt = 0;
     foreach ($recent_milestones as $key => $value) {
	$cnt++;
	$total_progress = 0;
	if($value['0']['totalcases']) {
	    $total_progress = round((($value['0']['resolved']/$value['0']['totalcases']) * 100),2);
	}
	$clr = 'red';
	$progress = intval($total_progress);
	if($progress > 30 && $progress < 60) {
	    $clr = 'orange';
	} else if($progress >= 60) {
	    $clr = 'green';
	}

	$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Milestone']['end_date'], "date");
	$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
	$due_date = $this->Datetime->dateFormatOutputdateTime_day($locDT, $gmdate,"date");
	$is_overdue = 0;
	if(strtotime($gmdate) > strtotime($locDT)) {
	    $is_overdue = 1;
	}
    ?>
	<div class="listdv">
	    <div>
		<div class="fl proj_ttl"><?php echo $value['Milestone']['title'];?></div>
		<div class="fr imprv_bar col-lg-4" style="margin: 5px 0;">
		    <div style="width:<?php echo $total_progress;?>%" class="cmpl_<?php echo $clr;?>"></div>
		</div>
		<div class="cb"></div>
	    </div>
	    <div>
		<div class="fl status-dta"><?php if($is_overdue) {?><span class="fnt_clr_rd">Overdue</span><?php } else {?>Due on <?php echo $due_date; }?> <?php if($project=='all'){ ?><a href="<?php echo HTTP_ROOT."dashboard/?project=".$value['Project']['uniq_id'];?>"><?php echo $value['Project']['name'];?></a><?php }?></div>
		<div class="fr status-dta"><?php echo $value['0']['resolved'];?> of <?php echo $value['0']['totalcases'];?> Tasks Resolved</div>
		<div class="cb"></div>
	    </div>
	</div>
	<?php if(count($recent_milestones) != $cnt) { ?>
	    <div class="lstbtndv"></div>
	<?php } ?>
     <?php }
     } else { ?>
    <div class="fnt_clr_rd">No milestone found.</div>
<?php } ?>