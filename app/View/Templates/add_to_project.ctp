<?php
$caseCount = count($temp_dtls_cases);
if($caseCount > 0)
{
?>
<div id="projectDropdown" class="col-lg-5 mrt-14 fl" >
<table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_tbl">
<tr style="border-bottom:0px;">
<td>Project : </td>
<td>
	
	<select name="" id="proj_id" style="width:200px;" class="form-control">
	<?php
	if(count($project_details))
	{ ?>
		<option value="0" selected>[Select]</option>
		<?php foreach($project_details as $project_details)
		{ ?>
			<option value="<?php echo $project_details['Project']['id'];?>"><?php echo $this->Format->formatText($project_details['Project']['name']); ?></option>
			<?php
		}
	}
	else
	{
	?>
		<option value="0" selected>[Select]</option>
	<?php
	}
	?>
	</select>
</td>
</tr>
</table>
</div>
<div class="cb"></div>
<div class="scrl-ovr">
<table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_tbl">
    <input type="hidden" id="templateId" value="<?php echo $template_id; ?>">
	<tr style="border-bottom:0px;">
	<td>
	    <table cellpadding="0" cellspacing="0" class="col-lg-12">
		<tr class="hdr_tr">
		    <th style="padding-left:20px;" width="35%">Title</th>
		    <th width="65%">Description</th>
		</tr>
		
		<?php
		//echo "<pre>";print_r($temp_dtls_cases);exit;
		
		$counter = 0;
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
		
		<tr id="listing<?php echo $counter; ?>" class="rw-cls <?php echo $class; ?>">	
				<td style="padding-left:20px;" width="35%">
					<span><?php echo $this->Format->shortLength($val['ProjectTemplateCase']['title'], 25); ?></span>
			    </td>
			    <td width="65%">
					<?php echo $val['ProjectTemplateCase']['description']; ?>
			    </td>
        </tr>
		
		<?php } ?>
		
		<?php /*?><?php
		$count = 20;
		for($i=0;$i<$count;$i++){
		?>
		
		<tr class="rw-cls">	
			    <td>
				<input type="checkbox" class="chkbx_cur ad-usr-prj" onclick=""/>
			    </td>
			    <td>
					<span>sandeep</span>
			    </td>
			    <td>
					fsandeep
			    </td>
        </tr>
		<?php } ?><?php */?>
</table>
</td>
</tr>
</table>
</div>
<div class="add-tmp-btn">
<span id="addtaskloader" class="ldr-ad-btn">
	<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
</span>
<span id="taskAddBtns">
	<button class="btn btn_blue" id="confirmusercls"  value="Confirm" type="button" onclick="add_cases_project()"><i class="icon-big-tick"></i>Add</button>
	<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>
</span>
</div>
<?php
}else{
?>
	<table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_tbl">
		<tr class="rw-cls">
			<td align="center">
				<div class="add-tmp-btn">
				<span id="excptAddContinue" >
					No task on template
				</span>
				</div>
			</td>
		</tr>
		<tr class="rw-cls">
			<td align="center">
				<div class="add-tmp-btn">
				<span id="excptAddContinue">
					<button class="btn btn_add_task_template" type="button" onclick="addTempToTask('<?php echo $template_id; ?>', '', 1)">Add Task to Template</button>
				</span>
				</div>
			</td>
		</tr>
	</table>
<?php } ?>