<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<?php /*?><tr>
		<td style="padding-top:10px;">
			
			<!--<div style="float:right" >
				<a href="<?php echo HTTP_ROOT;?>projects/add_template/" style="text-decoration:none;"><div align="center"><button class="green" >+ New Template</button></div></a>
			</div>-->
		</td>
	</tr><?php */?>
	<tr>
		<td style="padding-top:10px">
			<div id="show_temp_cases">
				<table border="0" style="border:1px solid #DCDCDC" width=100%>
				<tr height="28px">
						<td  style="padding-left:4px;" align="left" width="300px" class="tophead">Name</td>
						<td  style="padding-left:4px;" align="left" width="80px" class="tophead">Created By</td>
						<td  style="padding-left:4px;" align="left" width="80px" class="tophead">Created On</td>
						<td align="center" width="55px" class="tophead">Action</td>
					</tr>
					<?php
						$count=0; $clas = "";
						$totCase = 0; 
						if(count($proj_temp)) {
						foreach($proj_temp as $template) {
						$count++;
						if($count %2==0) { $clas = "row_col"; } else { $clas = "row_col_alt"; }
					?>
					<tr class="<?php echo $clas?>" height="22px" id="templist<?php echo $count;?>" onmouseover="show_edit('<?php echo $count;?>');" onmouseout="hide_edit('<?php echo $count;?>');" <?php if($template['ProjectTemplate']['is_active'] == 0){?>style="background-color:#C8C8C8;"<?php }?>>
						<td style="padding-left:2px;">
							<div  id="val_div<?php echo $count;?>" style="float:left">
								<a class="classhover" href="javascript:void(0);"  title="Click here to view tasks" onclick="opencases('<?php echo $count;?>');caseListing('<?php echo $count;?>','<?php echo $template['ProjectTemplate']['id'];?> ')">
								<?php echo $this->Format->formatText($template['ProjectTemplate']['module_name']);?>
								</a>
							</div>
							<div style="display:none;margin-left:5px;" id="img_div<?php echo $count;?>">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="Saving your data..." title="Saving your data..."/>
							</div>
							<div style="display:none" id="text_div<?php echo $count;?>">
								<input type="text" id="edit_template_value<?php echo $count;?>" value="<?php echo $this->Format->formatText($template['ProjectTemplate']['module_name']);?>" onblur="save_edit_template('<?php echo $count;?>','<?php echo $template['ProjectTemplate']['id'];?>')">
								<input type="hidden" id="orig_template_value<?php echo $count; ?>" value="<?php echo $this->Format->formatText($template['ProjectTemplate']['module_name']);?>">
							</div>
							<div style="float:left;position:relative;display:none;" id="edit_div<?php echo $count;?>" >
								<div style="float:left;position:absolute;left:10px;width:50px;">
									<div class="fl"><a href="javascript:void(0);" onclick="open_edit('<?php echo $count;?>');"><img src="<?php echo HTTP_ROOT.'img/images/comments.gif'?>"></div><div class="fl" style="margin-left:5px">Edit</div></a>
								</div>
							</div>
						</td>
						<td style="padding-left:2px;">
							<?php if(trim($template['ProjectTemplate']['is_default']) == 0){
								echo "<font style='color:#8C8C8C'>Default</font>";
							}else if( $template['ProjectTemplate']['user_id'] == SES_ID){
								echo "You";
							}else{
								$usr_arr=$this->Casequery->getUserDtls($template['ProjectTemplate']['user_id']);
								echo $usr_arr['User']['short_name'];
							}
							?>
						</td>
						<td style="padding-left:5px;" align="left">
							<?php
							$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['ProjectTemplate']['created'],"datetime");
							$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATE,"date");
							echo $dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT,$gmdate,'date');						
							?>
						</td>
						<td  align="center">
							<div style="width:55px;text-align:center;">
								<?php			
									if($template['ProjectTemplate']['user_id'] == SES_ID || SES_TYPE == 1)
									{
									?>
								<?php
							if($template['ProjectTemplate']['is_active'] == 1){
						?>
								<a href="javascript:void(0);" onClick="inacttemplate(<?php echo $template['ProjectTemplate']['id']?>);">
								<div class="act_dis" rel=tooltip  title="Deactivate"></div>
								</a>&nbsp;
						<?php }else{ ?>
								<a href="javascript:void(0);" onClick="acttemplate(<?php echo $template['ProjectTemplate']['id']?>);">
								<div class="act_dis" rel=tooltip  title="Activate"></div>
								</a>&nbsp;
						<?php } ?>
								<a href="javascript:void(0);" onClick="deltemplate(<?php echo $template['ProjectTemplate']['id'];?>);">
									<div class="act_del" style="margin-left:5px;" rel="tooltip" title="Delete"></div>
								</a>
								<?php } ?>
								</div>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center" style="padding-left:40px">
							<div id="caseImg<?php echo $count; ?>" style="display:none;width:100%;text-align:center;padding-top:5px;">
								<img src="<?php echo HTTP_IMAGES; ?>images/case_thread_loader.gif" alt="loading..." title="loading..."/>
							</div>
							<div id="caseDiv<?php echo $count; ?>" style="display:none;text-align:left;color:#666;" class="lidata_div"></div>
						</td>
					</tr>
					<tr>
					<?php /*?><td colspan="4" align="center">
						<div id="caseImg<?php echo $count; ?>" style="display:none;width:100%;text-align:center;padding-top:5px;">
							<img src="<?php echo HTTP_IMAGES; ?>images/case_thread_loader.gif" alt="loading..." title="loading..."/>
						</div>
						<div id="caseDiv<?php echo $count; ?>" style="display:none;color:#666;" class="lidata_div"></div>
					</td><?php */?>
				
						<?php 
						}
						}
						else {
							?>
						<td colspan="4" align="center">
							<a href="<?php echo HTTP_ROOT;?>projects/add_template/" style="text-decoration:none;"><div align="center" style="margin:10px;" class="button green">Add new Template</button></div></a>
						</td>
					<?php
						}
					?>
				    </tr>
				</table>
				</div>
			</td>
		</tr>
</table>
<input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/>
<script type="text/javascript">
function acttemplate(id)
{
	
	var conf = confirm("Are you sure to activate this template?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT?>";
		var strURL = strURL+'projects/manage_template/?act='+id;
		window.location = strURL;
	}
	else {
		return false;
	}
}
function inacttemplate(id)
{
	
	var conf = confirm("Are you sure to deactivate this template?");
	if(conf == true) {
		var strURL = "<?php echo HTTP_ROOT?>";
		var strURL = strURL+'projects/manage_template/?inact='+id;
		window.location = strURL;
	}
	else {
		return false;
	}
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
function opencases(count) {

	var caseDiv = 'caseDiv'+count;
	var list = 'templist'+count;
	
	var total = document.getElementById('totalcount').value;
	for(var i=1;i<=total;i++) 
	{
		if(i == count && document.getElementById(caseDiv).style.display == 'block') {
			document.getElementById(caseDiv).style.display = 'none';
			document.getElementById(list).style.background = '';
		}
		else {
			var divname = 'caseDiv'+i;
			var divStyle = document.getElementById(divname).style;
			divStyle.display=(caseDiv==divname)?'block':'none';
			
			var divcolname = 'templist'+i;
			var divcolStyle = document.getElementById(divcolname).style; 
			divcolStyle.background=(list==divcolname)?'#FFFFCC':'';
		}
	}
}
function caseListing(count,template_id) {
	var caseDiv = 'caseDiv'+count;
	var caseImg = 'caseImg'+count;
	var strURL = document.getElementById('pageurl').value;
	var strURL = strURL+'projects/ajax_template_case_listing';
	
	if(document.getElementById(caseDiv).innerHTML == "") {
		$("#"+caseImg).show();
		$.post(strURL,{"count":count,"template_id":template_id},function(data) {
		 if(data) { //alert(data);return false;
			$('#'+caseDiv).html(data);
			$("#"+caseImg).hide();
			
		  }
		});
	}
}
function removecase(count,case_template_id,temp_name) {
	
	var conf = confirm("Are you sure you want to remove ' "+temp_name+"' from the template?");
	if(conf == true) {
		var caseDiv = 'caseDiv'+count;
		var caseImg = 'caseImg'+count;
		var listing = 'listing'+count;
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'projects/ajax_template_case_listing';
		//$("#"+userImg).show();
		$("#"+listing).fadeOut(1000);
		$.post(strURL,{"count":count,"rem_template_id":case_template_id},function(data) {
		 if(data) {
			
		  }
		});
		return true;
	}
	else {
		var checkBox = 'usCheckBox'+count;
		document.getElementById(checkBox).checked = true;
		return false;
	}
}
	function show_edit(id){
		if(document.getElementById('text_div'+id).style.display == 'none'){
			$('#edit_div'+id).show();
		}
	}
	function hide_edit(id){
		$('#edit_div'+id).hide();
	}
	function open_edit(id){
		$('#edit_div'+id).hide();
		$('#text_div'+id).show();
		$('#val_div'+id).hide();
		document.getElementById('edit_template_value'+id).focus();
	}
	function save_edit_template(id,template_id){
		var temp_title=document.getElementById('edit_template_value'+id).value;
		var orig_temp_title=document.getElementById('orig_template_value'+id).value;
		var strURL = document.getElementById('pageurl').value;
		var strURL = strURL+'projects/ajax_template_edit';
		
		if(temp_title.trim() != ''){
			$('#edit_div'+id).hide();
			$('#text_div'+id).hide();
			$("#img_div"+id).show();
			if(temp_title.trim() == orig_temp_title.trim()){
				$("#img_div"+id).hide();
				$('#val_div'+id).show();
			}else{
				$.post(strURL,{"count":id,"template_id":template_id,"module_name":escape(temp_title)},function(data) {
					if(data){
						 if(data.trim() == 'fail') {
								$("#img_div"+id).hide();
								$('#val_div'+id).show();
						  }else if(data.trim() == 'exist'){
								$("#img_div"+id).hide();
								$('#val_div'+id).show();
								$('#edit_template_value'+id).val(orig_temp_title);
								var op = 100;
								showTopErrSucc('error',"Template name already exist.");
								return false;
							  }else{
								$("#img_div"+id).hide();
								$('#val_div'+id).show();
								$('#val_div'+id).html(data);
								document.getElementById('orig_template_value'+id).value=temp_title;
								var op = 100;
								showTopErrSucc('success',"Template updated successfully.");
								return false;
						 }
					}
				});
			}
		}else{
			$('#text_div'+id).hide();	
			$('#val_div'+id).show();
			$('#text_div'+id).val(orig_temp_title);
			$('#edit_template_value'+id).val(orig_temp_title);
			var op = 100;
			showTopErrSucc('error',"Template name can't be blank.");
			return false;
		}
	}
</script>
