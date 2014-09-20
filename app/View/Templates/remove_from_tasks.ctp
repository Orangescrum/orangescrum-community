<script>
$(document).ready(function(){
	$("#sortme").sortable({
		update : function (){
			var serial = $('#sortme').sortable('serialize');
			$.ajax({
				url: HTTP_ROOT+"templates/ajax_sort_tasks",
				type: "post",
				data: serial,
				error: function(){
					alert("theres an error with AJAX");
				}
			});
		}
	});
});
</script>
<?php
$caseCount = count($temp_dtls_cases);
if($caseCount > 0)
{
?>
<div class="scrl-ovr">
<ul style="padding:0px;margin:0px;">
<li style="list-style:none;">
<table cellpadding="0" cellspacing="0" class="col-lg-12">
<input type="hidden" id="templateId" value="<?php echo $template_id; ?>">
<tr class="hdr_tr">
	<th width="7%" style="text-align:right;padding-right:19px;"><input type="checkbox" class="chkbx_cur" onclick="selectcaseAll(1,0)" id="checkAll" /></th>
	<th width="3%">&nbsp;</th>
	<th width="25%" style="padding-left:0px;">Title</th>
	<th width="55%" style="padding-left:0px;">Description</th>
	<th width="10%" style="padding-left:0px;">Action</th>
</tr>
</table>
</li>
</ul>
<ul style="padding:0px;margin:0px;" id="sortme">
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
	<li style="list-style:none;" id="menu_<?php echo $val['ProjectTemplateCase']['id']; ?>">
		<table cellpadding="0" cellspacing="0" class="col-lg-12" style="">
			<tr id="listing_<?php echo $val['ProjectTemplateCase']['id']; ?>" class="rw-cls <?php echo $class; ?>" style="border:0px;cursor:move;">	
				<td width="7%">
					<input type="checkbox" class="chkbx_cur ad-usr-prj" id="actionChk<?php echo $val['ProjectTemplateCase']['id']; ?>" data-case-name="<?php echo trim($val['ProjectTemplateCase']['title']);?>" value="<?php echo $val['ProjectTemplateCase']['id']; ?>" onclick="selectcaseAll(0,<?php echo $val['ProjectTemplateCase']['id']; ?>);"/>
					<input type="hidden" id="actionCls<?php echo $val['ProjectTemplateCase']['id']; ?>" value="0"/>
				</td>
				<td width="3%">
					<div class="drag_icn"></div>
				</td>
				<td width="25%" style="text-align:left">
					<span><?php echo $this->Format->shortLength($val['ProjectTemplateCase']['title'], 25); ?></span>
				</td>
				<td width="55%" style="text-align:left">
					<?php echo $val['ProjectTemplateCase']['description']; ?>
				</td>
				<td width="10%" style="text-align:left">
					<div title="Edit" class="act_icon act_edit_task fl" style="cursor:pointer;" onclick="EditTaskProject('<?php echo $val['ProjectTemplateCase']['id']; ?>', '<?php echo addslashes($val['ProjectTemplateCase']['title']); ?>', '<?php echo addslashes($val['ProjectTemplateCase']['description']); ?>')"></div>
					<div title="Delete" class="act_icon act_del_task fl" style="cursor:pointer;margin-left:5px;" onclick="deltemplatecases('<?php echo $val['ProjectTemplateCase']['id']; ?>', '<?php echo addslashes($val['ProjectTemplateCase']['title']); ?>');"></div>
				</td>
			</tr>
		</table>
	</li>
	
	<?php } ?>

</ul>
</div>
<div class="add-tmp-btn">
<span id="addtaskloader" class="ldr-ad-btn">
	<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
</span>
<span id="taskAddBtns" style="display:none;">
	<button class="btn btn_blue" id="confirmusercls"  value="Confirm" type="button" onclick="remove_cases_template();"><i class="icon-big-tick"></i>Remove</button>
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
					No task on this template
				</span>
				</div>
			</td>
		</tr>
		<tr class="rw-cls">
			<td align="center">
				<div class="add-tmp-btn">
				<span id="excptAddContinue" >
					<button class="btn btn_add_task_template" type="button" onclick="addTempToTask('<?php echo $template_id; ?>', '', 1)">Add Task to Template</button>
				</span>
				</div>
			</td>
		</tr>
	</table>
<?php } ?>