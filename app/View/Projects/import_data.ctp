<div class="fl_content">
	<h3 class="imp_head">Import Milestone/Task</h3>
	<div class="imp_mgtl">
		<ul id="breadcrumbs_imp">
			<li <?php if(isset($upload_file)){ echo 'class="activ"';}?> >Upload File</li>
			<li <?php if(isset($preview_data)){ echo 'class="activ"';}?> >Preview Data</li>
			<li <?php if(isset($import_summery)){ echo 'class="activ"';}?>>Upload Summary</li>
		</ul>
		<div id="upload_file" <?php if(isset($fileds)){ echo "style='display:none;'";}?> >
			<div class="cb"></div>
			<div class="chk_content">
				<h4 class="chk_head">Checklist Before Import</h4>
				<ul class="chk_desc">
					<li>What format is your list? We can import <b>CSV</b> files  </li>
					<li>
						<a href="<?php echo HTTP_ROOT;?>projects/download_sample_csvfile">Download a sample file</a> to see what we can import (
						<a href="<?php echo HTTP_ROOT;?>projects/learnmore" target="_blank" onclick="window.open(this.href, this.target, 'width=400,height=450,resizable,scrollbars');return false;">Learn more</a>)		</li>
				</ul>
			</div>
			<div class="cb"></div>
			<div class="form_file">
				<h4 class="chk_head">Upload your file</h4>
				<hr/>
				<form action="<?php echo HTTP_ROOT;?>projects/csv_dataimport/<?php echo $proj_uid;?>" enctype="multipart/form-data" method="post" name="data_import_form" id="data_import_form">
					<input type="hidden" value="<?php echo $proj_id;?>" name="proj_id" id="proj_id"/> 
					<input type="hidden" value="<?php echo $proj_uid;?>" name="proj_uid" id="proj_uid"/> 
				<label>Please upload a CSV file</label></br>
				<input type="file" size="25" name="import_csv" id="import_csv" onchange="check_csvfile()"/></br>
				<span id="err_span" style="color: #900;"></span>
				<i>2MB or 1,000 rows maximum size</i>
				</br></br>
				<img src="<?php echo HTTP_IMAGES;?>images/loading_dark_nested.gif" style="display: none;" id="loader_img_csv"/>
				<button type="submit" id="cnt_btn" class="activ" style="display: none;"><span style="color: #fff;">Continue</span></button>
				</form>
			</div>
		</div>
		<div id="review_data" <?php if(!isset($fileds)){ ?>style="display: none;"<?php }?>>
			<?php if(isset($fileds)){ ?>
			<form action="<?php echo HTTP_ROOT;?>projects/confirm_import/<?php echo $porj_uid;?>" method="post" />
			<input type="hidden" value="<?php echo $porj_id;?>" name="project_id" /> 
			<input type="hidden" value="<?php echo $csv_file_name;?>" name="csv_file_name" /> 
			<input type="hidden" value="<?php echo $total_rows;?>" name="total_rows" /> 
			<input type="hidden" value="<?php $mserialize = serialize($milestone_arr);echo htmlentities($mserialize); ?>" name="milestone_arr"/>
			<input type="hidden" value="<?php $tserialize = serialize($task);echo htmlentities($tserialize); ?>" name="task_arr"/>
			<div class="imp_data_outer">
			<table id="preview_data_tbl" border="1" width="100%">
				<tr>
					<th style="text-align:left" class="imp_tl">Title</th>
					<th style="text-align:left" class="imp_ds">Description</th>
					<th style="text-align:left" class="imp_dd">Due Date</th>
					<th style="text-align:left" class="imp_st">Status</th>
					<th style="text-align:left" class="imp_tp">Type</th>
					<th style="text-align:left" class="imp_as">Assigned to</th>
				</tr>
				<tr>
					<td colspan="6">

						<div style="" class="imp_data_div">
						<?php foreach ($milestone_arr as $key => $val) {?>
						<table width="100%" style="text-align:left" >
							<tbody>
							<tr>
								<td colspan="6" class="tophead" style="border:1px solid #ddd"><?php echo '<b><i>'.$val['title'].'</i></b>';?> &nbsp;&nbsp; <span class="fr" style="margin-right: 2px;font-style:italic"><?php if($val['start_date']){?> <b>Start Date: </b><?php echo date('m/d/Y',strtotime($val['start_date']));  }?>&nbsp;&nbsp; <?php if($val['end_date']){?> <b>End Date: </b><?php echo date('m/d/Y',strtotime($val['end_date'])); }?></span></td>
							</tr>
							<?php
							if(isset($task) && $task){
								$error_arr =$task_err[$key];
							foreach($task[$key] AS $k=>$v){
								?>
							<tr>
								<td valign="top" class="imp_tl_det"><?php echo $v['title'] ;?> </td>
								<td valign="top" class="imp_ds_det"><?php echo $v['description'] ;?></td>
								<td class="imp_dd_det" style="<?php if($error_arr[$k]['due date']){ $err =1;?>color:red;<?php }?>" valign="top" ><?php echo $v['due date'] ;?></td>
								<td class="imp_st_det" style="<?php if($error_arr[$k]['status']){$err =1;?>color:red;<?php }?>" valign="top"><?php echo $v['status'] ;?></td>
								<td class="imp_tp_det" style="<?php if($error_arr[$k]['type']){$err =1;?>color:red;<?php }?>" valign="top"><?php echo $v['type'] ;?></td>
								<td class="imp_as_det" style="<?php if($error_arr[$k]['assigned to']){?>color:red;<?php }?>" valign="top"><?php echo ($v['assigned to'] && strtolower($v['assigned to'])!='me')?$v['assigned to']:'me' ;?></td>
							</tr>
							<?php }?>
							</tbody>
						</table>
							<?php }}?>
						</div>
					</td>
				</tr>
				<?php if(isset($err) && $err){?>
					<tr>
						<td style="" colspan="6">
							<ul class="chk_desc red_fnt">
								<li>Field values marked as red contain error data ,Those data will be assigned default value.</li>
								<li>Due date will be Current date</li>
								<li>Status will be New</li>
								<li>Type will be Development</li>
								<li>Assigned to will be Me</li>
							</ul>
						</td>
					</tr>	
					<?php }?>
				<?php if($task){?>
				<tr>
					<td colspan="6" align="center">
						<button type="submit" class="pop_btn" style="width:97px">Confirm</button>
					</td>
				</tr>
				<?php }?>
			</table>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function check_csvfile(){
		//$('#cnt_btn').attr('disabled','disabled');
		//$('#cnt_btn').removeClass('activ');
		$('#cnt_btn').hide();
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
				$('#cnt_btn').hide();
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
								$('#cnt_btn').show();
							}
						}else{
							$('#cnt_btn').show();
						}
					}
				});
			}else{
				$('#err_span').html('Please upload a valid csv file<br/>');
				$('#import_csv').val('');
			}
		}else{
			$('#cnt_btn').attr('disabled','disabled');
			$('#cnt_btn').removeClass('activ');
		}
	}
</script>