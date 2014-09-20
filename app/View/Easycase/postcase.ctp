<?php
if($caseid)
{
?>
	<h1 class="toplink">Edit Task</h1>
<?php
}
else
{
?>
	<h1 class="toplink">Add New Task</h1>
<?php
}
?>
<script>
$('.datepicker').datepick({ pickerClass: 'jq-datepicker' });
/*$('#description').wysiwyg({
	controls: {
	  strikeThrough : { visible : true },
	  underline     : { visible : true },
	  separator00 : { visible : true },
	  insertOrderedList    : { visible : true },
	  insertUnorderedList  : { visible : true },
	  separator01 : { visible : true },
	  indent    : { visible : true },
	  outdent  : { visible : true }
	}
});*/
$('.description').wysiwyg({ iFrameClass:'wysiwyg-iframe' });

$(function ()
{
	$('#file_upload').fileUploadUI({
		uploadTable: $('#up_files'),
		downloadTable: $('#up_files'),
		buildUploadRow: function (files, index)
		 {
			return $('<tr><td valign="top">' + files[index].name + '</td>' +
					'<td valign="top" width="200px" style="padding-left:10px;" title="Uploading..." rel="tooltip"><div class="progress-bar"><div class="progress-bar red"><\/div><\/div></td>' +
					'<td valign="top" style="padding-left:10px;"><div class="file_upload_cancel">' +
					'<font id="cancel" class="ui-state-default ui-corner-all" title="Cancel" title="Cancel" rel="tooltip">' +
					'<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
					'<\/font><\/div><\/td><\/tr>');
		},
		buildDownloadRow: function (file)
		{
			if(file.name != "error")
			{
				//var id = "chkfile'+file.count+'";
				//var oncheck = "onClick='return confirm(\"Are you sure you want to remove this file?\")'";
				
				if(parseFloat(file.sizeinkb) <= 5120)
				{
					var oncheck = "";
					
					return $('<tr><td style="color:#0683B8;" valign="top"><input type="checkbox" checked '+oncheck+' style="cursor:pointer;"/>&nbsp;&nbsp;<a href="#" style="text-decoration:underline;position:relative;top:4px;">'+ file.name +'</a><input type="hidden" name="data[Easycase][name][]" value="'+file.filename+'"/><\/td><\/tr>');
				}
				else
				{
					alert("Error uploading file. File size cannot be more then 5 Mb!");
				}
			}
			else
			{
				alert("Error uploading file. Please try with another file");
			}
		}
	});
});
</script>
<style>

</style>
<hr style="padding:0;margin:0;"/>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left" valign="top">
			<?php //echo $this->Form->create('Easycase',array('url'=>'/easycases/post','onsubmit'=>'return validateCase()','enctype'=>'multipart/form-data')); ?>
			<input type="hidden" name="data[Easycase][istype]" value="1" readonly="true"/>
			<table cellpadding="0" cellspacing="10" border="0" style="border:0px solid #FF0000;">
				<tr>
					<td align="right" width="130px">
						<strong>Project:</strong>
					</td>
					<td align="left" width="270px">
						<?php
						if(count($projArr) == 0)
						{
							echo "<span style='font-weight:normal;color:#FF0000;'>yet to assign!</span>&nbsp;&nbsp;&nbsp;&nbsp;";
							?>
							<input type="hidden" id='sel_myproj' value=""/>
							<?php
						}
						else
						{
						?>
							<select id="sel_myproj"  name="data[Easycase][sel_myproj]" class="case_fields" onChange="ajaxMemberView(this.value,'ajxMemaddNewCase','ajaxCaseAsgnMe');" style="width:315px;">
							<option value="">[Select]</option>
							<?php
							foreach($projArr as $getProj)
							{
							?>
								<option
								<?php
								if($getProj['Project']['uniq_id'] == $sel_myproj)
								{
									echo "selected";
								}
								?>
								value="<?php echo $getProj['Project']['uniq_id']?>"><?php echo $this->Format->formatText($getProj['Project']['name'])?></option>
								<?php
								}
								?>
							</select>
						<?php
						}
						?>
					</td>
					<?php
					$titleValue = "Daily Update - ".date("m/d");
					?>
					<td align="right" width="155px" >
						<strong>Task Type:</strong>&nbsp;
					<select id="sel_mytype"  name="data[Easycase][type_id]" class="case_fields" style="width:80px;" onChange="hide_prifield('<?php echo $titleValue; ?>','txt_Title');">
						<?php
						if(count($quickTyp))
						{
							foreach($quickTyp as $getType)
							{
							?>
								<option 
								<?php
								if($getType['Type']['id'] == $sel_mytype)
								{
									echo "selected";
								}
								elseif($getType['Type']['id'] == 2 && !$caseid)
								{
									echo "selected";
								}
								?>
								value="<?php echo $getType['Type']['id']; ?>" title="<?php echo $getType['Type']['name']; ?>"  style="background:url(<?php echo HTTP_IMAGES;?>images/<?php echo $getType['Type']['short_name']?>.png);background-repeat: no-repeat;padding-left:20px;height:15px;"><?php echo $getType['Type']['short_name']; ?></option>
							<?php
							}
						}
						?>
					</select>
					</td>
					<td align="left" style="padding-left:10px;">
						<span id="leb_pri" <?php if($sel_mytype == 10) { echo "style='display:none'"; } ?>>
							<strong>Priority:</strong>&nbsp;
							<select name="data[Easycase][priority]" id="sel_casePri" class="case_fields" style="width:80px">						  
								<?php
								for($pri=0;$pri<=2;$pri++)
								{
								?>
									<option
									<?php
									if($pri == $sel_casePri)
									{
										echo "selected";
									}
									elseif($pri == 2 && !$caseid)
									{
										echo "selected";
									}
									?>
									value="<?php echo $pri; ?>">
									<?php if($pri == 0) { echo "High"; } elseif($pri == 1) { echo "Medium"; } else { echo "Low"; } ?>
									</option>
								<?php
								}
								?>
							</select>
						</span>
					</td>
				</tr>
				<tr>
					<td align="right" width="80px">
						<strong>Title:</strong>
					</td>
					<td align="left" colspan="3">
						<?php echo $this->Form->text('title',array('value'=>stripslashes($txt_Title),'id'=>'txt_Title','maxlength'=>'240','class'=>'large','style'=>'width:625px;font-weight:bold;padding:5px;')); ?>
						
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">
						<strong>Description:</strong>
					</td>
					<td align="left" colspan="3">
						<textarea name="data[Easycase][message]" id="description" style="width:620px;height:160px;" class="txtarea"><?php echo $txa_Msg; ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top"><strong>Email Notification:</strong></td>
					<td align="left" colspan="3" valign="top">
						<div id="ajxMemaddNewCase" style="display:none"><!--ajax_postcase_mem.ctp--></div>
						<div id="ajxMemaddNewCase2" style="display:block"><input type="checkbox" name="chk_all" id="chk_all" value="all" class="checkbox_style" onClick="checkedAllRes()" checked="checked">&nbsp;All</div>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">
						<strong>Attachment:</strong><br/><font style="font-size:12px;color:#008000;">(optional)</font>
					</td>
					<td align="left" colspan="3">
						<table cellpadding="0" cellspacing="0" >
							<tr>
								<td>
									<form id="file_upload" action="<?php echo HTTP_ROOT."easycases/fileupload/"; ?>" method="POST" enctype="multipart/form-data">
										<div class="customfile">
										<span class="customfile-button button" aria-hidden="true">Browse</span>
										<span class="customfile-feedback" aria-hidden="true">Select files to upload...</span>
										<input class="fileupload customfile-input" name="data[Easycase][case_files]" type="file" multiple="" />
										</div>
									</form>
								</td>
								<td style="padding-left:10px;" valign="top">
									<img src="<?php echo HTTP_IMAGES;?>images/attach.png" width="20" height="20" style="position:relative;top:4px;"/> <i> Maximum file size allowed is 5 MB.</i>
								</td>
							</tr>
							<tr>
								<td style="padding-left:5px;" colspan="2">
									<table id="up_files" style="font-weight:normal;"></table>
								</td>	
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="right" valign="middle">
						<strong>Assign To:</strong>
					</td>
					<td align="left" colspan="3">
						<table cellpadding="0" cellspacing="0" >
							<tr>
								<td>
									<div id="ajaxCaseAsgnMe" style="display:none"><!--ajax_postcase_assign.ctp--></div>
									<div id="ajaxCaseAsgnMe2" style="display:block;">
										<select name="data[Easycase][assign_to]" id="assign" class="case_fields">
											<option value="">me</option>
										</select>
									</div>
								</td>
								<td align="right" valign="middle" style="padding-left:20px;">
									<strong>Due Date:</strong>&nbsp;&nbsp;
								</td>
								<td align="left">
									<?php
									$today = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
									$curDate = date("m/d/Y",strtotime($today));
									?>
									<input type="text" name="data[Easycase][due_date]" id="due_date" class="datepicker small" value="<?php echo $case_due_date; ?>" onchange="checkDate('due_date','<?php echo $curDate; ?>')" style="width:146px"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				if($caseid)
				{
				?>
				<tr>
					<td></td>
					<td align="left" colspan="3">
						<div id="ajxFileDel"><!--ajax_case_files.ctp--></div>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td>&nbsp;</td>
					<td align="left" valign="top" colspan="3" style="font-weight:normal;">
					<input type="hidden" id="edit_proj" name="edit_proj" value="<?php echo $edit_proj; ?>" readonly="true">
					<?php
					if(!$caseid)
					{
					?>
					<span id='sub_post'>
						<input type="hidden" name="easycaseid" id="easycaseid" value="" readonly="true"/>
						<button type="submit" value="Post" name="data[Easycase][postdata]" style="margin-left:3px;margin-top:5px;width:75px;padding:5px 5px 15px 5px;" class="blue small">Post</button>
						or
						<a href="javascript:void(0);" class="nyroModalClose">Cancel</a>
					</span>
					<span id='sub_loader' style="display:none;">
						<img src='<?php echo HTTP_IMAGES;?>images/del.gif' alt="Loading..." title="Loading..." width="16" height="16"/>
					</span>
					<?php
					}
					else
					{
					?>
					<input type="hidden" name="data[Easycase][case_no]" id="case_no" value="<?php if($caseno) { echo $caseno; } ?>" readonly="true"/>
					<input type="hidden" name="data[Easycase][id]" id="easycaseid" value="<?php if($caseid) { echo $caseid; } ?>" readonly="true"/>
					<input type="hidden" name="data[Easycase][uniqid]" id="uniqid" value="<?php if($caseid) { echo $_GET['case']; } ?>" readonly="true"/>
					<span id='sub_post'>
						<button type="submit" value="Update & Continue" name="data[Easycase][postdata]" style="margin-left:3px;margin-top:5px;width:130px" class="blue small">Update & Continue</button>
						<button type="submit" value="Update & View List" name="data[Easycase][postdata]" style="margin-left:3px;margin-top:5px;width:130px" class="blue small">Update & View List</button>
						or
						<a href="javascript:void(0);" class="nyroModalClose">Cancel</a>
					</span>
					<span id='sub_loader' style="display:none;">
						<img src='<?php echo HTTP_IMAGES;?>images/del.gif' alt="Loading..." title="Loading..." width="16" height="16"/>
					</span>
					<?php
					}
					?>
					</td>
				</tr>
			</table>
			<!--</form>-->
			<!--<div style="position:absolute;top:405px;left:110px;"></div>-->
				
			<!--<input type="hidden" name="data[Easycase][name]" id="file_name" size="10" readonly="true"/>
			<input type="hidden" name="data[Easycase][size]" id="file_size" size="10" readonly="true"/>
			<input type="hidden" name="data[Easycase][total]" id="file_total" size="10" readonly="true"/>-->
		</td>
		<?php /*?><td width="200px" valign="top" style="font-size:14px;">
			<strong>Legends</strong>
			<table cellpadding="4" cellspacing="4" width="100%" border="0" style="font-size:13px;border:1px solid #A6A6A6;margin-top:5px;">
				<tr><td colspan="3"></td></tr>
				<?php
				foreach($quickTyp as $caseTypes)
				{
				?>
				<tr><td width="15px" align="right"><b><?php echo $caseTypes['Type']['short_name']?></b>:&nbsp;</td><td width="18px">
				<?php echo $this->Format->todo_typ($caseTypes['Type']['short_name'],$caseTypes['Type']['name'])?>
				</td><td><?php echo $caseTypes['Type']['name']?></td></tr>
				<?php
				}
				?>
			</table>
		</td><?php */?>
	 </tr>
</table>