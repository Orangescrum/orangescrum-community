<div class="user_profile_con">
<!--Tabs section starts -->
<?php echo $this->element("company_settings");?>
</div>
<div class="impexp_div">
    <h2 class="fl">Import Task</h2>
    <div class="fr"><a><button class="customfile-button" onclick="ajax_exportCsv(0);"><span class="icon-exp"></span>Export Task (.csv)</button></a></div>
    <div class="cb"></div>
</div>
<div>
<ul id="breadcrumbs_imp">
	<li <?php if(PAGE_NAME=='importexport'){?>class="activ"<?php }?>>Upload File</li>
	<li <?php if(PAGE_NAME=='csv_dataimport'){?>class="activ"<?php }?> >Preview Data</li>
	<li <?php if(PAGE_NAME=='confirm_import'){?>class="activ"<?php }?>>Upload Summary</li>
</ul>
</div>
<?php if(PAGE_NAME!='confirm_import'){?>
<div class="exp_innerdiv" id="imploade_file"  <?php if(isset($fileds)){ echo "style='display:none;'";}?>>
<div class="fl import-csv-file" style="border:1px solid #ccc;">
	<div class="import_project_div">
	Project: 
	<?php 
	if((count($getallproj) == 0) && (SES_TYPE == 1 || SES_TYPE == 2) ) { ?>
		<button onclick="newProject('menupj','loaderpj');">Create Project</button>
	<?php }else{
			if(count($getallproj)=='0'){ ?>
				--None--
		<?php }else {
				if(count($getallproj)=='1'){
				   echo $getallproj['0']['Project']['name'];
				   $swPrjVal = $getallproj['0']['Project']['name'];
			   }else{
				  $swPrjVal = $import_pjname;
				  ?>
					<a href="javascript:void(0);" onclick="view_project_menu('import');" data-toggle="dropdown" class="option-toggle" id="prj_ahref">
						<span id="pname_dashboard"><b><?php echo $this->Format->shortLength(ucfirst($swPrjVal),30); ?></b></span>
						<i class="caret"></i>
					</a>
					<div class="dropdown-menu lft popup" id="projpopup">
						<center>
							<div id="loader_prmenu" style="display:none;">
								<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" alt="loading..." title="loading..."/>
							</div>
						</center>
						<?php if(count($getallproj) >= 6) { ?>
						<div id="find_prj_dv" style="display: none;">
							<input type="text" placeholder="Find a Project" class="form-control pro_srch" onkeyup="search_project_menu('import',this.value,event)" id="search_project_menu_txt">
							<i class="icon-srch-img"></i>
							<div id="load_find_dashboard" style="display:none;" class="loading-pro">
							<img src="<?php echo HTTP_IMAGES;?>images/del.gif"/>
							</div>
						</div>
						<?php } ?>
						<input type="hidden" id="caseMenuFilters" value="" />
						<div id="ajaxViewProject" style='display:none;'></div>
						<div id="ajaxViewProjects"></div>
					</div>
			<?php } 
			 }
		} ?>
	</div>			
	<form action="<?php echo HTTP_ROOT;?>projects/csv_dataimport/<?php echo $proj_uid;?>" enctype="multipart/form-data" method="post" name="data_import_form" id="data_import_form">
		<input type="hidden" value="<?php echo $proj_id;?>" name="proj_id" id="proj_id"/> 
		<input type="hidden" value="<?php echo $proj_uid;?>" name="proj_uid" id="proj_uid"/> 
	<div class="upld_file">Upload your CSV file</div>
	<div class="fl customfile-button">
		<input type="file" class="fl" name="import_csv" id="import_csv" onchange="check_csvfile()"/>
		<div>Choose file</div>
	</div>
	<span class="upload_limit" style="color:#333333"><b>2 MB</b> or <b>1,000</b> rows maximum size</span>
	<div class="cb"></div>
	<span id="err_span" style="color: #900;"></span>
	<div class="import_btn_div">
		<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." title="Loading..."  id="loader_img_csv" style="display: none;position: absolute;"/>
		<button type="submit" id="cnt_btn" class="btn btn_blue btn_impcsv" disabled="true" style="position: relative;">
		<i class="icon-big-tick"></i>
		<span style="color: #fff;">Continue</span>
	</button>
	</div>
	</form>
    </div>
    <div class="fl import-info-dif import_proj" style="border:1px solid #DDD;">
		<div class="chk_content">
			<h4 class="chk_head">CSV File</h4>
			<div class="download_samplefile">
				<a href="<?php echo HTTP_ROOT;?>projects/download_sample_csvfile" style="text-decoration:underline;color:#0000FF">Download the sample file</a> to see what you can import
			</div>
				<ul class="chk_desc">
					<li><b>Title - </b> Task Title - <span style="color:#FF0000">mandatory</span></li>
					<li style="color:#666666"><b>Description - </b> Description of the task</li>
					<li style="color:#666666"><b>Due Date - </b> Due date of Task (mm/dd/yyyy) </li>
					<li style="color:#666666"><b>Status - </b> Current status of the Task (NEW, In Progress, RESOLVED, CLOSED)</li>
					<li style="color:#666666"><b>Type - </b> Task type (Bug, Development, Enhancement, RnD, QA, Unit Testing, Maintenance, Release, Updates, Idea, Others) </li>
					<li style="color:#666666"><b>Assign to  - </b> Email ID of Task Assigned To</li>
				</ul>

				<h4 class="chk_head">Help</h4>
				<ul class="chk_desc">
					<li>Choose a project to Import Task</li>
					<li>Upload the valid CSV file with your Tasks</li>
					<li>First system will prompt a preview of all the data in the file</li>
					<li>In preview you can validate your data</li>
					<li>Confirm the data in the preview and Import them to the system</li>
					<li>All the Tasks will be posted to the selected Project.</li>
				</ul>
			<!--<div class="fr more-help-tips"><a href="<?php echo HTTP_ROOT;?>projects/learnmore" target="_blank" onclick="window.open(this.href, this.target, 'width=430,height=450,resizable,scrollbars');return false;"> More on Help & Tips</a></div>-->
			<!--<div class="cb"></div>-->
		</div>
	</div>
    <div class="cb"></div>
    </div>
	<div id="review_data" <?php if(!isset($fileds)){ ?>style="display: none;"<?php }?>>
		<?php if(isset($fileds)){ ?>
		<form action="<?php echo HTTP_ROOT;?>projects/confirm_import/<?php echo $porj_uid;?>" method="post" >
		<input type="hidden" value="<?php echo $porj_id;?>" name="project_id" /> 
		<input type="hidden" value="<?php echo $csv_file_name;?>" name="csv_file_name" /> 
		<input type="hidden" value="<?php echo $total_rows;?>" name="total_rows" /> 
		<input type="hidden" value="<?php $mserialize = serialize($milestone_arr);echo htmlentities($mserialize); ?>" name="milestone_arr"/>
		<input type="hidden" value="<?php $tserialize = serialize($task);echo htmlentities($tserialize); ?>" name="task_arr"/>
		<div class="imp_data_outer">
			<?php if(isset($task_err) && $task_err){?>
			<div class="data-import-err" style="background:#F9DDA9;color:#000000;margin:0 45px 10px 10px;padding:10px;">
				Project: <span style="color:#2D678D"><b><?php echo $projectname; ?></b></span>
				<br/>
				<span <?php if(count($task) == 0) { ?>style="color:red"<?php } else { ?>style="color:green"<?php } ?>>
				<b><?php echo count($task); ?></b> Tasks to Import
				</span>
				<br/>
				<span style="font-size:14px;">Please double-check the below points before importing your Tasks</span>
				<ul style="font-size:13px;margin-top:0;">
					<li>Blank Title</li>
					<li>Invalid Due Date (should be <b>MM</b>/<b>DD</b>/<b>YYYY</b>)</li>
					<li>Invalid or Misspelled Status</li>
					<li>Invalid or Misspelled Type</li>
					<li>Unknown Assigned To Email ID (User must be associated with the project)</li>
				</ul>
				<button type="submit" class="btn btn_blue" style="position:relative">
						<i class="icon-big-tick"></i>
						Confirm & Import
					</button>
				</div>
			</div>
			<?php }?>
			<table id="preview_data_tbl" width="95%" class="tsk_tbl arc_tbl">
			<tr class="tab_tr">
				<td width="2%">Sl#</td>
				<td width="20%">Title</td>
				<td width="30%">Description</td>
				<td class="due_imp_date">Due Date</td>
				<td width="10%">Status</td>
				<td width="10%">Type</td>
				<td width="10%">Assigned to</td>
			</tr>
<!--			<tr>
				<td colspan="6">
					<div style="" class="imp_data_div">
					<?php //foreach ($milestone_arr as $key => $val){?>
					<table width="100%">
						<tbody>
						<tr>
							<td colspan="6"><?php echo '<b><i>'.$val['title'].'</i></b>';?> &nbsp;&nbsp; <span class="fr" style="margin-right: 2px;font-style:italic"><?php if($val['start_date']){?> <b>Start Date: </b><?php echo date('m/d/Y',strtotime($val['start_date']));  }?>&nbsp;&nbsp; <?php if($val['end_date']){?> <b>End Date: </b><?php echo date('m/d/Y',strtotime($val['end_date'])); }?></span></td>
						</tr>-->
						<?php
						if(isset($task) && $task){
						$error_arr =$task_err;
						$i=0;
						foreach($task AS $k=>$v){
							$i++;
							?>
						<tr class="tr_all">
							<td valign="top"  width="2%"><?php echo $i;?> </td>
							<td valign="top"  width="20%"><?php echo htmlentities($v['title']);?> </td>
							<td valign="top" width="30%"><?php echo htmlentities($v['description']) ;?></td>
							<td  width="10%" <?php if($error_arr[$k]['due date']){ $err =1;?>class="error-imp-data"<?php }?> valign="top" ><?php echo htmlentities($v['due date']);?></td>
							<td  width="10%" <?php if($error_arr[$k]['status']){$err =1;?>class="error-imp-data"<?php }?> valign="top"><?php echo htmlentities($v['status']) ;?></td>
							<td  width="10%" <?php if($error_arr[$k]['type']){$err =1;?>class="error-imp-data"<?php }?> valign="top"><?php echo htmlentities($v['type']) ;?></td>
							<td  width="10%" <?php if($error_arr[$k]['assigned to']){?>class="error-imp-data"<?php }?> valign="top"><?php echo htmlentities(($v['assigned to'] && strtolower($v['assigned to'])!='me')?$v['assigned to']:'me') ;?></td>
						</tr>
						<?php }?>
						</tbody>
					<!--</table>-->
						<?php //}}?>
						<?php }?>
<!--					</div>
				</td>
			</tr>-->
			<?php /*if(isset($err) && $err){?>
				<!--tr>
					<td style="" colspan="6">
						<ul class="chk_desc red_fnt">
							<li>Field values marked as red contain error data ,Those data will be assigned default value.</li>
							<li>Due date will be Current date</li>
							<li>Status will be New</li>
							<li>Type will be Development</li>
							<li>Assigned to will be Me</li>
						</ul>
					</td>
				</tr-->	
				<?php } */?>
			<?php if($task){?>
			<tr>
				<td colspan="7" align="center">
					<button type="submit" class="btn btn_blue" style="position:relative">
						<i class="icon-big-tick"></i>
						Import
					</button>
				</td>
			</tr>
			<?php }?>
		</table>
		<?php } ?>
		</div>
		</form>	
	</div>
<?php  }else{?>
<div id="review_data">
	<table class="fyl_table">
		<tr>
			<td class="upload_summery_text" colspan="2">Upload Summary</td>
		</tr>
		<tr>
			<td colspan="2">
				<table>
					<tr>
						<td colspan="2">Input CSV file:&nbsp;<b><?php echo $csv_file_name;?></b></td>
					</tr>
					<tr>
						<td colspan="2">Total data:&nbsp;<b><?php echo ($total_rows-1);?></b> rows</td>
					</tr>
					<tr>
						<td colspan="2" >Valid data:&nbsp;<b><?php echo $total_valid_rows;?></b> rows</td>
					</tr>
					<tr>
						<td colspan="2" ><b><?php echo $total_task;?></b> Task(s) Imported into project:&nbsp;<b><?php echo $proj_name;?></b></td>
					</tr>
<!--					<tr>
						<td valign="top">Milestone:&nbsp;</td>
						<td>
							<?php foreach($history AS $key=>$val){?>
									<table style="text-align:left" cellpadding='0' cellspacing='0'>
										<tr>
											<td>
												<?php echo $val['milestone_title'];?> / <?php echo $val['total_task'];?> Task(s)
											</td>
										</tr>
									</table>
							<?php }?>
						</td>
					</tr>-->
				</table>
			</td>
		</tr>
	</table>
</div>
<?php }?>
<script type="text/javascript">
	function check_csvfile(){
		//$('#cnt_btn').attr('disabled','disabled');
		//$('#cnt_btn').removeClass('activ');
		var url ='<?php echo HTTP_ROOT;?>'+'projects/checkfile_existance';
		if($('#import_csv').val()){
			var file= $('#import_csv').val();
			var ext = file.split('.').pop();
			if(ext=='csv' || ext=='CSV'){
				var data = new FormData();
				jQuery.each($('#import_csv')[0].files, function(i, file) {
					data.append('file-'+i, file);
				});
				data.append('porject_id',$('#proj_id').val());

				$('#loader_img_csv').show();
				//$('#cnt_btn').text('Loading...');
				$.ajax({
					url:url,
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST',
					dataType:'json',
					success: function(data){
						$('#loader_img_csv').hide();
						$('#err_span').html('');
						if(data.error){
							if(confirm(data.msg)){
								$('#cnt_btn').removeAttr('disabled');
								$('#cnt_btn').removeClass('btn_impcsv');
							}
						}else{
							$('#cnt_btn').removeAttr('disabled');
							$('#cnt_btn').removeClass('btn_impcsv');
						}
					}
				});
			}else{
				$('#err_span').html('Please upload a valid csv file<br/>');
				$('#import_csv').val('');
			}
		}else{
			$('#cnt_btn').attr('disabled','disabled');
			$('#cnt_btn').addClass('btn_impcsv');
		}
	}
	function ajax_exportCsv(is_milestone) {
		openPopup();
		$(".exportcsv").show();
		$('#exportcsv_content').html('');
		//$('#expimppopup').toggle();
	    var projFil = $('#projFil').val();
	    if(parseInt(is_milestone)){
			$("#popup_heading").text("Export milestone to CSV");
	    }else{
			$("#popup_heading").text("Export Tasks to CSV");
	    }
	    //$('#exporttaskcsv_popup').show();
	    //$('#popdv_csv').show();
	    $.post(HTTP_ROOT+"easycases/ajax_exportcsv",{"projUniq":projFil,"is_milestone":is_milestone}, function(res){
		if(res) {
		  //  $('#popdv_csv').hide();
			$('.loader_dv').hide();
			$('#exportcsv_content').show();
		    $('#exportcsv_content').html(res);
		}
	    });
	   // cover_open('cover','exporttaskcsv_popup');
	}
	          
	function ExportCSV()
	{
		var projFil = $('#projFil').val();
		window.location = HTTP_ROOT+"easycases/exporttoCSV/"+projFil;
	}
	function exportcsv(){
		var chkedarr = new Array();
		var carr = new Array();
		var j= 0;
		$('input[id^="mstones"]').each(function(i){
			if($(this).attr('checked')){
				j++;
				chkedarr.push($(this).val());
			}	
		});	
		if(j){
			document.getElementById('check_csv').value=chkedarr;
			document.getElementById('check_typ').value="printcsv";
			document.getElementById('idaud').submit();
		}else{
			alert('Please select atleast one checkbox to export the milestone tasks to csv');	
		}
}
	function change_milestone(obj){
	    var strURL = $('#pageurl').val();
	    strURL = strURL+"easycases/ajax_change_milestone";
	    $.post(strURL,{"id":obj.value},function(res){
		if(res){
		    $("#milestone_dv").html(res);
		}
	    });
	}
	function change_milestone_options(obj){
	    var strURL = $('#pageurl').val();
	    strURL = strURL+"easycases/ajax_change_milestone_options";
	    $.post(strURL,{"id":obj.value},function(res){
		if(res){
		    $("#milestone_list").html(res);
                }
	    });
	}
	
	function change_member_assignto(obj) {
            change_milestone_options(obj);
	    var strURL = $('#pageurl').val();
	    strURL = strURL+"easycases/ajax_member_assignto";
	    $.post(strURL,{"id":obj.value},function(res){
		if(res){
		    $("#tr_members").remove();
		    $("#tr_assign_to").remove();
		    $(res).insertAfter($("#tr_priority"));
		}
	    });
	}
	
	function showCustomRange(obj) {
	    if(obj.value=='cst_rng'){
		$("#tr_cst_rng").show();
	    }else{
		$("#tr_cst_rng").hide();
	    }
	}
	
</script>