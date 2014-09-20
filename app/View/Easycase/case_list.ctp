<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<td align="left" style="padding:2px;font-size:20px;text-shadow: 0 1px 1px #B3B3B3;font-weight: bold;text-shadow: 0 1px 1px #B3B3B3;">
			<h1 class="toplink">Archive > Tasks</h1>
		</td>
	</tr>
	<tr>
	<td>
		<a href="javascript:void(0)" onclick="caseall()">All</a>
		<a href="javascript:void(0)" onclick="casenone()">None</a>
		<a href="javascript:void(0)" onclick="restoreall()">Restore</a>
		<a href="javascript:void(0)" onclick="removeall()">Remove</a>
	</td>
	</tr>
	<tr>
		<td>
			<?php
			$count=0;
			if(count($list)) {
			?>
			<table border="1" style="border:1px solid #CFD7DD" width="100%">
				<tr bgcolor="#CFD7DD" style="font-weight:bold;color:#333333;padding-left:5px;" height="25px"  valign="top">
					<td style="width:10px;" align="center"><input id="allcase" type="checkbox" onclick="caseall()" style="cursor: pointer;"></td>
					<td  style="width:40px;" align="center">Task#</td>
					<td  style="padding-left:4px;" align="left">Title</td>
					<td  style="padding-left:4px;width:80px;" align="left">Status</td>
					<td  style="padding-left:4px;width:140px;" align="left">Project</td>
					<td  style="padding-left:4px;width:160px;" align="left">Archive Date</td>
					<td align="center" width="80px">Action</td>
				</tr>
			<?php
				$repeatLastUid = "";
				$clas = "";
				$totCase = 0; 
				App::import('Model','Easycase'); $Easycase = new Easycase();
				$Easycase->recursive = -1;
				foreach($list as $lis)
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
				<tr  class="<?php echo $clas?>" height="22px" id="cslisting<?php echo $count; ?>">
					<td align="right" style="padding-right:4px;">
						<input id="case<?php echo $count; ?>" value="<?php echo $lis['Easycase']['uniq_id'];?>" type="checkbox" style="cursor: pointer;">
					</td>
					<td align="right" style="padding-right:4px;">
						<?php echo $lis['Easycase']['case_no']?>
					</td>
					<td align="left" style="padding-left:5px;">
						<?php echo $lis['Easycase']['title'];?>
					</td>
					<td align="left" style="padding-left:5px;">
						<?php
						echo $this->Format->getStatus($lis['Easycase']['type_id'],$lis['Easycase']['legend']);
						?>		
					</td>
					<td align="left" style="padding-left:4px;">
				<?php 
						if($lis['Easycase']['project_id'])
						{
							$projectname = $this->Casequery->getpjname($lis['Easycase']['project_id']);
							echo $projectname;
						}
						?>
						
					</td>
					<td align="left" style="padding-left:4px">
						<?php 
							$caseDtUploaded = $lis['Archive']['dt_created'];
							$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$caseDtUploaded,"datetime");
							$updatedCur = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
							echo $this->Datetime->dateFormatOutputdateTime_day($updated,$updatedCur);
							
						?>
					</td>
					<td align="center">
						<?php echo $this->Html->link($this->Html->image("images/reply.png",array('rel' => 'tooltip','original-title' => "Move to Tasks",'height' => '14','width' => '14','onclick'=>'return confirm("Are you sure you want to move \''.$lis['Easycase']['title'].'\' to cases?")')), array('controller'=>'easycases','action' => 'move_list/'.$lis['Easycase']['uniq_id']) ,array('escape' => false)); ?>
						&nbsp;
						<?php echo $this->Html->link($this->Html->image("images/rem.png",array('rel' => 'tooltip','original-title' => "Remove Permanently",'height' => '14','width' => '14','onclick'=>'return confirm("Are you sure you want to remove \''.$lis['Easycase']['title'].'\' permanently ?")')), array('controller'=>'easycases','action' => 'case_list/'.$lis['Easycase']['uniq_id']) ,array('escape' => false)); ?>	
					</td>
					
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<?php 
		}
		else
		{
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

<script>
/*function delcase(id,idd)
{
	var conf = confirm("Are you sure you want to delete '"+id+"' ?");
	if(conf == true) {
		var strURL = document.getElementById('pageurl').value; //alert(strURL);
		var strURL = strURL+'easycases/delet_list/'+id;//alert(strURL);
		$.post(strURL,{"cid":id},function(data) {
			if(data){
				
				document.getElementById(idd).style.display="none";
			}
		});
		
	}
	else {
		return false;
	}
}*/
</script>
<input type="text" id="all" value="<?php echo $count;?>">
