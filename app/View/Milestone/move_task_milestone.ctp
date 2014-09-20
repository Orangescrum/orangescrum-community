<style type="text/css">
	.ad_cs_mlstn_tbl .tsk_sts div.label {
    border-radius: 0;
    font-family: arial;
    font-size: 11px;
    font-weight: normal;
    padding: 2px 4px;
}
</style>
<div class="scrl-ovr">
    <table cellpadding="0" cellspacing="0" class="col-lg-12 ad_cs_mlstn_tbl ml_ipad">
	<tr class="hdr_tr">
	    <th></th>
	    <th>Milestone</th>
	    <th>Start Date</th>
	    <th>End Date</th>
	</tr>
	<?php
	    $caseCount = count($milestones);
	    if ($caseCount) {
		foreach ($milestones as $getdata) {
			$mlstAutoId = $getdata['Milestone']['id'];
		    $frmt_data = $this->Format->formatText(ucfirst($getdata['Milestone']['title']));
		    $milestoneTitle = $this->Format->convert_ascii($frmt_data);
			$st_dt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Milestone']['start_date'],"date");
			$end_dt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Milestone']['end_date'],"date");
			$start_date = date('D, M j Y',  strtotime($st_dt));
			$end_date = date('D, M j Y',  strtotime($end_dt));
		    $count++;
		    if ($count % 2 == 0) {
			$class = "row_col";
		    } else {
			$class = "row_col_alt";
		    }
		?>
		<tr id="mvtask_listings<?php echo $count; ?>" class="rw-cls <?php echo $class; ?>">	
		    <td align="left">
				<input type="radio" class="radio_cur ad-mlstn" id="actradio<?php echo $count; ?>" value="<?php echo $mlstAutoId; ?>" name="milestone_radio" <?php if($mlstid==$mlstAutoId){?>checked='true'<?php }?>/>
				<input type="hidden" id="mvtask_actionClss<?php echo $count; ?>" value="0"/>
		    </td>
		    <td><div class="ad_cs" title="<?php echo htmlentities($milestoneTitle); ?>"><?php echo htmlentities($milestoneTitle); ?></div></td>
			<td> <?php echo $start_date; ?></td>
			<td> <?php echo $end_date; ?></td>
		    
		</tr>
		<?php
		}
	    } else { ?>
		<tr valign="middle">
			<td colspan="7" align="center">
				<center class="fnt_clr_rd">No Milestone(s) available.</center>
			</td>
		</tr>
	    <?php } ?>
    </table>
	<?php
		$proj_name = $this->Casequery->getProjectName($project_id);
	?>
    <input type="hidden" id="mvtask_project_id" value="<?php echo $project_id;?>" />
    <input type="hidden" id="mvtask_proj_name" value="<?php echo $this->Format->formatText($proj_name['Project']['name']);?>" />
    <input type="hidden" id="ext_mlst_id" value="<?php echo $mlstid; ?>" />
    <input type="hidden" id="mvtask_id" value="<?php echo $mlst_id; ?>" />
    <input type="hidden" id="mvtask_task_no" value="<?php echo $task_no; ?>" />
    <input type="hidden" id="mvtask_cnt" value="<?php echo $caseCount; ?>" />
</div>