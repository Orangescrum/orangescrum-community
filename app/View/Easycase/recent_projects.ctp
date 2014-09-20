<?php if(isset($recent_projects) && !empty($recent_projects)) {
    $cnt = 0;
     foreach ($recent_projects as $key => $value) {
	 $cnt++;
    ?>
	<div class="listdv">
	    <div class="fl">
		<a href="<?php echo HTTP_ROOT."dashboard/?project=".$value['Project']['uniq_id'];?>">
		    <div class="prj_title_db" title="<?php echo ucfirst($value['Project']['name']); ?>"><?php echo ucfirst($value['Project']['name']);?></div>
		</a>
	    </div>
	    <?php 
		$total_progress = 0;
		if($value['0']['total']) {
		    $total_progress = round((($value['0']['resolved']/$value['0']['total']) * 100),2);
		}
		$clr = 'red';
		$progress = intval($total_progress);
		if($progress > 30 && $progress < 60) {
		    $clr = 'orange';
		} else if($progress >= 60) {
		    $clr = 'green';
		}
		
		$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $value['Project']['dt_created'], "date");
		$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
		$created = $this->Datetime->dateFormatOutputdateTime_day($locDT, $gmdate,"date");
	    ?>
	   <div class="fr imprv_bar col-lg-4" style="margin: 5px 0;" title="<?php echo $total_progress;?>%" rel="tooltip">
		<div style="width:<?php echo $total_progress;?>%" class="cmpl_<?php echo $clr;?>"></div>
	    </div>
	    <div class="cb"></div>
	    <div class="fl status-dta">Created on <?php echo $created;?></div>
	    <div class="fr status-dta"><?php echo $value['0']['resolved'];?> of <?php echo $value['0']['total'];?> Tasks Resolved</div>
	    <div class="cb"></div>
	    <?php if(count($recent_projects) != $cnt) { ?>
	    <div class="lstbtndv"></div>
	    <?php } ?>
	</div>
	<div class="cb"></div>
     <?php } ?>
	<div id="recent_projects_more" data-value="<?php echo $total;?>" style="display: none;"></div>
     <?php } else { ?>
    <div class="fnt_clr_rd">No project found.</div>
    <div id="recent_projects_more" data-value="0" style="display: none;"></div>
<?php } ?>