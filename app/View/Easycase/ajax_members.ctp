<input type="hidden" id="types_all">
<?php
$m=0;
if(isset($memArr))
{
	$m=0;
	$totMemCase = 0;
	$h = 0;
	foreach($memArr as $mem)
	{   $members=explode("-",$CookieMem);
		$m++;
		$memId = $mem['User']['id'];
		$memUniqId = $mem['User']['uniq_id'];
		$memName = $mem['User']['name'];
		$memLogin = $mem['User']['dt_last_login'];
		$shortname =  $mem['User']['short_name'];
		?>
		<li <?php if($m > 5){$h++;?> id="hidMem_<?php echo $h; ?>" style="display:none;" <?php }?> >
		<a href="javascript:void(0);">
		<input type="checkbox" id="mems_<?php echo $m; ?>" onClick="checkboxMems('mems_<?php echo $m; ?>','check');filterRequest('mems');" style="cursor:pointer;" <?php if (in_array($memId, $members)) { echo "checked"; } ?>/>
		
		<font onClick="checkboxMems('mems_<?php echo $m; ?>','text');filterRequest('mems');" style="cursor:pointer;" color="#464646" title='<?php echo $this->Format->formatText($shortname); ?>'>
		&nbsp;<?php echo $this->Format->formatText($memName); ?> (<?php echo $mem[0]['cases']; ?>)</font>
		<div style="margin:0;color:#999999;line-height:16px;padding-left:20px;font-size:11px;">
			<?php
			if($memLogin == "" || $memLogin == "NULL" || $memLogin == "0000-00-00 00:00:00" || !SES_TIMEZONE)
			{
				echo "Yet to Sign In";
			}
			else
			{
				$last_logindt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$memLogin,"datetime");
				$locDResFun2 = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
				echo "Last Sign In: ".$this->Datetime->dateFormatOutputdateTime_day($last_logindt,$locDResFun2);
			}
			?>
		</div>
		<input type="hidden" name="memids_<?php echo $m; ?>" id="memids_<?php echo $m; ?>" value="<?php echo $memId; ?>" readonly="true">
		    </a>
	    </li>
		<?php
	}
	if($h != 0){?>
	<div class="slide_menu_div1 more-hide-div">
		<div class="more" align="right" id="mem_more">
			<a href="javascript:jsVoid();" onClick="moreLeftNav('mem_more','mem_hide','<?php echo $h; ?>','hidMem_')">more...</a>
		</div>
		<div class="more" align="right" id="mem_hide" style="display:none;">
			<a href="javascript:jsVoid();" onClick="hideLeftNav('mem_more','mem_hide','<?php echo $h; ?>','hidMem_')">hide...</a>
		</div>
	</div>
	<?php
	} ?>
<!-- Add user option start -->
<?php /* ?>
<?php if(SES_TYPE == 1 || SES_TYPE == 2){ ?>
     <hr style="padding:0px;margin:2px 0px;"/>
     <a href="javascript:void(0);" style="line-height:15px" class="makeHover" onClick="openUsPopup('add_user','<?php echo $proj_id; ?>','<?php echo urlencode($prj_name); ?>'),'1'"><div>Add User</div></a></div>
<?php } ?>
<?php */ ?>
<!-- Add user option end -->
<?php } ?>
<input type="hidden" id="totMemId" value="<?php echo $m; ?>" readonly="true"/>
<?php
/*if($proj_uniq_id == 'all')
{
?>
<input type="hidden" id="types_all">
<?php
$m=0;
if(isset($memArr))
{
	$m=0;
	$totMemCase = 0;
	$h = 0;
	foreach($memArr as $mem)
	{   $members=explode("-",$CookieMem);
		$m++;
		$memId = $mem['id'];
		$memUniqId = $mem['uniq_id'];
		$memName = $mem['name'];
		$memLogin = $mem['dt_last_login'];
		
		if($m > 5)
		{
		$h++;
		?>
		<span id="hidMem_<?php echo $h; ?>" style="display:none;">
		<?php
		}
		?>
		<div class="slide_menu_div1" style="color:#6A6A6A;font-weight:normal;">
		<input type="checkbox" id="mems_<?php echo $m; ?>" onClick="checkboxMems('mems_<?php echo $m; ?>','check');filterRequest('mems');" style="cursor:pointer;" <?php if (in_array($memId, $members)) { echo "checked"; } ?>/>
		
		<font onClick="checkboxMems('mems_<?php echo $m; ?>','text');filterRequest('mems');" style="cursor:pointer;" color="#464646">
		&nbsp;<?php echo $this->Format->formatText($memName); ?> (<?php echo $this->Casequery->displayCaseNo($proj_id,'member',$memId,$caseMenuFilters,'all');?>)</font>
		<div style="margin:0;color:#999999;line-height:16px;padding-left:20px;font-size:12px;">
			<?php
			if($memLogin == "" || $memLogin == "NULL" || $memLogin == "0000-00-00 00:00:00" || !SES_TIMEZONE)
			{
				echo "Yet to Sign In";
			}
			else
			{
				$last_logindt = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,$memLogin,"datetime");
				$locDResFun2 = $this->Tmzone->GetDateTime(SES_TIMEZONE,TZ_GMT,TZ_DST,TZ_CODE,GMT_DATETIME,"date");
				echo "Last Sign In: ".$this->Datetime->dateFormatOutputdateTime_day($last_logindt,$locDResFun2);
			}
			?>
		</div>
		<input type="hidden" name="memids_<?php echo $m; ?>" id="memids_<?php echo $m; ?>" value="<?php echo $memId; ?>" readonly="true">
		</div>
		<?php
		if($m > 5)
		{
		?>
		</span>
		<?php
		}
	}
	if($h != 0)
	{
	?>
	<div class="slide_menu_div1" style="color:#6A6A6A;font-weight:normal;">
		<div class="more" align="right" id="mem_more" style="line-height:0px;padding:3px 2px;">
			<a href="javascript:jsVoid();" onClick="moreLeftNav('mem_more','mem_hide','<?php echo $h; ?>','hidMem_')">more...</a>
		</div>
		<div class="more" align="right" id="mem_hide" style="display:none;line-height:0px;padding:3px 2px;">
			<a href="javascript:jsVoid();" onClick="hideLeftNav('mem_more','mem_hide','<?php echo $h; ?>','hidMem_')">hide...</a>
		</div>
	</div>
	<?php
	}
}
?>
<input type="hidden" id="totMemId" value="<?php echo $m; ?>" readonly="true"/>
<?php
}*/
?>
<?php //echo $this->element('sql_dump'); ?>
