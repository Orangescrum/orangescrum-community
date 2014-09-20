<style type="text/css">
.act_set{
	background:url(../../img/images/setting_icon.png) 0px 0px  no-repeat;
	height:17px;
	width:18px;
	float:left;
}
.act_set:hover{
	background:url(../../img/images/setting_icon.png) 0px -27px  no-repeat;
	height:17px;
	width:18px;
}
.act_del{
	background:url(../../img/images/rem_icon.png) 0px 0px  no-repeat;
	height:17px;
	width:18px;
	float:left;
}
.act_del:hover{
	background:url(../../img/images/rem_icon.png) 0px -28px  no-repeat;
	height:17px;
	width:18px;
}
</style>
<table border="0" style="border:1px solid #CFD7DD" width="100%">
				<tr bgcolor="#CFD7DD" style="font-weight:bold;color:#333333;padding-left:5px;" height="25px"  valign="top">
					<td  style="padding-left:4px;" align="left" width="300px">Title</td>
					<td  style="padding-left:4px;" align="left">Description</td>                        
					<td  style="padding-left:4px;" align="left" width="80px">Created Date</td>
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
				<tr class="<?php echo $clas?>" height="22px" id="mslisting<?php echo $count; ?>" <?php echo $count; ?>">
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
						<span style="width:100%;text-align:center;font-size:14px;color:#FF0000;list-style:none;height:60px;">No data available</span>
					</td>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
