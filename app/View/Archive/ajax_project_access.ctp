<?php
if($projArr['Project']['name']) { ?>
	Last Project accessed: <?php echo $this->Format->shortLength($projArr['Project']['name'],20); ?>,
	<?php 
	//$latestdt = $this->Casequery->getlatestactivity(SES_ID);
	if($dt_visited && !stristr($dt_visited,"0000"))
	{
		$last_logindt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$dt_visited,"datetime");
		$locDResFun2 = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
		echo $this->Datetime->dateFormatOutputdateTime_day($last_logindt,$locDResFun2);
	}
}
?>
<input type="hidden" id="last_project_id" value="<?php echo $projArr['Project']['id']; ?>">
<input type="hidden" id="last_project_uniqid" value="<?php echo $projArr['Project']['uniq_id']; ?>">