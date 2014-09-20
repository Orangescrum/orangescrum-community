<div class="fl_content">
	<h3 class="imp_head">Import Milestone/Task</h3>
	<div style="margin-left:20px">
		<ul id="breadcrumbs_imp">
			<li>Upload File</li>
			<li> Preview Data</li>
			<li class="activ">Upload Summary</li>
		</ul>
		<div id="review_data">
			<table class="fyl_table">
				<tr>
					<th style="padding-bottom:10px" colspan="2"><h3>Upload Summary</h3></th>
				</tr>
				<tr>
					<td colspan="2">Input CSV file:&nbsp;<?php echo $csv_file_name;?></td>
				</tr>
				<tr>
					<td colspan="2">Total data:&nbsp;<?php echo $total_rows;?> rows</td>
				</tr>
				<tr>
					<td colspan="2" >Valid data:&nbsp;<?php echo $total_valid_rows;?> rows</td>
				</tr>
				<tr>
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
				</tr>
			</table>
		</div>
	</div>
</div>