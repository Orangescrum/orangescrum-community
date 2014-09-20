<!--[if IE 8]>
    <style>
        .add_project_template .cs_prj_tmpl{padding-left:3px}
    </style>	
<![endif]-->
<!--[if IE 9]>
    <style>
        .add_temp_prj_ie .case_fieldprof{padding-right:4px;} 
    </style>	
<![endif]-->
<script>
$(document).ready(function(){
    
 	var clonedRow = $('.sectionpj').clone().html();

    var appendRow = '<tr class="appendtotr"><td style="padding-left:9px;">' + clonedRow + '</td><td><div align="center" width="226px" id="btnRemove" valign="top"><a style="color:red;" href="#">Remove</a></div></td></tr>';  
      
     $('#btnRemove').live('click',function()
	 {
		 var rowLength = $('.appendtotr').length;
		 if(rowLength > 1)
		 {
			deleteRow(this);
		 }
		 
		 if(rowLength == 2){
		 	$(".btnsForAdd").css("width","454px");
		 }
		
	 });
     function deleteRow(currentNode)
	 {
		$(currentNode).parent().parent().remove();
	 }
});
function add_more_temp(){
	var clonedRow = $('.sectionpj').clone().html();

   var appendRow = '<tr class="appendtotr"><td style="padding-left:5px;">' + clonedRow + '</td><td><div align="left" width="226px" id="btnRemove" valign="top"><a style="color:red;" href="#">Remove</a></div></td></tr>';
	$('.appendtotr:last').after(appendRow);
	$(".btnsForAdd").css("width","458px");
}
</script>
<?php echo $this->Form->create('ProjectTemplateCase',array('url'=>'/templates/add_template_task','id'=>'addTemplste','onsubmit'=>'return validateTaskTemplate()')); ?>
<input type="hidden" name="data[ProjectTemplateCase][template_id]" id="temp_id" value="<?php echo $temp_id; ?>" />
<input type="hidden" name="data[ProjectTemplateCase][temp_name]" id="temp_name" value="<?php echo $temp_name; ?>" />
<div class="scrl-ovr" style="min-height:165px;">
	<center><div id="project_temp_err" class="fnt_clr_rd" style="display:block;"></div></center>
	<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%;">
		<tr class="appendtotr">
			<td class="sectionpj" align="left" style="width:82%;padding-right:0;">
				<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%;margin-left:13px;">
					<tr>
						<td>Task Title:</td>
						<td style="padding-right:0px;">
							<input type="text" name="data[ProjectTemplateCase][title][]" id="title" class="form-control" value= "" style="width:415px;" />
						</td>
					</tr>
					<tr>
						<td valign="top">Description:</td>
						<td style="padding-right:0px;">
							<textarea name="data[ProjectTemplateCase][description][]" id="description" class="form-control" style="width:415px"></textarea>
						</td>
					</tr>
				</table>
			</td>
			<td class="btn_align" style="width:18%"><div onclick="add_more_temp();"><a style="color:#5895B4; cursor:pointer">Add new Row</a></div></td>
		</tr>			
	</table> 
</div>
<div class="add-tmp-btn">
	<span id="addtasktotemploader" class="ldr-ad-btn">
		<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="loading..." title="loading..."/> 
	</span>
	<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%;">
		<tr>
			<td class="sectionpj" align="left" style="width:82%;padding:0;">
				<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab" style="width:100%;margin-left:13px;">
					<tr>
						<td style="padding:0px;">&nbsp;</td>
						<td style="padding:0px;text-align:left;width:454px;" class="btnsForAdd">
							<span id="taskAddBtns">
								<button class="btn btn_blue" name="submit_template" type="submit"><i class="icon-big-tick"></i>Add</button>
								<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>
							</span>
						</td>
					</tr>
				</table>
			</td>
			<td class="btn_align" style="width:18%;padding:0px;">&nbsp;</td>
		</tr>			
	</table>
</div>
<?php echo $this->Form->end(); ?>