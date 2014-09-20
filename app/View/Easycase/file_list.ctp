<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td align="left" style="padding:2px;font-size:20px;text-shadow: 0 1px 1px #B3B3B3;font-weight: bold;text-shadow: 0 1px 1px #B3B3B3;">
			<h1 class="toplink">Archive > Files</h1>
		</td>
	</tr>
	<tr>
		<td> <br><br>
			<?php
			$count=0;
			if(count($file)) {
			?>
			<table border="1" style="border:1px solid #CFD7DD" width="100%">
				<tr bgcolor="#CFD7DD" style="font-weight:bold;color:#333333;padding-left:5px;" height="25px"  valign="top">
					<td  style="width:40px;" align="center">Task#</td>
					<td  style="padding-left:4px;" align="left">File Name</td>
					<td  style="width:100px" align="center">Type</td>
					<td  style="width:100px" align="center">Size (Kb)</td> 
					<td  style="padding-left:4px;width:140px;" align="left">Project</td>                            
					<td  style="padding-left:4px;width:160px;" align="left">Archive Date</td>
					<td  style="padding-left:4px;width:80px;" align="center">Action</td>
				</tr>
				<?php 
					$repeatLastUid = "";
					$clas = "";
					$totCase = 0; 
					
					App::import('Model','Easycase'); $Easycase = new Easycase();
					$Easycase->recursive = -1;
					foreach($file as $fil)
					{	
						$count++;
						if($count %2==0)
						{
							$clas = "row_col";
						}
						else
						{
							$clas = "row_col_alt";
						}
				?>
				<tr id="fllisting<?php echo $count; ?>" class="<?php echo $clas?>" height="22px">
					<td style="padding-right:4px;" align="right">
						<?php echo $fil['Easycase']['case_no']?>
					</td>
					<td align="left" style="padding-left:4px;">
						<a href='<?php echo HTTP_ROOT; ?>easycases/download/<?php echo $fil['CaseFile']['file']; ?>' style='color:#000'>
						<?php echo $fil['CaseFile']['file']; ?>
						</a>
					</td>
					<td align="center">
						<?php 
							$file_type = $fil['CaseFile']['file'];
							echo $this->Format->imageType($file_type,32,32,1); 
						?>
					</td>
					<td align="right" style="padding-right:4px;">
						<?php echo $this->Format->getFileSize($fil['CaseFile']['file_size']);  ?>
					</td>
					<td align="left"  style="padding-left:4px;">
						<?php 
							if($fil['Easycase']['project_id']) {
							$projectname = $this->Casequery->getpjname($fil['Easycase']['project_id']);
							echo $projectname;
							}
						 ?>
					</td>
					<td align="left" style="padding-left:4px;">
						<?php 
							$caseDtUploaded = $fil['Archive']['dt_created'];
							$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$caseDtUploaded,"datetime");
							$updatedCur = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
							echo $this->Datetime->dateFormatOutputdateTime_day($updated,$updatedCur);
						?>
					</td>
					<td align="center">
						<?php echo $this->Html->link($this->Html->image("images/reply.png",array('rel' => 'tooltip','original-title' => "Move to Files",'height' => '14','width' => '14','onclick'=>'return confirm("Are you sure you want to move \''.$fil['CaseFile']['file'].'\' to files ?")')), array('controller'=>'easycases','action' => 'move_file/'.$fil['Easycase']['uniq_id']) ,array('escape' => false)); ?>
						&nbsp;
						<?php echo $this->Html->link($this->Html->image("images/rem.png",array('rel' => 'tooltip','original-title' => "Remove Permanently",'height' => '14','width' => '14','onclick'=>'return confirm("Are you sure you want to remove \''.$fil['CaseFile']['file'].'\' permanently ?")')), array('controller'=>'easycases','action' => 'file_list/'.$fil['Easycase']['uniq_id']) ,array('escape' => false)); ?>	
					</td>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<?php 
		}else {
	?>
	<tr>
		<td colspan="7" align="center">
			<span style="width:100%;text-align:center;font-size:14px;color:#FF0000;list-style:none;height:60px;">No data available</span>
		</td>
	</tr>
	<?php
		}
	?>
</table>
