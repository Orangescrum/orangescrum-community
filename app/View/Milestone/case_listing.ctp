<?php
$cscount=0; $class = ""; $totCase = 0;
$repeatcaseTypeId = "";
$repeatLastUid = "";
$repeatAssgnUid = "";
$totids = "";
$getlastUid = "";
$caseCount = count($allCases);
if($allCases) {
?>
<table border="0" style="border:1px solid #CFD7DD" width="100%" align="center" cellspacing="0" cellpadding="0">
	<tr bgcolor="#E5E5E5" valign="top" style="font-weight:bold;color:#333333;padding-left:5px;">
		<td style="padding-left:5px;" width="20px"><!--<input type="checkbox" style="cursor:pointer" onclick="selectCaseAll()" id="checkAll"/>--></td>
		<td width="55px" align="right" style="padding-right:15px;">Task#</td>
		<td width="20px"></td>
		<td width="15px"></td>
		<td style="padding-left:10px;">Title</td>
		<td width="80px">Status</td>
		<td width="80px">Due Date</td>
	</tr>
	<?php
	foreach($allCases as $getdata) { 
		//$getdata = $this->Casequery->getAllCases($allCase['EasycaseMilestone']['easycase_id']);
		$caseAutoId = $getdata['Easycase']['id'];
		$caseNo = $getdata['Easycase']['case_no'];
		$caseUserId = $getdata['Easycase']['user_id'];
		$caseTypeId = $getdata['Easycase']['type_id'];
		$caseLegend = $getdata['Easycase']['legend'];
		$casePriority = $getdata['Easycase']['priority'];
		$caseFormat = $getdata['Easycase']['format'];
		$frmt_data = $this->Format->formatText($getdata['Easycase']['title']);
		$frmtdata = htmlentities(trim($frmt_data));
		$caseTitle = $this->Format->convert_ascii($frmtdata);
		//$caseTitle = $getdata['Easycase']['title'];
		$caseAssgnUid = $getdata['Easycase']['assign_to'];
		$caseDueDate = $getdata['Easycase']['due_date'];
		$caseUserId = $getdata['Easycase']['user_id'];                       
		$cscount++;
		if($cscount%2 == 0) { $class = 'border-bottom:1px solid #F2F2F2'; }
		else { $class = "border-bottom:1px solid #F2F2F2"; }
		
		if($repeatcaseTypeId != $caseTypeId)
		{
			$types = $this->Casequery->getType($caseTypeId);
			if(count($types)) {
				$typeShortName = $types['Type']['short_name'];
				$typeName = $types['Type']['name'];
			}
			else{
				$typeShortName = "";
				$typeName = "";
			}
		}
		/*if($caseLegend != 1) {
			$getlastcase = $this->Casequery->getLastCase($getdata['Easycase']['case_no'],$getdata['Easycase']['project_id']);
			$getlastId = $getlastcase['Easycase']['id'];
			$getlastUid = $getlastcase['Easycase']['user_id'];
			
			$getTotCase = $this->Casequery->allCaseReply($getdata['Easycase']['case_no'],$getdata['Easycase']['project_id']);
			if(count($getTotCase)){
				$getTotRep = count($getTotCase)-1;
			}
		}
		else {
			$getlastId = $caseAutoId;
			$getlastUid = $getdata['Easycase']['user_id'];
			$getTotRep = 0;
		}*/
		?>
		<tr height="22px" id="listing<?php echo $cscount; ?>">	
		<td style="padding-left:5px;<?php echo $class; ?>">
			<?php		
			if($uid == SES_ID || SES_TYPE == 1)
			{
			?>
			<input type="checkbox" style="cursor:pointer;position:relative;top:-2px;" id="csCheckBox<?php echo $cscount; ?>" value="<?php echo $getdata['EasycaseMilestone']['id']; ?>" onclick="removeThisCase('<?php echo $cscount; ?>','<?php echo $getdata['EasycaseMilestone']['id']; ?>','<?php echo $getCount; ?>','<?php echo $getdata['EasycaseMilestone']['milestone_id']; ?>','<?php echo $caseNo; ?>','<?php echo $uid; ?>')" checked="checked"/>
			<input type="hidden" id="actionCls<?php echo $cscount; ?>" value="0"/>
			<?php
			}
			else {
				?>
				<input type="checkbox" style="position:relative;top:-2px;" id="csCheckBox<?php echo $cscount; ?>" value="<?php echo $getdata['EasycaseMilestone']['id']; ?>" onclick="removeThisCase('<?php echo $cscount; ?>','<?php echo $getdata['EasycaseMilestone']['id']; ?>','<?php echo $getCount; ?>','<?php echo $getdata['EasycaseMilestone']['milestone_id']; ?>','<?php echo $caseNo; ?>','<?php echo $uid; ?>')" checked="checked" disabled="disabled"/>
				<input type="hidden" id="actionCls<?php echo $cscount; ?>" value="0"/>
				<?php
			}
			?>
		</td>
		<td align="right" style="text-align:right;padding-right:20px;<?php echo $class; ?>"><?php echo $caseNo; ?></td>
		<td style="font-weight:normal;<?php echo $class; ?>"><?php echo $this->Format->todo_typ($typeShortName,$typeName); ?></td>
		<td style="font-weight:normal;<?php echo $class; ?>">
			<?php
			if($casePriority == "NULL" || $casePriority == "") { 
				echo "";
			}
			elseif($casePriority == 0){ 
				echo "<span class='tag red' style='width:5px;height:12px;' title='High' rel='tooltip'>&nbsp;</span>";
			}
			elseif($casePriority == 1){ 
				echo "<span class='tag orange' style='width:5px;height:12px;' title='Medium' rel='tooltip'>&nbsp;</span>";
			}
			elseif($casePriority >= 2){ 
				echo "<span class='tag green' style='width:5px;height:12px;' title='Low' rel='tooltip'>&nbsp;</span>";
			}
			?>
		</td>
		<?php
		/*if($repeatLastUid != $getlastUid)
		{
			if($getlastUid && $getlastUid != SES_ID){
				$usrDtls = $this->Casequery->getUserDtls($getlastUid);
				$usrName = $this->Format->formatText($usrDtls['User']['name']);
				$usrShortName = strtoupper($usrDtls['User']['short_name']);
			}
			else{
				$usrName = "";
				$usrShortName = "me";
			}
		}*/
		?>
		<td style="padding-left:10px;<?php echo $class; ?>">
			<?php echo $this->Format->formatText(ucfirst($caseTitle)); ?>
			<br/>
			<?php
			/*if($caseLegend == 1) {
				$sts = "Created";
			}
			else {
				$sts = "Updated";
			}
			?>
			<font style="font-size:11px;color:#A9A9A9;font-style:italic;"><?php echo $sts; ?> by <font <?php if($usrName) { ?> rel='tooltip' original-title='<?php echo $usrName; ?>' <?php } ?>><?php echo $usrShortName; ?></font>
			on
			<?php
			$updated = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$getdata['Easycase']['dt_created'],"datetime");
			$updatedCur = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
			?>
			<span id="timedis<?php echo $cscount; ?>">
				<?php echo $this->Datetime->dateFormatOutputdateTime_day($updated,$updatedCur); ?>
			</span><?php */?>
		</td>
		<td style="font-weight:normal;<?php echo $class; ?>"><?php echo $this->Format->getStatus($caseTypeId,$caseLegend); ?></td>
		<td style="font-weight:normal;<?php echo $class; ?>">
		<?php
		$dateCurnt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
		if($caseDueDate != "NULL" && $caseDueDate != "0000-00-00" && $caseDueDate != "" && $caseDueDate != "1970-01-01") {
			echo $this->Datetime->dueDateFormat($caseDueDate,$dateCurnt);
		}
		else {
			echo "&nbsp;";
		}
		?>
		</td>
		</tr>
		<?php 
		$repeatLastUid = $getlastUid;
		$repeatAssgnUid = $caseAssgnUid;
		$repeatcaseTypeId = $caseTypeId;
		$totids.= $caseAutoId."|";
	} 
	?>
</table>
<input type="hidden" id="allcases" name="allcases" value="<?php echo $cscount; ?>"/>
<?php
}
else {
	?>
	<center style="font-weight:normal;color:#FF0000;padding:10px;">No tasks available</center>
	<?php
}