<table border="0" style="border:1px solid #DCDCDC" width=100%>
				<tr height="28px" class="tophead">
					<td  style="padding-left:4px;" align="left" width="300px">Title</td>
					<td  style="padding-left:4px;" align="left">Description</td>                        
					<td  style="padding-left:4px;" align="left" width="80px">Created Date</td>
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
							<?php echo $this->Format->formatText($template['ProjectTemplateCase']['title']);?>
					</td>
					<td style="padding-left:2px;">
							<?php echo $this->Format->formatCms($template['ProjectTemplateCase']['description']);?>
					</td>
					<td style="padding-left:2px;" align="left">
						<?php
						$current_dt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
						$actual_dt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$template['ProjectTemplateCase']['created'],"datetime");
						echo $this->Datetime->dateFormatOutputdateTime_day($actual_dt,$current_dt,'date');					
						?>
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
						<span style="width:100%;text-align:center;font-size:14px;color:#FF0000;list-style:none;height:60px;">No tasks available</span>
					</td>
					<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
