<?php
	if(count($list)) {
	$count = $lastCount;
	$legendClass = '';
	//echo "<pre>";print_r($list);exit;
	foreach($list as $lis)
	{ 
		$count++;
		//echo "<pre>";print_r($lis);exit;
		$caseDtUploaded = $lis['Archive']['dt_created'];
		$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$caseDtUploaded,"datetime");
		$updatedCur = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
		$displayTime = $this->Datetime->dateFormatOutputdateTime_day($updated,$updatedCur); //Nov 25, Thu at 1:25 pm
?>
	
	<tr class="tr_all all_first_rows" id="cslisting<?php echo $count; ?>" data-value="<?php echo $count; ?>">
		<td>
			<input id="case<?php echo $count; ?>" value="<?php echo $lis['Easycase']['uniq_id'];?>" type="checkbox" style="cursor: pointer;" class="mglt chkOneArcCase">
			<input type="hidden" id="csn<?php echo $count;?>" value="<?php echo $lis['Easycase']['case_no'];?>">
		</td>
		<td align="right"><a href="<?php echo HTTP_ROOT; ?>dashboard#details/<?php echo $lis['Easycase']['uniq_id']?>">#<?php echo $lis['Easycase']['case_no']?></a></td>
		<td></td>
		<td>
			<div>
				<?php echo $this->Format->convert_ascii($lis['Easycase']['title']); ?>
			</div>
			<div class="fnt999">Archived by <font><?php echo $lis['User']['short_name'];?></font>
			<?php if(strpos($displayTime,'Today')===false && strpos($displayTime,'Y\'day')===false) echo 'on'; ?>
			<font><?php echo $displayTime; ?></font></div>
		</td>
		<td>
			<?php
				echo $this->Format->getStatus($lis['Easycase']['type_id'],$lis['Easycase']['legend']);
			?>	
		</td>
		<td>
			<div>
				<?php 
					if($lis['Easycase']['project_id'])
					{
						$projectname = $this->Casequery->getpjname($lis['Easycase']['project_id']);
						echo $projectname;
					}
				?>
			</div>
		</td>
	</tr>
	
	<?php
		} }else{
	?>	
	 <tr>
		<td colspan="7">
			<?php echo $this->element('no_data', array('nodata_name' => 'caselist')); ?>
		</td>
	</tr>
	<?php } ?>
	

	<?php if($lastCount < 15){ ?>
		<?php /*?><input type="hidden" id="all" class="all_count" value="<?php echo $count;?>"><?php */?>
		<input type="hidden" id="pjid" class="pj_id" value="<?php echo $pjid;?>">
		<input type="hidden" id="totalCases" class="total_case_count" value="<?php echo $caseCount;?>">
		<input type="hidden" id="displayedCases" value="<?php echo ARC_CASE_PAGE_LIMIT; ?>">
	<?php } ?>