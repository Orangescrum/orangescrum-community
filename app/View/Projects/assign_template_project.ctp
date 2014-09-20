<style>
.icon_fil{background: url("<?php echo HTTP_ROOT;?>img/html5/icons/proj_alert.png") no-repeat scroll 7px 5px rgba(0, 0, 0, 0);height: 26px;margin:-4px -16px;position: absolute;width: 23px;}
</style>
<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td style="padding-top:10px;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="left" style="font-weight:bold;font-size:14px;">
						<div style="float:left;margin-top:5px;">Template:&nbsp;&nbsp;</div>
						<div style="float:left;margin-top:5px;" >
						<select name="data[ProjectTemplate][project_id]" id="temp_mod_id" style="padding:2px;width:200px;border-radius: 3px 3px 3px 3px;" class="text_field" onchange="show_cases_temp_module(this.value);">
						<?php
						if(count($temp_module))
						{ ?>
							<option value="0" >[Select]</option>
							<?php foreach($temp_module as $temp_module)
							{ ?>
								<option  <?php if($temp_mod_ids == $temp_module['ProjectTemplate']['id']){echo "selected ";}?>value="<?php echo $temp_module['ProjectTemplate']['id'];?>"><?php echo $this->Format->formatText($temp_module['ProjectTemplate']['module_name']); ?></option>
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
						</div>
						<div style="float:left;margin-top:5px;padding-left:25px">Project:&nbsp;&nbsp;</div>
						<div style="float:left;margin-top:5px;" >
						<select name="data[ProjectTemplate][project_id]" id="proj_id" style="padding:2px;width:200px;border-radius: 3px 3px 3px 3px;" class="text_field">
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
						</div>
						<div style="float:left;font-size:10px;margin-left:10px;font-size:13px;">
							<span id="subprof1">
								<button style="margin: 5px 0 0;width:121px;text-align:right" value="Show Tasks" onclick="add_cases_project()"><div class="icon_fil"></div>Import Tasks</button>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="<?php echo $referer; ?>" style="text-decoration:none;font-size:13px;font-weight:normal;">Cancel</a>
							</span>
						</div>
						<div style="float:left;font-size:10px;margin-left:5px;">
							<span id="subprof2" style="display:none;margin-top:7px;margin-left:5px;">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/> 
							</span>
						</div>
					</td>
					<td align="right">
						<?php
						/*if(count($temp_dtls_cases)) {*/ ?>
						<!--<div style="float:right;margin-top:5px;" >
							<a href="#" onclick="add_template_cs();" ><div class="btn topbtn" id="newcase_but">+ New Template</div></a>-->
						</div>
						<?php
						// }
						 ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-top:10px">
			<div id="show_temp_cases">
				<table border="0" style="border:1px solid #DCDCDC" width=100%>
				<tr height="28px" class="tophead">
						<td  style="padding-left:4px;" align="left" width="300px">Title</td>
						<td  style="padding-left:4px;" align="left">Description</td>                        
						<td  style="padding-left:4px;" align="left" width="80px">Created On</td>
						<td align="center" width="75px">Action</td>
					</tr>
					<?php
						$count=0; $clas = "";
						$totCase = 0; 
						if(count($temp_dtls_cases)) {
						foreach($temp_dtls_cases as $template) {
						$count++;
						if($count %2==0) { $clas = "row_col"; } else { $clas = "row_col_alt"; }
					?>
					<tr class="<?php echo $clas?>" height="22px" id="mslisting<?php echo $count; ?>">
						<td style="padding-left:2px;">
								<?php echo $this->Format->formatText($template['ProjectTemplate']['title']);?>
						</td>
						<td style="padding-left:2px;">
							<?php echo $this->Format->formatCms($template['ProjectTemplate']['description']);?>
						</td>
						<td style="padding-left:2px;" align="left">
							<?php
							$dt = explode(" ",$template['ProjectTemplate']['created']);
							$dt = explode("-",$dt[0]);
							$dateformat=$dt['1']."/".$dt['2']."/".$dt['0'];
							echo $dateformat;						
							?>
						</td>
						<td  align="center">
							<div style="width:75px;text-align:center;">
								<a href='<?php echo HTTP_ROOT; ?>projects/add_template/<?php echo $template['ProjectTemplate']['id']; ?>' class="makeHover"><div class="act_set" style="margin-left:5px;" rel=tooltip  title="setting"></div></a>
								<a href="javascript:void(0);" onClick="deltemplate(<?php echo $template['ProjectTemplate']['id'];?>);">
									<div class="act_del" style="margin-left:5px;" rel="tooltip" title="delete"></div>
								</a>
			
								</div>
						</td>
					</tr>
					<tr>
						<td colspan="7" align="center">
							<div id="caseImg<?php echo $count; ?>" style="display:none;width:100%;text-align:center;padding-top:5px;">
								<img src="<?php echo HTTP_IMAGES; ?>images/case_thread_loader.gif" alt="loading..." title="loading..."/>
							</div>
							<div id="caseDiv<?php echo $count; ?>" style="display:none;text-align:left;color:#666;" class="lidata_div"></div>
						</td>
					</tr>
						<?php 
						}
						}
						else {
							?>
						<td colspan="7" align="center">
							<div style="margin:10px;width:100%;text-align:center">Select a template to view tasks</div>
					</td>
					<?php
						}
					?>
				</table>
			</td>
		</tr>
</table>
<div id="subprof2" style="display:none;margin-top:37px;" align="center">
	 <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading tasks..." title="loading..."/> 
</div>

<script>
function viewEditDeleteHover(a,b)
{
	document.getElementById(b).style.display='block';
}
function viewEditDelete(a,b)
{
	document.getElementById(b).style.display='none';
}
function deltemplate(id)
{
	
	var conf = confirm("Are you sure to delete this template?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT?>";
		var strURL = strURL+'projects/manage_template?id='+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
</script>
